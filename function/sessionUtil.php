<?php

$time_out_session = 20000; //min

function logged() {
    return isset ($_SESSION["user"]);

};

function createUserSession($username, $password)
{

    if (isset($_SESSION["user"]))
        destroySession();

    $user = getUser($username, $password);


    $row = $user->fetch_assoc();

    if (!$user || $row == null) {
        destroySession();
        return false;
    }

    $_SESSION["user"] = $row["name"];
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