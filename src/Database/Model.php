<?php

namespace Siphon\Database;

use Siphon\Event\EventGenerator;
use Siphon\Event\GeneratorInterface;

abstract class Model extends \Illuminate\Database\Eloquent\Model implements GeneratorInterface
{
    use EventGenerator;
}