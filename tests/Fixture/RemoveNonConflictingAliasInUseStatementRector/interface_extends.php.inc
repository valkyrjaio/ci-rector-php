<?php

namespace App\Demo;

use App\Contract as AppContract;

interface Foo extends AppContract
{
}

?>
-----
<?php

namespace App\Demo;

use App\Contract;

interface Foo extends \App\Contract
{
}

?>
