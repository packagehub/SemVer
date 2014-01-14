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

use PhSemVer\Constraint\ConstraintInterface;
use PhSemVer\Constraint\NotConstraint;
use PhSemVer\Entity\Version;

/**
 * Test of not constraint.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class NotConstraintTest extends \PHPUnit_Framework_TestCase
{
    /**
     * tests list of not constraints
     * @param \PhSemVer\Constraint\ConstraintInterface $constraint
     * @param \PhSemVer\Entity\Version                 $version
     * @param bool                                     $match
     * @param string                                   $string
     * @dataProvider notProvider
     * @covers \PhSemVer\Constraint\NotConstraint::__construct
     * @covers \PhSemVer\Constraint\NotConstraint::match
     * @covers \PhSemVer\Constraint\NotConstraint::__toString
     */
    public function testNot(ConstraintInterface $constraint, Version $version, $match, $string)
    {
        $nc = new NotConstraint($constraint);
        $this->assertEquals($match, $nc->match($version));
        $this->assertEquals($string, $nc->__toString());
    }

    /**
     * provide list of not constraints, match results and strings
     * @return array
     */
    public function notProvider()
    {
        $true = new TrueConstraint();
        $false = new FalseConstraint();
        $version = new Version('1');

        return array(
            array($true, $version, false, '!true'),
            array($false, $version, true, '!false'),
        );
    }
}
