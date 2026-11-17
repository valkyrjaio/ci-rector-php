<?php

declare(strict_types=1);

namespace App\Fixture\OverrideEmptyMethod;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Trait\NonEmptyRunTrait;

// @see https://3v4l.org/ACu8F
final class SkipOverrideFromTraitFromNonParent
{
    use NonEmptyRunTrait;

    public function run(): void
    {
        return 'hello';
    }
}
