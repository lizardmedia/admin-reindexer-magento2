<?php

declare(strict_types=1);

/**
 * File:ProcessFactory.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Model\Adapter\ReactPHP\ChildProcess;

use LizardMedia\AdminIndexer\Api\Adapter\ReactPHP\ChildProcess\ProcessFactoryInterface;
use React\ChildProcess\Process;

/**
 * Class ProcessFactory
 * @package LizardMedia\AdminIndexer\Model\Adapter\ReactPHP
 */
class ProcessFactory implements ProcessFactoryInterface
{
    /**
     * @param string $command
     * @return Process
     */
    public function create(string $command): Process
    {
        return new Process($command);
    }
}
