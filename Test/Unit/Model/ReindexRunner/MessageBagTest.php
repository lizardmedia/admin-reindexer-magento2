<?php

declare(strict_types=1);

/**
 * File: MessageBagTest.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Unit\Test\Model\ReindexRunner;

use LizardMedia\AdminIndexer\Model\ReindexRunner\MessageBag;
use PHPUnit\Framework\TestCase;

/**
 * Class MessageBagTest
 * @package LizardMedia\AdminIndexer\Unit\Test\Model\ReindexRunner
 */
class MessageBagTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function testAddMessage(): void
    {
        $messageBag = new MessageBag();
        $messageBag->addMessage('test');
        $this->assertAttributeSame(['test'], 'messages', $messageBag);
    }

    /**
     * @test
     * @return void
     */
    public function testGetMessage(): void
    {
        $messageBag = new MessageBag();
        $messageBag->addMessage('test');
        $messageBag->addMessage('test1');

        $this->assertSame(['test', 'test1'], $messageBag->getMessages());
    }
}
