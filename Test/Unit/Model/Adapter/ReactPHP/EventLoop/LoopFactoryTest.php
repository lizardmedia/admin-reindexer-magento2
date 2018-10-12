<?php

declare(strict_types=1);

/**
 * File: LoopFactoryTest.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Test\Unit\Model\Adapter\ReactPHP\EventLoop;

use LizardMedia\AdminIndexer\Model\Adapter\ReactPHP\EventLoop\LoopFactory;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

/**
 * Class LoopFactoryTest
 * @package LizardMedia\AdminIndexer\Test\Unit\Model\Adapter\ReactPHP\EventLoop
 */
class LoopFactoryTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testCreateReturnsCorrectInstance(): void
    {
        $loopFactory = new LoopFactory(new Factory());
        $this->assertInstanceOf(
            LoopInterface::class,
            $loopFactory->create()
        );
    }
}