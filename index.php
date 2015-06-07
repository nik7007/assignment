<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <?php require_once("include.php") ?>
</head>

<body>

<div class="container">
    <?php
    require "header.php";
    require "main_menu.php";
    ?>
    <article class="content">
        <h1>Activities</h1>

        <?php
        $result = getActivities();
        if (!$result)
            echo "<section><p>No activities at the moment</p></section>";
        else {
            if ($result["all"]) {
                printContent($result);
            } else {

                $Max = (int)round(($result["lineNumber"] / $db_limit_to_show)+0.4);



            }


        }


        ?>

        <!-- end .content --></article>
    <?php
    require "footer.php";

    ?>
    <!-- end .container --></div>
</body>
</html>