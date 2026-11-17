<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentUsingTraitFixture;

class ExtendsParentConsumeTrait extends ParentUsingTraitFixture
{
    public function run(): void
    {
        echo 'override default';
    }
}

?>
-----
<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentUsingTraitFixture;

class ExtendsParentConsumeTrait extends ParentUsingTraitFixture
{
    #[\Override]
    public function run(): void
    {
        echo 'override default';
    }
}

?>
