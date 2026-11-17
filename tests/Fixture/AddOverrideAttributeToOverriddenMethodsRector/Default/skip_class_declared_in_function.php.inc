<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\ParentWithFooFixture;

function declareSkipClassDeclaredInFunction(): void
{
    final class SkipClassDeclaredInFunction extends ParentWithFooFixture
    {
        public function foo(): void
        {
        }
    }
}

?>
