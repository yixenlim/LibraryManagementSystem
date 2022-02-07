<?php
    $db_servername = "localhost";
    $db_username = "root";
    $db_password = "mysql123";
    $db_database = "library";

    // create connection
    $conn = new mysqli($db_servername, $db_username, $db_password, $db_database);

    // check connection
    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
?>