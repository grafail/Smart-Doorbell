<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    exit('Connection failed: '.$conn->connect_error);
}
$ins = $conn->prepare('INSERT INTO User (Username, Fullname, Email, pass) VALUES (?, ?, ?, ?)');
$ins->bind_param('ssss', $_POST['username'], $_POST['fullname'], $_POST['email'], $_POST['pass']);

$check = $conn->prepare('SELECT username FROM User WHERE Username=?');
$check->bind_param('s', $_POST['username']);
$check->execute();
$check->store_result();

if (0 != $check->num_rows || null == $_POST['username']) {
    echo 'Username already exists!';
} elseif (true === $ins->execute()) {
    echo 'User registration was successfull!';
    $ins->free_result();
    $ins->close();
} else {
    echo 'Error: '.$conn->error;
}

$check->free_result();
$check->close();

$conn->close();

header('refresh:3;url=/index.html');

?>
</body>
</html>
