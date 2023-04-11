
<?php

include '../config/constants.php';

$conn = new mysqli($SERVER_NAME, $USER_NAME, $PASSWORD, $DATABASE_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>