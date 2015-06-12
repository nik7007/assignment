<?php

if ($_SERVER["HTTPS"] != "on") {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
require_once "./function/functions.php";


switch (sanitizeString($_POST["action"])) {

    case "printRegistrableActivities":
        if (!logged() || !isset($_POST["action"]))
            die();

        $page = false;
        if (isset($_POST["page"]) && $_POST["page"] > 0)
            $page = $_POST["page"];
        printRegistrableActivities(getActivities($page, $_SESSION["user"]));
        break;

    case "registerNewActivity":

        if (isset($_POST["activity"]) && isset($_POST["number"]))
            echo registerNewActivity($_POST["activity"], (int)$_POST["number"]);
        else echo "Error! unable to satisfy the request.";

        break;

    default:
        die();


}

