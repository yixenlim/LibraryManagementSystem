<?php
    require_once "php/session.php";

    if($_SESSION["type"] != "Admin"){ // cannot access page if not ADMIN
        header("location: login.php");
        exit;
    }

    # echo rand_string(8); --> to call a random string, no repeated characters though
    function rand_string($length) { 
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }

    $generated_password = rand_string(8);
?>

<!DOCTYPE html>
<html>

    <head>
		<title>Register | Pro Library</title>
        <link href="css/general.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>
	
	<body id="body">
        <header>
            <h1>Pro Library</h1> 
		   
            <!-- Navigation bar -->
			<?php include 'php/viewNav-UI.php';?>
        </header>
        
        <div class="container">
            <h1>Register New User</h1>
            <p>Please fill in the details to create an account.</p>
            <hr class="hr">

            <!-- Modal -->
            <?php include 'php/viewModal-UI.php';?>

            <form method="POST" onsubmit="register();return false">
                <fieldset>
                    <p class="form-group">
                        <label>IC number:</label>
                        <input type="number" id="ic_number" name="ic_number" class="form-control" required>
                    </p>
                
                    <p class="form-group">
                        <label>Generated Password:</label>
                        <input type="text" id="password" name="password" value="<?php echo $generated_password;?>" class="form-control" required>
                    </p>
                
                    <p class="form-group">
                        <label>Contact Number:</label>
                        <input type="text" id="contact_number" name="contact_number" class="form-control" required>
                    </p>
                
                        <label>Email Address:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </p>

                    <p class="form-group">
                        <input class="btn btn-light" type="submit" name="submit" value="Register">
                    </p>
                </fieldset>
            </form>
        </div>
        <!-- <p>Already own an account? Log in <a href="login.php">here</a>.</p> -->
	</body>

    <script>
        <?php require_once 'php/automateUpdate.php'; ?>
        
        function register()
        {
            var ic_number = $("#ic_number").val();
			var password = $("#password").val();
			var contact_number = $("#contact_number").val();
            var email = $("#email").val();

            $.ajax(
            {
                url: 'php/registerUser.php',
                data: {ic_number:ic_number, password:password, contact_number:contact_number, email:email},
                type: 'POST',
                async: false,
                cache: false,
                timeout: 5000,
                success: function(data)
                {
                    $("#titleModal").html("Info");
                    $("#content").html(data);
                    $("#buttonModal").html(`<button type="button" class="btn btn-secondary" onclick="reload()" data-dismiss="modal">Close</button>`);
                    $("#modal").modal('show');
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

        function reload()
		{
			location.reload();
		}
    </script>
</html>