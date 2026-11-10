<?php

declare(strict_types=1);

namespace App\Fixture\OverrideEmptyMethod;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Trait\NonEmptyRunTrait;
use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentWithRequiredRunFixture;

// @see https://3v4l.org/RrkgD
final class OverrideParentFromTrait extends ParentWithRequiredRunFixture
{
    use NonEmptyRunTrait;

    public function run(): void
    {
        return 'hello';
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace App\Fixture\OverrideEmptyMethod;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Trait\NonEmptyRunTrait;
use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentWithRequiredRunFixture;

// @see https://3v4l.org/RrkgD
final class OverrideParentFromTrait extends ParentWithRequiredRunFixture
{
    use NonEmptyRunTrait;

    #[\Override]
    public function run(): void
    {
        return 'hello';
    }
}

?>
