<?php
session_start();

require_once('bootstrap.php');

if ( isset($_SESSION['SclWrapper']['accessToken']['access_token']) ) {
    $scl->setAccessToken($_SESSION['SclWrapper']['accessToken']['access_token']);

    if ( isset($_GET['playlist']) ) {
        $listUrl = $_GET['playlist'];

        echo $scl->play($listUrl)->html;
    }

} else {
    header('Location: ' . $sclConfig['redirectHome']);
    die();
}