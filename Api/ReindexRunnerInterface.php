<?php

declare(strict_types=1);

/**
 * File: ReindexRunnerInterface.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @author Paweł Papke <pawel.papke@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Api;

use LizardMedia\AdminIndexer\Exception\ReindexFailureAggregateException;

/**
 * Interface ReindexRunnerInterface
 * @package LizardMedia\AdminIndexer\Api
 */
interface ReindexRunnerInterface
{
    /**
     * @param string[] ...$indexerIds
     * @return void
     * @throws ReindexFailureAggregateException
     */
    public function run(string ...$indexerIds): void;
}
