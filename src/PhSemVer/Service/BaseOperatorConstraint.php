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

namespace PhSemVer\Service;

use PhSemVer\Entity\Version;
use PhSemVer\Exception\InvalidArgumentException;

/**
 * Model of semantic version base operator constraint.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
class BaseOperatorConstraint extends AbstractSemVerAware implements ConstraintInterface
{
    /**
     * Known operators of constraint
     *
     * @var string
     */
    protected $operators = array(
        '<' => 'compareLess',
        '<=' => 'compareLessOrEqual',
        '>' => 'compareGreater',
        '>=' => 'compareGreaterOrEqual',
        '==' => 'compareEqual',
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
    protected $version;

    /**
     * Create constraint from operator and version
     *
     * @param string                   $operator
     * @param string                   $version
     * @param \PhSemVer\Service\SemVer $semVerService
     */
    public function __construct($operator, Version $version, SemVer $semVerService = null)
    {
        if (!array_key_exists($operator, $this->operators)) {
            throw new InvalidArgumentException('invalid oparator "' . $operator . '" provided');
        }
        $this->operator = $operator;
        $this->version = $version;
        if (null !== $semVerService) {
            $this->setSemVerService($semVerService);
        }
    }

    /**
     * Check, if semantic version matches constraint
     *
     * @param  Version $version
     * @return boolean
     */
    public function match(Version $version)
    {
        $cmp = $this->getSemVerService()->compareVersions($this->version, $version);
        $method = $this->operators[$this->operator];
        return $this->$method($cmp);
    }

    /**
     * Convert constraint to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->operator . $this->version;
    }

    /**
     * Compare with constraint <
     *
     * @param  int $cmp
     * @return boolean
     */
    protected function compareLess($cmp)
    {
        return 0 < $cmp;
    }

    /**
     * Compare with constraint <=
     *
     * @param  int $cmp
     * @return boolean
     */
    protected function compareLessOrEqual($cmp)
    {
        return 0 <= $cmp;
    }

    /**
     * Compare with constraint >
     *
     * @param  int $cmp
     * @return boolean
     */
    protected function compareGreater($cmp)
    {
        return 0 > $cmp;
    }

    /**
     * Compare with constraint >=
     *
     * @param  int $cmp
     * @return boolean
     */
    protected function compareGreaterOrEqual($cmp)
    {
        return 0 >= $cmp;
    }

    /**
     * Compare with constraint ==
     *
     * @param  int $cmp
     * @return boolean
     */
    protected function compareEqual($cmp)
    {
        return 0 == $cmp;
    }
}
