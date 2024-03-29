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

    <hr />

    <form name="like_track" method="post" action="/app/like.php">
        <p>Track to like (permalink):</p>
        <input name="track_to_like" type="text" />
        <br />
        <input type="submit" value="Like long" />
    </form>

    <hr />

    <form name="follow_user" method="post" action="/app/follow.php">
        <p>User to follow (permalink):</p>
        <input name="user_to_follow" type="text" />
        <br />
        <input type="submit" value="Follow the user" />
    </form>

    <hr />

    <form name="comment_track" method="post" action="/app/comment.php">
        Track to comment (permalink):&nbsp;
        <input name="track_to_comment" name="track_name" type="text" />
        <br />
        Comment:&nbsp;
        <input type="text" value="comment here" name="comment_body" />
        <br />
        Time to comment:&nbsp;
        <input type="text" value="1200" name="comment_time" />
        <br />
        <input type="submit" value="Comment the track" />
    </form>
</body>
</html>