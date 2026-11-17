<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\ParentWithFooFixture;

class ChildClass extends ParentWithFooFixture
{
    #[\Override]
    public function foo(): void
    {
    }
}

?>
