<?php
/**
 * File: MessageBag.php
 *
 * @author Paweł Papke <pawel.papke@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

/**
 * File: MessageBag.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @author Paweł Papke <pawel.papke@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Model\ReindexRunner;

use LizardMedia\AdminIndexer\Api\ReindexRunner\MessageBagInterface;

/**
 * Class MessageBag
 * @package LizardMedia\AdminIndexer\Model\ReindexRunner
 */
class MessageBag implements MessageBagInterface
{
    /**
     * @var array
     */
    private $messages;

    /**
     * MessageBag constructor.
     */
    public function __construct()
    {
        $this->messages = [];
    }

    /**
     * @param string $message
     * @return void
     */
    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
