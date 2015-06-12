<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Error</title>
    <?php //require_once("include.php") ?>
    <noscript>Javascript is not supported by your browser, or is disabled. Please turn it on.</noscript>
    <script type="text/javascript" src="jQuery/jquery-1.11.3.js"></script>
    <script type="text/javascript" src="JS/myScript.js"></script>
    <link href="./CSS/style.css" rel="stylesheet" type="text/css">
</head>

<body>

<div class="container">
    <?php
    require "header.php";

    //require "main_menu.php";
    ?>
    <article class="content">
        <h1>Error</h1>

        <section>
            <p>Enable cookies to navigate in this web site!</p>

        </section>

        <!-- end .content -->
    </article>


    <footer>
        <p>Spare time activities</p>
        <address>
            Address
        </address>
    </footer>
    <!-- end .container -->

    <script>

        resizePage();

        $(window).resize(function () {
            resizePage();


        });


    </script>


</div>


</body>
</html>
