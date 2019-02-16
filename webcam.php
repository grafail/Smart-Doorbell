<?php

session_start();

if (!isset($_SESSION['userId'])) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    echo 'Error 403!<br>Access is Forbidden!';
    exit();
}

$file = './scripts/webcam.png';
$type = 'image/jpeg';
header('Content-Type:'.$type);
header('Content-Length: ' . filesize($file));
readfile($file);

?>
</div>
</body>

</html>