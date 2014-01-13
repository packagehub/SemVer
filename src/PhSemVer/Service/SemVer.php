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
     * @param  string $v1
     * @param  string $v2
     * @return int
     */
    public function compareVersionStrings($v1, $v2)
    {
        return $this->compareVersions(new Version($v1), new Version($v2));
    }

    /**
     * compare two version instances
     *
     * @param  Version $v1
     * @param  Version $v2
     * @return int
     */
    public function compareVersions(Version $v1, Version $v2)
    {
        $cmp = $this->compareVersionLevels($v1, $v2);
        if (0 != $cmp) {
            return $cmp;
        }

        return $this->compareAppendedVersionLevels($v1, $v2);
    }

    /**
     * sort an array of version strings
     *
     * @param  array &$versions
     * @return array of version strings
     */
    public function sortVersionStrings(array &$versions)
    {
        return usort($versions, array($this, 'compareVersionStrings'));
    }

    /**
     * sort an array of version instances
     *
     * @param  array &$versions
     * @return bool  true on success or false on failure
     */
    public function sortVersions(array &$versions)
    {
        return usort($versions, array($this, 'compareVersions'));
    }
    
    /**
     * compare version levels of two version instances
     *
     * @param  Version $v1
     * @param  Version $v2
     * @return int
     */
    protected function compareVersionLevels(Version $v1, Version $v2)
    {
        //check major, minor and patch level
        foreach (array('getMajor', 'getMinor', 'getPatch') as $part) {
            $p1 = $v1->$part();
            $p2 = $v2->$part();
            if ($p1 != $p2) {
                return $p1 - $p2;
            }
        }

        return 0;
    }

    /**
     * compare appended version levels of two version instances
     *
     * @param  Version $v1
     * @param  Version $v2
     * @return int
     */
    protected function compareAppendedVersionLevels(Version $v1, Version $v2)
    {
        foreach (array('getPres', 'getPosts') as $part) {
            $p1 = $v1->$part();
            $p2 = $v2->$part();
            $cmp = (int) empty($p1) - (int) empty($p2);
            if (0 != $cmp) {
                //stop if different
                return ('getPres' == $part) ? $cmp : -$cmp;
            }
            $cmp = $this->compareArray($p1, $p2);
            if (0 != $cmp) {
                return $cmp;
            }
        }

        return 0;
    }

    /**
     * Compare arrays of pres or posts from two versions
     *
     * @param  array $v1
     * @param  array $v2
     * @return int
     */
    protected function compareArray(array $v1, array $v2)
    {
        $count = 0;
        while (isset($v1[$count]) || isset($v2[$count])) {
            //ending versions are older
            if (!isset($v1[$count])) {
                return -1;
            }
            if (!isset($v2[$count])) {
                return 1;
            }
            $cmp = $this->comparePart($v1[$count], $v2[$count]);
            if (0 != $cmp) {
                //stop if different
                return $cmp;
            }
            $count++;
        }

        return 0;
    }

    /**
     * Compare two version parts
     *
     * @param  int|string $version1
     * @param  int|string $version2
     * @return int
     */
    protected function comparePart($version1, $version2)
    {
        if (is_int($version1)) {
            if (is_int($version2)) {
                //both ints
                return $version1 - $version2;
            } else {
                //int before string
                return -1;
            }
        } else {
            if (is_int($version2)) {
                //string after int
                return 1;
            } else {
                //both strings
                return strcasecmp($version1, $version2);
            }
        }
    }
}
