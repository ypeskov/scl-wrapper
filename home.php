<?php
session_start();

require_once('app/bootstrap.php');

?>
<html>
<head>
</head>

<body>
    <a href="<?= $scl->getAuthUrl(); ?>">Auth</a>
</body>
</html>
