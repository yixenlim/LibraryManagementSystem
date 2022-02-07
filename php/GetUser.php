<?php
    include_once "config.php";
    include_once "session.php";

    $user_ID = $_SESSION['userid'];
    $sql = "SELECT * FROM user WHERE User_ID = $user_ID";

    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);

    if($resultCheck > 0) {
        $row = $result->fetch_row();
        $User_ID = $row[0];
        $User_email = $row[1];
        $User_contact = $row[2];
        $User_password = $row[3];
        $User_account_status = $row[4];
    
        $user[] = array("User_ID" => $User_ID,
                        "User_email" => $User_email,
                        "User_contact" => $User_contact,
                        "User_password" => $User_password, 
                        "User_account_status" => $User_account_status
                        );

        echo json_encode($user);
    }
    else {
        echo FALSE;
    }

    mysqli_close($conn);

?>