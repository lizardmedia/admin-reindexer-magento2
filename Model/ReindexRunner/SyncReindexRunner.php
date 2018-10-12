<?php

declare(strict_types=1);

/**
 * File: IndexerProcessor.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @author Paweł Papke <pawel.papke@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Model\ReindexRunner;

use LizardMedia\AdminIndexer\Api\ReindexRunnerInterface;
use LizardMedia\AdminIndexer\Exception\ReindexFailureAggregateException;
use Magento\Framework\Indexer\IndexerRegistry;

/**
 * Class SyncReindexRunner
 * @package LizardMedia\AdminIndexer\Model\ReindexRunner
 */
class SyncReindexRunner implements ReindexRunnerInterface
{
    /**
     * @var ReindexFailureAggregateException
     */
    private $reindexFailureAggregateException;

    /**
     * @var IndexerRegistry
     */
    private $indexerRegistry;

    /**
     * ReindexRunner constructor.
     * @param IndexerRegistry $indexerRegistry
     */
    public function __construct(
        IndexerRegistry $indexerRegistry
    ) {
        $this->indexerRegistry = $indexerRegistry;
    }

    /**
     * {@inheritDoc}
     */
    public function run(string ...$indexerIds): void
    {
        foreach ($indexerIds as $indexerId) {
            try {
                $indexer = $this->indexerRegistry->get($indexerId);
                $indexer->reindexAll();
            } catch (\Exception $exception) {
                $this->addReindexFailureException();
                $this->reindexFailureAggregateException->addError(
                    __(
                        'Indexing of %1 has failed: %2',
                        $indexerId,
                        $exception->getMessage()
                    )
                );

                continue;
            }
        }

        $this->handleExceptions();
    }

    /**
     * @return void
     */
    private function addReindexFailureException(): void
    {
        if (!$this->reindexFailureAggregateException instanceof ReindexFailureAggregateException) {
            $this->reindexFailureAggregateException = new ReindexFailureAggregateException(
                __('Following indexing errors has occurred: ')
            );
        }
    }

    /**
     * @throws ReindexFailureAggregateException
     * @return void
     */
    private function handleExceptions(): void
    {
        if ($this->reindexFailureAggregateException instanceof ReindexFailureAggregateException) {
            throw $this->reindexFailureAggregateException;
        }
    }
}
