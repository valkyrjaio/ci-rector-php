<?php

namespace App\Demo;

use App\Bar as Bar;

class Foo extends Bar
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
