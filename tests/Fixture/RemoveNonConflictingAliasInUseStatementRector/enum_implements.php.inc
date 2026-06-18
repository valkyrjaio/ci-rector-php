<?php

namespace App\Demo;

use App\HasLabel as AppHasLabel;

enum Foo implements AppHasLabel
{
    case A;
}

?>
-----
<?php

namespace App\Demo;

use App\HasLabel;

enum Foo implements \App\HasLabel
{
    case A;
}

?>
