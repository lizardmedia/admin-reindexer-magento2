<?php

declare(strict_types=1);

/**
 * File: IndexerProcessor.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @author Paweł Papke <pawel.papke@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Model;

use LizardMedia\AdminIndexer\Api\IndexerProcessorInterface;
use LizardMedia\AdminIndexer\Api\ReindexRunnerInterface;
use LizardMedia\AdminIndexer\Exception\ReindexFailureAggregateException;

/**
 * Class IndexerProcessor
 * @package LizardMedia\AdminIndexer\Model
 */
class IndexerProcessor implements IndexerProcessorInterface
{
    /**
     * @var ReindexRunnerInterface
     */
    private $reindexRunner;

    /**
     * IndexerProcessor constructor.
     * @param ReindexRunnerInterface $reindexRunner
     */
    public function __construct(
        ReindexRunnerInterface $reindexRunner
    ) {
        $this->reindexRunner = $reindexRunner;
    }

    /**
     * {@inheritDoc}
     */
    public function process(string ...$indexerIds): void
    {
        $this->reindexRunner->run(...$indexerIds);
    }
}
