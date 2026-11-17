<?php

declare(strict_types=1);

namespace App\Fixture\AddToInterfaceMethods;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentWithFooContractFixture;

final class ExtendsAbstractParent extends ParentWithFooContractFixture
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

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentWithFooContractFixture;

final class ExtendsAbstractParent extends ParentWithFooContractFixture
{
    #[\Override]
    public function foo(): void
    {
        return true;
    }
}

?>
