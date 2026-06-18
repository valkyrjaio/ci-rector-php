<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Rector package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Rector\Config\RectorConfig;
use Valkyrja\Rector\CodingStyle\Rector\Stmt\RemoveNonConflictingAliasInUseStatementRector;

return RectorConfig::configure()
    ->withRules([
        RemoveNonConflictingAliasInUseStatementRector::class,
    ]);