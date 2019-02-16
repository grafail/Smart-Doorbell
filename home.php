<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="global.css">
<title>Home</title>
</head>

<body>


<?php

session_start();

if (!isset($_SESSION['userId'])) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    echo 'Error 403!<br>Access is Forbidden!';
    exit();
}



echo 'Webcam Feed: </br>';

echo '<img src="webcam.php"> </img>';

header('Refresh: 1; url='.$_SERVER['PHP_SELF']);

?>
</div>
</body>

</html>