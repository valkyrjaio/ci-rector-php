<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Contract\BazContract;
use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\ParentWithFooFixture;

final class SkipInterfaceMixedUsage extends ParentWithFooFixture implements BazContract
{
    public function foo(): void
    {
        return 'overrides method';
    }

    public function baz(): void
    {
        return 'implements only';
    }
}

?>
-----
<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Contract\BazContract;
use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\ParentWithFooFixture;

final class SkipInterfaceMixedUsage extends ParentWithFooFixture implements BazContract
{
    #[\Override]
    public function foo(): void
    {
        return 'overrides method';
    }

    public function baz(): void
    {
        return 'implements only';
    }
}

?>
