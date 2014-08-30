<?php
session_start();

require_once('bootstrap.php');
$userList   = require(__DIR__ . '/config/users.php');
$trackList  = require(__DIR__ . '/config/tracks.php');

if ( isset($_SESSION['SclWrapper']['accessToken']['access_token']) ) {
    $scl->setAccessToken($_SESSION['SclWrapper']['accessToken']['access_token']);
} else {
    header('Location: ' . $sclConfig['redirectHome']);
    die();
}



?>
<html>
<head>
</head>

<body>
    <div>
        <a href="/app/search.php?permalinks=<?= implode($userList, ','); ?>">Get my tracks</a>
    </div>

    <hr />

    <div>
        <h4>Adding tracks to a playlist and embed it to player</h4>
        <form name="create_playlist" method="post" action="/app/embed.php">
            Playlist name:&nbsp;<input type="text" name="playlist_name" />
            <br />
            <?php $i=1; foreach($trackList as $track): ?>
                Track permalink #<?= $i ?>:&nbsp;
                <input type="text" name="tracks[]" value="<?= $track; ?>" size="50" />
                <br />
            <?php $i++; endforeach; ?>
            <input type="submit" value="Create playlist" />
        </form>
    </div>

    <hr />

    <div>
        <p>Playlists:</p>
        <?php foreach($scl->getUserPlaylists($scl->getMyInfo()->id) as $list): ?>
            <p><a href="/app/play.php?playlist=<?= $list->permalink_url; ?>"><?= $list->title; ?></a></p>
        <?php endforeach; ?>
    </div>

</body>
</html>