<?php
/**
 * This file is part of SemVer.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2014 Gordon Schmidt
 * @license   MIT
 */

namespace PhSemVerTest\Service;

use PhSemVer\Service\SemVer;

/**
 * Test of and constraint.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class AbstractSemVerAwareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * tests setter and getter of abstract SemVer aware class
     * @covers \PhSemVer\Service\AbstractSemVerAware::setSemVerService
     * @covers \PhSemVer\Service\AbstractSemVerAware::getSemVerService
     */
    public function testSetterGetter()
    {
        $semVerService = new SemVer();
        $stub = $this->getMockForAbstractClass('\PhSemVer\Service\AbstractSemVerAware');
        $stubSemVerService = $stub->getSemVerService();
        $this->assertEquals($semVerService, $stubSemVerService);
        $this->assertNotSame($semVerService, $stubSemVerService);
        $stub->setSemVerService($semVerService);
        $this->assertSame($semVerService, $stub->getSemVerService());
    }
}
