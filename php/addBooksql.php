<?php
    include_once "config.php";

    // get the post records
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $year = $_POST['year'];
    $isbn = $_POST['isbn'];
    $amount = $_POST['amount'];
    $info = $_POST['info'];

    // database insert SQL code
    for ($x = 0; $x < $amount; $x++)
    {
        $sql = "INSERT INTO `BOOK` (`Book_title`,`Book_author`,`Book_publisher`,`Book_published_year`,`Book_status`,`Book_ISBN`,`Book_info`) VALUES ('$title', '$author', '$publisher', '$year', 'Available', '$isbn', '$info')";
        $rs = mysqli_query($conn, $sql);
    }

    mysqli_close($conn);
?>