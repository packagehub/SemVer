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
            switch ($this->compareType($v1[$count], $v2[$count])) {
                case 'ii':
                    //both ints
                    $cmp = $v1[$count] - $v2[$count];
                    break;
                case 'is':
                    //int before string
                    return -1;
                    break;
                case 'si':
                    //string after int
                    return 1;
                    break;
                case 'ss':
                default:
                    //both strings
                    $cmp = strcasecmp($v1[$count], $v2[$count]);
                    break;
            }
            if (0 != $cmp) {
                //stop if different
                return $cmp;
            }
            $count++;
        }

        return 0;
    }

    /**
     * Get comparison type of two version parts
     *
     * @param  int|string $version1
     * @param  int|string $version2
     * @return string
     */
    protected function compareType($version1, $version2)
    {
        $type = (is_int($version1) ? 'i' : 's');
        $type .= (is_int($version2) ? 'i' : 's');
 
        return $type;
    }
}
