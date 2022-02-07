<?php 
    require_once "php/session.php";

    if($_SESSION["type"] != "Admin"){ // cannot access page if not ADMIN
        header("location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Add book | Pro Library</title>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <link href="css/general.css" rel="stylesheet">
	</head>
	
	<body id="body">
        <header>
            <h1>Pro Library</h1> 
		   
            <!-- Navigation bar -->
			<?php include 'php/viewNav-UI.php';?>
        </header>

        <div class="container">
            <h1>Add book(s)</h1>
            <p>Please enter the book information below.</p>
            <hr class="hr">

            <!-- Modal -->
            <?php include 'php/viewModal-UI.php';?>

            <!-- book info -->
            <form method="POST" onsubmit="addBook();return false">
                <fieldset>
                    <p class="form-group">
                        <label>Title:</label>
                        <input name="title" id='title' type="text" class="form-control" required/>
                    </p>
                    <p class="form-group">
                        <label>Author:</label>
                        <input name="author" id='author' type="text" class="form-control" required/>
                    </p>
                    <p class="form-group">
                        <label>Publisher:</label>
                        <input name="publisher" id='publisher' type="text" class="form-control" required/>
                    </p>
                    <p class="form-group">
                        <label>Published Year:</label>
                        <input name="year" id='year' type="number" class="form-control" required/>
                    </p>
                    <p class="form-group">
                        <label>ISBN:</label>
                        <input name="isbn" id='isbn' type="number" class="form-control" required/>
                    </p>
                    <p class="form-group">
                        <label>Information:</label>
                        <textarea name="info" id='info' cols="40" rows="5" class="form-control" required></textarea>
                    </p>
                    <p class="form-group">
                        <label>Amount:</label>
                        <input name="amount" id='amount' type="number" class="form-control" required/>
                    </p>
                    <p class="form-group">
                        <input class="btn btn-light" type="submit" value="Add to database"/>
                    </p>
                </fieldset>
            </form>
        </div>
	</body>
	
    <script>
        <?php require_once 'php/automateUpdate.php'; ?>
        
        function addBook()
        {
			var title = $("#title").val();
			var author = $("#author").val();
            var publisher = $("#publisher").val();
			var year = $("#year").val();
            var isbn = $("#isbn").val();
			var info = $("#info").val();
            var amount = $("#amount").val();

            $.ajax(
            {
                url: 'php/addBooksql.php',
                data: {title:title, author:author, publisher:publisher, year:year, isbn:isbn, info:info, amount:amount},
                type: 'POST',
                async: false,
                cache: false,
                timeout: 5000,
                success: function(data)
                {
                    $("#titleModal").html("Info");
                    $("#content").html('Book(s) added!');
                    $("#buttonModal").html(`<button type="button" class="btn btn-secondary" onclick="reload()" data-dismiss="modal">Close</button>`);
                    $("#modal").modal('show');
                },
                error: function()
                {
                    $("#titleModal").html("Error");
                    $("#content").html('<p>There is an error adding to the database!<p>');
                    $("#buttonModal").html(`<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`);
                    $("#modal").modal('show');
                }
            });
        }

        function reload()
		{
			location.reload();
		}
    </script>
</html>