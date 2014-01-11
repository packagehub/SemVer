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

namespace PhSemVer\Entity;

use PhSemVer\Exception\InvalidArgumentException;

/**
 * This class provides a SemVer compatible version.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class Version
{
    /**
     * Default value for minor and patch levels
     *
     * @var int
     */
    protected $default = 0;

    /**
     * Major version level
     *
     * @var int
     */
    protected $major = 0;

    /**
     * Minor version level
     *
     * @var int|null
     */
    protected $minor = null;

    /**
     * Patch version level
     *
     * @var int|null
     */
    protected $patch = null;

    /**
     * List of appended pre-release version levels (alpha, beta, rc)
     *
     * @var array of int and string values
     */
    protected $pres = array();

    /**
     * List of appended post-release version levels (build, hotfix, pl)
     *
     * @var array of int and string values
     */
    protected $posts = array();

    /**
     * Initialize version from string
     *
     * @param  string $versionString
     * @param  int    $default
     * @return void
     */
    public function __construct($versionString, $default = 0)
    {
        $this->default = $default;
        $re = "/(?P<major>\d+)\.(?P<minor>\d+)(?:\.(?P<patch>\d+)(?:[-]?(?P<pres>[\da-z][\da-z\-]*"
            . "(?:\.[\da-z\-]+)*))?(?:\+(?P<posts>[\da-z\-]+(?:\.[\da-z\-]+)*))?)?/i";
        if (!preg_match($re, $versionString, $matches)) {
              throw new InvalidArgumentException('invalid version string ' . $versionString);
        }
        $this->major = (int) $matches['major'];
        if (isset($matches['minor'])) {
            $this->minor = (int) $matches['minor'];
        }
        if (isset($matches['patch'])) {
            $this->patch = (int) $matches['patch'];
        }
        if (isset($matches['pres']) && !empty($matches['pres'])) {
            $this->pres = $this->getAppendedVersionLevels($matches['pres']);
        }
        if (isset($matches['posts']) && !empty($matches['posts'])) {
            $this->posts = $this->getAppendedVersionLevels($matches['posts']);
        }
    }

    public function getMajor()
    {
        return $this->major;
    }

    public function getMinor($useDefault = true)
    {
        if (null === $this->minor && $useDefault) {
            return $this->default;
        }

        return $this->minor;
    }

    public function getPatch($useDefault = true)
    {
        if (null === $this->patch && $useDefault) {
            return $this->default;
        }

        return $this->patch;
    }

    public function getAppendedString()
    {
        $string = '';
        if (!empty($this->pres)) {
            $string .= '-' . implode('.', $this->pres);
        }
        if (!empty($this->posts)) {
            $string .= '+' . implode('.', $this->posts);
        }

        return $string;
    }

    public function updateMajor()
    {
        $this->major++;
        $this->minor = 0;
        $this->patch = 0;
        $this->pres = array();
        $this->posts = array();

        return $this;
    }

    public function updateMinor()
    {
        if (null === $this->minor) {
            $this->minor = $this->default;
        }
        $this->minor++;
        $this->patch = 0;
        $this->pres = array();
        $this->posts = array();

        return $this;
    }

    public function updatePatch()
    {
        if (null === $this->patch) {
            $this->patch = $this->default;
        }
        $this->patch++;
        $this->pres = array();
        $this->posts = array();

        return $this;
    }

    /**
     * Export version as string
     *
     * @return string
     */
    public function __toString()
    {
        $string = implode('.', array($this->getMajor(), $this->getMinor(), $this->getPatch()));
        $string .= $this->getAppendedString();

        return $string;
    }

    /**
     * Compare with version instance (return as soon as differences are found)
     *
     * @param  Version $version
     * @return int
     */
    public function compare(Version $version)
    {
        //check major, minor and patch level
        foreach (array('getMajor', 'getMinor', 'getPatch') as $part) {
            $p1 = $this->$part();
            $p2 = $version->$part();
            if ($p1 != $p2) {
                return $p1 - $p2;
            }
        }
        foreach (array('pres', 'posts') as $part) {
            $cmp = (int) empty($this->$part) - (int) empty($version->$part);
            if (0 != $cmp) {
                //stop if different
                return ('pres' == $part) ? $cmp : -$cmp;
            }
            $cmp = $this->compareArray($this->$part, $version->$part);
            if (0 != $cmp) {
                return $cmp;
            }
        }

        return 0;
    }

    /**
     * Get Stability of version
     *     a version is stable if major >= 1 and no pre-release is appended
     *
     * @return boolean
     */
    public function isStable()
    {
        return $this->getMajor() >= 1 && empty($this->pres);
    }

    /**
     * Compare arrays of pres or posts from two versions
     *
     * @param  array $version1
     * @parma  array $version2
     * @return int
     */
    protected function compareArray(array $version1, array $version2)
    {
        $count = 0;
        while (isset($version1[$count]) || isset($version2[$count])) {
            //ending versions are older
            if (!isset($version1[$count])) {
                return -1;
            }
            if (!isset($version2[$count])) {
                return 1;
            }
            switch ($this->getCompareType($version1[$count], $version2[$count])) {
                case 'ii':
                    //both ints
                    $cmp = $version1[$count] - $version2[$count];
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
                    $cmp = strcasecmp($version1[$count], $version2[$count]);
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
     * @parma  int|string $version2
     * @return string
     */
    protected function getCompareType($version1, $version2)
    {
        $type = (is_int($version1) ? 'i' : 's');
        $type .= (is_int($version2) ? 'i' : 's');
        return $type;
    }

    /**
     * Get appended version levels seperated by .
     *
     * @param  string $version
     * @return array
     */
    protected function getAppendedVersionLevels($version)
    {
        $matches = explode('.', $version);
        //convert int values in appendages
        foreach ($matches as $key => $value) {
            if (is_numeric($value)) {
                $matches[$key] = intval($value);
            }
        }

        return $matches;
    }
}
