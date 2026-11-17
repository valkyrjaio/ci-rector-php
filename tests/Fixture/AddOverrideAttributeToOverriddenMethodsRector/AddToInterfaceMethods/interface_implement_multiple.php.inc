<?php

declare(strict_types=1);

namespace App\Fixture\AddToInterfaceMethods;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Contract\BazContract;
use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Contract\FooContract;

final class InterfaceImplementMultiple implements FooContract, BazContract
{
    public function foo(): void
    {
        return true;
    }

    public function baz(): void
    {
        return true;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace App\Fixture\AddToInterfaceMethods;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Contract\BazContract;
use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Contract\FooContract;

final class InterfaceImplementMultiple implements FooContract, BazContract
{
    #[\Override]
    public function foo(): void
    {
        return true;
    }

    #[\Override]
    public function baz(): void
    {
        return true;
    }
}

?>
