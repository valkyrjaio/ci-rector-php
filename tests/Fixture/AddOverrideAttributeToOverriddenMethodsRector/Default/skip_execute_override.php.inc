<?php

declare(strict_types=1);

namespace App\Fixture\Default;

use LogicException;

class ParentWithThrowingExecute
{
    protected function execute(): int
    {
        throw new LogicException('Override the execute method.');
    }
}

final class SkipExecuteOverride extends ParentWithThrowingExecute
{
    protected function execute(): int
    {
        return 0;
    }
}
