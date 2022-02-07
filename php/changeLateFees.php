<?php
    //connect to database
    include_once "config.php";

    //get data from html ajax
    $resID = $_GET['resID'];
    $fees = $_GET['fees'];

    //change reservation status 
    $sql = "UPDATE `library`.`RESERVATION` SET `Late_fees` = '$fees' WHERE (`Reservation_ID` = '$resID')";
    $rs = mysqli_query($conn, $sql);

    //close the connection
    mysqli_close($conn);
?>