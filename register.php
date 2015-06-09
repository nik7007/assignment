<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <?php require_once("include.php") ?>
</head>

<body>

<div class="container">
    <?php
    require "header.php";

    if (isset($_POST["user"])) {
        $result = createUserSession($_POST["user"], $_POST["pass"]);
    }

    require "main_menu.php";
    ?>
    <article class="content">
        <h1>Register</h1>

        <section>


        </section>

        <!-- end .content -->
    </article>



    <?php
    require "footer.php";

    ?>
    <!-- end .container -->


</div>


</body>
</html>
