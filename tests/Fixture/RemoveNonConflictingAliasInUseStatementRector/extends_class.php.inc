<?php

namespace App\Demo;

use App\Bar as AppBar;

class Foo extends AppBar
{
}

?>
-----
<?php

namespace App\Demo;

use App\Bar;

class Foo extends \App\Bar
{
}

?>
