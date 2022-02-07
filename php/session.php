<?php
    // start the session
    session_start();

    /* 
    The session holds these info below:

        User:
        $_SESSION['userid']
        $_SESSION['useremail']
        $_SESSION['usercontact']
        $_SESSION['userpassword']
        $_SESSION['useraccountstatus']
        $_SESSION['userlatefees']
        $_SESSION['type'] --> User

        Admin:
        $_SESSION['userid']
        $_SESSION['usercontact']
        $_SESSION['userpassword']
        $_SESSION['type'] --> Admin
        
    These variables will be set once login.php is run once.
    These variables are cleared once logged out and the user has to login again.

    NOTE: Session is lost when browser is closed. Can be changed but not necessary at the moment.
    https://stackoverflow.com/questions/34595871/setting-secure-session-cookies-in-php
    https://itqna.net/questions/4355/how-do-i-keep-session-after-browser-closing
    */

    // redirect user to login page if not signed in
    if(!isset($_SESSION["userid"])){
        header("location: login.php");
        exit;
    }
?>