<?php

declare(strict_types=1);

namespace App\Fixture\OverrideEmptyMethod;

use PHPUnit\Framework\TestCase;

final class SetupOverride extends TestCase
{
    protected function setUp(): void
    {
        $result = 1000;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace App\Fixture\OverrideEmptyMethod;

use PHPUnit\Framework\TestCase;

final class SetupOverride extends TestCase
{
    #[\Override]
    protected function setUp(): void
    {
        $result = 1000;
    }
}

?>
