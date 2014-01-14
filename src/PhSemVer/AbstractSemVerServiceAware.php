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

namespace PhSemVer;

use PhSemVer\Exception\InvalidArgumentException;
use PhSemVer\Service\SemVer;

/**
 * This class provides an abstract service aware of the SemVer service.
 *
 * @author Gordon Schmidt <schmidt.gordon@web.de>
 */
abstract class AbstractSemVerServiceAware
{
    /**
     * Instance of SemVer service
     *
     * @var \PhSemVer\Service\SemVer
     */
    protected $semVerService;

    /**
     * Set SemVer service
     *
     * @param \PhSemVer\Service\SemVer $semVerService
     * @return self
     */
    public function setSemVerService(SemVer $semVerService)
    {
        $this->semVerService = $semVerService;

        return $this;
    }

    /**
     * Get SemVer service
     *
     * @return \PhSemVer\Service\SemVer
     */
    public function getSemVerService()
    {
        if (null === $this->semVerService) {
            $this->semVerService = new SemVer();
        }

        return $this->semVerService;
    }
}
