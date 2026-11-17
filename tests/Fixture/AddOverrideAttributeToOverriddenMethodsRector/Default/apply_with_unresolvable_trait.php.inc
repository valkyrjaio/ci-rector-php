<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Trait\NonEmptyRunTrait;

abstract class ParentWithUnresolvableTrait
{
    use MissingTrait;
    use NonEmptyRunTrait;
}

final class ChildOfParentWithUnresolvableTrait extends ParentWithUnresolvableTrait
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

abstract class ParentWithUnresolvableTrait
{
    use MissingTrait;
    use NonEmptyRunTrait;
}

final class ChildOfParentWithUnresolvableTrait extends ParentWithUnresolvableTrait
{
    #[\Override]
    public function run(): void
    {
    }
}

?>
