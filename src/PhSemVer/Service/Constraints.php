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

use PhSemVer\Entity\Version;
use PhSemVer\Exception\InvalidArgumentException;

/**
 * This class provides creation of semantic version constraints.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class Constraints extends AbstractSemVerAware
{
    /**
     * Create constraints from string
     *
     * @param string $constraintsString
     * @return \PhSemVer\Service\ConstraintInterface
     */
    public function create($constraintsString)
    {
        $versionPattern = '[a-z0-9.\-+*]*';
        $operatorPattern = '(?<o>[<>=!~]*)';
        $operatorConstraintPattern = '(?<oc>' . $operatorPattern . '\s?(?<v>' . $versionPattern . '))';
        $openParenthesisPattern = '(?<op>[(\[])';
        $closeParenthesisPattern = '(?<cp>[)\]])';
        $intervalConstraintPattern = '(?<ic>' . $openParenthesisPattern . '(?<v1>' . $versionPattern
            . '),(?<v2>' . $versionPattern . ')' . $closeParenthesisPattern . ')';
        $connectorPattern = '\s?(?<connector>&&|,| )?\s?';
        $constraintsPattern = '/' . $connectorPattern . '(?<constraint>' . $intervalConstraintPattern . '|'
            . $operatorConstraintPattern . ')/i';

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
        $operator = $this->mapOperator($operator);
        $semVerService = $this->getSemVerService();

        if (in_array($operator, array('<', '<=', '>', '>=', '=='))) {
            return new BaseOperatorConstraint($operator, new Version($version), $semVerService);
        }
        if ('!==' == $operator || '!=' == $operator) {
            return new NotConstraint($this->getOperatorConstraint(substr($operator, 1), $version));
        }
        if ('=' == $operator) {
            return new AndConstraint(array(
                new BaseOperatorConstraint('>=', new Version($version), $semVerService),
                new BaseOperatorConstraint('<=', new Version($version, PHP_INT_MAX), $semVerService),
            ));
        }
        if ('~>' == $operator) {
            $minVersion = new Version($version);
            if (null == $minVersion->getMinor(false)) {
                //not top constraint
                return new BaseOperatorConstraint('>=', $minVersion, $semVerService);
            }
            return new AndConstraint(array(
                new BaseOperatorConstraint('>=', $minVersion, $semVerService),
                new BaseOperatorConstraint('<', $this->getNextBigVersion($version), $semVerService),
            ));
        }
        throw new InvalidArgumentException('invalid oparator "' . $operator . '" provided');
    }
 
    /**
     * Map operators
     *
     * @param string $operator
     * @return string
     */
    protected function mapOperator($operator)
    {
        switch ($operator) {
            case '<>':
            case '!':
                $operator = '!=';
                break;
            case '':
                $operator = '=';
                break;
            case '~':
                $operator = '~>';
                break;
            default:
                break;
        }
        
        return $operator;
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
