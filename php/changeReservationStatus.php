<?php
    //connect to database
    include_once "config.php";

    //get data from html ajax
    $resID = $_GET['resID'];
    $bookID = $_GET['bookID'];

    //change reservation status 
    $sql = "UPDATE `library`.`RESERVATION` SET `Reservation_status` = 'Over' WHERE (`Reservation_ID` = '$resID')";
    $rs = mysqli_query($conn, $sql);

    //change book status (reservation over 3 days, change book to available)
    $sql = "UPDATE `library`.`BOOK` SET `Book_status` = 'Available' WHERE (`Book_ID` = '$bookID')";
    $rs = mysqli_query($conn, $sql);

    //close the connection
    mysqli_close($conn);
?>