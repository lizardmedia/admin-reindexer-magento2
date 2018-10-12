<?php

declare(strict_types=1);

/**
 * File: AsyncReindexRunner.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @author Paweł Papke <pawel.papke@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Model\ReindexRunner;

use LizardMedia\AdminIndexer\Api\Adapter\ReactPHP\ChildProcess\ProcessFactoryInterface;
use LizardMedia\AdminIndexer\Api\Adapter\ReactPHP\EventLoop\LoopFactoryInterface;
use LizardMedia\AdminIndexer\Api\ReindexRunnerInterface;
use LizardMedia\AdminIndexer\Api\ReindexRunner\MessageBagInterface;
use LizardMedia\AdminIndexer\Exception\ReindexFailureAggregateException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Indexer\IndexerRegistry;
use React\EventLoop\LoopInterface;
use React\ChildProcess\Process;

/**
 * Class AsyncReindexRunner
 * @package LizardMedia\AdminIndexer\Model\ReindexRunner
 */
class AsyncReindexRunner implements ReindexRunnerInterface
{
    /**
     * @var string
     */
    private const INDEXER_REINDEX_COMMAND = 'bin/magento indexer:reindex';

    /**
     * @var ProcessFactoryInterface
     */
    private $childProcessFactory;

    /**
     * @var LoopFactoryInterface
     */
    private $loopFactory;

    /**
     * @var MessageBagInterface
     */
    private $messageBag;

    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @var IndexerRegistry
     */
    private $indexerRegistry;

    /**
     * AsyncReindexRunner constructor.
     * @param ProcessFactoryInterface $childProcessFactory
     * @param LoopFactoryInterface $loopFactory
     * @param MessageBagInterface $messageBag
     * @param DirectoryList $directoryList
     * @param IndexerRegistry $indexerRegistry
     */
    public function __construct(
        ProcessFactoryInterface $childProcessFactory,
        LoopFactoryInterface $loopFactory,
        MessageBagInterface $messageBag,
        DirectoryList $directoryList,
        IndexerRegistry $indexerRegistry
    ) {
        $this->childProcessFactory = $childProcessFactory;
        $this->loopFactory = $loopFactory;
        $this->messageBag = $messageBag;
        $this->directoryList = $directoryList;
        $this->indexerRegistry = $indexerRegistry;
    }

    /**
     * {@inheritDoc}
     */
    public function run(string ...$indexerIds): void
    {
        $this->informAboutIndexing($indexerIds);
        $indexerIds = $this->formatIndexersToBeReindex(...$indexerIds);
        $loop = $this->instantiateLoop();

        try {
            $command = $this->buildCommand($indexerIds);
            $process = $this->instantiateNewProcess($command);
            $process->start($loop);
            $loop->run();
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @param string[] ...$indexerIds
     * @return string
     */
    private function formatIndexersToBeReindex(string ...$indexerIds): string
    {
        return implode(' ', $indexerIds);
    }

    /**
     * @param array $indexerIds
     * @return void
     */
    private function informAboutIndexing(array $indexerIds): void
    {
        foreach ($indexerIds as $indexerId) {
            $this->messageBag->addMessage(__('Indexing of indexer %1 has been executed', $indexerId)->render());
        }
    }

    /**
     * @return LoopInterface
     */
    private function instantiateLoop(): LoopInterface
    {
        return $this->loopFactory->create();
    }

    /**
     * @param string $indexerIds
     * @return string
     */
    private function buildCommand(string $indexerIds): string
    {
        return sprintf(
            'php %s/%s %s > /dev/null 2>&1 &',
            $this->getRootDir(),
            self::INDEXER_REINDEX_COMMAND,
            $indexerIds
        );
    }

    /**
     * @return string
     */
    private function getRootDir(): string
    {
        return $this->directoryList->getRoot();
    }

    /**
     * @param string $command
     * @return Process
     */
    private function instantiateNewProcess(string $command): Process
    {
        return $this->childProcessFactory->create($command);
    }


    /**
     * @param \Exception $exception
     * @return void
     * @throws ReindexFailureAggregateException
     */
    private function handleException(\Exception $exception): void
    {
        throw new ReindexFailureAggregateException(
            __($exception->getMessage()),
            $exception,
            $exception->getCode()
        );
    }
}
