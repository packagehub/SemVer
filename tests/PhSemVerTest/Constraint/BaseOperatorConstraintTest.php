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

namespace PhSemVerTest\Constraint;

use PhSemVer\Constraint\BaseOperatorConstraint;
use PhSemVer\Service\SemVer;
use PhSemVer\Entity\Version;

/**
 * Test of base operator constraints.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class BaseOperatorConstraintTest extends \PHPUnit_Framework_TestCase
{
    /**
     * tests list of base operator constraints
     * @param string                   $operator
     * @param \PhSemVer\Entity\Version $version
     * @param \PhSemVer\Entity\Version $matchVersion
     * @param bool                     $match
     * @param string                   $string
     * @dataProvider baseOperatorProvider
     * @covers \PhSemVer\Constraint\BaseOperatorConstraint::__construct
     * @covers \PhSemVer\Constraint\BaseOperatorConstraint::match
     * @covers \PhSemVer\Constraint\BaseOperatorConstraint::__toString
     * @covers \PhSemVer\Constraint\BaseOperatorConstraint::compareLess
     * @covers \PhSemVer\Constraint\BaseOperatorConstraint::compareLessOrEqual
     * @covers \PhSemVer\Constraint\BaseOperatorConstraint::compareGreater
     * @covers \PhSemVer\Constraint\BaseOperatorConstraint::compareGreaterOrEqual
     * @covers \PhSemVer\Constraint\BaseOperatorConstraint::compareEqual
     */
    public function testBase($constraints, Version $version, Version $matchVersion, $match, $string)
    {
        $semVerService = new SemVer();
        $boc = new BaseOperatorConstraint($constraints, $version, $semVerService);
        $this->assertEquals($match, $boc->match($matchVersion));
        $this->assertEquals($string, $boc->__toString());
    }

    /**
     * tests list of base operator constraints
     * @expectedException \PhSemVer\Exception\InvalidArgumentException
     * @covers \PhSemVer\Constraint\BaseOperatorConstraint::__construct
     */
    public function testInvalidOperator()
    {
        $boc = new BaseOperatorConstraint('?', new Version('1.2.3'));
    }

    /**
     * provide list of base operator constraints, match results and strings
     * @return array
     */
    public function baseOperatorProvider()
    {
        $v122 = new Version('1.2.2');
        $v123 = new Version('1.2.3');
        $v124 = new Version('1.2.4');

        return array(
            array('<', $v123, $v122, true, '<1.2.3'),
            array('<', $v123, $v123, false, '<1.2.3'),
            array('<', $v123, $v124, false, '<1.2.3'),
            array('<=', $v123, $v122, true, '<=1.2.3'),
            array('<=', $v123, $v123, true, '<=1.2.3'),
            array('<=', $v123, $v124, false, '<=1.2.3'),
            array('>', $v123, $v124, true, '>1.2.3'),
            array('>', $v123, $v123, false, '>1.2.3'),
            array('>', $v123, $v122, false, '>1.2.3'),
            array('>=', $v123, $v124, true, '>=1.2.3'),
            array('>=', $v123, $v123, true, '>=1.2.3'),
            array('>=', $v123, $v122, false, '>=1.2.3'),
            array('==', $v123, $v123, true, '==1.2.3'),
            array('==', $v123, $v124, false, '==1.2.3'),
            array('==', $v123, $v122, false, '==1.2.3'),
        );
    }
}
