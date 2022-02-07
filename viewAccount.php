<?php
    require_once "php/session.php";

    if($_SESSION["type"] != "User"){ // cannot access page if not USER
        header("location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<title>View Account | Pro Library</title>
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
            <h1>Account Information</h1>
            <?php 
                if ($_SESSION["type"] == 'Admin')
                    echo '<p>Please view your account information below. Back to <a href="mainPageAdmin-addBook.php">home page</a>.</p>';
                else
                    echo '<p>Please view your account information below. Back to <a href="mainPageUser-BookView.php">home page</a>.</p>';
            ?>
            <hr class="hr">

            <!-- Modal -->
            <?php include 'php/viewModal-UI.php';?>

            <!-- Account info -->
            <div id="account">
                <fieldset>
                    <form action="" method="post">
                        <p class="form-group">
                            <label>
                                <b>Account Status: </b>
                                <?php 
                                    if ($_SESSION['useraccountstatus'] == 'ACTIVE')
                                        echo $_SESSION['useraccountstatus'];
                                    else
                                        echo '<text class="overdue">'.$_SESSION['useraccountstatus'].'</text>';
                                ?>
                            </label>
                        </p>
                        <p class="form-group">
                            <label><b>IC number: </b><?php echo $_SESSION['userid']?></label>
                        </p>
                        <p class="form-group">
                            <label>
                                <b>Total Late Fees: </b>
                                <?php 
                                    if ($_SESSION['userlatefees'] == 0)
                                        echo $_SESSION['userlatefees'];
                                    else
                                        echo '<text class="overdue">RM' .$_SESSION['userlatefees'].'</text>';
                                ?>
                            </label>
                        </p>
                    </form>
                    <hr class="hr">

                    <form action="" method="post" onsubmit="changeDetails('Password');return false">
                        <p class="form-group">
                            <label><b>Password: </b><?php echo $_SESSION['userpassword']?></label>
                        </p>
                        <p class="form-group">
                            <input type="password" id="new_password" name="new_password" placeholder="New Password" class="form-control" required>
                        </p>
                        <p class="form-group">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="form-control" required>
                        </p>
                        <p class="form-group">
                            <input class="btn btn-light" type="submit" name="chg_password" value="Change">
                        </p>
                    </form>
                    <hr class="hr">

                    <form action="" method="post" onsubmit="changeDetails('Contact Number');return false">
                        <p class="form-group">
                            <label><b>Contact Number: </b><?php echo $_SESSION['usercontact']?></label>
                        </p>
                        <p class="form-group">
                            <input type="number" id="contact_number" name="contact_number" placeholder="New Contact Number" class="form-control" required>
                        </p>
                        <p class="form-group">
                            <input class="btn btn-light" type="submit" name="chg_contact" value="Change">
                        </p>
                    </form>
                    <hr class="hr">

                    <form action="" method="post" onsubmit="changeDetails('Email');return false">
                        <p class="form-group">
                            <label><b>Email Address: </b><?php echo $_SESSION['useremail']?></label>
                        </p>
                        <p class="form-group">
                            <input type="email" id="email" name="email" placeholder="New Email Address" class="form-control" required>
                        </p>
                        <p class="form-group">
                            <input class="btn btn-light" type="submit" name="chg_email" value="Change">   
                        </p>
                    </form>  
                </fieldset>
            </div>

            <!-- Borrowing info -->
            <div id="reservation"></div>
        </div>
	</body>
	
    <script>
        <?php require_once 'php/automateUpdate.php'; ?>
        
        $(document).ready(
            function() 
            {
                if ('<?php echo $_SESSION["type"];?>' == 'User')
                {
                    $.ajax(
                    {
                        url: 'php/getFromDatabase.php',
                        data: {idType:'User_ID', idContent:'<?php echo $_SESSION["userid"];?>'},
                        type: 'GET',
                        async: false,
					    cache: false,
                        success: function(res_data) 
                        {
                            var res_array = JSON.parse(res_data);
                            var total_book_array = [];
                            
                            if (res_array.length > 0)
                            {
                                $("#reservation").html('<hr class="hr"> <h1>Reservation Details</h1> <br/> ');

                                //get book information from each reservation
                                res_array.forEach(
                                    function(res_item)
                                    {
                                        $.ajax(
                                        {
                                            url: 'php/getFromDatabase.php',
                                            data: {idType:"Book_ID", idContent:parseInt(res_item.Book_ID)},
                                            type: 'GET',
                                            async: false,
					                        cache: false,
                                            success: function(book_data) 
                                            {
                                                var book_array = JSON.parse(book_data);
                                                total_book_array.push(book_array[0]);
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
                                );

                                //add header of the table
                                $("#reservation").append(`<table class="table table-bordered" id="table">
                                                            <thead class="thead-dark">
                                                            <tr>
                                                                <th class="col-sm-1">Book ID</th>
                                                                <th class="col-sm-3">Book Title</th>
                                                                <th class="col-sm-1">Reservation_date</th>
                                                                <th class="col-sm-1">Return_date</th>
                                                                <th class="col-sm-1">Late Fees</th>
                                                                <th class="col-sm-2">Status</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody></tbody></table>`);
                                
                                for (var i=0; i<total_book_array.length; i++)
                                {
                                    var html = `<tr>
                                                    <td>`+total_book_array[i].Book_ID+`</td>
                                                    <td>`+total_book_array[i].Book_title+`</td>
                                                    <td>`+res_array[i].Reservation_date+`</td>
                                                    <td>`+res_array[i].Return_date+`</td>`;

                                    if (res_array[i].Late_fees != 0)
                                    {
                                        html += `   <td class="overdue">`+res_array[i].Late_fees+`</td>`;
                                    }
                                    else
                                    {
                                        html += `   <td>`+res_array[i].Late_fees+`</td>`;
                                    }

                                    if (res_array[i].Reservation_status == 'Overdue')
                                    {
                                        html += `   <td class="overdue">`+res_array[i].Reservation_status+`</td>
                                                </tr>`;
                                    }
                                    else
                                    {
                                        html += `   <td>`+total_book_array[i].Book_status+`</td>
                                                </tr>`;
                                    }

                                    $("#table tbody").append(html);	    
                                }               
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
            }
        );

        function changeDetails(form_type)
        {
            var new_password = $("#new_password").val();
			var confirm_password = $("#confirm_password").val();
            var contact_number = $("#contact_number").val();
			var email = $("#email").val();

            if (form_type == 'Password' && new_password != confirm_password)
            {
                $("#titleModal").html("Error");
                $("#content").html("The passwords do not match!");
                $("#buttonModal").html(`<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`);
                $("#modal").modal('show');
            }
            else
            {
                $.ajax(
                {
                    url: 'php/changeAccountDetails.php',
                    data: {form_type:form_type, new_password:new_password,contact_number:contact_number,email:email},
                    type: 'POST',
                    async: false,
                    cache: false,
                    success: function(data)
                    {
                        $("#titleModal").html("Info");
                        $("#content").html(form_type+" changed successfully!");
                        $("#buttonModal").html(`<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="reload()">Close</button>`);
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
        }

        function reload()
		{
			location.reload();
		}
    </script>
</html>