<?php

namespace App\Demo;

use App\Bar as AppBar;
use App\Baz as AppBaz;
use Other\Baz;

class Foo extends AppBar implements AppBaz
{
}

?>
-----
<?php

namespace App\Demo;

use App\Bar;
use App\Baz as AppBaz;
use Other\Baz;

class Foo extends \App\Bar implements AppBaz
{
}

?>
