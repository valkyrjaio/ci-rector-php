<?php

namespace App\Demo;

use App\Contract as AppContract;

/**
 * @see AppContract
 */
class Foo implements AppContract
{
}

?>
-----
<?php

namespace App\Demo;

use App\Contract;

/**
 * @see Contract
 */
class Foo implements \App\Contract
{
}

?>
