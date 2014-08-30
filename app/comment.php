<?php
session_start();

require_once('bootstrap.php');

if ( isset($_SESSION['SclWrapper']['accessToken']['access_token']) ) {
    $scl->setAccessToken($_SESSION['SclWrapper']['accessToken']['access_token']);

    if ( isset($_POST['track_to_comment'])
        and isset($_POST['comment_body'])
        and isset($_POST['comment_time']) ) {
        $trackPermalink = $_POST['track_to_comment'];
        $commentBody    = $_POST['comment_body'];
        $commentTime    = $_POST['comment_time'];

        var_dump($scl->commentTrack($trackPermalink, $commentBody, $commentTime));
    }

} else {
    header('Location: ' . $sclConfig['redirectHome']);
    die();
}