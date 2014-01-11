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

namespace PhSemVerTest\Entity;

use PhSemVer\Entity\Version;

/**
 * test initialization of version and export to string
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class VersionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * tests list of valid version strings
     * @param string $version
     * @dataProvider validStringProvider
     * @covers \PhSemVer\Entity\Version::__construct
     * @covers \PhSemVer\Entity\Version::__toString
     */
    public function testValidVersions($version)
    {
        $v = new Version($version);
        $this->assertEquals($version, $v->__toString());
    }

    /**
     * tests list of invalid version strings
     * @param string $providedVersion
     * @dataProvider invalidStringProvider
     * @expectedException \PhSemVer\Exception\InvalidArgumentException
     * @covers \PhSemVer\Entity\Version::__construct
     * @covers \PhSemVer\Entity\Version::__toString
     */
    public function testInvalidVersionsException($providedVersion)
    {
        $v = new Version($providedVersion);
        $this->assertEquals($createdVersion, $v->__toString());
    }

    /**
     * tests list of changed version strings
     * @param string $providedVersion
     * @param string $createdVersion
     * @dataProvider changedStringProvider
     * @covers \PhSemVer\Entity\Version::__construct
     * @covers \PhSemVer\Entity\Version::__toString
     */
    public function testChangedVersions($providedVersion, $createdVersion)
    {
        $v = new Version($providedVersion);
        $this->assertEquals($createdVersion, $v->__toString());
    }

    /**
     * tests major level of version strings
     * @param string $providedVersion
     * @param string $major
     * @param string $minor
     * @param string $patch
     * @param string $appended
     * @param string $updateMajor
     * @param string $updatetMinor
     * @param string $updatePatch
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getMajor
     */
    public function testGetMajor($providedVersion, $major, $minor, $patch, $appended,
        $updateMajor, $updateMinor, $updatePatch)
    {
        $v = new Version($providedVersion);
        $this->assertEquals($major, $v->getMajor());
    }

    /**
     * tests minor level of version strings
     * @param string $providedVersion
     * @param string $major
     * @param string $minor
     * @param string $patch
     * @param string $appended
     * @param string $updateMajor
     * @param string $updatetMinor
     * @param string $updatePatch
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getMinor
     */
    public function testGetMinor($providedVersion, $major, $minor, $patch, $appended,
        $updateMajor, $updateMinor, $updatePatch)
    {
        $v = new Version($providedVersion);
        if (null == $minor) {
            $this->assertEquals(0, $v->getMinor());
            $this->assertEquals($minor, $v->getMinor(false));
        } else {
            $this->assertEquals($minor, $v->getMinor());
        }
    }

    /**
     * tests patch level of version strings
     * @param string $providedVersion
     * @param string $major
     * @param string $minor
     * @param string $patch
     * @param string $appended
     * @param string $updateMajor
     * @param string $updatetMinor
     * @param string $updatePatch
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getPatch
     */
    public function testGetPatch($providedVersion, $major, $minor, $patch, $appended,
        $updateMajor, $updateMinor, $updatePatch)
    {
        $v = new Version($providedVersion);
        if (null == $patch) {
            $this->assertEquals(0, $v->getPatch());
            $this->assertEquals($patch, $v->getPatch(false));
        } else {
            $this->assertEquals($patch, $v->getPatch());
        }
    }

    /**
     * tests appended level of version strings
     * @param string $providedVersion
     * @param string $major
     * @param string $minor
     * @param string $patch
     * @param string $appended
     * @param string $updateMajor
     * @param string $updatetMinor
     * @param string $updatePatch
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getAppendedString
     */
    public function testGetAppendedString($providedVersion, $major, $minor, $patch, $appended,
        $updateMajor, $updateMinor, $updatePatch)
    {
        $v = new Version($providedVersion);
        if (null !== $appended) {
            $this->assertEquals($appended, $v->getAppendedString());
        }
    }

    /**
     * tests updating major level
     * @param string $providedVersion
     * @param string $major
     * @param string $minor
     * @param string $patch
     * @param string $appended
     * @param string $updateMajor
     * @param string $updatetMinor
     * @param string $updatePatch
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::updateMajor
     * @covers \PhSemVer\Entity\Version::__toString
     */
    public function testUpdateMajor($providedVersion, $major, $minor, $patch, $appended,
        $updateMajor, $updateMinor, $updatePatch)
    {
        $v = new Version($providedVersion);
        $this->assertEquals($updateMajor, $v->updateMajor()->__toString());
    }

    /**
     * tests updating minor level
     * @param string $providedVersion
     * @param string $major
     * @param string $minor
     * @param string $patch
     * @param string $appended
     * @param string $updateMajor
     * @param string $updatetMinor
     * @param string $updatePatch
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::updateMinor
     * @covers \PhSemVer\Entity\Version::__toString
     */
    public function testUpdateMinor($providedVersion, $major, $minor, $patch, $appended,
        $updateMajor, $updateMinor, $updatePatch)
    {
        $v = new Version($providedVersion);
        $this->assertEquals($updateMinor, $v->updateMinor()->__toString());
    }

    /**
     * tests updating patch level
     * @param string $providedVersion
     * @param string $major
     * @param string $minor
     * @param string $patch
     * @param string $appended
     * @param string $updateMajor
     * @param string $updatetMinor
     * @param string $updatePatch
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::updatePatch
     * @covers \PhSemVer\Entity\Version::__toString
     */
    public function testUpdatePatch($providedVersion, $major, $minor, $patch, $appended,
        $updateMajor, $updateMinor, $updatePatch)
    {
        $v = new Version($providedVersion);
        $this->assertEquals($updatePatch, $v->updatePatch()->__toString());
    }

    /**
     * provide list of valid version strings
     * @return array
     */
    public function validStringProvider()
    {
        return array(
            array('1.2.3'),
            array('1.2.3-beta'),
            array('1.2.3-alpha.4'),
            array('1.2.3-alpha.4+build.5'),
            array('1.2.3+patch.4.5.blah.blubb'),
        );
    }

    /**
     * provide list of invalid version strings
     * @return array
     */
    public function invalidStringProvider()
    {
        return array(
            array(''),
            array('a'),
        );
    }

    /**
     * provide list of changed version strings
     * @return array
     */
    public function changedStringProvider()
    {
        return array(
            array('1', '1.0.0'),
            array('1.2', '1.2.0'),
            array('1.2.3.4', '1.2.3'),
            array('1.2.3-', '1.2.3'),
            array('1.2.3+', '1.2.3'),
            array('1.2.3-a.', '1.2.3-a'),
            array('1.2.3-a+', '1.2.3-a'),
        );
    }

    /**
     * provide list of changed version strings
     * @return array
     */
    public function componentProvider()
    {
        return array(
            array('1.2.3', '1', '2', '3', null, '2.0.0', '1.3.0', '1.2.4'),
            array('1.2.3-beta', '1', '2', '3', '-beta', '2.0.0', '1.3.0', '1.2.3'),
            array('1.2.3-alpha.4', '1', '2', '3', '-alpha.4', '2.0.0', '1.3.0', '1.2.3'),
            array('1.2.3-alpha.4+build.5', '1', '2', '3', '-alpha.4+build.5', '2.0.0', '1.3.0', '1.2.3'),
            array('1.2.3+patch.4.5.blah.blubb', '1', '2', '3', '+patch.4.5.blah.blubb', '2.0.0', '1.3.0', '1.2.4'),
            array('1', '1', null, null, null, '2.0.0', '1.1.0', '1.0.1'),
            array('1.2', '1', '2', null, null, '2.0.0', '1.3.0', '1.2.1'),
            array('1.2.3.4', '1', '2', '3', null, '2.0.0', '1.3.0', '1.2.4'),
            array('1.2.3-', '1', '2', '3', null, '2.0.0', '1.3.0', '1.2.4'),
            array('1.2.3+', '1', '2', '3', null, '2.0.0', '1.3.0', '1.2.4'),
            array('1.2.3-a.', '1', '2', '3', '-a', '2.0.0', '1.3.0', '1.2.3'),
            array('1.2.3-a+', '1', '2', '3', '-a', '2.0.0', '1.3.0', '1.2.3'),
        );
    }
}
