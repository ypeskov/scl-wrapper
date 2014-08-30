<?php
ini_set('display_errors', 1);
session_start();

require_once('bootstrap.php');

if ( isset($_SESSION['SclWrapper']['accessToken']['access_token']) ) {
    $scl->setAccessToken($_SESSION['SclWrapper']['accessToken']['access_token']);

    if ( isset($_POST['track_to_like']) ) {
        $trackPermalink = $_POST['track_to_like'];

        var_dump($scl->likeTrack($trackPermalink));
    }

} else {
    header('Location: ' . $sclConfig['redirectHome']);
    die();
}