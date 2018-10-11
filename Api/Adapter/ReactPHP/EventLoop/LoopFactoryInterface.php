<?php

declare(strict_types=1);

/**
 * File: LoopFactoryInterface.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Api\Adapter\ReactPHP\EventLoop;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

/**
 * Interface LoopFactoryInterface
 * @package LizardMedia\AdminIndexer\Api\Adapter\ReactPHP\EventLoop
 */
interface LoopFactoryInterface
{
    /**
     * @return LoopInterface
     */
    public function create(): LoopInterface;
}