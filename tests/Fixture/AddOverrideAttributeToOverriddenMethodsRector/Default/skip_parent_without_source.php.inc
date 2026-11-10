<?php

namespace App\Fixture\Default;

final class SkipParentWithoutSource extends \ArrayIterator
{
    public function count(): int
    {
        return 0;
    }
}

?>
