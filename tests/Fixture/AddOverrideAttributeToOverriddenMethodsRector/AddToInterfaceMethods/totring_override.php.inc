<?php

declare(strict_types=1);

namespace App\Fixture\AddToInterfaceMethods;

use Stringable;

final class ToStringOverride implements Stringable
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

use Stringable;

final class ToStringOverride implements Stringable
{
    #[\Override]
    public function __toString(): string
    {
        return 'hello';
    }
}

?>
