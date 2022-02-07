<?php
    include_once "config.php";

    $sql = "SELECT *, COUNT(case when Book_status = 'Available' then 1 end) as Num_Available FROM book GROUP BY Book_ISBN";

    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);

    $books = array();

    if($resultCheck > 0) {
        while($row = $result->fetch_row()){
            $Book_ID = $row[0];
            $Book_title = $row[1];
            $Book_author = $row[2];
            $Book_publisher = $row[3];
            $Book_published_year = $row[4];
            $Book_ISBN = $row[5];
            $Book_status = $row[6];
            $Book_info = $row[7];
            $Num_Available = $row[8];
        
            $books[] = array("Book_ID" => $Book_ID,
                            "Book_title" => $Book_title,
                            "Book_author" => $Book_author,
                            "Book_publisher" => $Book_publisher, 
                            "Book_published_year" => $Book_published_year, 
                            "Book_ISBN" => $Book_ISBN, 
                            "Book_status" => $Book_status, 
                            "Book_info" => $Book_info, 
                            "Num_Available" => $Num_Available
                            );
        }
    }

   echo json_encode($books);

   mysqli_close($conn);
?>