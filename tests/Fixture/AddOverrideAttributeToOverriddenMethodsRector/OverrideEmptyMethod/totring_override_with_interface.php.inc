<?php

declare(strict_types=1);

namespace App\Fixture\OverrideEmptyMethod;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Contract\FooContract;
use Stringable;

final class ToStringOverrideWithInterface implements Stringable, FooContract
{
    public function __toString(): string
    {
        return 'hello';
    }

    public function foo(): void
    {
        return 'implements only';
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace App\Fixture\OverrideEmptyMethod;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Contract\FooContract;
use Stringable;

final class ToStringOverrideWithInterface implements Stringable, FooContract
{
    #[\Override]
    public function __toString(): string
    {
        return 'hello';
    }

    public function foo(): void
    {
        return 'implements only';
    }
}

?>
