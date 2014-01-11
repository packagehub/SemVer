<?php
/**
 * This file is part of SemVer.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2013,2014 Gordon Schmidt
 * @license   MIT
 */

namespace PhSemVer\Entity;

use PhSemVer\Exception\InvalidArgumentException;

/**
 * Model of semantic version base operator constraint.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class BaseOperatorConstraint implements ConstraintInterface
{
    /**
     * Known operators of constraint
     *
     * @var string
     */
    protected $operators = array(
        '<' => 'matchL',
        '<=' => 'matchLE',
        '>' => 'matchG',
        '>=' => 'matchGE',
        '==' => 'matchE',
    );

    /**
     * Operator of constraint
     *
     * @var string
     */
    protected $operator;

    /**
     * Semantic version of constraint
     *
     * @var Version
     */
    protected $semVer;

    /**
     * Create constraint from operator and version
     *
     * @param string  $operator
     * @param Version $semVer
     */
    public function __construct($operator, Version $semVer)
    {
        if (!array_key_exists($operator, $this->operators)) {
            throw new InvalidArgumentException('invalid oparator "' . $operator . '" provided');
        }
        $this->operator = $operator;
        $this->semVer = $semVer;
    }

    /**
     * Check, if semantic version matches constraint
     *
     * @param  Version $version
     * @return boolean
     */
    public function match(Version $version)
    {
        return $this->operators[$this->operator]($version);
    }

    /**
     * Convert constraint to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->operator . $this->semVer;
    }

    /**
     * Check, if semantic version with constraint <
     *
     * @param  Version $version
     * @return boolean
     */
    protected function matchL(Version $version)
    {
        return 0 < $this->semVer->compare($version);
    }

    /**
     * Check, if semantic version with constraint <=
     *
     * @param  Version $version
     * @return boolean
     */
    protected function matchLE(Version $version)
    {
        return 0 <= $this->semVer->compare($version);
    }

    /**
     * Check, if semantic version with constraint >
     *
     * @param  Version $version
     * @return boolean
     */
    protected function matchG(Version $version)
    {
        return 0 > $this->semVer->compare($version);
    }

    /**
     * Check, if semantic version with constraint >=
     *
     * @param  Version $version
     * @return boolean
     */
    protected function matchGE(Version $version)
    {
        return 0 >= $this->semVer->compare($version);
    }

    /**
     * Check, if semantic version with constraint ==
     *
     * @param  Version $version
     * @return boolean
     */
    protected function matchE(Version $version)
    {
        return 0 == $this->semVer->compare($version);
    }
}
