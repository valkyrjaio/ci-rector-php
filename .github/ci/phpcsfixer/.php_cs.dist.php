<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Rector package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

use PhpCsFixer\Finder;
use Valkyrja\Fixer\Rules;

$header = <<<EOF
    This file is part of the Valkyrja Rector package.

    Copyright (c) 2016-present Melech Mizrachi

    Released under the MIT License. See LICENSE.md for details.
    EOF;

$finder = Finder::create()
    // Finder ignores a dot directory by default, which put every PHP file under
    // .github outside the header rule. Those files are this repository's own source
    // and carry the header too, so the finder descends into them.
    ->ignoreDotFiles(false)
    ->exclude('.git')
    ->exclude('docs')
    ->exclude('vendor')
    ->in(__DIR__ . '/../../../');

return Rules::getConfig($finder, $header);
