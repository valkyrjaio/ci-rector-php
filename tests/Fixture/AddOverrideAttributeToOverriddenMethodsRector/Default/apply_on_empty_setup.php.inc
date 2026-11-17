<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentWithSetUpFixture;

final class ApplyOnEmptySetUp extends ParentWithSetUpFixture
{
    protected function setUp(): void
    {
    }
}

?>
-----
<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentWithSetUpFixture;

final class ApplyOnEmptySetUp extends ParentWithSetUpFixture
{
    #[\Override]
    protected function setUp(): void
    {
    }
}

?>
