<?php
    // start the session
    session_start();

    // destroy the session
    if(session_destroy()){
        header("location: ../login.php");
        exit;
    }
?>
