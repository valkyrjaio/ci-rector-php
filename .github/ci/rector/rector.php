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

use Valkyrja\Rector\Rules;

require_once __DIR__ . '/../../../vendor/autoload.php';

return Rules::getConfig()
    ->withAutoloadPaths([
        __DIR__ . '/../../../vendor/autoload.php',
    ])
    ->withPaths([
        __DIR__ . '/../../../src',
        __DIR__ . '/../../../tests',
    ]);
