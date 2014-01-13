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

use PhSemVer\Service\Constraints;
use PhSemVer\Entity\Version;

/**
 * Test of constraint creation.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class ConstraintsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Instance of constraints service
     *
     * @var \PhSemVer\Service\Constraints
     */
    protected $constraintsService;

    /**
     * initialize constraints service
     */
    protected function setUp()
    {
        $this->constraintsService = new Constraints();
    }

    /**
     * tests list of and constraints
     * @param string $constraintsString
     * @param string $matchString
     * @dataProvider constraintsProvider
     * @covers \PhSemVer\Service\Constraints::create
     * @covers \PhSemVer\Service\Constraints::getConstraints
     * @covers \PhSemVer\Service\Constraints::getOperatorConstraint
     * @covers \PhSemVer\Service\Constraints::getNextBigVersion
     */
    public function testCreate($constraintsString, $matchString)
    {
        $this->assertEquals($matchString, $this->constraintsService->create($constraintsString)->__toString());
    }

    /**
     * tests list of and constraints
     * @param string $constraintsString
     * @param string $matchString
     * @expectedException \PhSemVer\Exception\InvalidArgumentException
     * @dataProvider invalidConstraintsProvider
     * @covers \PhSemVer\Service\Constraints::create
     * @covers \PhSemVer\Service\Constraints::getConstraints
     * @covers \PhSemVer\Service\Constraints::getOperatorConstraint
     */
    public function testCreateException($constraintsString)
    {
        $c = $this->constraintsService->create($constraintsString);
    }

    /**
     * provide list of constraints strings
     * @return array
     */
    public function constraintsProvider()
    {
        return array(
            array('1.2.3','(>=1.2.3 && <=1.2.3)'),
            array('=1.2.3', '(>=1.2.3 && <=1.2.3)'),
            array('=1.2', '(>=1.2.0 && <=1.2.' . PHP_INT_MAX . ')'),
            array('==1.2.3', '==1.2.3'),
            array('~1', '>=1.0.0'),
            array('~>1.2', '(>=1.2.0 && <2.0.0)'),
            array('~>1.2.3', '(>=1.2.3 && <1.3.0)'),
            array('>=1.2.3 && <1.3', '(>=1.2.3 && <1.3.0)'),
            array('(>=1.2.3 && <1.3)', '(>=1.2.3 && <1.3.0)'),
            array('!1.2.3', '!(>=1.2.3 && <=1.2.3)'),
            array('!1.2', '!(>=1.2.0 && <=1.2.' . PHP_INT_MAX . ')'),
            array('!==1.2.3', '!==1.2.3'),
        );
    }

    /**
     * provide list of invalid constraints strings
     * @return array
     */
    public function invalidConstraintsProvider()
    {
        return array(
            array(''),
            array('foo'),
            array('*'),
            array('<!1'),
        );
    }
}
