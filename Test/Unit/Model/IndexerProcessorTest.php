<?php

declare(strict_types=1);

/**
 * File: IndexerProcessorTest.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Unit\Test\Model;

use LizardMedia\AdminIndexer\Api\ReindexRunnerInterface;
use LizardMedia\AdminIndexer\Exception\ReindexFailureAggregateException;
use LizardMedia\AdminIndexer\Model\IndexerProcessor;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class IndexerProcessorTest
 * @package LizardMedia\AdminIndexer\Test\Unit\Model
 */
class IndexerProcessorTest extends TestCase
{
    /**
     * @var ReindexRunnerInterface | MockObject
     */
    private $reindexRunnerMock;

    /**
     * @var IndexerProcessor
     */
    private $indexerProcessor;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->reindexRunnerMock = $this->getMockBuilder(ReindexRunnerInterface::class)->getMock();
        $this->indexerProcessor = new IndexerProcessor($this->reindexRunnerMock);
    }

    /**
     * @test
     * @return void
     */
    public function testWhenReindexeRunnerThrowsException(): void
    {
        $this->reindexRunnerMock->expects($this->once())
            ->method('run')
            ->withAnyParameters()
            ->willThrowException(new ReindexFailureAggregateException(__()));
        $this->expectException(ReindexFailureAggregateException::class);
        $this->indexerProcessor->process('catalog_category_product', 'catalog_product_category');
    }


    /**
     * @test
     * @return void
     */
    public function testWhenReindexed(): void
    {
        $exampleIndexers = ['catalog_category_product', 'catalog_product_category'];
        $this->reindexRunnerMock->expects($this->once())
            ->method('run')
            ->with(...$exampleIndexers);
        $this->indexerProcessor->process('catalog_category_product', 'catalog_product_category');
    }
}
