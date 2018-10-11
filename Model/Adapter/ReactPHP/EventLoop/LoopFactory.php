<?php

declare(strict_types=1);

/**
 * File: LoopFactory.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Model\Adapter\ReactPHP\EventLoop;

use LizardMedia\AdminIndexer\Api\Adapter\ReactPHP\EventLoop\LoopFactoryInterface;
use React\EventLoop\LoopInterface;
use React\EventLoop\Factory;

/**
 * Class LoopFactory
 * @package LizardMedia\AdminIndexer\Model\Adapter\ReactPHP\EventLoop
 */
class LoopFactory implements LoopFactoryInterface
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * LoopFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @return LoopInterface
     */
    public function create(): LoopInterface
    {
        return $this->factory->create();
    }
}