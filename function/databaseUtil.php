<?php

$mysqli = new mysqli();

$db_url = "";
$db_user_name = "";
$db_password = "";
$db_name = "";

$db_table_users = "";
$db_table_activities = "";
$db_table_reservations = "";

$db_limit_to_show = 0;
$_db_create_demo_field = true;


function dbConnection()
{
    global $db_url, $db_user_name, $db_password, $mysqli;

    $mysqli = new mysqli($db_url, $db_user_name, $db_password);

    if (mysqli_connect_errno()) {
        echo "DBMS connection error: " . mysqli_connect_error();
        die();
    }

    $mysqli->autocommit(true);

}

function dbSelectOrCreateDB()
{
    global $mysqli, $db_name;

    if (!$mysqli->select_db($db_name)) {

        $sql = 'CREATE DATABASE ' . $db_name;

        $mysqli->query($sql);
        $mysqli->select_db($db_name);

    }
}

function dbCheckTable($table_name)
{
    global $mysqli;

    $query = "SHOW TABLES LIKE '" . $table_name . "'";

    $result = $mysqli->query($query);

    if (!$result) return false;

    return ($result->num_rows > 0);
}

function initTables()
{

    global $mysqli, $db_table_users, $db_table_activities, $db_table_reservations, $_db_create_demo_field;


    if (!dbCheckTable($db_table_users)) {
        $query = "CREATE TABLE $db_table_users(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(30) UNIQUE NOT NULL,
        password VARCHAR(36) NOT NULL
        )";

        $mysqli->query($query);

        if ($_db_create_demo_field):

            saveNewUser("u1", "p1");
            saveNewUser("u2", "p2");
            saveNewUser("u3", "p3");

        endif;

    }

    if (!dbCheckTable($db_table_activities)) {

        $query = "CREATE TABLE $db_table_activities(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(30) UNIQUE NOT NULL,
        description VARCHAR(250)  NOT NULL,
        slot INT UNSIGNED NOT NULL
        )";

        $mysqli->query($query);

        //if ($_db_create_demo_field):

        $mysqli->query("
        INSERT INTO $db_table_activities(name,description,slot)
        VALUES('Tennis','We have all the necessary equipment to allow you to play tennis at any age.',6)
        ");

        $mysqli->query("
        INSERT INTO $db_table_activities(name,description,slot)
        VALUES('Golf','Have fun on our lawns course. Fun for the whole family!',8)
        ");

        $mysqli->query("
        INSERT INTO $db_table_activities(name,description,slot)
        VALUES('Swimming','In our swimming pools you can swim in complete safety and quiet. Our experienced lifeguards are prepared for any emergency.',4)
        ");

        //endif;

    }


    if (!dbCheckTable($db_table_reservations)) {


        $query = "CREATE TABLE $db_table_reservations(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user INT(6) UNSIGNED NOT NULL,
        activity INT(6) UNSIGNED NOT NULL,
        reservation INT(1) UNSIGNED NOT NULL,
        FOREIGN KEY(user) REFERENCES $db_table_users(id),
        FOREIGN KEY(activity) REFERENCES $db_table_activities(id)
        )";

        $mysqli->query($query);

        if ($_db_create_demo_field):
            newReservation('u1', 'Tennis', 2);
            newReservation('u1', 'Golf', 1);

            newReservation('u2', 'Tennis', 1);
            newReservation('u2', 'Golf', 2);

            newReservation('u3', 'Tennis', 2);
            newReservation('u3', 'Swimming', 2);

        endif;

    }


}

function getTableSize($table)
{
    global $mysqli;

    $result = $mysqli->query("SELECT count(*) FROM $table");

    if (!$result)
        return 0;

    $r = $result->fetch_assoc();

    return (int)$r["count(*)"];
}

function getActivities($par1 = false, $user = false, $in = false)
{

    $result = array();
    global $mysqli, $db_table_activities, $db_table_reservations, $db_limit_to_show;

    $notIn = "";


    if ($user) {
        global $db_table_users;

        $uD = $mysqli->query("SELECT id FROM $db_table_users WHERE  name = '" . sanitizeString($user) . "'")->fetch_assoc();

        $u = $uD['id'];

        if (!$in)
            $notIn = "AND $db_table_activities.id NOT IN (SELECT  activity FROM $db_table_reservations WHERE user = $u)";
        else
            $notIn = "AND $db_table_activities.id IN (SELECT $db_table_reservations.activity FROM $db_table_reservations WHERE $db_table_reservations.user = $u)";

    }

    if (!$user)
        $i = getTableSize($db_table_activities);
    else {
        $qn1 = "
              SELECT count(DISTINCT $db_table_activities.id)
              FROM $db_table_activities
              WHERE 1=1 $notIn
              ";

        $n1 = $mysqli->query($qn1);
        if ($n1) {
            $nA = $n1->fetch_assoc();
            $n = $nA["count(DISTINCT $db_table_activities.id)"];
        } else {
            $qn2 = "
              SELECT count(DISTINCT $db_table_activities.id)
              FROM $db_table_activities
              WHERE 1=1 $notIn";

            $n2 = $mysqli->query($qn2);

            if ($n2) {
                $nA = $n2->fetch_assoc();
                $n = $nA["count(DISTINCT $db_table_activities.id)"];
            } else
                $n = 0;
        }

        $i = $n;
    }

    $result["lineNumber"] = $i;

    if ($i == 0) {
        $result["all"] = true;
        return $result;
        $result["content"] = null;
    }

    $query = "
              SELECT $db_table_activities.name, $db_table_activities.description, $db_table_activities.slot, ($db_table_activities.slot-COALESCE((SELECT SUM(reservation) FROM $db_table_reservations WHERE $db_table_reservations.activity = activities.id),0)) AS disp
              FROM $db_table_activities
              WHERE 1=1 $notIn
              GROUP BY $db_table_activities.id
              ORDER BY disp DESC
              ";

    $query1 = "SELECT $db_table_activities.name, $db_table_activities.description, $db_table_activities.slot
              FROM $db_table_activities
              WHERE 1=1 $notIn
              ORDER BY $db_table_activities.slot DESC";

    if ($i <= $db_limit_to_show) {
        $result["all"] = true;

        $r1 = $mysqli->query($query);

        if ($r1->num_rows != 0)
            $result["content"] = $r1;
        else
            $result["content"] = $mysqli->query($query1);

    } else {

        if (!$par1)
            $start = 1;
        else if ($par1 > $i / $db_limit_to_show) $start = ceil($i / $db_limit_to_show);
        else
            $start = ceil((float)$par1 * $db_limit_to_show);

        $howMany = $db_limit_to_show;

        $start--;

        $result["all"] = false;

        $r1 = $mysqli->query($query . "
                                            LIMIT $start, $howMany
                                            ");
        if ($r1 != null)
            $result["content"] = $r1;
        else
            $result["content"] = $mysqli->query($query1 . "
                                            LIMIT $start, $howMany
                                            ");

    }

    return $result;
}

function getNumberReserved($activity)
{

    global $mysqli, $db_table_reservations, $db_table_activities;

    $result = $mysqli->query("
        SELECT SUM(reservation)
        FROM $db_table_reservations
        WHERE  activity = ( SELECT id
                            FROM $db_table_activities
                            WHERE name = '$activity')
        GROUP BY activity

    ");

    if ($result->num_rows == 0)
        return 0;
    else {

        $r = $result->fetch_assoc();

        return $r["SUM(reservation)"];

    }


}

function getUser($username, $password)
{
    global $mysqli, $db_table_users;
    $encodePassword = md5(sanitizeString($password));


    $query = "SELECT name
              FROM $db_table_users
              WHERE name = '" . sanitizeString($username) . "' AND password = '$encodePassword'";

    return $mysqli->query($query);

}

function existUser($username)
{
    global $mysqli, $db_table_users;

    $result = $mysqli->query("
                            SELECT name
                            FROM $db_table_users
                            WHERE name = '$username'
                            ");

    return ($result->num_rows > 0);
}

function saveNewUser($username, $password)
{

    global $mysqli, $db_table_users;

    $usr = sanitizeString($username);
    $ps = sanitizeString($password);

    if (empty($usr) || empty($ps))
        return false;

    $query = "SELECT COUNT(*) FROM $db_table_users WHERE name = '$usr'";

    $flag = $mysqli->query($query)->fetch_assoc();

    if ($flag["COUNT(*)"] > 0)
        return false;

    $encodePassword = md5($ps);

    return ($mysqli->query("
        INSERT INTO $db_table_users(name,password)
        VALUES('" . $usr . "', '$encodePassword')
        ") != false);

}

function getFreeSlots($activity)
{

    global $mysqli, $db_table_activities;

    $toSearch = sanitizeString($activity);

    $reserved = getNumberReserved($activity);

    $query = "
              SELECT slot
              FROM $db_table_activities
              WHERE $db_table_activities.name = '$toSearch'
              ";


    $result = $mysqli->query($query);


    if ($result) {
        $row = $result->fetch_assoc();

        return $row['slot'] - $reserved;

    }


    return -1;

}

/*
function getReservation($user, $par = false)
{

    global $mysqli, $db_table_users, $db_table_activities, $db_table_reservations, $db_limit_to_show;

    $u = $mysqli->query("SELECT id FROM $db_table_users WHERE  name = '" . sanitizeString($user) . "'")->fetch_assoc()['id'];

    $i = $mysqli->query("
                        SELECT Count(*)
                        FROM $db_table_reservations
                        WHERE user = $u
                        ");

    if ($i == 0) return false;

    $result["lineNumber"] = $i;

    $query = "
              SELECT $db_table_activities.name, $db_table_reservations.reservation
              FROM $db_table_activities,$db_table_reservations
              WHERE $db_table_activities.id = $db_table_reservations.activity AND $db_table_reservations.user= $u";

    if ($i < $db_limit_to_show) {

        $result["all"] = true;

        $r = $mysqli->query($query);

        if ($r)
            $result["content"] = $r->fetch_assoc();
        else
            $result["content"] = false;

    } else {

        if (!$par)
            $start = 1;
        else if ($par > $i / $db_limit_to_show) $start = ceil($i / $db_limit_to_show);
        else
            $start = ceil((float)$par * $db_limit_to_show);

        $howMany = $db_limit_to_show;

        $start--;

        $result["all"] = false;
        $r = $mysqli->query($query . "LIMIT $start, $howMany");

        if ($r)
            $result["content"] = $r->fetch_assoc();
        else
            $result["content"] = false;

    }

    return $result;


}
*/

function canReserve($user, $activity)
{
    global $mysqli, $db_table_users, $db_table_activities, $db_table_reservations;

    $uD = $mysqli->query("SELECT id FROM $db_table_users WHERE  name = '" . sanitizeString($user) . "'")->fetch_assoc();
    $aD = $mysqli->query("SELECT id FROM $db_table_activities WHERE  name = '" . sanitizeString($activity) . "'")->fetch_assoc();

    $u = $uD['id'];
    $a = $aD['id'];

    $result = $mysqli->query("SELECT Count(*) FROM $db_table_reservations WHERE activity = $a AND user = $u");

    if (!$result)
        return true;


    $r = $result->fetch_assoc();

    if ($r['Count(*)'] == 0)
        return true;

    return false;

}

function newReservation($user, $activity, $howMany)
{

    global $mysqli, $db_table_users, $db_table_activities, $db_table_reservations;

    if ($howMany > 4) {
        return -4;
    }
    if (!canReserve($user, $activity))
        return -3;

    $uD = $mysqli->query("SELECT id FROM $db_table_users WHERE  name = '" . sanitizeString($user) . "'")->fetch_assoc();
    $aD = $mysqli->query("SELECT id FROM $db_table_activities WHERE  name = '" . sanitizeString($activity) . "'")->fetch_assoc();

    $u = $uD['id'];
    $a = $aD['id'];

    try {
        $mysqli->autocommit(false);

        $slotFree = getFreeSlots($activity);

        if ($slotFree == null)
            $slotFree = 0;


        if ($howMany > $slotFree) {
            throw new Exception("Error! not enough slot.");

        } else {

            $mysqli->autocommit(false);


            //die("Start");

            $query = "
        INSERT INTO $db_table_reservations(user,activity,reservation)
        VALUES($u,$a,$howMany)
        ";

            $result = $mysqli->query($query);
            if (!$result) {
                throw new Exception("Error! Query fail.");
            }

            $mysqli->commit();
            $result = 1;
        }

    } catch (Exception $e) {
        $mysqli->rollback();

        switch ($e->getMessage()) {

            case "Error! not enough slot.":
                $result = -1;
                break;
            case "Error! Query fail.":
                $result = -2;
                break;
            default:
                $result = 1;
        }

    }

    $mysqli->autocommit(true);

    return $result;
}

function removeReservation($user, $activity)
{
    global $mysqli, $db_table_users, $db_table_activities, $db_table_reservations;

    $uD = $mysqli->query("SELECT id FROM $db_table_users WHERE  name = '" . sanitizeString($user) . "'")->fetch_assoc();
    $aD = $mysqli->query("SELECT id FROM $db_table_activities WHERE  name = '" . sanitizeString($activity) . "'")->fetch_assoc();

    $u = $uD['id'];
    $a = $aD['id'];


    $result = $mysqli->query("DELETE FROM $db_table_reservations WHERE user = $u AND activity = $a");


    return $result;
}

function sanitizeString($var)
{
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return mysql_real_escape_string($var);
}


function initDB()
{

    $db_config = parse_ini_file("./config/database.php", true);

    global $db_url, $db_user_name, $db_password, $db_name, $db_table_users, $db_table_activities, $db_table_reservations, $_db_create_demo_field, $db_limit_to_show;

    $db_url = $db_config["db_information"]["host"];
    $db_user_name = $db_config["db_information"]["user"];
    $db_password = $db_config["db_information"]["password"];
    $db_name = $db_config["db_information"]["name"];


    $db_table_users = $db_config["db_table_information"]["users"];
    $db_table_activities = $db_config["db_table_information"]["activities"];
    $db_table_reservations = $db_config["db_table_information"]["reservations"];
    $_db_create_demo_field = (bool)$db_config["db_table_information"]["demo"];
    $db_limit_to_show = (int)$db_config["db_table_information"]["limit"];

    dbConnection();
    dbSelectOrCreateDB();
    initTables();

}


