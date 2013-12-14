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
 * Model of semantic version operator constraint.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class OperatorConstraint implements ConstraintInterface
{
    /**
     * Sub constraint
     *
     * @var ConstraintInterface
     */
    protected $constraint;

    /**
     * Create constraint from operator and version
     *
     * @param string $operator
     * @param string $version
     */
    public function __construct($operator, $version)
    {
        if ('' == $operator) {
            $operator = '=';
        } elseif ('<>' == $operator) {
            $operator = '!=';
        } elseif ('~' == $operator) {
            $operator = '~>';
        }
        if (!in_array($operator, array('<', '<=', '>', '>=', '==', '!==', '=', '!=', '~>'))) {
            throw new InvalidArgumentException('invalid oparator "' . $operator . '" provided');
        }
        $this->constraint = $this->getConstraint($operator, $version);
    }

    /**
     * Check, if semantic version matches constraint
     *
     * @param  Version $version
     * @return boolean
     */
    public function match(Version $version)
    {
        return $this->constraint->match($version);
    }

    /**
     * Get sub constraint for operator and version
     *
     * @param  string              $operator
     * @param  string              $version
     * @return ConstraintInterface
     */
    protected function getConstraint($operator, $version)
    {
        switch ($operator) {
            case '!==':
            case '!=':
                return new NotConstraint($this->getConstraint(substr($operator, 1), $version));
                break;
            case '=':
                return new AndConstraint(array(
                    new BaseOperatorConstraint('>=', new Version($version)),
                    new BaseOperatorConstraint('<=', new Version($version, PHP_INT_MAX)),
                ));
                break;
            case '~>':
                $nextBigVersion = new Version($version);
                if (null == $nextBigVersion->getMinor(false)) {
                    //not top constraint
                    return new BaseOperatorConstraint('>=', $nextBigVersion);
                } elseif (null == $nextBigVersion->getPatch(false)) {
                    //next major
                    $nextBigVersion->updateMajor();
                } else {
                    //next minor
                    $nextBigVersion->updateMinor();
                }

                return new AndConstraint(array(
                    new BaseOperatorConstraint('>=', new Version($version)),
                    new BaseOperatorConstraint('<', $nextBigVersion),
                ));
                break;
            default:
                return new BaseOperatorConstraint($operator, new Version($version));
                break;
        }
    }

    /**
     * Convert constraint to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->operator . $this->version;
    }
}
