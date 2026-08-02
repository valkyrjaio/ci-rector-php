<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Rector package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

use Rector\Config\RectorConfig;
use Valkyrja\Rector\CodingStyle\Rector\Stmt\RemoveNonConflictingAliasInUseStatementRector;

return RectorConfig::configure()
    ->withRules([
        RemoveNonConflictingAliasInUseStatementRector::class,
    ]);
