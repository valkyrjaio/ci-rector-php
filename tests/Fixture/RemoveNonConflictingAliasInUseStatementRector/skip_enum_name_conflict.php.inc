<?php

namespace App\Demo;

use App\Status as AppStatus;

enum Status
{
    case A;
}

class Foo extends AppStatus
{
}

?>
