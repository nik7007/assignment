<?php
require_once("./function/databaseUtil.php");
require_once("./function/sessionUtil.php");

session_start();

if (!isset($_SERVER['HTTP_COOKIE'])) {
    if (!isset($_GET['test_enabled_cookie'])) {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?test_enabled_cookie=test');
        exit();
    } else {
        header('Location: disabled_cookie.php');
        exit();
    }
}


initDB();
checkTimeout();

function printContent($content)
{

    while ($row = $content["content"]->fetch_assoc()) {
        echo "<section>";
        echo "<h2>" . $row["name"] . "</h2>";

        echo "<p>" . $row["description"] . "<br></p>";
        echo "<p class='activityInfo'>Maximum number of places available: " . $row["slot"];
        echo "<br>";
        echo "Reserved places: " . getNumberReserved($row["name"]) . "</p>";

        echo "</section>";

    }
}


function printRegistrableActivities($activities)
{
    for ($i = 0; $row = $activities["content"]->fetch_assoc(); $i++) {
        echo "<tr>";

        echo "<td id='activity$i' >" . $row["name"] . "</td><td id='ts$i' >" . $row["slot"] . "</td>
            <td id='as$i' >" . $row["disp"] . "</td>
            <td>
                <select id='ac$i'>
                    <option value='0'>0</option>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option></select>
            </td>
            <td>
                <button type='button' onclick=\"sendRegister('activity$i','ac$i');\">Register</button>
            </td>";


        echo "</tr>";

    }
}

function printCancelableActivities($activities)
{
    for ($i = 0; $row = $activities["content"]->fetch_assoc(); $i++) {

        $cn = $row["slot"] - $row["disp"] - 1;

        echo "<tr>";
        echo "<td id = 'activityC$i'>" . $row["name"] . "</td><td id = 'na$i'>1</td>
              <td id = 'nc$i'>$cn</td>
              <td>
                <button type = 'button' onclick = \"cancelRegistration('activityC$i')\">Cancel Registration</button>
              </td>
              ";

        echo "</tr>";


    }

}

function registerNewActivity($activity, $howMany)
{

    $result = newReservation($_SESSION["user"], $activity, (int)$howMany);

    if (!isset($result))
        return "Error! Unable to perform the request";

    if ($result == -4)
        return "Error! Too many children";
    if ($result == -3)
        return "Error! This user is already register for this activity.";
    if ($result == -2)
        return "Error! Database busy. Tray again later.";
    if ($result == -1)
        return "Error! Slots not available.";
    if ($result == 1)
        return "Insert new reservation.";

    return "Error! Unexpected behavior";


}

function cancelRegister($activity, $user)
{

    if (removeReservation($user, $activity)) {
        echo "Reservation removed";
    } else
        echo "Error! Unable to perform the require operation.";

}
