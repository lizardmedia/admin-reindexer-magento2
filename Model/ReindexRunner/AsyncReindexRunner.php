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

use LizardMedia\AdminIndexer\Api\ReindexRunner\AsyncReindexRunnerInterface;
use LizardMedia\AdminIndexer\Api\ReindexRunner\MessageBagInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Indexer\IndexerRegistry;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessFactory;

/**
 * Class AsyncReindexRunner
 * @package LizardMedia\AdminIndexer\Model\ReindexRunner
 */
class AsyncReindexRunner implements AsyncReindexRunnerInterface
{
    /**
     * @const string
     */
    const INDEXER_REINDEX_COMMAND = 'bin/magento indexer:reindex';

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
     * @var ProcessFactory
     */
    private $processFactory;

    /**
     * ReindexRunner constructor.
     * @param MessageBagInterface $messageBag
     * @param DirectoryList $directoryList
     * @param IndexerRegistry $indexerRegistry
     * @param ProcessFactory $processFactory
     */
    public function __construct(
        MessageBagInterface $messageBag,
        DirectoryList $directoryList,
        IndexerRegistry $indexerRegistry,
        ProcessFactory $processFactory
    ) {
        $this->messageBag = $messageBag;
        $this->directoryList = $directoryList;
        $this->indexerRegistry = $indexerRegistry;
        $this->processFactory = $processFactory;
    }


    /**
     * {@inheritDoc}
     */
    public function run(string $indexerId): void
    {
        $command = $this->buildCommand($indexerId);
        $process = $this->instantiateNewProcess($command);
        $process->start();
        $this->messageBag->addMessage(__('Indexing of indexer %1 has been executed', $indexerId)->render());
    }


    /**
     * @param string $indexerId
     * @return string
     */
    private function buildCommand(string $indexerId): string
    {
        return sprintf('php %s/%s %s', $this->getRootDir(), self::INDEXER_REINDEX_COMMAND, $indexerId);
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
        return $this->processFactory->create(['commandline' => $command]);
    }
}
