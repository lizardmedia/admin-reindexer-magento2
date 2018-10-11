<?php

declare(strict_types=1);

/**
 * File: ProcessFactoryTest.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Test\Unit\Model\Adapter\ReactPHP;

use LizardMedia\AdminIndexer\Model\Adapter\ReactPHP\ChildProcess\ProcessFactory;
use PHPUnit\Framework\TestCase;
use React\ChildProcess\Process;

/**
 * Class ProcessFactoryTest
 * @package LizardMedia\AdminIndexer\Test\Unit\Model\Adapter\ReactPHP\ChildProcess
 */
class ProcessFactoryTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testCreateReturnsCorrectInstance(): void
    {
        $processFactory = new ProcessFactory();
        $this->assertInstanceOf(
            Process::class,
            $processFactory->create('ls')
        );
    }
}
