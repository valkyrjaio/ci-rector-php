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
use Rector\ValueObject\PhpVersion;
use Valkyrja\Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddOverrideAttributeToOverriddenMethodsRector::class);
    $rectorConfig->phpVersion(PhpVersion::PHP_83);
};
