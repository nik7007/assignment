<?php
require_once("./function/databaseUtil.php");

initDB();

function printContent($content){

    while ($row = $content["content"]->fetch_assoc()){
        echo"<section>";
        echo"<h2>".$row["name"]."</h2>";

        echo "<p>".$row["description"] . "<br><br>";
        echo "Maximum number of places available: " . $row["slot"];
        echo "</p>";
        echo "<p>Reserved places: ".getNumberReserved($row["name"])."</p>";

        echo"</section>";



    }
}

?>