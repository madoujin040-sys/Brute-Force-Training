<?php
$host = "localhost";
$user = "root";
$pass = ""; 
$db   = "db_cybernusa_v2";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("System error: Unable to connect to the database.");
}
?>