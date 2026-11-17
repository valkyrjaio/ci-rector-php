<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Contract\FooContract;

final class SkipInterface implements FooContract
{
    public function foo(): void
    {
        return 'implements only';
    }
}
