<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';

function get_user_info($userId)
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        exit('Connection failed: '.$conn->connect_error);
    }

    $check = $conn->prepare('SELECT * FROM User WHERE id=?');
    $check->bind_param('s', $userId);
    $check->execute();
    $result = $check->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row;
    } else {
        return array();
    }

    $check->free_result();
    $check->close();

    $conn->close();
}

function user_exists($username, $pass)
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        exit('Connection failed: '.$conn->connect_error);
    }

    $check = $conn->prepare('SELECT pass FROM User WHERE Username=?');
    $check->bind_param('s', $username);
    $check->execute();
    $check->store_result();
    $check->bind_result($p);
    $check->fetch();
    if ($pass == $p) {
        return true;
    } else {
        return false;
    }

    $check->free_result();
    $check->close();

    $conn->close();
}

function get_user_id($username)
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        exit('Connection failed: '.$conn->connect_error);
    }

    $check = $conn->prepare('SELECT ID FROM User WHERE Username=?');
    $check->bind_param('s', $username);
    if (true === $check->execute()) {
        $check->store_result();
        $check->bind_result($userId);
        $check->fetch();

        return $userId;
    } else {
        echo 'Error accessing table!';
        exit();
    }

    $check->free_result();
    $check->close();

    $conn->close();
}
