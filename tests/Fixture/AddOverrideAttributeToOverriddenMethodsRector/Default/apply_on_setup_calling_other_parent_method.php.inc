<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentWithSetUpFixture;

final class ApplyOnSetUpCallingOtherParentMethod extends ParentWithSetUpFixture
{
    protected function setUp(): void
    {
        parent::tearDown();
    }
}

?>
-----
<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentWithSetUpFixture;

final class ApplyOnSetUpCallingOtherParentMethod extends ParentWithSetUpFixture
{
    #[\Override]
    protected function setUp(): void
    {
        parent::tearDown();
    }
}

?>
