<?php
session_start();

require_once('bootstrap.php');
//$sclConfig = require(__DIR__ . '/config/scl_app.php');

use Soundcloud\Exception\InvalidHttpResponseCodeException;


if ( isset($_GET['code']) ) {
    try {
        $accessToken = $scl->getAccessToken($_GET['code']);
        $_SESSION['SclWrapper']['accessToken'] = $accessToken;

        header('Location: ' . $sclConfig['redirectAfterAuth']);
        die();
    } catch(InvalidHttpResponseCodeException $e) {
//        echo $e->getMessage();
        var_dump($e);
    }

} else {
    echo "You ae not authorized";
}