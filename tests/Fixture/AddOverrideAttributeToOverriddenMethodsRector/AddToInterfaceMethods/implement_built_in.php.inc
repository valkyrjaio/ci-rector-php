<?php

declare(strict_types=1);

namespace App\Fixture\AddToInterfaceMethods;

use Countable;

final class ImplementBuiltIn implements Countable
{
    public function count(): int
    {
        return 0;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace App\Fixture\AddToInterfaceMethods;

use Countable;

final class ImplementBuiltIn implements Countable
{
    #[\Override]
    public function count(): int
    {
        return 0;
    }
}

?>
