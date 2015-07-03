<?php require_once("./init.php"); ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <?php require_once("include.php"); ?>
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

                $Max = (int)round(($result["lineNumber"] / $db_limit_to_show) + 0.4);

                if (!isset($_GET["page"])) {
                    $pag = 1;
                } else {
                    if ($_GET["page"] < 1)
                        $pag = 1;
                    else if ($_GET["page"] > $Max)
                        $pag = $Max;
                    else
                        $pag = $_GET["page"];
                }
                if ($pag > 1)
                    $result = getActivities($pag);
                printContent($result);
            }
        }

        if (isset($pag)):
            ?>
            <section>
                <form class="navPages" action="./" method="get">
                    <input type="button" value="<<" onclick="preF();">
                    <input type="text" name="page" style="display:none" onchange="check();"
                           title="page"><?php echo "Page: $pag"; ?>
                    <input type="button" value=">>" onclick="nextF();">
                </form>
            </section>
        <?php endif; ?>

        <?php if (!logged()): ?>
            <section>
                <p class="userInfo">It seems that you are not registered. Do it now, it's free! If you are already a
                    member, <a
                        href="./login.php">click here</a> to log in, or
                    on the login button.</p>
            </section>
        <?php endif; ?>
        <!-- end .content -->
    </article>



    <?php
    require "footer.php";

    ?>
    <!-- end .container -->


</div>

<script type="text/javascript">

    <?php if(isset($pag)): ?>
    function check() {
        var max = 0;
        <?php echo "max = $Max; "; ?>
        if (document.forms[0].page.value > max)
            document.forms[0].page.value = max;
        if (document.forms[0].page.value < 1)
            document.forms[0].page.value = 1;
        document.forms[0].submit();

    }

    function preF() {

        <?php
            if($pag>1){
        $toSend = $pag-1;
        echo "document.forms[0].page.value = $toSend;
              document.forms[0].submit();";

         }?>

    }

    function nextF() {

        <?php $toSend = $pag+1;
        echo "document.forms[0].page.value = $toSend;"; ?>
        document.forms[0].submit();

    }

    <?php endif; ?>

</script>

</body>
</html>
<?php closingPage(); ?>