<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/scripts/check_user.php';

if (user_exists($_POST['username'], $_POST['pass'])) {
    echo 'Login was succesfull!';
    if (isset($_SESSION['username']) || isset($_SESSION['userId'])) {
        session_start();
        session_unset();
        session_destroy();
    }
    session_start();
    session_regenerate_id(true);
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['userId'] = get_user_id($_POST['username']);
    header('refresh:3;url=/home.php');
} else {
    echo 'Wrong username or password!';
}

?>
</body>
</html>