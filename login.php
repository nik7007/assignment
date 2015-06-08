<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
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
        <h1>Login</h1>

        <section>

            <?php

            $error = "";

            if (isset($result) && $result) {
                echo "<p> Successfully login! </p>";
            } else if (isset($result) && !$result) {
                $error = "Wrong username or password!</p><p>";
            }


            if (!logged()):
                echo "<p>$error Please insert here your username and password: </p>";
                ?>

                <form style="margin-left: 15px; margin-bottom: 15px; width: 757px;display: block;" method='post'
                      action="./login.php">

                    <table>
                        <tr>
                            <td>Username:</td>
                            <td><input type='text' maxlength='16' name='user' value=''/></td>
                        </tr>
                        <tr>
                            <td>Password:</td>
                            <td><input type='password' maxlength='16' name='pass' value=''/></td>
                        </tr>

                        <tr>
                            <td><input type='submit' value='Login'/></td>
                        </tr>
                    </table>

                </form>
            <?php endif; ?>

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
