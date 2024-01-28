<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "intech-intern";

// Create connection
$dbconn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($dbconn->connect_error) {
    die("Connection failed: " . $dbconn->connect_error);
}
?>