<?php

include '../../vendor/autoload.php';

use SciPhp\NumPhp as np;

$a = np::random()->randn();
echo('random float: '.$a);