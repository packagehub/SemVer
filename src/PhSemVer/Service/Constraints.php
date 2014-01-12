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
        $constraints = array();
        for ($i = 0; $i < $count; $i++) {
            if (!empty($matches['oc'][$i])) {
                $constraints[] = $this->getOperatorConstraint($matches['o'][$i], $matches['v'][$i]);
            }
        }
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
     * Get constraint for operator and version
     *
     * @param string $operator
     * @param string $version
     * @return ConstraintInterface
     */
    protected function getOperatorConstraint($operator, $version)
    {
        switch ($operator) {
            case '<>':
            case '!=':
            case '!':
                return new NotConstraint($this->getOperatorConstraint('=', $version));
                break;
            case '!==':
                return new NotConstraint($this->getOperatorConstraint('==', $version));
                break;
            case '':
            case '=':
                $semVerService = $this->getSemVerService();
                return new AndConstraint(array(
                    new BaseOperatorConstraint('>=', new Version($version), $semVerService),
                    new BaseOperatorConstraint('<=', new Version($version, PHP_INT_MAX), $semVerService),
                ));
                break;
            case '~':
            case '~>':
                $semVerService = $this->getSemVerService();
                $nextBigVersion = new Version($version);
                if (null == $nextBigVersion->getMinor(false)) {
                    //not top constraint
                    return new BaseOperatorConstraint('>=', $nextBigVersion, $semVerService);
                } elseif (null == $nextBigVersion->getPatch(false)) {
                    //next major
                    $nextBigVersion->updateMajor();
                } else {
                    //next minor
                    $nextBigVersion->updateMinor();
                }

                return new AndConstraint(array(
                    new BaseOperatorConstraint('>=', new Version($version), $semVerService),
                    new BaseOperatorConstraint('<', $nextBigVersion, $semVerService),
                ));
                break;
            case '<':
            case '<=':
            case '>':
            case '>=':
            case '==':
                $semVerService = $this->getSemVerService();
                return new BaseOperatorConstraint($operator, new Version($version), $semVerService);
                break;
            default:
                throw new InvalidArgumentException('invalid oparator "' . $operator . '" provided');
                break;
        }
    }
}
