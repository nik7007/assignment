<?php
require_once("./init.php");

require_once "./function/functions.php";


switch (sanitizeString($_POST["action"])) {

    case "printRegistrableActivities":
        if (!logged())
            die("Reload");

        $page = false;
        if (isset($_POST["page"]) && $_POST["page"] > 0)
            $page = $_POST["page"];
        printRegistrableActivities(getActivities($page, $_SESSION["user"]));
        break;

    case "registerNewActivity":
        if (!logged())
            die("Reload");
        if (isset($_POST["activity"]) && isset($_POST["number"]))
            echo registerNewActivity($_POST["activity"], (int)$_POST["number"]);
        else echo "Error! unable to satisfy the request.";

        break;

    case "printCancelableActivities":
        if (!logged())
            die("Reload");
        $page = false;
        if (isset($_POST["page"]) && $_POST["page"] > 0)
            $page = $_POST["page"];
        printCancelableActivities(getActivities($page, $_SESSION["user"], true));
        break;

    case "cancelRegister":
        if (!logged())
            die("Reload");
        if (isset($_POST["activity"]))
            cancelRegister($_POST["activity"], $_SESSION["user"]);
        else
            echo "Error! unable to satisfy the request.";
        break;

    default:
        die();


}

