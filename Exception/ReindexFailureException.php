<?php

declare(strict_types=1);

/**
 * File: ReindexFailureException.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @author Paweł Papke <pawel.papke@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Exception;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class ReindexFailureException extends LocalizedException
{
    private $indexerName;

    /**
     * ReindexFailureException constructor.
     * @param Phrase $phrase
     * @param string $indexerName
     * @param \Exception|null $cause
     * @param int $code
     */
    public function __construct(Phrase $phrase, string $indexerName, \Exception $cause = null, int $code = 0)
    {
        parent::__construct($phrase, $cause, $code);

        $this->indexerName = $indexerName;
    }

    /**
     * @return string
     */
    public function getIndexerName(): string
    {
        return $this->indexerName;
    }
}
