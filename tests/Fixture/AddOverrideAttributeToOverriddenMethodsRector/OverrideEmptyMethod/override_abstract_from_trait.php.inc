<?php

declare(strict_types=1);

namespace App\Fixture\OverrideEmptyMethod;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Trait\RequiredRunTrait;

// @see https://3v4l.org/X2LNT
final class OverrideAbstractFromTrait
{
    use RequiredRunTrait;

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

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Trait\RequiredRunTrait;

// @see https://3v4l.org/X2LNT
final class OverrideAbstractFromTrait
{
    use RequiredRunTrait;

    #[\Override]
    public function run(): void
    {
        return 'hello';
    }
}

?>
