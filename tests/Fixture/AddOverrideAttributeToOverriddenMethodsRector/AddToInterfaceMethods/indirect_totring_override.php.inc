<?php

declare(strict_types=1);

namespace App\Fixture\AddToInterfaceMethods;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentStringableFixture;

final class IndirectToStringOverride extends ParentStringableFixture
{
    public function __toString(): string
    {
        return 'hello';
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace App\Fixture\AddToInterfaceMethods;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentStringableFixture;

final class IndirectToStringOverride extends ParentStringableFixture
{
    #[\Override]
    public function __toString(): string
    {
        return 'hello';
    }
}

?>
