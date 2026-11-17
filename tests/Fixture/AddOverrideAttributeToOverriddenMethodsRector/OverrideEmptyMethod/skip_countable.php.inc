<?php

namespace App\Fixture\OverrideEmptyMethod;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Contract\FooContract;
use Countable;

final class SkipCountable implements Countable
{
    public function count(): int
    {
        return 0;
    }
}
