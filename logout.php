<?php require_once("./init.php"); ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Logout</title>
    <?php require_once("include.php"); ?>

</head>

<body>

<div class="container">
    <?php
    require "header.php";
    if (logged())
        destroySession();
    require "main_menu.php";
    ?>
    <article class="content">
        <h1>Logout</h1>

        <p>Logout complete!</p>

        <p><a href="./">Click here</a> to come back to the Home page.</p>

        <!-- end .content -->
    </article>



    <?php
    require "footer.php";

    ?>
    <!-- end .container -->


</div>


</body>
</html>
