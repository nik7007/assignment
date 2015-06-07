<?php

$mysqli = new mysqli();

$db_url = "";
$db_user_name = "";
$db_password = "";
$db_name = "";

$db_table_users = "";
$db_table_activities = "";
$db_table_reservations = "";

$db_limit_to_show = 10;


function dbConnection()
{
    global $db_url, $db_user_name, $db_password, $mysqli;

    $mysqli = new mysqli($db_url, $db_user_name, $db_password);

    if (mysqli_connect_errno()) {
        echo "DBMS connection error: " . mysqli_connect_error();
        die();
    }

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

    global $mysqli, $db_table_users, $db_table_activities, $db_table_reservations;


    if (!dbCheckTable($db_table_users)) {
        $query = "CREATE TABLE $db_table_users(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(30) UNIQUE NOT NULL,
        children INT(3) UNSIGNED NOT NULL,
        password VARCHAR(30) NOT NULL
        )";

        $mysqli->query($query);

        $mysqli->query("
        INSERT INTO $db_table_users(name,children,password)
        VALUES('u1',3 ,'" . md5("p1") . "')
        ");

        $mysqli->query("
        INSERT INTO $db_table_users(name,children,password)
        VALUES('u2', 3,'" . md5("p2") . "')
        ");

        $mysqli->query("
        INSERT INTO $db_table_users(name,children,password)
        VALUES('u3',3 ,'" . md5("p3") . "')
        ");

    }

    if (!dbCheckTable($db_table_activities)) {

        $query = "CREATE TABLE $db_table_activities(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(30) UNIQUE NOT NULL,
        description VARCHAR(250)  NOT NULL,
        slot INT UNSIGNED NOT NULL
        )";

        $mysqli->query($query);


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

        $mysqli->query("
        INSERT INTO $db_table_reservations(user,activity,reservation)
        VALUES(1,1,2)
        ");

        $mysqli->query("
        INSERT INTO $db_table_reservations(user,activity,reservation)
        VALUES(1,2,1)
        ");

        $mysqli->query("
        INSERT INTO $db_table_reservations(user,activity,reservation)
        VALUES(2,1,1)
        ");

        $mysqli->query("
        INSERT INTO $db_table_reservations(user,activity,reservation)
        VALUES(2,2,2)
        ");

        $mysqli->query("
        INSERT INTO $db_table_reservations(user,activity,reservation)
        VALUES(3,1,2)
        ");

        $mysqli->query("
        INSERT INTO $db_table_reservations(user,activity,reservation)
        VALUES(3,3,2)
        ");


    }


}

function getTableSize($table)
{
    global $mysqli;

    $result = $mysqli->query("SELECT count(*) FROM $table");

    if (!$result)
        return 0;

    return (int)$result->fetch_assoc()["count(*)"];
}

function getActivities($par1 = false)
{

    $result = [];

    global $mysqli, $db_table_activities, $db_table_reservations, $db_limit_to_show;

    $i = getTableSize($db_table_activities);

    if ($i == 0) return false;

    $result["lineNumber"] = $i;

    if ($i <= $db_limit_to_show) {
        $result["all"] = true;
        $result["content"] = $mysqli->query("
                                            SELECT *
                                            FROM $db_table_activities,$db_table_reservations
                                            WHERE $db_table_reservations.activity = $db_table_activities.id
                                            GROUP BY $db_table_activities.id
                                            ORDER BY  $db_table_activities.slot-$db_table_reservations.reservation DESC
                                            ");
    } else {

        if (!$par1)
            $start = 1;
        else
            $start = $par1 * $db_limit_to_show;

        $howMany = $db_limit_to_show;

        $start--;

        $result["all"] = false;
        $result["content"] = $mysqli->query("
                                            SELECT *
                                            FROM $db_table_activities,$db_table_reservations
                                            WHERE $db_table_reservations.activity = $db_table_activities.id
                                            GROUP BY $db_table_activities.id
                                            ORDER BY $db_table_activities.slot-$db_table_reservations.reservation DESC
                                            LIMIT $start, $howMany
                                            ");
    }

    return $result;
}

function getNumberReserved($activity)
{

    global $mysqli, $db_table_reservations, $db_table_activities;
    $count = 0;
    $result = $mysqli->query("
        SELECT reservation
        FROM $db_table_reservations
        WHERE  activity = ( SELECT id
                            FROM $db_table_activities
                            WHERE name = '$activity')

    ");

    if (!$result)
        return 0;
    else {

        while ($row = $result->fetch_assoc()) {

            $count += (int)$row["reservation"];

        }

    }
    return $count;

}


function initDB()
{

    $db_config = parse_ini_file("./config/database.php", true);

    global $db_url, $db_user_name, $db_password, $db_name, $db_table_users, $db_table_activities, $db_table_reservations;

    $db_url = $db_config["db_information"]["host"];
    $db_user_name = $db_config["db_information"]["user"];
    $db_password = $db_config["db_information"]["password"];
    $db_name = $db_config["db_information"]["name"];

    $db_table_users = $db_config["db_table_information"]["users"];
    $db_table_activities = $db_config["db_table_information"]["activities"];
    $db_table_reservations = $db_config["db_table_information"]["reservations"];

    dbConnection();
    dbSelectOrCreateDB();
    initTables();

}


?>