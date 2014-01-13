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

namespace PhSemVerTest;

use PhSemVer\Service\SemVer;
use PhSemVer\Entity\Version;

/**
 * test sorting of version arrays and comparing of two versions
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class SemVerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * SemVer instance
     * @var \PhSemVer\Service\SemVer
     */
    protected $semVer;

    /**
     * initialize SemVer instance
     */
    protected function setUp()
    {
        $this->semVer = new SemVer();
    }

    /**
     * tests comparing of version strings
     * @param string $v1
     * @param string $v2
     * @param string $method
     * @dataProvider compareProvider
     * @covers \PhSemVer\Service\SemVer::compareVersionStrings
     */
    public function testCompareVersionStrings($v1, $v2, $method)
    {
        $this->$method(0, $this->semVer->compareVersionStrings($v1, $v2));
    }

    /**
     * tests comparing of version strings
     * @param string $v1
     * @param string $v2
     * @param string $method
     * @dataProvider compareProvider
     * @covers \PhSemVer\Service\SemVer::compareVersions
     * @covers \PhSemVer\Service\SemVer::compareVersionLevels
     * @covers \PhSemVer\Service\SemVer::compareAppendedVersionLevels
     * @covers \PhSemVer\Service\SemVer::compareArray
     */
    public function testCompareVersions($v1, $v2, $method)
    {
        $version1 = new Version($v1);
        $version2 = new Version($v2);
        $this->$method(0, $this->semVer->compareVersions($version1, $version2));
    }

    /**
     * tests sorting a list of version strings
     * @param array $unsortedVersions
     * @param array $sortedVersions
     * @dataProvider sortVersionStringsProvider
     * @covers \PhSemVer\Service\SemVer::sortVersionStrings
     */
    public function testSortVersionStrings(array $unsortedVersions, array $sortedVersions)
    {
        $this->assertTrue($this->semVer->sortVersionStrings($unsortedVersions));
        $this->assertEquals($unsortedVersions, $sortedVersions);
    }

    /**
     * tests sorting a list of version instances
     * @param array $unsortedVersions
     * @param array $sortedVersions
     * @dataProvider sortVersionsProvider
     * @covers \PhSemVer\Service\SemVer::sortVersions
     */
    public function testSortVersions(array $unsortedVersions, array $sortedVersions)
    {
        $this->assertTrue($this->semVer->sortVersions($unsortedVersions));
        $this->assertEquals($unsortedVersions, $sortedVersions);
    }

    /**
     * tests compare type
     * @param string $version1
     * @param string $version2
     * @param mixed $result
     * @dataProvider comparePartProvider
     * @covers \PhSemVer\Service\SemVer::comparePart
     */
    public function testComparePart($version1, $version2, $result)
    {
        $method = new \ReflectionMethod('\PhSemVer\Service\SemVer', 'comparePart');
        $method->setAccessible(true);
        $this->assertSame($result, $method->invoke($this->semVer, $version1, $version2));
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
     * provide list of valid version strings
     * @return array
     */
    public function sortVersionStringsProvider()
    {
        return array(
            array(
                array(
                    '1.2.4',
                    '1.2.3',
                    '1.2.3-beta',
                    '1.2.3-4-5',
                    '1.2.3-4',
                    '1.2.3-4+5',
                    '1.2.3-14.31',
                    '1.2.3+patch.4.5.blah.blubb',
                    '1.2.3-14.5',
                    '1.2.3-14.4',
                    '1.2.3-beta.5',
                    '1.2.3-124',
                    '1.2.3+patch.45',
                    '1.2.3-beta',
                    '1.2.3',
                    '1.2.3-alpha.4',
                    '1.2.3-alpha.a',
                    '1.2.3-c',
                    '1.2.3+patch.145'
                ),
                array(
                    '1.2.3-4',
                    '1.2.3-4+5',
                    '1.2.3-14.4',
                    '1.2.3-14.5',
                    '1.2.3-14.31',
                    '1.2.3-124',
                    '1.2.3-4-5',
                    '1.2.3-alpha.4',
                    '1.2.3-alpha.a',
                    '1.2.3-beta',
                    '1.2.3-beta',
                    '1.2.3-beta.5',
                    '1.2.3-c',
                    '1.2.3',
                    '1.2.3',
                    '1.2.3+patch.4.5.blah.blubb',
                    '1.2.3+patch.45',
                    '1.2.3+patch.145',
                    '1.2.4'
                )
            )
        );
    }

    /**
     * provide list of invalid version strings
     * @return array
     */
    public function sortVersionsProvider()
    {
        return array(
            array(
                array(
                    new Version('1.2.4'),
                    new Version('1.2.3'),
                    new Version('1.2.3-beta'),
                    new Version('1.2.3-4-5'),
                    new Version('1.2.3-4'),
                    new Version('1.2.3-4+5'),
                    new Version('1.2.3-14.31'),
                    new Version('1.2.3+patch.4.5.blah.blubb'),
                    new Version('1.2.3-14.5'),
                    new Version('1.2.3-14.4'),
                    new Version('1.2.3-beta.5'),
                    new Version('1.2.3-124'),
                    new Version('1.2.3+patch.45'),
                    new Version('1.2.3-beta'),
                    new Version('1.2.3'),
                    new Version('1.2.3-alpha.4'),
                    new Version('1.2.3-alpha.a'),
                    new Version('1.2.3-c'),
                    new Version('1.2.3+patch.145')
                ),
                array(
                    new Version('1.2.3-4'),
                    new Version('1.2.3-4+5'),
                    new Version('1.2.3-14.4'),
                    new Version('1.2.3-14.5'),
                    new Version('1.2.3-14.31'),
                    new Version('1.2.3-124'),
                    new Version('1.2.3-4-5'),
                    new Version('1.2.3-alpha.4'),
                    new Version('1.2.3-alpha.a'),
                    new Version('1.2.3-beta'),
                    new Version('1.2.3-beta'),
                    new Version('1.2.3-beta.5'),
                    new Version('1.2.3-c'),
                    new Version('1.2.3'),
                    new Version('1.2.3'),
                    new Version('1.2.3+patch.4.5.blah.blubb'),
                    new Version('1.2.3+patch.45'),
                    new Version('1.2.3+patch.145'),
                    new Version('1.2.4')
                )
            )
        );
    }

    /**
     * provide list of compare part strings
     * @return array
     */
    public function comparePartProvider()
    {
        return array(
            array(1, 1, 0),
            array(2, 1, 1),
            array(1, 2, -1),
            array(3, 'foo', -1),
            array('bar', 4, 1),
            array('foo', 'bar', strcasecmp('foo', 'bar')),
            array('foo', 'foo', strcasecmp('foo', 'foo')),
        );
    }
}
