<?php

namespace App\Fixture\Default;

use Countable;

final class SkipCountable implements Countable
{
    public function count(): int
    {
        return 0;
    }
}
