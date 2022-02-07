<?php
    //connect to database
    include_once "config.php";

    //get data from html ajax
    $idType = $_GET['idType'];
    $idContent = $_GET['idContent'];

    // database select SQL code
    $myArray = array();
    if ($idType == "User_ID")//get reservation
        $sql = "SELECT * FROM RESERVATION where User_ID = $idContent and Reservation_status != 'Completed' and Reservation_status != 'Over' ORDER BY Book_ID";
    else//get book
        $sql = "SELECT * FROM BOOK where Book_ID=$idContent ORDER BY Book_ID";

    $rs = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_array($rs)) 
    {
        if ($idType == "User_ID")
        {
            $myArray[] = ['Reservation_ID' => $row['Reservation_ID'],
                        'User_ID' => $row['User_ID'],
                        'Book_ID' => $row['Book_ID'],
                        'Late_fees' => $row['Late_fees'],
                        'Reservation_status' => $row['Reservation_status'],
                        'Reservation_date' => $row['Reservation_date'],
                        'Return_date' => $row['Return_date']
                        ]; 
        }
        else
        {
           $myArray[] = ['Book_ID' => $row['Book_ID'],
                        'Book_title' => $row['Book_title'],
                        'Book_author' => $row['Book_author'],
                        'Book_publisher' => $row['Book_publisher'],
                        'Book_published_year' => $row['Book_published_year'],
                        'Book_ISBN' => $row['Book_ISBN'],
                        'Book_status' => $row['Book_status'],
                        'Book_info' => $row['Book_info']
                        ]; 
        }
    }

    echo json_encode($myArray);

    mysqli_close($conn);
?>