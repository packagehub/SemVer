<?php
/**
 * This file is part of SemVer.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2013 Gordon Schmidt
 * @license   MIT
 */

namespace PhSemVer\Entity;

use PhSemVer\Exception\InvalidArgumentException;

/**
 * Model of semantic version constraints.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class Constraints implements ConstraintInterface
{
    /**
     * Base constraint
     *
     * @var ConstraintInterface
     */
    protected $constraint;

    /**
     * Create constraints from string
     *
     * @param string $constraintsString
     */
    public function __construct($constraintsString)
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
        if (!preg_match_all($constraintsPattern, $constraintsString, $matches)) {
              throw new InvalidArgumentException('invalid constraints string ' . $constraintsString);
        }
        $count = count($matches[0]);
        $constraints = array();
        $connector = null;
        for ($i = 0; $i < $count; $i++) {
            /*if (!empty($matches['ic'][$i])) {
                $constraint = new IntervalConstraint(
                    $matches['op'][$i],
                    $matches['v1'][$i],
                    $matches['v2'][$i],
                    $matches['cp'][$i]
                );
            } else*/if (!empty($matches['oc'][$i])) {
                $constraints[] = new OperatorConstraint($matches['o'][$i], $matches['v'][$i]);
            }
        }
        $this->constraint = new AndConstraint($constraints);
    }

    /**
     * Check, if semantic version matches constraint
     *
     * @param Version $version
     * @return boolean
     */
    public function match(Version $version)
    {
        return $this->constraint->match($version);
    }

    /**
     * Convert constraint to string
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->constraint;
    }
}
