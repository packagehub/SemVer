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

namespace PhSemVerTest;

use PhSemVer\Service\SemVer;

/**
 * Test of and constraint.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class AbstractSemVerServiceAwareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * tests setter and getter of abstract SemVer service aware class
     * @covers \PhSemVer\AbstractSemVerServiceAware::setSemVerService
     * @covers \PhSemVer\AbstractSemVerServiceAware::getSemVerService
     */
    public function testSetterGetter()
    {
        $semVerService = new SemVer();
        $stub = $this->getMockForAbstractClass('\PhSemVer\AbstractSemVerServiceAware');
        $stubSemVerService = $stub->getSemVerService();
        $this->assertEquals($semVerService, $stubSemVerService);
        $this->assertNotSame($semVerService, $stubSemVerService);
        $stub->setSemVerService($semVerService);
        $this->assertSame($semVerService, $stub->getSemVerService());
    }
}
