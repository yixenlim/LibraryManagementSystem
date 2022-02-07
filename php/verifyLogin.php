<?php
    require_once "config.php";
    
    // start the session
    session_start();

    $myArray = array();

    $user_ID = trim($_POST["ic_number"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM USER WHERE User_ID = '$user_ID';";
    $rs = $conn->query($sql);
    
    if ($rs->num_rows > 0) { // if an account with this user ID exists in USER
        $row = $rs->fetch_assoc();
        
        if($password == $row['User_password']){
            
            $_SESSION['userid'] = $row["User_ID"]; 
            $_SESSION['useremail'] = $row["User_email"];
            $_SESSION['usercontact'] = $row["User_contact"];
            $_SESSION['userpassword'] = str_repeat("●", strlen($row["User_password"]));
            $_SESSION['useraccountstatus'] = strtoupper($row["User_account_status"]);
            $_SESSION['type'] = 'User';

            /* Check if any PickedUp books are overdue and update the status */
            $status_sql = "SELECT * FROM RESERVATION WHERE User_ID = '$user_ID' AND Reservation_status = 'PickedUp';";
            $status_rs = $conn->query($status_sql);

            if ($status_rs->num_rows > 0){
                while($row = $status_rs->fetch_assoc()){

                    date_default_timezone_set('Asia/Kuala_Lumpur');
                    $today_date = new DateTime(date("Y-m-d")); // date() doesn't return a DateTime object, but a formatted string.
                    $return_date = new DateTime($row["Return_date"]);

                    if ($return_date < $today_date)//overdue
                    {
                        $interval = $return_date->diff($today_date);
                        $days_overdue = $interval->days;
                        $reservation_id = $row["Reservation_ID"]; // for query purpose
                        $temp_status_sql = "UPDATE RESERVATION SET Reservation_status = 'Overdue' WHERE Reservation_ID = '$reservation_id'";
                        $temp_status_rs = $conn->query($temp_status_sql);
                    }
                }
            }

            /* Check if there's an overdue book */
            $overdue_sql = "SELECT * FROM RESERVATION WHERE User_ID = '$user_ID';";
            $overdue_rs = $conn->query($overdue_sql);

            // modify late fees value here
            $late_fee_per_day = 1; // RM1 per day from return date
            $max_late_fee = 50; // Max late fee for each book = RM50

            $hasOverdueBook = false;

            if ($overdue_rs->num_rows > 0){

                $total_late_fees = 0;

                while($row = $overdue_rs->fetch_assoc()){
                    if($row["Reservation_status"] == 'Overdue'){
                        // update latest Late_fees for each overdue book
                        // ONLY if the book's Reservation_status is overdue
                        date_default_timezone_set('Asia/Kuala_Lumpur');
                        $today_date = new DateTime(date("Y-m-d")); // date() doesn't return a DateTime object, but a formatted string.
                        $return_date = new DateTime($row["Return_date"]);
                        $interval = $return_date->diff($today_date);
                        $days_overdue = $interval->days;
                        $latest_late_fees = $days_overdue * $late_fee_per_day;

                        // Late_fees will not exceed max amount
                        if($latest_late_fees > $max_late_fee){ 
                            $latest_late_fees = $max_late_fee;
                        }

                        $reservation_id = $row["Reservation_ID"]; // for query purpose
                        $temp_sql = "UPDATE RESERVATION SET Late_fees = '$latest_late_fees' WHERE Reservation_ID = '$reservation_id'";
                        $temp_rs = $conn->query($temp_sql);

                        // ..and prepare to freeze account
                        $hasOverdueBook = true;
                    }
                }
            }

            /* If got overdue book, freeze account */
            if($hasOverdueBook){
                $freeze_sql = "UPDATE USER SET User_account_status = 'Frozen' WHERE User_ID = '$user_ID';";
                $freeze_rs = $conn->query($freeze_sql);
                $_SESSION['useraccountstatus'] = strtoupper("Frozen");
            }

            /* Calculate user's total late fees */
            $total_sql = "SELECT * FROM RESERVATION WHERE User_ID = '$user_ID';";
            $total_rs = $conn->query($total_sql);

            $total_late_fees = 0;

            if ($total_rs->num_rows > 0) {
                while($row = $total_rs->fetch_assoc()) {
                    $total_late_fees += $row["Late_fees"];
                }
            }

            $_SESSION['userlatefees'] = $total_late_fees;
            
            $myArray[] = ['isSuccessful' => true,
                            'error_msg' => '',
                            'landing_page' => 'mainPageUser-BookView.php'];
        }
        else{
            $myArray[] = ['isSuccessful' => false,
                            'error_msg' => 'Wrong password!',
                            'landing_page' => ''];
        }
    }
    else{ // if not in USER try ADMIN
        $sql = "SELECT * FROM ADMIN WHERE Admin_ID = '$user_ID';";
        $rs = $conn->query($sql);
        if ($rs->num_rows > 0) { // if an account with this user ID exists in ADMIN
            $row = $rs->fetch_assoc();
            
            if($password == $row['Admin_password']){
                $_SESSION['userid'] = $row["Admin_ID"]; 
                $_SESSION['usercontact'] = $row["Admin_contact"];
                $_SESSION['userpassword'] = str_repeat("●", strlen($row["Admin_password"]));
                $_SESSION['type'] = 'Admin';
                
                $myArray[] = ['isSuccessful' => true,
                            'error_msg' => '',
                            'landing_page' => 'mainPageAdmin-addBook.php'];
            }
            else{
                $myArray[] = ['isSuccessful' => false,
                            'error_msg' => 'Wrong password!',
                            'landing_page' => ''];
            }
        }
        else{
            $myArray[] = ['isSuccessful' => false,
                            'error_msg' => 'This account does not exist!',
                            'landing_page' => ''];
        }
    }
    echo json_encode($myArray);
    $conn->close();
?>