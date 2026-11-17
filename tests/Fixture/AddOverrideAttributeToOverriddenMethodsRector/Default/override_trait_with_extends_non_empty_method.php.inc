<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Trait\NonEmptyRunTrait;
use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\ParentWithRunFixture;

final class OverrideTraitWithExtendsNonEmptyMethod extends ParentWithRunFixture
{
    use NonEmptyRunTrait;

    public function run(): void
    {
    }
}

?>
-----
<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Trait\NonEmptyRunTrait;
use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\ParentWithRunFixture;

final class OverrideTraitWithExtendsNonEmptyMethod extends ParentWithRunFixture
{
    use NonEmptyRunTrait;

    #[\Override]
    public function run(): void
    {
    }
}

?>
