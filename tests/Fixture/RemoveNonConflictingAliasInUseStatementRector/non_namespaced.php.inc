<?php

use App\Bar as AppBar;

class Foo extends AppBar
{
}

?>
-----
<?php

use App\Bar;

class Foo extends \App\Bar
{
}

?>
