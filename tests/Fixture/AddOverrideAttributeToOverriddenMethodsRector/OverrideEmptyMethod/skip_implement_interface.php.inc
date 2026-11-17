<?php

declare(strict_types=1);

namespace App\Fixture\OverrideEmptyMethod;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Contract\FooContract;

final class SkipImplementInterface implements FooContract
{
    public function foo(): void
    {
        return true;
    }
}
