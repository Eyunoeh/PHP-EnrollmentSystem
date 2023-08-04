<?php
$host = "localhost";
$username = "root";
$database = "db_enrollment_system";

$conn = new mysqli($host, $username, '', $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
