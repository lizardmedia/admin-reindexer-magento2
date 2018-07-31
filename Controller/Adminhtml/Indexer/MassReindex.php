<?php

declare(strict_types=1);

/**
 * File: MassReindex.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @author Paweł Papke <pawel.papke@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Controller\Adminhtml\Indexer;

use LizardMedia\AdminIndexer\Api\IndexerProcessorInterface;
use LizardMedia\AdminIndexer\Api\ReindexRunner\MessageBagInterface;
use LizardMedia\AdminIndexer\Exception\ReindexFailureException;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;

/**
 * Class MassReindex
 * @package LizardMedia\AdminIndexer\Controller\Adminhtml\Indexer
 */
class MassReindex extends Action
{
    /**
     * @const string
     */
    const ADMIN_RESOURCE = 'LizardMedia_AdminIndexer::indexer';

    /**
     * @var MessageBagInterface
     */
    private $messageBag;

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var IndexerProcessorInterface
     */
    private $indexerProcessor;

    /**
     * MassReindex constructor.
     * @param MessageBagInterface $messageBag
     * @param Context $context
     * @param RedirectFactory $redirectFactory
     * @param IndexerProcessorInterface $indexerProcessor
     */
    public function __construct(
        MessageBagInterface $messageBag,
        Context $context,
        RedirectFactory $redirectFactory,
        IndexerProcessorInterface $indexerProcessor
    ) {
        parent::__construct($context);

        $this->messageBag = $messageBag;
        $this->redirectFactory = $redirectFactory;
        $this->indexerProcessor = $indexerProcessor;
    }


    /**
     * @return Redirect
     */
    public function execute() : Redirect
    {
        $indexerIds = $this->getRequest()->getParam('indexer_ids');

        if (!$this->validateParam($indexerIds)) {
            $this->messageManager->addErrorMessage(__('Please select at least one index.'));
            return $this->getRedirect();
        }
        $indexerIds = $this->castValuesToString($indexerIds);

        try {
            $this->indexerProcessor->process(...$indexerIds);
            $this->displayMessages();
        } catch (ReindexFailureException $exception) {
            $this->messageManager->addErrorMessage(__('Reindex failed on indexer %1.', $exception->getIndexerName()));
        }

        return $this->getRedirect();
    }


    /**
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }


    /**
     * @return Redirect
     */
    private function getRedirect(): Redirect
    {
        return $this->redirectFactory->create()->setPath('indexer/indexer/list');
    }

    /**
     * @param $indexerIds
     * @return bool
     */
    private function validateParam($indexerIds): bool
    {
        return is_array($indexerIds) && !empty($indexerIds);
    }

    /**
     * @param array $indexerIds
     * @return array
     */
    private function castValuesToString(array $indexerIds): array
    {
        return array_filter($indexerIds, function ($indexerId) {
            return (string)$indexerId;
        });
    }

    /**
     * @return void
     */
    private function displayMessages(): void
    {
        foreach ($this->messageBag->getMessages() as $message) {
            $this->messageManager->addNoticeMessage($message);
        }
    }
}
