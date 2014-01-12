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

use PhSemVer\Service\ConstraintInterface;
use PhSemVer\Service\NotConstraint;
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
     * @param \PhSemVer\Service\ConstraintInterface $constraint
     * @param \PhSemVer\Entity\Version              $version
     * @param bool                                  $match
     * @param string                                $string
     * @dataProvider notProvider
     * @covers \PhSemVer\Service\NotConstraint::__construct
     * @covers \PhSemVer\Service\NotConstraint::match
     * @covers \PhSemVer\Service\NotConstraint::__toString
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
