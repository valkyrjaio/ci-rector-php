<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\ParentChildFixture;

class ChildClass extends ParentChildFixture
{
    public function foo(): void
    {
    }
}

?>
-----
<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\ParentChildFixture;

class ChildClass extends ParentChildFixture
{
    #[\Override]
    public function foo(): void
    {
    }
}

?>
