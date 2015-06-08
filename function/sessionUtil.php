<?php

$time_out_session = 2; //min

function createUserSession($username, $password)
{

    $user = getUser($username, $password);

    if (!$user)
        return false;

    if (isset($_SESSION["user"]))
        destroySession();

    $_SESSION["user"] = $user->fetch_assoc()["name"];
    $_SESSION["children"] = $user->fetch_assoc()["children"];
    $_SESSION["activity"] = time();

    return true;

}

function checkTimeout()
{
    global $time_out_session;

    if (isset($_SESSION["user"])) {
        $activity = $_SESSION["activity"];

        if (time() - $activity > $time_out_session * 60)
            destroySession();
        else
            $_SESSION["activity"] = time();
    }

}


function destroySession()
{
    $_SESSION = array();


    if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time() - 2592000, '/');

    session_destroy();
}