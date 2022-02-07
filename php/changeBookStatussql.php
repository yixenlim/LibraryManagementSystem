<?php
    //connect to database
    include_once "config.php";

    //get data from html ajax
    $bookID = $_GET['bookID'];
    $resID = $_GET['resID'];
    $status = $_GET['status'];

    //change book status (admin change if book broken)
    $sql = "UPDATE `library`.`BOOK` SET `Book_status` = '$status' WHERE (`Book_ID` = '$bookID')";
    $rs = mysqli_query($conn, $sql);
    
    //change reservation status
    if ($resID != "0" and $status == "Borrowed")//user comes to pick up
    {
        $sql = "UPDATE `library`.`RESERVATION` SET `Reservation_status` = 'PickedUp' WHERE (`Reservation_ID` = '$resID')";
        $rs = mysqli_query($conn, $sql);
    }
    else if ($resID != "0" and ($status == "Available" or $status == "Unavailable"))//user comes to pay late fees or return book
    {
        $sql = "UPDATE `library`.`RESERVATION` SET `Reservation_status` = 'Completed' WHERE (`Reservation_ID` = '$resID')";
        $rs = mysqli_query($conn, $sql);
    }

    //close the connection
    mysqli_close($conn);
?>