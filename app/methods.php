<?php
session_start();

require_once('bootstrap.php');
$userList = require(__DIR__ . '/config/users.php');

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
        <a href="/app/search.php?users=<?= implode($userList, ','); ?>">Get my tracks</a>
    </div>
</body>
</html>