<?php

if ($_SERVER["HTTPS"] != "on") {
    header( "HTTP/1.1 301 Moved Permanently" );
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
require_once("./function/functions.php");
?>
<script type="text/javascript" src="jQuery/jquery-1.11.3.js"></script>
<script type="text/javascript" src="JS/myScript.js"></script>
<link href="./CSS/style.css" rel="stylesheet" type="text/css"><!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->