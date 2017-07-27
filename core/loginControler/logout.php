<?php

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['user']) || isset($_COOKIE['user'])) {
    destroySession();
}

echo "<script>window.location.href = '.';</script>";

function destroySession($excl = '')
{
    $exl_array[] = array();

    if (is_array($excl)) {
        $exl_array = $excl;
    } else {
        $exl_array[] = $excl;
    }

    $_SESSION = array();
    if (session_id() != "" || isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 2592000, '/');
        foreach ($_COOKIE as $key => $value) {
            if (!(in_array($key, $exl_array))) {
                setcookie($key, '', time() - 2592000, '/');
            }
        }
    }

    session_destroy();
}
