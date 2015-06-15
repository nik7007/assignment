<?php
session_start();

/*if ($_SERVER["HTTPS"] != "on") {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}*/

/*if (!isset($_SERVER["HTTP_COOKIE"])) {
    if (!isset($_GET["test_enabled_cookie"])) {
        header('Location: http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '?test_enabled_cookie=test');
        exit();
    } else {
        header('Location: disabled_cookie.php');
        exit();
    }
}*/