<?php

declare(strict_types=1);

/**
 * File: MassReindexTest.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Test\Unit\Controller\Adminhtml\Indexer;

use LizardMedia\AdminIndexer\Api\IndexerProcessorInterface;
use LizardMedia\AdminIndexer\Api\ReindexRunner\MessageBagInterface;
use LizardMedia\AdminIndexer\Controller\Adminhtml\Indexer\MassReindex;
use LizardMedia\AdminIndexer\Exception\ReindexFailureAggregateException;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class MassReindexTest
 * @package LizardMedia\AdminIndexer\Test\Unit\Controller\Adminhtml\Indexer
 */
class MassReindexTest extends TestCase
{
    /**
     * @var IndexerProcessorInterface | MockObject
     */
    private $indexerProcessorMock;

    /**
     * @var MessageBagInterface | MockObject
     */
    private $messageBagMock;

    /**
     * @var MassReindex
     */
    private $massReindex;

    /**
     * @var ReindexFailureAggregateException
     */
    private $reindexFailureAggregateException;

    /**
     * @var Redirect | MockObject
     */
    private $redirectMock;

    /**
     * @var RequestInterface | MockObject
     */
    private $requestMock;

    /**
     * @var RedirectFactory | MockObject
     */
    private $redirectFactoryMock;

    /**
     * @var ManagerInterface | MockObject
     */
    private $messageManager;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        //Internal mocks
        $this->reindexFailureAggregateException = new ReindexFailureAggregateException(__('Some message'));
        $context = $this->getMockBuilder(Context::class)->disableOriginalConstructor()->getMock();
        $this->requestMock = $this->getMockBuilder(RequestInterface::class)->getMock();
        $this->redirectMock = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->messageManager = $this->getMockBuilder(ManagerInterface::class)->getMock();

        //Dependencies mocks
        $this->messageBagMock = $this->getMockBuilder(MessageBagInterface::class)->getMock();
        $this->redirectFactoryMock = $this->getMockBuilder(RedirectFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->indexerProcessorMock = $this->getMockBuilder(IndexerProcessorInterface::class)->getMock();

        $context->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->requestMock);
        $context->expects($this->once())
            ->method('getMessageManager')
            ->willReturn($this->messageManager);

        $this->massReindex = new MassReindex(
            $this->messageBagMock,
            $context,
            $this->redirectFactoryMock,
            $this->indexerProcessorMock
        );
    }


    /**
     * @test
     * @dataProvider provideInvalidRequestParamsExamples
     * @param $invalidParam
     * @return void
     */
    public function testExecuteWhenParamsAreInvalid($invalidParam): void
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('indexer_ids')
            ->willReturn($invalidParam);
        $this->gettingRedirectExpectations();
        $this->massReindex->execute();
    }


    /**
     * @return array
     */
    public function provideInvalidRequestParamsExamples(): array
    {
        return [
            [[]],
            [''],
            ['test'],
            [1],
            [null]
        ];
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteWhenIndexerProcessorThrowsException(): void
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('indexer_ids')
            ->willReturn(['catalog_product', 'customer_grid']);
        $this->indexerProcessorMock->expects($this->once())
            ->method('process')
            ->with('catalog_product', 'customer_grid')
            ->willThrowException($this->reindexFailureAggregateException);
        $this->simulateTwoIndexerErros();

        $this->messageBagMock->expects($this->never())->method('getMessages');

        $this->messageManager->expects($this->exactly(3))
            ->method('addErrorMessage')
            ->withConsecutive(
                [$this->reindexFailureAggregateException->getMessage()],
                ['sth'],
                ['sth else']
            );
        $this->gettingRedirectExpectations();
        $this->massReindex->execute();
    }

    /**
     * @test
     * @return void
     */
    public function testExecuteWhenSucceed(): void
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('indexer_ids')
            ->willReturn(['catalog_product', 'customer_grid']);
        $this->indexerProcessorMock->expects($this->once())
            ->method('process')
            ->with('catalog_product', 'customer_grid');
        $this->expectationsForInformingAboutIndexersRunning();
        $this->gettingRedirectExpectations();
        $this->massReindex->execute();
    }


    /**
     * @return void
     */
    private function gettingRedirectExpectations(): void
    {
        $this->redirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->redirectMock);
        $this->redirectMock->expects($this->once())
            ->method('setPath')
            ->with('indexer/indexer/list')
            ->willReturnSelf();
    }

    /**
     * @return void
     */
    private function expectationsForInformingAboutIndexersRunning(): void
    {
        $this->messageBagMock->expects($this->once())
            ->method('getMessages')
            ->willReturn(['one', 'two']);
        $this->messageManager->expects($this->exactly(2))
            ->method('addNoticeMessage')
            ->withConsecutive(['one'], ['two']);
    }

    /**
     * @return void
     */
    private function simulateTwoIndexerErros(): void
    {
        $this->reindexFailureAggregateException->addError(__('sth'));
        $this->reindexFailureAggregateException->addError(__('sth else'));
    }
}
