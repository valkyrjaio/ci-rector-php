<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\ParentWithRunFixture;

class GrandChild extends ParentWithRunFixture
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

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\ParentWithRunFixture;

class GrandChild extends ParentWithRunFixture
{
    #[\Override]
    public function run(): void
    {
        echo 'override default';
    }
}

?>
