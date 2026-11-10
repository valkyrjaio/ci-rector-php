<?php

namespace App\Fixture\Default;

class ParentWithSeveralStatements
{
    public function run(): void
    {
        $first = 1;
        $second = 2;
    }
}

final class ChildOfParentWithSeveralStatements extends ParentWithSeveralStatements
{
    public function run(): void
    {
    }
}

?>
-----
<?php

namespace App\Fixture\Default;

class ParentWithSeveralStatements
{
    public function run(): void
    {
        $first = 1;
        $second = 2;
    }
}

final class ChildOfParentWithSeveralStatements extends ParentWithSeveralStatements
{
    #[\Override]
    public function run(): void
    {
    }
}

?>
