
<div class="sidebar1">

    <?php

    if (logged())
    echo "<p id = 'userLoggedInfo'>Hi ".$_SESSION['user']." </p>";
    ?>

    <ul class="nav">
        <li><a href="./">Home Page</a></li>
    </ul>

    <ul class="nav">
        <?php
        if (!logged()):
            ?>
            <li><a href="./login.php">Login</a></li>
            <li><a href="#">Register</a></li>
        <?php else: ?>
            <li><a href="#">Personal Page</a></li>
            <li><a href="./logout.php">Logout</a></li>
        <?php endif ?>

    </ul>

    <!-- end .sidebar1 --></div>