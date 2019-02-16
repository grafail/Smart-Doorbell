<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="global.css">
</head>
<body>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/scripts/check_user.php';
session_start();
if (!isset($_SESSION['userId'])) {
    echo 'You are already logged out!';
    header('refresh:3;url=/');
    exit();
}
session_unset();
session_destroy();
session_start();
session_regenerate_id(true);
header('refresh:0;url=/');
?>
</body>
</html>
