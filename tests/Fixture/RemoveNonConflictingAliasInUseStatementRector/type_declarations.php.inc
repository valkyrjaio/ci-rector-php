<?php

namespace App\Demo;

use App\Bar as AppBar;

class Foo
{
    private AppBar $bar;

    public function make(AppBar $in): AppBar
    {
        $x = new AppBar();

        return AppBar::create();
    }
}

?>
-----
<?php

namespace App\Demo;

use App\Bar;

class Foo
{
    private \App\Bar $bar;

    public function make(\App\Bar $in): \App\Bar
    {
        $x = new \App\Bar();

        return \App\Bar::create();
    }
}

?>
