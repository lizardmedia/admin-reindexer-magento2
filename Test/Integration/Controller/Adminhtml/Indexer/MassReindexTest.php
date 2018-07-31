<?php

declare(strict_types=1);

/**
 * File: MassReindexTest.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @author Paweł Papke <pawel.papke@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Test\Integration\Controller\Adminhtml\Indexer;

use Magento\TestFramework\TestCase\AbstractBackendController;

class MassReindexTest extends AbstractBackendController
{
    /**
     * @var string
     */
    protected $resource = 'LizardMedia_AdminIndexer::indexer';

    /**
     * @var string
     */
    protected $uri = 'indexer/indexer/massReindex';
}
