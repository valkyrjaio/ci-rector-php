<?php

declare(strict_types=1);

namespace App\Fixture\Default;

use PHPUnit\Framework\TestCase;

final class SkipSetupOverride extends TestCase
{
    protected function setUp(): void
    {
        $result = 1000;
    }
}
