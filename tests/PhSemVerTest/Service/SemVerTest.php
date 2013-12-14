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
     * @var \SemVer\SemVer
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
     * @param int $result
     * @dataProvider compareVersionStringsProvider
     * @covers \PhSemVer\Service\SemVer::compareVersionStrings
     */
    public function testCompareVersionStrings($v1, $v2, $result)
    {
        $this->assertEquals($this->semVer->compareVersionStrings($v1, $v2), $result);
    }

    /**
     * tests comparing of version strings
     * @param Version $v1
     * @param Version $v2
     * @param int $result
     * @dataProvider compareVersionsProvider
     * @covers \PhSemVer\Service\SemVer::compareVersions
     */
    public function testCompareVersions(Version $v1, Version $v2, $result)
    {
        $this->assertEquals($this->semVer->compareVersions($v1, $v2), $result);
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
     * provide list of version string pairs with result
     * @return array
     */
    public function compareVersionStringsProvider()
    {
        return array(
            array('1.2', '1.3', -1),
        );
    }

    /**
     * provide list of version pairs with result
     * @return array
     */
    public function compareVersionsProvider()
    {
        return array(
            array(new Version('1.2'), new Version('1.3'), -1),
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
}
