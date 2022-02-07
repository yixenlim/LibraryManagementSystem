<?php
    //connect to database
    include_once "config.php";

    //get data from html ajax
    $userID = $_GET['userID'];
    $status = $_GET['status'];

    //change reservation status 
    $sql = "UPDATE `library`.`USER` SET `User_account_status` = '$status' WHERE (`User_ID` = '$userID')";
    $rs = mysqli_query($conn, $sql);
    echo $rs;
    //close the connection
    mysqli_close($conn);
?>