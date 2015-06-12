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

            $error = "<p>";

            if (isset($result) && $result) {
                echo "<p> Successfully login! </p>";
            } else if (isset($result) && !$result) {
                $error = "<p class='error'>Wrong username or password!</p><p>";
            }


            if (!logged()):
                echo "$error Please insert here your username and password: </p>";
                ?>

                <form method='post' action="./login.php">

                    <table>
                        <tr>
                            <td>Username:</td>
                            <td><input type='text' onclick="clearError()" maxlength='16' name='user' value=''/></td>
                            <td id="errorUser" class="error"></td>
                        </tr>
                        <tr>
                            <td>Password:</td>
                            <td><input type='password' onclick="clearError()" maxlength='16' name='pass' value=''/></td>
                            <td id="errorPass" class="error"></td>
                        </tr>

                        <tr>
                            <td><input type='button' onclick="sendToServer();" value='Login'/></td>
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
<?php if (!logged()): ?>
    <script>


        function sendToServer() {

            var userName = $('form')[0][0];
            var password = $('form')[0][1];
            var error = false;

            clearError();

            if (userName.value === "") {
                error = true;
                $('#errorUser').html("Invalid User name!!");
                userName.value = "";
                password.value = "";
            }

            if (password.value === "") {
                error = true;
                $('#errorPass').html("Invalid Password!!");
                password.value = "";
            }

            if (!error) {
                $('form')[0].submit();
            }


        }

    </script>
<?php endif; ?>


</body>
</html>
