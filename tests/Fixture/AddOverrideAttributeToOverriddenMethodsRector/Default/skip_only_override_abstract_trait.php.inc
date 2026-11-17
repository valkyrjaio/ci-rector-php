<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Trait\RequiredRunTrait;

final class SkipOnlyAbstractTrait
{
    use RequiredRunTrait;

    public function run(): void
    {
    }
}
