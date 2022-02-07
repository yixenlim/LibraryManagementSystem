<?php
    include_once "config.php";
    include_once "session.php"; 

    $user_ID = $_SESSION['userid'];
    $book_ISBN = $_POST['Book_ISBN'];
    $reserveDate = $_POST['Reservation_date'];
    $returnDate = $_POST['Return_date'];

    if (empty($reserveDate)) {
        $reserveDate = 'NULL';
    }

    if (empty($returnDate)) {
        $returnDate = 'NULL';
    }

    $sql = "SELECT Book_ID FROM book WHERE Book_ISBN = '$book_ISBN' AND Book_status = 'Available' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $book_ID = $row['Book_ID'];

    $sql = "INSERT INTO reservation (User_ID, Book_ID, Late_fees, Reservation_status, Reservation_date, Return_date) VALUES
           ('$user_ID', $book_ID, 0, 'Waiting', '$reserveDate', '$returnDate')";
    $insertResult = mysqli_query($conn, $sql);

    $sql = "UPDATE book SET Book_status = 'Reserved' WHERE Book_ID = $book_ID";
    $updateResult = mysqli_query($conn, $sql);

    if ($insertResult and $updateResult) {
        echo TRUE;
    } else {
        echo FALSE;
    }

    mysqli_close($conn);

?>