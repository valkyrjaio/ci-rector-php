<?php

namespace App\Fixture\Default;

class ParentWithBareReturn
{
    public function run()
    {
        return;
    }
}

final class ChildOfParentWithBareReturn extends ParentWithBareReturn
{
    public function run()
    {
    }
}

?>
-----
<?php

namespace App\Fixture\Default;

class ParentWithBareReturn
{
    public function run()
    {
        return;
    }
}

final class ChildOfParentWithBareReturn extends ParentWithBareReturn
{
    #[\Override]
    public function run()
    {
    }
}

?>
