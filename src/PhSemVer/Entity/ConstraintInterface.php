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
 * Interface for models of semantic version constraints.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
interface ConstraintInterface
{
    /**
     * Check, if semantic version matches constraint
     *
     * @param Version $version
     * @return boolean
     */
    public function match(Version $version);

    /**
     * Convert constraint to string
     *
     * @return string
     */
    public function __toString();
}
