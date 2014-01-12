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

use PhSemVer\Service\AndConstraint;
use PhSemVer\Entity\Version;

/**
 * Test of and constraint.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class AndConstraintTest extends \PHPUnit_Framework_TestCase
{
    /**
     * tests list of and constraints
     * @param \PhSemVer\Service\ConstraintInterface[] $constraints
     * @param \PhSemVer\Entity\Version                $version
     * @param bool                                    $match
     * @param string                                  $string
     * @dataProvider andProvider
     * @covers \PhSemVer\Service\AndConstraint::__construct
     * @covers \PhSemVer\Service\AndConstraint::match
     * @covers \PhSemVer\Service\AndConstraint::__toString
     */
    public function testAnd($constraints, Version $version, $match, $string)
    {
        $ac = new AndConstraint($constraints);
        $this->assertEquals($match, $ac->match($version));
        $this->assertEquals($string, $ac->__toString());
    }

    /**
     * provide list of and constraints, match results and strings
     * @return array
     */
    public function andProvider()
    {
        $true = new TrueConstraint();
        $false = new FalseConstraint();
        $version = new Version('1');

        return array(
            array(array($true, $true, $true), $version, true, '(true && true && true)'),
            array(array($false, $true, $true), $version, false, '(false && true && true)'),
            array(array($true, $false, $true), $version, false, '(true && false && true)'),
            array(array($true, $true, $false), $version, false, '(true && true && false)'),
            array(array($false, $false, $true), $version, false, '(false && false && true)'),
            array(array($false, $true, $false), $version, false, '(false && true && false)'),
            array(array($true, $false, $false), $version, false, '(true && false && false)'),
            array(array($false, $false, $false), $version, false, '(false && false && false)'),
        );
    }
}
