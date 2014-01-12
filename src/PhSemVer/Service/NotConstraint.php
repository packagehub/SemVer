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

namespace PhSemVer\Service;

use PhSemVer\Entity\Version;

/**
 * Model of semantic version not constraint.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class NotConstraint implements ConstraintInterface
{
    /**
     * Base constraint
     *
     * @var ConstraintInterface
     */
    protected $constraint;

    /**
     * Create constraint
     *
     * @param ConstraintInterface $constraint
     */
    public function __construct(ConstraintInterface $constraint)
    {
        $this->constraint = $constraint;
    }

    /**
     * Check, if semantic version matches constraint
     *
     * @param  Version $version
     * @return boolean
     */
    public function match(Version $version)
    {
        return !$this->constraint->match($version);
    }

    /**
     * Convert constraint to string
     *
     * @return string
     */
    public function __toString()
    {
        return '!' . $this->constraint;
    }
}
