<?php
    require_once "config.php";
    require_once "session.php";

    $user_ID = $_SESSION['userid'];

    //update the database
    if ($_POST["form_type"] == 'Password')
    {
        $new_password = trim($_POST["new_password"]);
        if ($_SESSION['type'] == 'User')
            $sql = "UPDATE USER SET User_password = '$new_password' WHERE User_ID = '$user_ID';";
        else
            $sql = "UPDATE ADMIN SET Admin_password = '$new_password' WHERE Admin_ID = '$user_ID';";
    }
    else if ($_POST["form_type"] == 'Contact number')
    {
        $new_contact = trim($_POST["contact_number"]);
        if ($_SESSION['type'] == 'User')
            $sql = "UPDATE USER SET User_contact = '$new_contact' WHERE User_ID = '$user_ID';";
        else
            $sql = "UPDATE ADMIN SET Admin_contact = '$new_contact' WHERE Admin_ID = '$user_ID';";
    }
    else if ($_POST["form_type"] == 'Email')
    {
        $new_email = trim($_POST["email"]);
        if ($_SESSION['type'] == 'User')
            $sql = "UPDATE USER SET User_email = '$new_email' WHERE User_ID = '$user_ID';";
    }

    $rs = $conn->query($sql);

    //update the session
    if ($_SESSION['type'] == 'User')
    {
        $sql = "SELECT * FROM USER WHERE User_ID = '$user_ID';";
        $rs = $conn->query($sql);

        if ($rs->num_rows > 0) {
            $row = $rs->fetch_assoc();
            $_SESSION['useremail'] = $row["User_email"];
            $_SESSION['usercontact'] = $row["User_contact"];
            $_SESSION['userpassword'] = str_repeat("●", strlen($row["User_password"]));
        }
    }  
    else
    {
        $sql = "SELECT * FROM ADMIN WHERE Admin_ID = '$user_ID';";
        $rs = $conn->query($sql);

        if ($rs->num_rows > 0) {
            $row = $rs->fetch_assoc();
            $_SESSION['usercontact'] = $row["Admin_contact"];
            $_SESSION['userpassword'] = str_repeat("●", strlen($row["Admin_password"]));
        }
    }

    $conn->close();
?>