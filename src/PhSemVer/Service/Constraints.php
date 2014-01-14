<?php
/**
 * This file is part of SemVer.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2013,2014 Gordon Schmidt
 * @license   MIT
 */

namespace PhSemVer\Service;

use PhSemVer\AbstractSemVerServiceAware;
use PhSemVer\Constraint\AndConstraint;
use PhSemVer\Constraint\BaseOperatorConstraint;
use PhSemVer\Constraint\NotConstraint;
use PhSemVer\Entity\Version;
use PhSemVer\Exception\InvalidArgumentException;

/**
 * This class provides creation of semantic version constraints.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class Constraints extends AbstractSemVerServiceAware
{
    /**
     * Create constraints from string
     *
     * @param string $constraintsString
     * @return \PhSemVer\Service\ConstraintInterface
     */
    public function create($constraintsString)
    {
        $constraintsPattern = '/\s?(?<connector>&&|,| )?\s?(?<constraint>(?<ic>(?<op>[(\[])(?<v1>[a-z0-9.\-+*]*)'
            . ',(?<v2>[a-z0-9.\-+*]*)(?<cp>[)\]]))|(?<oc>(?<o>[<>=!~]*)\s?(?<v>[a-z0-9.\-+*]*)))/i';

        $count = preg_match_all($constraintsPattern, $constraintsString, $matches);
        $constraints = $this->getConstraints($count, $matches);

        $count = count($constraints);
        if (0 == $count) {
            throw new InvalidArgumentException('invalid constraints string ' . $constraintsString);
        } elseif (1 < $count) {
            //@todo use connector matches, if available
            return new AndConstraint($constraints);
        }

        return $constraints[0];
    }

    /**
     * Get Constraints from preg match
     *
     * @param int   $count
     * @param array $matches
     * @return \PhSemVer\Service\ConstraintInterface[]
     */
    protected function getConstraints($count, $matches)
    {
        $constraints = array();
        for ($i = 0; $i < $count; $i++) {
            if (!empty($matches['oc'][$i])) {
                $constraints[] = $this->getOperatorConstraint($matches['o'][$i], $matches['v'][$i]);
            }
        }
        
        return $constraints;
    }
    
    /**
     * Get constraint for operator and version
     *
     * @param string $operator
     * @param string $version
     * @return ConstraintInterface
     */
    protected function getOperatorConstraint($operator, $version)
    {
        if (in_array($operator, array('<', '<=', '>', '>=', '=='))) {
            return new BaseOperatorConstraint($operator, new Version($version), $this->getSemVerService());
        } elseif (in_array($operator, array('<>', '!=', '!'))) {
            return new NotConstraint($this->getAndConstraint($version));
        } elseif (in_array($operator, array('=', ''))) {
            return $this->getAndConstraint($version);
        } elseif (in_array($operator, array('~>', '~'))) {
            return $this->getTildeConstraint($version);
        } elseif ('!==' == $operator) {
            return new NotConstraint(
                new BaseOperatorConstraint('==', new Version($version), $this->getSemVerService())
            );
        } else {
            throw new InvalidArgumentException('invalid oparator "' . $operator . '" provided');
        }
    }
    
    /**
     * Get tilde constraint
     *
     * @param string $version
     * @return \PhSemVer\Service\ConstraintInterface
     */
    protected function getTildeConstraint($version)
    {
        $semVerService = $this->getSemVerService();
        $minVersion = new Version($version);
        if (null == $minVersion->getMinor(false)) {
            //not top constraint
            return new BaseOperatorConstraint('>=', $minVersion, $semVerService);
        } else {
            return new AndConstraint(array(
                new BaseOperatorConstraint('>=', $minVersion, $semVerService),
                new BaseOperatorConstraint('<', $this->getNextBigVersion($version), $semVerService),
            ));
        }
    }

    /**
     * Get and constraint
     *
     * @param string $version
     * @return \PhSemVer\Service\AndConstraint
     */
    protected function getAndConstraint($version)
    {
        $semVerService = $this->getSemVerService();

        return new AndConstraint(array(
            new BaseOperatorConstraint('>=', new Version($version), $semVerService),
            new BaseOperatorConstraint('<=', new Version($version, PHP_INT_MAX), $semVerService),
        ));
    }

    /**
     * Get next version depending on definition depth
     *
     * @param string $version
     * @return \PhSemVer\Entity\Version
     */
    protected function getNextBigVersion($version)
    {
        $nextBigVersion = new Version($version);
        if (null == $nextBigVersion->getPatch(false)) {
            //next major
            $nextBigVersion->updateMajor();
        } else {
            //next minor
            $nextBigVersion->updateMinor();
        }
                
        return $nextBigVersion;
    }
}
