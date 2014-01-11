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
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getMajor
     */
    public function testGetMajor($providedVersion, $major, $minor, $patch, $appended)
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
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getMinor
     */
    public function testGetMinor($providedVersion, $major, $minor, $patch, $appended)
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
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getPatch
     */
    public function testGetPatch($providedVersion, $major, $minor, $patch, $appended)
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
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getAppendedString
     */
    public function testGetAppendedString($providedVersion, $major, $minor, $patch, $appended)
    {
        $v = new Version($providedVersion);
        if (null !== $appended) {
            $this->assertEquals($appended, $v->getAppendedString());
        }
    }

    /**
     * tests updating major level
     * @param string $providedVersion
     * @param string $updateMajor
     * @param string $updatetMinor
     * @param string $updatePatch
     * @dataProvider updateProvider
     * @covers \PhSemVer\Entity\Version::updateMajor
     * @covers \PhSemVer\Entity\Version::__toString
     */
    public function testUpdateMajor($providedVersion, $updateMajor, $updateMinor, $updatePatch)
    {
        $v = new Version($providedVersion);
        $this->assertEquals($updateMajor, $v->updateMajor()->__toString());
    }

    /**
     * tests updating minor level
     * @param string $providedVersion
     * @param string $updateMajor
     * @param string $updatetMinor
     * @param string $updatePatch
     * @dataProvider updateProvider
     * @covers \PhSemVer\Entity\Version::updateMinor
     * @covers \PhSemVer\Entity\Version::__toString
     */
    public function testUpdateMinor($providedVersion, $updateMajor, $updateMinor, $updatePatch)
    {
        $v = new Version($providedVersion);
        $this->assertEquals($updateMinor, $v->updateMinor()->__toString());
    }

    /**
     * tests updating patch level
     * @param string $providedVersion
     * @param string $updateMajor
     * @param string $updatetMinor
     * @param string $updatePatch
     * @dataProvider updateProvider
     * @covers \PhSemVer\Entity\Version::updatePatch
     * @covers \PhSemVer\Entity\Version::__toString
     */
    public function testUpdatePatch($providedVersion, $updateMajor, $updateMinor, $updatePatch)
    {
        $v = new Version($providedVersion);
        $this->assertEquals($updatePatch, $v->updatePatch()->__toString());
    }

    /**
     * tests for stable versions
     * @param string $version1
     * @param string $version2
     * @param string $method
     * @dataProvider compareProvider
     * @covers \PhSemVer\Entity\Version::compare
     * @covers \PhSemVer\Entity\Version::compareArray
     */
    public function testCompare($version1, $version2, $method)
    {
        $v1 = new Version($version1);
        $v2 = new Version($version2);
        $this->$method(0, $v1->compare($v2));
    }

    /**
     * tests for stable versions
     * @param string $providedVersion
     * @param bool   $stable
     * @dataProvider stableProvider
     * @covers \PhSemVer\Entity\Version::isStable
     */
    public function testIsStable($providedVersion, $stable)
    {
        $v = new Version($providedVersion);
        $this->assertEquals($stable, $v->isStable());
    }

    /**
     * tests compare type
     * @param string $version1
     * @param string $version2
     * @param string $compareType
     * @dataProvider compareTypeProvider
     * @covers \PhSemVer\Entity\Version::getCompareType
     */
    public function testGetCompareType($version1, $version2, $compareType)
    {
        $method = new \ReflectionMethod('\PhSemVer\Entity\Version', 'getCompareType');
        $method->setAccessible(TRUE);
        $this->assertEquals($compareType, $method->invoke(new Version('1'), $version1, $version2));
    }

    /**
     * tests appended version levels
     * @param string $version
     * @param string $compareArray
     * @dataProvider appendedVersionProvider
     * @covers \PhSemVer\Entity\Version::getAppendedVersionLevels
     */
    public function testGetAppendedVersionLevels($version, $compareArray)
    {
        $method = new \ReflectionMethod('\PhSemVer\Entity\Version', 'getAppendedVersionLevels');
        $method->setAccessible(TRUE);
        $this->assertEquals($compareArray, $method->invoke(new Version('1'), $version));
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
     * provide list of version strings
     * @return array
     */
    public function componentProvider()
    {
        return array(
            array('1.2.3', '1', '2', '3', null),
            array('1.2.3-beta', '1', '2', '3', '-beta'),
            array('1.2.3-alpha.4', '1', '2', '3', '-alpha.4'),
            array('1.2.3-alpha.4+build.5', '1', '2', '3', '-alpha.4+build.5'),
            array('1.2.3+patch.4.5.blah.blubb', '1', '2', '3', '+patch.4.5.blah.blubb'),
            array('1', '1', null, null, null),
            array('1.2', '1', '2', null, null),
            array('1.2.3.4', '1', '2', '3', null),
            array('1.2.3-', '1', '2', '3', null),
            array('1.2.3+', '1', '2', '3', null),
            array('1.2.3-a.', '1', '2', '3', '-a'),
            array('1.2.3-a+', '1', '2', '3', '-a'),
        );
    }

    /**
     * provide list of updated version strings
     * @return array
     */
    public function updateProvider()
    {
        return array(
            array('1.2.3', '2.0.0', '1.3.0', '1.2.4'),
            array('1.2.3-beta', '2.0.0', '1.3.0', '1.2.3'),
            array('1.2.3+patch.4.5.blah.blubb', '2.0.0', '1.3.0', '1.2.4'),
            array('1', '2.0.0', '1.1.0', '1.0.1'),
            array('1.2', '2.0.0', '1.3.0', '1.2.1'),
            array('1.2.99', '2.0.0', '1.3.0', '1.2.100'),
        );
    }

    /**
     * provide list of compare version strings
     * @return array
     */
    public function compareProvider()
    {
        return array(
            array('1', '1', 'assertEquals'),
            array('1', '2', 'assertLessThan'),
            array('2', '1', 'assertGreaterThan'),
            array('1.2', '1.2', 'assertEquals'),
            array('1.2', '1.3', 'assertLessThan'),
            array('1.3', '1.2', 'assertGreaterThan'),
            array('1', '1.2', 'assertLessThan'),
            array('1.2', '1', 'assertGreaterThan'),
            array('1.2.3', '1.2.3', 'assertEquals'),
            array('1.2.3', '1.2.4', 'assertLessThan'),
            array('1.2.4', '1.2.3', 'assertGreaterThan'),
            array('1.2', '1.2.3', 'assertLessThan'),
            array('1.2.3', '1.2', 'assertGreaterThan'),
            array('1', '1.0.0', 'assertEquals'),
            array('1', '1.0.1', 'assertLessThan'),
            array('1.0.1', '1', 'assertGreaterThan'),
            array('1.2.3-alpha', '1.2.3-alpha', 'assertEquals'),
            array('1.2.3-alpha', '1.2.3-beta', 'assertLessThan'),
            array('1.2.3-beta', '1.2.3-alpha', 'assertGreaterThan'),
            array('1.2.3-alpha', '1.2.3', 'assertLessThan'),
            array('1.2.3', '1.2.3-alpha', 'assertGreaterThan'),
            array('1.2.3-alpha', '1.2.3', 'assertLessThan'),
            array('1.2.3+build', '1.2.3+build', 'assertEquals'),
            array('1.2.3+build', '1.2.3+patch', 'assertLessThan'),
            array('1.2.3+patch', '1.2.3+build', 'assertGreaterThan'),
            array('1.2.3', '1.2.3+build', 'assertLessThan'),
            array('1.2.3+build', '1.2.3', 'assertGreaterThan'),
            array('1.2.3-alpha', '1.2.3+build', 'assertLessThan'),
            array('1.2.3+build', '1.2.3-alpha', 'assertGreaterThan'),
            array('1.2.3-alpha', '1.2.3-alpha.1', 'assertLessThan'),
            array('1.2.3-alpha.1', '1.2.3-alpha.2', 'assertLessThan'),
            array('1.2.3-alpha.1', '1.2.3-alpha.1.2', 'assertLessThan'),
            array('1.2.3-alpha.1.2', '1.2.3-alpha.1.2.3', 'assertLessThan'),
            array('1.2.3-alpha.1.2.3', '1.2.3-alpha.1.2', 'assertGreaterThan'),
            array('1.2.3+build', '1.2.3+build.1', 'assertLessThan'),
            array('1.2.3+build.1', '1.2.3+build.2', 'assertLessThan'),
            array('1.2.3+build.1', '1.2.3+build.1.2', 'assertLessThan'),
            array('1.2.3+build.1.2', '1.2.3+build.1', 'assertGreaterThan'),
            array('1.2.3+build.1.a', '1.2.3+build.1.b', 'assertLessThan'),
            array('1.2.3-alpha.1', '1.2.3-alpha.a', 'assertLessThan'),
            array('1.2.3-alpha.a', '1.2.3-alpha.b', 'assertLessThan'),
            array('1.2.3-alpha.a', '1.2.3-alpha.1', 'assertGreaterThan'),
            array('1.2.3+build.1', '1.2.3+build.a', 'assertLessThan'),
            array('1.2.3+build.a', '1.2.3+build.b', 'assertLessThan'),
            array('1.2.3+build.a', '1.2.3+build.1', 'assertGreaterThan'),
        );
    }

    /**
     * provide list of stable version strings
     * @return array
     */
    public function stableProvider()
    {
        return array(
            array('1.2.3', true),
            array('1.2.3-beta', false),
            array('1.2.3+patch.4.5.blah.blubb', true),
            array('0.9.3', false),
            array('0.1.2-alpha.3', false),
        );
    }

    /**
     * provide list of compare type strings
     * @return array
     */
    public function compareTypeProvider()
    {
        return array(
            array(1, 2, 'ii'),
            array(3, 'foo', 'is'),
            array('bar', 4, 'si'),
            array('foo', 'bar', 'ss'),
        );
    }

    /**
     * provide list of appended version levels
     * @return array
     */
    public function appendedVersionProvider()
    {
        return array(
            array('1.foo.2bar.3', array(1, 'foo', '2bar', 3)),
            array('alpha.0xff', array('alpha', 0)),
        );
    }
}
