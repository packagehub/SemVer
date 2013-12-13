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
     * @dataProvider validProvider
     */
    public function testValidVersions($version)
    {
        $v = new Version($version);
        $this->assertEquals($version, $v->__toString());
    }

    /**
     * tests list of invalid version strings
     * @param string $providedVersion
     * @param string $createdVersion
     * @dataProvider invalidProvider
     */
    public function testInvalidVersions($providedVersion, $createdVersion)
    {
        $v = new Version($providedVersion);
        $this->assertEquals($createdVersion, $v->__toString());
    }

    /**
     * provide list of valid version strings
     * @return array
     */
    public function validProvider()
    {
        return array(
            array('1.2.3'),
            array('1.2.3-beta'),
            array('1.2.3-alpha.4'),
            array('1.2.3-alpha.4+build.5'),
            array('1.2.3+patch.4.5.blah.blubb')
        );
    }

    /**
     * provide list of changed version strings
     * @return array
     */
    public function invalidProvider()
    {
        return array(
            array('1.2', '1.2.0'),
            array('1.2.3.4', '1.2.3'),
            array('1.2.3-', '1.2.3'),
            array('1.2.3+', '1.2.3'),
            array('1.2.3-a.', '1.2.3-a'),
            array('1.2.3-a+', '1.2.3-a'),
            array('1.2.3-.', '1.2.3')
        );
    }
}
