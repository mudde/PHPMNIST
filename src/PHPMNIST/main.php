<?php

include '../../vendor/autoload.php';

use SciPhp\NumPhp as np;

echo '<pre>';
try {
    $a = np::random();
    var_dump($a->randn(10,1,10));
} catch (Exception $e) {
    var_dump($e->getMessage());
}