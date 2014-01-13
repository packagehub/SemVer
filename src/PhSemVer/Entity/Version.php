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
        $re = "/(?P<major>\d+)(?:\.(?P<minor>\d+)(?:\.(?P<patch>\d+)(?:[-]?(?P<pres>[\da-z][\da-z\-]*"
            . "(?:\.[\da-z\-]+)*))?(?:\+(?P<posts>[\da-z\-]+(?:\.[\da-z\-]+)*))?)?)?/i";
        if (!preg_match($re, $versionString, $matches)) {
            throw new InvalidArgumentException('invalid version string ' . $versionString);
        }

        $this->major = (int) $matches['major'];

        foreach (array('minor', 'patch') as $part) {
            if (isset($matches[$part])) {
                $this->$part = (int) $matches[$part];
            }
        }

        foreach (array('pres', 'posts') as $part) {
            if (isset($matches[$part]) && !empty($matches[$part])) {
                $this->$part = $this->getAppendedVersionLevels($matches[$part]);
            }
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

    public function getPres()
    {
        return $this->pres;
    }

    public function getPosts()
    {
        return $this->posts;
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
        if (empty($this->pres)) {
            $this->patch++;
        }
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
