<?php
    // start the session
    session_start();

    // redirect user to welcome page if logged in before
    if(isset($_SESSION["userid"])){
        if ($_SESSION["type"] == 'Admin')
            header("location: mainPageAdmin-addBook.php");
        else
            header("location: mainPageUser-BookView.php");
        exit;
    }
?>

<!DOCTYPE html>
<html>
    <head>
		<title>Login | Pro Library</title>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <link href="css/general.css" rel="stylesheet">
	</head>
	
	<body id="body" class="loginBody">
        <header id="loginHeader">
            <h1>Pro Library</h1>
        </header>

        <div class="container">
            <h1>Login</h1>
            <p>Sign in into your account to access the online library.</p>
            <hr class="hr">

            <!-- Modal -->
            <?php include 'php/viewModal-UI.php';?>

            <form action="" method="post" onsubmit="verifyLogin();return false">
                <fieldset>
                    <p class="form-group">
                        <label>IC number:</label>
                        <input type="number" id="ic_number" name="ic_number" class="form-control" required>
                    </p>
                    <p class="form-group">
                        <label>Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </p>
                    <p class="form-group">
                        <input class="btn btn-light" type="submit" name="submit" value="Login">
                    </p>
                </fieldset>
            </form>
		</div>
	</body>

    <script>
        <?php require_once 'php/automateUpdate.php'; ?>

        function verifyLogin()
        {
            var ic_number = $("#ic_number").val();
			var password = $("#password").val();
            
            $.ajax(
            {
                url: 'php/verifyLogin.php',
                data: {ic_number:ic_number, password:password},
                type: 'POST',
                async: false,
                cache: false,
                success: function(data)
                {
                    var array = JSON.parse(data)[0];

                    if (array.isSuccessful)
                        window.location.href = array.landing_page;
                    else
                    {
                        $("#titleModal").html("Error");
                        $("#content").html(array.error_msg);
                        $("#buttonModal").html(`<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`);
                        $("#modal").modal('show');
                    }
                },
                error: function()
                {
                    $("#titleModal").html("Error");
                    $("#content").html('<p>There is an error!<p>');
                    $("#buttonModal").html(`<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`);
                    $("#modal").modal('show');
                }
            });
        }
    </script>
</html>