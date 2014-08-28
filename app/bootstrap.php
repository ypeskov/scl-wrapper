<?php

require_once(__DIR__ . '/../vendor/autoload.php');
$sclConfig = require(__DIR__ . '/config/scl_app.php');

use SclWrapper\SclWrapper;



try {
    $scl = new SclWrapper($sclConfig);
} catch(Exception $e) {
    echo $e->getMessage();
}