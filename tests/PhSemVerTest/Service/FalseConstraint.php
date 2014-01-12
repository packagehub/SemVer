<?php
/**
 * This file is part of SemVer.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2014 Gordon Schmidt
 * @license   MIT
 */

namespace PhSemVerTest\Service;

use PhSemVer\Service\ConstraintInterface;
use PhSemVer\Entity\Version;

/**
 * Test Model for an constraint which never matches.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class FalseConstraint implements ConstraintInterface
{
    /**
     * Check, if semantic version matches constraint
     *
     * @param  Version $version
     * @return boolean
     */
    public function match(Version $version)
    {
        return false;
    }

    /**
     * Convert constraint to string
     *
     * @return string
     */
    public function __toString()
    {
        return 'false';
    }
}
