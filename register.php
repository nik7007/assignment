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
        $result = saveNewUser($_POST["user"], $_POST["pass"]);
    }

    require "main_menu.php";
    ?>
    <article class="content">
        <h1>Register</h1>

        <section>

            <?php

            $error = "<p>";

            if (isset($result)) {
                if ($result)
                    echo "<p> Successfully Register! </p>";
                else $error = "<p class='error'>Username not available!</p><p>";
            }
            if (!isset($result) || !$result):
                echo "$error Please choose an usermane and password! Do not forgot them, there is no way to recover: </p>";
                ?>

                <form method='post'
                      action="./register.php">

                    <table>
                        <tr>
                            <td>Username:</td>
                            <td><input type='text' onclick="clearError()" maxlength='16' name='user' value=''/></td>
                            <td id="errorUser" class="error"></td>
                        </tr>
                        <tr>
                            <td>Password:</td>
                            <td><input type='password' onclick="clearError()" maxlength='16' name='pass' value=''/>
                            </td>
                            <td id="errorPass" class="error"></td>
                        </tr>
                        <tr>
                            <td>Confirm:</td>
                            <td><input type='password' onclick="clearError()" maxlength='16' name='confirmPass'
                                       value=''/>
                            </td>
                            <td id="errorConfPass" class="error"></td>
                        </tr>

                        <tr>
                            <td><input type='button' onclick="sendToServer();" value='Login'/></td>
                        </tr>
                    </table>

                </form>
            <?php endif;
            if (isset($result) && $result)
                echo "<p>Now you can <a href='./login.php'>Login</a> and use our services!</p>"; ?>


        </section>

        <!-- end .content -->
    </article>



    <?php
    require "footer.php";

    ?>
    <!-- end .container -->


</div>

<script>

    document.addEventListener("keypress", function (event) {
        if (event.keyCode == 13) sendToServer();
    });

    function sendToServer() {

        var userName = $('form')[0][0];
        var password = $('form')[0][1];
        var confPass = $('form')[0][2];
        var error = false;

        clearError();

        if (userName.value === "") {
            error = true;
            $('#errorUser').html("Invalid User name!!");
            userName.value = "";
            password.value = "";
            confPass.value = "";
        }

        if (password.value === "") {
            error = true;
            $('#errorPass').html("Invalid Password!!");
            password.value = "";
            confPass.value = "";
            return;
        }
        if (password.value !== confPass.value) {
            error = true;
            $('#errorConfPass').html("Passwords do not match!!");
            password.value = "";
            confPass.value = "";
        }

        if (!error) {
            $('form')[0].submit();
        }


    }

</script>


</body>
</html>
