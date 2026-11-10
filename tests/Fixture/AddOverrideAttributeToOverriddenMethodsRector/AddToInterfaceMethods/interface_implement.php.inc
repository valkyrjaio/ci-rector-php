<?php

declare(strict_types=1);

namespace App\Fixture\AddToInterfaceMethods;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Contract\FooContract;

final class InterfaceImplement implements FooContract
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

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Contract\FooContract;

final class InterfaceImplement implements FooContract
{
    #[\Override]
    public function foo(): void
    {
        return true;
    }
}

?>
