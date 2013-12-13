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
 * This class provides validate, sort and compare functionality for versions.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class SemVer
{
    /**
     * compare two version strings
     *
     * @param string $v1
     * @param string $v2
     * @return int
     */
    public function compareVersionStrings($v1, $v2)
    {
        return $this->compareVersions(new Version($v1), new Version($v2));
    }

    /**
     * compare two version instances
     *
     * @param Version $v1
     * @param Version $v2
     * @return int
     */
    public function compareVersions(Version $v1, Version $v2)
    {
        return $v1->compare($v2);
    }

    /**
     * sort an array of version strings
     *
     * @param array &$versions
     * @return array of version strings
     */
    public function sortVersionStrings(array &$versions)
    {
        return usort($versions, array($this, 'compareVersionStrings'));
    }

    /**
     * sort an array of version instances
     *
     * @param array &$versions
     * @return bool true on success or false on failure
     */
    public function sortVersions(array &$versions)
    {
        return usort($versions, array($this, 'compareVersions'));
    }
}
