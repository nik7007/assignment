<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Personal Page</title>
    <?php require_once("include.php") ?>
</head>

<body>

<div class="container">
    <?php
    require "header.php";
    require "main_menu.php";
    ?>
    <article class="content">

        <?php
        if (!logged()): ?>

            <h1>Access denied</h1>

            <section>
                <p>You need to <a href="./login.php">login</a> to view this page</p>
            </section>

        <?php
        else:
            $result = getActivities(false, $_SESSION["user"]);
            ?>

            <h1>Welcome back <?php echo $_SESSION["user"]; ?></h1>

            <section>
                <h3>Available activities for reservation:</h3>

                <?php if ($result && $result["content"] != null): ?>

                    <table class="pPage">


                    </table>

                    <?php if(!$result["all"]){

                        //echo "";

                    } ?>

                    <p id="reservationAction"></p>

                <?php else: ?>
                    <p>Unable to get activities. Try again later.</p>

                <?php endif; ?>

            </section>

            <!-- end .content -->
        <?php endif; ?>
    </article>



    <?php require "footer.php"; ?>
    <!-- end .container -->


</div>
<?php if (logged() && $result && $result["content"] != null): ?>
    <script>

        printRegistrableActivities();

        function sendRegister(activityID, nC) {

            var nch = parseInt($('#' + nC).val()) + 1;
            var activity = $('#' + activityID).html();
            $.post('./ajaxHandler.php', {action: 'registerNewActivity', activity: activity, number: nch}).done(
                function (data) {
                    var pt = new RegExp("Error!*");
                    if (pt.test(data))
                        $('#reservationAction').css('color', '#e51a31');
                    else
                        $('#reservationAction').css('color', 'rgb(15, 142, 36)');

                    $('#reservationAction').html(data);

                }
            );
            printRegistrableActivities();

        }

        function colorTable() {
            for (var i = 1; i <= 4; i++)
                $('table tr > td:nth-child(' + parseInt(i) + ')').attr('style', 'background-color:#D6E4F2;');
        }

        function printRegistrableActivities(page) {

            if (page == undefined)
                page = 0;

            var table = $('.pPage');

            $.post('./ajaxHandler.php', {action: 'printRegistrableActivities', page: page}).done(
                function (data) {
                    console.log(data);
                    if (data === "")
                        table.html("<p>There is not available activities.</p>");
                    else {
                        table.html("<tr><td>Activity</td><td>Total Slot</td><td>Available Slot</td><td>How many children</td></tr>");
                        table.append(data);
                        colorTable();
                    }
                });

        }


    </script>

<?php endif; ?>
</body>
</html>
