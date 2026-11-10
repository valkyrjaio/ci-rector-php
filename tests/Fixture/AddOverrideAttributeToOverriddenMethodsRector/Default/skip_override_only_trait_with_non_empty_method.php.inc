<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Trait\NonEmptyRunTrait;

final class SkipOverrideOnlyTraitWithNonEmptyMethod
{
    use NonEmptyRunTrait;

    public function run(): void
    {
    }
}
