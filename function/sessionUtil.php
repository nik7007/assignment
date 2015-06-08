<?php

$time_out_session = 2; //min

$loggedIn = false;

function createUserSession($username, $password)
{
    global $loggedIn;

    $user = getUser($username, $password);

    if (!$user)
        return false;

    if (isset($_SESSION["user"]))
        destroySession();

    $_SESSION["user"] = $user->fetch_assoc()["name"];
    $_SESSION["activity"] = time();
    $loggedIn = true;

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
    global $loggedIn;
    $_SESSION = array();


    if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time() - 2592000, '/');
    $loggedIn = false;

    session_destroy();
}