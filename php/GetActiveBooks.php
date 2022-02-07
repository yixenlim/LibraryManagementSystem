<?php
    include_once "config.php";
    include_once "session.php";
    
    $user_ID = $_SESSION['userid'];
    $sql = "SELECT * FROM reservation WHERE User_ID = '$user_ID' AND Reservation_status != 'Completed' AND Reservation_status != 'Over';";

    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);

    $reservation = array();
    if($resultCheck > 0) {
        while($row = $result->fetch_row()){
            $reservation[] = ['Reservation_ID' => $row[0],
            'User_ID' => $row[1],
            'Book_ID' => $row[2],
            'Late_fees' => $row[3],
            'Reservation_status' => $row[4],
            'Reservation_date' => $row[5],
            'Return_date' => $row[6]
            ]; 
        }
        echo json_encode($reservation);
    }
    else {
        echo FALSE;
    }

    mysqli_close($conn);
?>