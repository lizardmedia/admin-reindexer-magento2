<?php

declare(strict_types=1);

/**
 * File: AsyncReindexRunnerTest.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Test\Unit\Model\ReindexRunner;

use LizardMedia\AdminIndexer\Api\Adapter\ReactPHP\ChildProcess\ProcessFactoryInterface;
use LizardMedia\AdminIndexer\Api\Adapter\ReactPHP\EventLoop\LoopFactoryInterface;
use LizardMedia\AdminIndexer\Api\ReindexRunner\MessageBagInterface;
use LizardMedia\AdminIndexer\Exception\ReindexFailureAggregateException;
use LizardMedia\AdminIndexer\Model\ReindexRunner\AsyncReindexRunner;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Indexer\IndexerInterface;
use Magento\Framework\Indexer\IndexerRegistry;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit\Framework\TestCase;
use React\ChildProcess\Process as ChildProcess;
use React\EventLoop\LoopInterface;

/**
 * Class AsyncReindexRunnerTest
 * @package LizardMedia\AdminIndexer\Test\Unit\Model\ReindexRunner
 */
class AsyncReindexRunnerTest extends TestCase
{
    /**
     * @var array
     */
    private $exampleIndexes;

    /**
     * @var ProcessFactoryInterface | MockObject
     */
    private $childProcessFactoryMock;

    /**
     * @var LoopFactoryInterface | MockObject
     */
    private $loopFactory;

    /**
     * @var MessageBagInterface | MockObject
     */
    private $messageBagMock;

    /**
     * @var ReindexFailureAggregateException | MockObject
     */
    private $reindexFailureAggregateExceptionMock;

    /**
     * @var AsyncReindexRunner
     */
    private $asyncReindexRunner;

    /**
     * @var DirectoryList | MockObject
     */
    private $directoryListMock;

    /**
     * @var IndexerInterface | MockObject
     */
    private $indexerMock;

    /**
     * @var IndexerRegistry | MockObject
     */
    private $indexerRegistryMock;

    /**
     * @var ChildProcess | MockObject
     */
    private $childProcess;

    /**
     * @var LoopInterface | MockObject
     */
    private $loopMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        //Internal mocks
        $this->exampleIndexes = ['catalog_product', 'customer_grid'];
        $this->reindexFailureAggregateExceptionMock = $this->getMockBuilder(ReindexFailureAggregateException::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->indexerMock = $this->getMockBuilder(IndexerInterface::class)->getMock();
        $this->loopMock = $this->getMockBuilder(LoopInterface::class)->getMock();

        //Dependencies mocks
        $this->childProcessFactoryMock = $this->getMockBuilder(ProcessFactoryInterface::class)->getMock();
        $this->loopFactory = $this->getMockBuilder(LoopFactoryInterface::class)->getMock();
        $this->messageBagMock = $this->getMockBuilder(MessageBagInterface::class)->getMock();
        $this->directoryListMock = $this->getMockBuilder(DirectoryList::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->childProcess = $this->getMockBuilder(ChildProcess::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->indexerRegistryMock = $this->getMockBuilder(IndexerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->asyncReindexRunner = new AsyncReindexRunner(
            $this->childProcessFactoryMock,
            $this->loopFactory,
            $this->messageBagMock,
            $this->directoryListMock,
            $this->indexerRegistryMock
        );
    }

    /**
     * @test
     * @return void
     */
    public function testRunWhenProcessStartingThrownException(): void
    {
        $this->initialExpectations();
        $this->childProcess->expects($this->once())
            ->method('start')
            ->with($this->loopMock)
            ->willThrowException(new \RuntimeException);
        $this->loopMock->expects($this->never())->method('run');

        $this->expectException(ReindexFailureAggregateException::class);
        $this->asyncReindexRunner->run(...$this->exampleIndexes);
    }

    /**
     * @test
     * @return void
     */
    public function testRunWhenSucceed(): void
    {
        $this->initialExpectations();
        $this->childProcess->expects($this->once())
            ->method('start');
        $this->loopMock->expects($this->once())
            ->method('run');

        $this->asyncReindexRunner->run(...$this->exampleIndexes);
    }

    /**
     * @return void
     */
    private function initialExpectations(): void
    {
        $this->messageBagMock->expects($this->exactly(2))
            ->method('addMessage');
        $this->loopFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->loopMock);
        $this->directoryListMock->expects($this->once())
            ->method('getRoot')
            ->willReturn('/var/www/html');
        $this->childProcessFactoryMock->expects($this->once())
            ->method('create')
            ->with(sprintf('php /var/www/html/bin/magento indexer:reindex %s > /dev/null 2>&1 &', implode(' ', $this->exampleIndexes)))
            ->willReturn($this->childProcess);
    }
}
