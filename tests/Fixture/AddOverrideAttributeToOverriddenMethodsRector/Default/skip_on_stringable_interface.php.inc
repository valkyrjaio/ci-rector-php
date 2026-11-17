<?php

namespace App\Fixture\Default;

final class SkipOnStringableInterface implements \Stringable
{
    public function __toString(): string
    {
        return 'implements only';
    }
}
