<?php

declare(strict_types=1);

/**
 * File: IndexerProcessorInterface.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @author Paweł Papke <pawel.papke@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Api;

use LizardMedia\AdminIndexer\Exception\ReindexFailureAggregateException;

/**
 * Interface IndexerProcessorInterface
 * @package LizardMedia\AdminIndexer\Api
 */
interface IndexerProcessorInterface
{
    /**
     * @param string[] ...$indexerIds
     * @return void
     * @throws ReindexFailureAggregateException
     */
    public function process(string ...$indexerIds): void;
}
