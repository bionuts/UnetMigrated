<?php

$food_servername = "localhost";
$food_username = "root";
$food_password = "hmmhmm";
$food_dbname = "unetdb";

function connect()
{
    global $food_servername;
    global $food_username;
    global $food_password;
    global $food_dbname;

    $conn = new mysqli($food_servername, $food_username, $food_password, $food_dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);//chnage it        ***************************
    }
    mysqli_set_charset($conn, "utf8");
    return $conn;
}