<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Personal Page</title>
    <?php require_once("include.php"); ?>
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


                <table id="available" class="pPage">


                </table>

                <?php if (!$result["all"]) {

                    echo "<div style='text-align: center;margin-top: 10px;'>
                                <button type='button' id = 'pPagePre' onclick='pre();'> << </button>
                                <span>Page: </span><span  id = 'pPagePage'>1</span>
                                <button type='button' id = 'pPageNext' onclick='next();'> >> </button>
                              </div>";

                } ?>

                <p id="reservationAction" class="error"></p>



                <?php $remove = getActivities(false, $_SESSION["user"], true);?>

                <table id="cancelable" class="rPage">


                </table>

                <?php if (!$remove["all"]) {

                    echo "<div style='text-align: center;margin-top: 10px;'>
                                <button type='button' id = 'pPagePreC' onclick='preC();'> << </button>
                                <span>Page: </span><span  id = 'pPagePageC'>1</span>
                                <button type='button' id = 'pPageNextC' onclick='nextC();'> >> </button>
                              </div>";

                } ?>


                <p id="removeAction" class="error"></p>


            </section>

            <!-- end .content -->
        <?php endif; ?>
    </article>



    <?php require "footer.php"; ?>
    <!-- end .container -->


</div>
<?php if (logged()): ?>
    <script>

        var limit = <?php echo $db_limit_to_show;  ?>;
        var totalNumber = <?php echo $result["lineNumber"];  ?>;
        var totalNumberC = <?php echo $remove["lineNumber"];  ?>;

        printRegistrableActivities();
        printCancelableActivities();

        function refreshContent() {

            if (typeof pageN === 'undefined')
                printRegistrableActivities();
            else
                printRegistrableActivities(pageN);

            if (typeof pageNc === 'undefined')
                printCancelableActivities();
            else
                printCancelableActivities(pageNc);
        }

        function sendRegister(activityID, nC) {

            var reservationAction = $('#reservationAction');

            var nch = parseInt($('#' + nC).val()) + 1;
            var activity = $('#' + activityID).html();

            clearError();

            $.post('./ajaxHandler.php', {action: 'registerNewActivity', activity: activity, number: nch}).done(
                function (data) {

                    if (data === "Reload")
                        location.reload();

                    var pt = new RegExp("Error!*");
                    if (pt.test(data))
                        reservationAction.css('color', '#e51a31');
                    else
                        reservationAction.css('color', 'rgb(15, 142, 36)');

                    reservationAction.html(data);

                    refreshContent();

                }
            );


        }

        function cancelRegistration(activityID) {

            var removeAction = $('#removeAction');
            var activity = $('#' + activityID).html();

            clearError();

            $.post('./ajaxHandler.php', {action: 'cancelRegister', activity: activity}).done(
                function (data) {

                    if (data === "Reload")
                        location.reload();

                    var pt = new RegExp("Error!*");
                    if (pt.test(data))
                        removeAction.css('color', '#e51a31');
                    else
                        removeAction.css('color', 'rgb(15, 142, 36)');

                    removeAction.html(data);

                    refreshContent();
                });


        }

        function colorTable() {
            for (var i = 1; i <= 4; i++)
                $('table.pPage tr > td:nth-child(' + parseInt(i) + ')').attr('style', 'background-color:#D6E4F2;');
            for (var i = 1; i <= 3; i++)
                $('table.rPage tr > td:nth-child(' + parseInt(i) + ')').attr('style', 'background-color:#D6E4F2;');

        }

        function printCancelableActivities(page) {

            if (page == undefined)
                page = 0;

            var table = $('#cancelable');

            $.post('./ajaxHandler.php', {action: 'printCancelableActivities', page: page}, 'json').done(
                function (data) {

                    if (data === "Reload")
                        location.reload();

                    data = JSON.parse(data);

                    if (data["lineNumber"] > limit && (typeof pageNc === 'undefined'))
                        location.reload();
                    else
                        totalNumberC = data["lineNumber"];

                    if (data["content"] === "")
                        table.html("<p>Your are not yet registered for any activities.</p>");
                    else {
                        table.html("<tr><td>Activity</td><td>Adults number</td><td>Children number</td>");
                        table.append(data["content"]);
                        colorTable();
                    }

                });


        }

        function printRegistrableActivities(page) {

            if (page == undefined)
                page = 0;

            var table = $('#available');

            $.post('./ajaxHandler.php', {action: 'printRegistrableActivities', page: page}, 'json').done(
                function (data) {

                    if (data === "Reload")
                        location.reload();

                    data = JSON.parse(data);

                    if (data["lineNumber"] > limit && (typeof pageN === 'undefined'))
                        location.reload();
                    else
                        totalNumber = data["lineNumber"];

                    if (data["content"] === "")
                        table.html("<p>There is not available activities.</p>");
                    else {
                        table.html("<tr><td>Activity</td><td>Total Slot</td><td>Available Slot</td><td>How many children</td></tr>");
                        table.append(data["content"]);


                        colorTable();

                        for (var i = 0; i < $('table#available tr').length; i++) {
                            if ($('#as' + parseInt(i)).html() <= 0) {
                                $('table#available tr:eq(' + parseInt(i) + 1 + ') td').attr('style', 'background-color:#e51a31!important;');
                                $('table#available tr:eq(' + parseInt(i) + 1 + ') > td:nth-child(5)').attr('style', '');
                                $('table#available tr:eq(' + parseInt(i) + ') > td:nth-child(5)').html("No mor slots available").css('color', '#e51a31');
                            }

                        }

                    }
                });

        }


        <?php if(!$result["all"]): ?>


        var pageN = $('#pPagePage').html();

        function pre() {

            if (pageN > 1) {
                pageN--;
                printRegistrableActivities(pageN);
                $('#pPagePage').html(pageN);

            }


        }
        function next() {

            if (pageN < Math.ceil(totalNumber / limit)) {
                pageN++;
                printRegistrableActivities(pageN);
                $('#pPagePage').html(pageN);
            }
        }

        <?php endif;
         if(!$remove["all"]):?>


        var pageNc = $('#pPagePageC').html();

        function preC() {

            if (pageNc > 1) {
                pageNc--;
                printCancelableActivities(pageNc);
                $('#pPagePageC').html(pageNc);

            }


        }
        function nextC() {

            if (pageNc < Math.ceil(totalNumberC / limit)) {
                pageNc++;
                printCancelableActivities(pageNc);
                $('#pPagePageC').html(pageNc);
            }
        }


        <?php endif; ?>

    </script>

<?php endif; ?>
</body>
</html>
