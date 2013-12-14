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

/**
 * Model of semantic version and constraint.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class AndConstraint implements ConstraintInterface
{
    /**
     * Base constraints
     *
     * @var ConstraintInterface[]
     */
    protected $constraints;

    /**
     * Create constraint
     *
     * @param ConstraintInterface[] $constraints
     */
    public function __construct($constraints)
    {
        $this->constraints = $constraints;
    }

    /**
     * Check, if semantic version matches constraint
     *
     * @param  Version $version
     * @return boolean
     */
    public function match(Version $version)
    {
        foreach ($this->constraints as $constraint) {
            if (!$constraint->match($version)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Convert constraint to string
     *
     * @return string
     */
    public function __toString()
    {
        return implode(' && ' . $this->constraints);
    }
}
