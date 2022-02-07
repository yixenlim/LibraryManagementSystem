<?php
    //connect to database
    include_once "config.php";

    // database select SQL code
    $myArray = array();
    $sql = "SELECT * FROM RESERVATION where Reservation_status = 'Waiting' or Reservation_status = 'PickedUp' ";
    $rs = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_array($rs)) 
    {
        $myArray[] = ['Reservation_ID' => $row['Reservation_ID'],
                    'User_ID' => $row['User_ID'],
                    'Book_ID' => $row['Book_ID'],
                    'Reservation_status' => $row['Reservation_status'],
                    'Reservation_date' => $row['Reservation_date'],
                    'Return_date' => $row['Return_date']
                    ]; 
    }

    echo json_encode($myArray);

    mysqli_close($conn);
?>