<?php

$hostname = "localhost";
$username = "root";
$password = ""; 
$dbName = "tc22";


$users = new mysqli($hostname, $username, $password, $dbName);

// Successfully database 
//echo "database succesfully! ";

// Check for connection errors
if ($users->connect_error) {

    die("Connection failed: " . $db->connect_error);
}



?>
