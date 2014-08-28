<?php
session_start();

require_once('bootstrap.php');


if ( isset($_GET['permalinks']) ) {
    $users = explode(',', $_GET['permalinks']);

    $tracks = $scl->searchTracks($users);

    $tplName = __DIR__ . '/templates/artists_tracks.php';
    $searchHtml = $scl->getHtml($tplName, ['tracks' => $tracks, ]);

    $fullHtml = __DIR__ . '/templates/layout.php';
    echo $scl->getHtml($fullHtml, ['template' => $searchHtml, ]);

}
