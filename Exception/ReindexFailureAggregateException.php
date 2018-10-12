<?php

declare(strict_types=1);

/**
 * File: ReindexFailureAggregateException.php
 *
 * @author Bartosz Kubicki bartosz.kubicki@lizardmedia.pl>
 * @copyright Copyright (C) 2018 Lizard Media (http://lizardmedia.pl)
 */

namespace LizardMedia\AdminIndexer\Exception;

use Magento\Framework\Exception\AbstractAggregateException;

/**
 * Class ReindexFailureAggregateException
 * @package LizardMedia\AdminIndexer\Exception
 */
class ReindexFailureAggregateException extends AbstractAggregateException
{
}