<?php
session_start();

require_once('bootstrap.php');


if ( isset($_GET['users']) ) {
    $users = explode(',', $_GET['users']);

    $tracks = $scl->searchTracks($users);

    $tplName = __DIR__ . '/templates/artists_tracks.php';
    $searchHtml = $scl->getHtml($tplName, ['tracks' => $tracks, ]);

    $fullHtml = __DIR__ . '/templates/layout.php';
    echo $scl->getHtml($fullHtml, ['template' => $searchHtml, ]);

}
//$tracks = $scl->searchTracks($userId);
//var_dump($tracks);