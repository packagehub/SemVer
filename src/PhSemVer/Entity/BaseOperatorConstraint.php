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
        if (!in_array($operator, array('<', '<=', '>', '>=', '=='))) {
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
        return eval('return 0 ' . $this->operator . ' $this->semVer->compare($version);');
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
}
