<?php

declare(strict_types=1);

/**
 * File: MessageBagInterface.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @author Paweł Papke <pawel.papke@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Api\ReindexRunner;

/**
 * Class MessageBagInterface
 * @package LizardMedia\AdminIndexer\Api\ReindexRunner
 */
interface MessageBagInterface
{
    /**
     * @param string $message
     */
    public function addMessage(string $message) : void;

    /**
     * @return array
     */
    public function getMessages(): array;
}
