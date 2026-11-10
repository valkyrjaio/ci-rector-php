<?php

declare(strict_types=1);

namespace App\Fixture\Default;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class SkipOnParentReturnNull extends NodeVisitorAbstract
{
    public function enterNode(Node $node)
    {
        return 1;
    }
}
