<?php

namespace App\Demo;

use App\SomeTrait as AppTrait;

class Foo
{
    use AppTrait;
}

?>
-----
<?php

namespace App\Demo;

use App\SomeTrait;

class Foo
{
    use \App\SomeTrait;
}

?>
