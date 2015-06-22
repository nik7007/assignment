
<div class="sidebar1">

    <?php

    if (logged())
    echo "<p id = 'userLoggedInfo'>Hi <span class='userName'>".$_SESSION['user']."</span></p>";
    ?>

    <ul class="nav">
        <li><a href="./">Home Page</a></li>
    </ul>

    <ul class="nav">
        <?php
        if (!logged()):
            ?>
            <li><a href="./login.php">Login</a></li>
            <li><a href="./register.php">Register</a></li>
        <?php else: ?>
            <li><a href="./personalpage.php">Personal Page</a></li>
            <li><a href="./logout.php">Logout</a></li>
        <?php endif ?>

    </ul>

    <!-- end .sidebar1 --></div>