<?php 
    require_once "config.php";

    $ic_number = trim($_POST["ic_number"]);
    $password = trim($_POST["password"]);
    $contact_number = trim($_POST["contact_number"]);
    $email = trim($_POST["email"]);

    $sql = "INSERT INTO USER (User_ID, User_email, User_contact, User_password, User_account_status) VALUES ('$ic_number', '$email', '$contact_number', '$password', 'Active')";

    if ($conn->query($sql) === TRUE){
        echo("New account created successfully!");
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
        echo("Registration Error: Check if the IC number has been registered before.");
    }

    $conn->close();
?>