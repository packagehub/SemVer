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

use PhSemVer\Service\OrConstraint;
use PhSemVer\Entity\Version;

/**
 * Test of or constraint.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class OrConstraintTest extends \PHPUnit_Framework_TestCase
{
    /**
     * tests list of or constraints
     * @param \PhSemVer\Service\ConstraintInterface[] $constraints
     * @param \PhSemVer\Entity\Version                $version
     * @param bool                                    $match
     * @param string                                  $string
     * @dataProvider orProvider
     * @covers \PhSemVer\Service\OrConstraint::__construct
     * @covers \PhSemVer\Service\OrConstraint::match
     * @covers \PhSemVer\Service\OrConstraint::__toString
     */
    public function testOr($constraints, Version $version, $match, $string)
    {
        $oc = new OrConstraint($constraints);
        $this->assertEquals($match, $oc->match($version));
        $this->assertEquals($string, $oc->__toString());
    }

    /**
     * provide list of or constraints, match results and strings
     * @return array
     */
    public function orProvider()
    {
        $true = new TrueConstraint();
        $false = new FalseConstraint();
        $version = new Version('1');

        return array(
            array(array($true, $true, $true), $version, true, '(true || true || true)'),
            array(array($false, $true, $true), $version, true, '(false || true || true)'),
            array(array($true, $false, $true), $version, true, '(true || false || true)'),
            array(array($true, $true, $false), $version, true, '(true || true || false)'),
            array(array($false, $false, $true), $version, true, '(false || false || true)'),
            array(array($false, $true, $false), $version, true, '(false || true || false)'),
            array(array($true, $false, $false), $version, true, '(true || false || false)'),
            array(array($false, $false, $false), $version, false, '(false || false || false)'),
        );
    }
}
