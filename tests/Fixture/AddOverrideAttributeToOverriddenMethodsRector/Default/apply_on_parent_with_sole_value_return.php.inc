<?php

namespace App\Fixture\Default;

class ParentWithSoleValueReturn
{
    public function run()
    {
        return 1;
    }
}

final class ChildOfParentWithSoleValueReturn extends ParentWithSoleValueReturn
{
    public function run()
    {
    }
}

?>
-----
<?php

namespace App\Fixture\Default;

class ParentWithSoleValueReturn
{
    public function run()
    {
        return 1;
    }
}

final class ChildOfParentWithSoleValueReturn extends ParentWithSoleValueReturn
{
    #[\Override]
    public function run()
    {
    }
}

?>
