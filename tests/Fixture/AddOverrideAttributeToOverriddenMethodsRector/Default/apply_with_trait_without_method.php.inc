<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Trait\NonEmptyRunTrait;

trait HelperWithoutRun
{
    public function help()
    {
    }
}

abstract class ParentWithTraitWithoutMethod
{
    use HelperWithoutRun;
    use NonEmptyRunTrait;
}

final class ChildOfParentWithTraitWithoutMethod extends ParentWithTraitWithoutMethod
{
    public function run(): void
    {
    }
}

?>
-----
<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Trait\NonEmptyRunTrait;

trait HelperWithoutRun
{
    public function help()
    {
    }
}

abstract class ParentWithTraitWithoutMethod
{
    use HelperWithoutRun;
    use NonEmptyRunTrait;
}

final class ChildOfParentWithTraitWithoutMethod extends ParentWithTraitWithoutMethod
{
    #[\Override]
    public function run(): void
    {
    }
}

?>
