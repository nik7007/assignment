<?php
require_once("./function/databaseUtil.php");
require_once("./function/databaseUtil.php");

session_start();

initDB();

function printContent($content){

    while ($row = $content["content"]->fetch_assoc()){
        echo"<section>";
        echo"<h2>".$row["name"]."</h2>";

        echo "<p>".$row["description"] . "<br></p>";
        echo "<p class='activityInfo'>Maximum number of places available: " . $row["slot"];
        echo "<br>";
        echo "Reserved places: ".getNumberReserved($row["name"])."</p>";

        echo"</section>";



    }
}

