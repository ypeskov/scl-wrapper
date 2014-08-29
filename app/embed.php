<?php
session_start();

require_once('bootstrap.php');

if ( isset($_SESSION['SclWrapper']['accessToken']['access_token']) ) {
    $scl->setAccessToken($_SESSION['SclWrapper']['accessToken']['access_token']);

    if ( isset($_POST['playlist_name']) and isset($_POST['tracks']) ) {
        $scl->createPlayList($_POST['playlist_name'], $_POST['tracks']);
    } else {
        echo "Please specify playlist and tracks.";
    }
} else {
    header('Location: ' . $sclConfig['redirectHome']);
    die();
}