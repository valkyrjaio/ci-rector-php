<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\ParentWithFooFixture;

class SkipPrivateMethod extends ParentWithFooFixture
{
    private function bar()
    {
    }
}

?>
