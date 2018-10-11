<?php

declare(strict_types=1);

/**
 * File:ProcessFactoryInterface.php
 *
 * @author Maciej Sławik <maciej.slawik@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Api\Adapter\ReactPHP\ChildProcess;

use React\ChildProcess\Process;

/**
 * Interface ProcessFactoryInterface
 * @package LizardMedia\AdminIndexer\Api\Adapter\ReactPHP
 */
interface ProcessFactoryInterface
{
    /**
     * @param string $command
     * @return Process
     */
    public function create(string $command): Process;
}
