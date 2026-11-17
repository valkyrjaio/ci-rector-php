<?php

declare(strict_types=1);

namespace App\Fixture\AddToInterfaceMethods;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\ParentWithFooFixture;

final class NormalExtends extends ParentWithFooFixture
{
    public function foo(): void
    {
        return true;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace App\Fixture\AddToInterfaceMethods;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\ParentWithFooFixture;

final class NormalExtends extends ParentWithFooFixture
{
    #[\Override]
    public function foo(): void
    {
        return true;
    }
}

?>
