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
        $this->assertSame($version, $v->__toString());
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
    }

    /**
     * tests list of changed version strings
     * @param string $providedVersion
     * @param string $createdVersion
     * @dataProvider changedStringProvider
     * @covers \PhSemVer\Entity\Version::__construct
     * @covers \PhSemVer\Entity\Version::setMatches
     * @covers \PhSemVer\Entity\Version::__toString
     */
    public function testChangedVersions($providedVersion, $createdVersion)
    {
        $v = new Version($providedVersion);
        $this->assertSame($createdVersion, $v->__toString());
    }

    /**
     * tests major level of version strings
     * @param string $version
     * @param string $major
     * @param string $minor
     * @param string $patch
     * @param array  $pres
     * @param array  $posts
     * @param string $appended
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getMajor
     */
    public function testGetMajor($version, $major, $minor, $patch, $pres, $posts, $appended)
    {
        $v = new Version($version);
        $this->assertSame($major, $v->getMajor());
    }

    /**
     * tests minor level of version strings
     * @param string $version
     * @param int    $major
     * @param int    $minor
     * @param int    $patch
     * @param array  $pres
     * @param array  $posts
     * @param string $appended
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getMinor
     */
    public function testGetMinor($version, $major, $minor, $patch, $pres, $posts, $appended)
    {
        $v = new Version($version);
        if (null == $minor) {
            $this->assertSame(0, $v->getMinor());
            $this->assertSame($minor, $v->getMinor(false));
        } else {
            $this->assertSame($minor, $v->getMinor());
        }
    }

    /**
     * tests patch level of version strings
     * @param string $version
     * @param string $major
     * @param string $minor
     * @param string $patch
     * @param array  $pres
     * @param array  $posts
     * @param string $appended
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getPatch
     */
    public function testGetPatch($version, $major, $minor, $patch, $pres, $posts, $appended)
    {
        $v = new Version($version);
        if (null == $patch) {
            $this->assertSame(0, $v->getPatch());
            $this->assertSame($patch, $v->getPatch(false));
        } else {
            $this->assertSame($patch, $v->getPatch());
        }
    }

    /**
     * tests pre appended levels of version strings
     * @param string $version
     * @param string $major
     * @param string $minor
     * @param string $patch
     * @param array  $pres
     * @param array  $posts
     * @param string $appended
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getPres
     */
    public function testGetPres($version, $major, $minor, $patch, $pres, $posts, $appended)
    {
        $v = new Version($version);
        $this->assertSame($pres, $v->getPres());
    }

    /**
     * tests post appended levels of version strings
     * @param string $version
     * @param string $major
     * @param string $minor
     * @param string $patch
     * @param array  $pres
     * @param array  $posts
     * @param string $appended
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getPosts
     */
    public function testGetPosts($version, $major, $minor, $patch, $pres, $posts, $appended)
    {
        $v = new Version($version);
        $this->assertSame($posts, $v->getPosts());
    }

    /**
     * tests appended level of version strings
     * @param string $version
     * @param string $major
     * @param string $minor
     * @param string $patch
     * @param array  $pres
     * @param array  $posts
     * @param string $appended
     * @dataProvider componentProvider
     * @covers \PhSemVer\Entity\Version::getAppendedString
     */
    public function testGetAppendedString($version, $major, $minor, $patch, $pres, $posts, $appended)
    {
        $v = new Version($version);
        $this->assertSame($appended, $v->getAppendedString());
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
        $this->assertSame($updateMajor, $v->updateMajor()->__toString());
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
        $this->assertSame($updateMinor, $v->updateMinor()->__toString());
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
        $this->assertSame($updatePatch, $v->updatePatch()->__toString());
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
        $this->assertSame($stable, $v->isStable());
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
        $method->setAccessible(true);
        $this->assertSame($compareArray, $method->invoke(new Version('1'), $version));
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
            array('1.2.3', 1, 2, 3, array(), array(), ''),
            array('1.2.3-beta', 1, 2, 3, array('beta'), array(), '-beta'),
            array('1.2.3-alpha.4', 1, 2, 3, array('alpha', 4), array(), '-alpha.4'),
            array('1.2.3-alpha.4+build.5', 1, 2, 3, array('alpha', 4), array('build', 5), '-alpha.4+build.5'),
            array('1.2.3+patch.4.5.a.b', 1, 2, 3, array(), array('patch', 4, 5, 'a', 'b'), '+patch.4.5.a.b'),
            array('1', 1, null, null, array(), array(), ''),
            array('1.2', 1, 2, null, array(), array(), ''),
            array('1.2.3.4', 1, 2, 3, array(), array(), ''),
            array('1.2.3-', 1, 2, 3, array(), array(), ''),
            array('1.2.3+', 1, 2, 3, array(), array(), ''),
            array('1.2.3-a.', 1, 2, 3, array('a'), array(), '-a'),
            array('1.2.3-a+', 1, 2, 3, array('a'), array(), '-a'),
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
