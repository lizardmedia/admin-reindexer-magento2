<?php

declare(strict_types=1);

/**
 * File: SyncReindexRunnerTest.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Unit\Test\Model\ReindexRunner;

use LizardMedia\AdminIndexer\Exception\ReindexFailureAggregateException;
use LizardMedia\AdminIndexer\Model\ReindexRunner\SyncReindexRunner;
use Magento\Framework\Indexer\IndexerInterface;
use Magento\Framework\Indexer\IndexerRegistry;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class SyncReindexRunnerTest
 * @package LizardMedia\AdminIndexer\Unit\Test\Model\ReindexRunner
 */
class SyncReindexRunnerTest extends TestCase
{
    /**
     * @var array
     */
    private $exampleIndexes;

    /**
     * @var ReindexFailureAggregateException | MockObject
     */
    private $reindexFailureAggregateExceptionMock;

    /**
     * @var SyncReindexRunner
     */
    private $syncReindexRunner;

    /**
     * @var IndexerInterface | MockObject
     */
    private $indexerMock;

    /**
     * @var IndexerRegistry | MockObject
     */
    private $indexerRegistryMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        //Internal mocks
        $this->exampleIndexes = ['catalog_product', 'customer_grid'];
        $this->reindexFailureAggregateExceptionMock = $this->getMockBuilder(ReindexFailureAggregateException::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['addError', 'getErrors'])
            ->getMock();
        $this->indexerMock = $this->getMockBuilder(IndexerInterface::class)->getMock();

        //Dependencies mocks
        $this->indexerRegistryMock = $this->getMockBuilder(IndexerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->syncReindexRunner = new SyncReindexRunner($this->indexerRegistryMock);
    }

    /**
     * @test
     * @return void
     */
    public function testRunWhenIndexerThrowsException(): void
    {
        $this->indexerRegistryMock->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(
                [$this->exampleIndexes[0]],
                [$this->exampleIndexes[1]]
            )->willReturn($this->indexerMock);

        $indexerException = new \Exception('Something went wrong');

        $this->indexerMock->expects($this->at(0))
            ->method('reindexAll')
            ->willThrowException($indexerException);
        $this->indexerMock->expects($this->at(1))
            ->method('reindexAll');

        $this->expectException(ReindexFailureAggregateException::class);
        $this->syncReindexRunner->run(...$this->exampleIndexes);
        $this->assertCount(1, $this->reindexFailureAggregateExceptionMock->getErrors());
    }

    /**
     * @test
     * @return void
     */
    public function testRunWhenSucceed(): void
    {
        $this->indexerRegistryMock->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(
                [$this->exampleIndexes[0]],
                [$this->exampleIndexes[1]]
            )->willReturn($this->indexerMock);

        $this->indexerMock->expects($this->exactly(2))
            ->method('reindexAll');

        $this->syncReindexRunner->run(...$this->exampleIndexes);
    }
}
