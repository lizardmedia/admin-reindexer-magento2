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

use LizardMedia\AdminIndexer\Api\ReindexRunner\SyncReindexRunnerInterface;
use Magento\Framework\Indexer\IndexerRegistry;

/**
 * Class SyncReindexRunner
 * @package LizardMedia\AdminIndexer\Model\ReindexRunner
 */
class SyncReindexRunner implements SyncReindexRunnerInterface
{
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
    public function run(string $indexerId): void
    {
        $indexer = $this->indexerRegistry->get($indexerId);
        $indexer->reindexAll();
    }
}
