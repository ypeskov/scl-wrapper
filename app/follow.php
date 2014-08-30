<?php
session_start();

require_once('bootstrap.php');

if ( isset($_SESSION['SclWrapper']['accessToken']['access_token']) ) {
    $scl->setAccessToken($_SESSION['SclWrapper']['accessToken']['access_token']);

    if ( isset($_POST['user_to_follow']) ) {
        $userPermalink = $_POST['user_to_follow'];

        var_dump($scl->followUser($userPermalink));
    }

} else {
    header('Location: ' . $sclConfig['redirectHome']);
    die();
}