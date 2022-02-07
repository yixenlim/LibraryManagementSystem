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
		<title>Welcome | Pro Library</title>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <link href="css/general.css" rel="stylesheet">
        <link href="css/mainPageUser-BookView.css" rel="stylesheet">
    </head>
	
	<body id="body">
        <header>
            <h1>Pro Library</h1> 
		   
            <!-- Navigation bar -->
			<?php include 'php/viewNav-UI.php';?>
        </header>

        <div class="container">
            <h1>Welcome</h1>
            <p>You can borrow all the books we have.</p>
            <hr class="hr">

            <!-- Modal -->
            <?php include 'php/viewModal-UI.php';?>

            <div class="modal fade" id="borrowBookModal" tabindex="-1" role="dialog" aria-labelledby="borrowBookModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="borrowBookModalLabel">Borrow Book Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="borrowBookForm" action="">
                                <div class="form-group">
                                    <label for="bookIsbn" class="col-form-label">Book_ISBN:</label>
                                    <input class="form-control" id="bookIsbn" name="Book_ISBN" readonly></input>
                                </div>

                                <div class="form-group">
                                    <label for="bookInfo" class="col-form-label">Book_info:</label>
                                    <textarea class="form-control" id="bookInfo" readonly></textarea>
                                </div>

                                <div class="form-group date" >
                                    <label for="reserveDate" class="col-form-label">Reservation Date:</label>
                                    <input type="date" class="form-control" id="reserveDate" name="Reservation_date" readonly>
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>

                                <div class="form-group date" >
                                    <label for="returnDate" class="col-form-label">Return Date:</label>
                                    <input type="date" class="form-control" id="returnDate" name="Return_date" readonly>
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>

                                <div>
                                    <strong>Please retrieve the book from the library within 3(three) days of reservation date.</strong>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn greenButton" onclick="borrowBookSubmit()">Borrow</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <table id="booksTable" class="table table-bordered" >
                <thead class="thead-dark">
                    <tr>
                    <th>Book Title</th>
                    <th>Book Author</th>
                    <th>Book Publisher</th>
                    <th>Book Published Year</th>
                    <th>Book ISBN</th>
                    <th>Book Status</th>
                    <th>Book Info</th>
                    <th>Number Available</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
	</body>

    <script>
        <?php require_once 'php/automateUpdate.php'; ?>

        $(document).ready(function () {
            loadBooks();
            setTimeout(checkNumAvailable,50);
            checkUserStatus();
            checkActiveBooks();
        });
        
        function checkActiveBooks() {
            
            $.ajax({
                url: "php/GetActiveBooks.php",
                type: "GET",
                success: function(response) {
                    if(response) {
                        response = JSON.parse(response);
                        
                        if (response.length >= 3) {
                            var buttons = document.getElementsByClassName("borrowButtons");
                            for(var i = 0; i < buttons.length; i++) {
                                buttons[i].disabled = true;
                            }
                        }
                    }
                }
            });
        }

        function checkUserStatus() {
            $.ajax({
                url: "php/GetUser.php",
                type: "GET",
                success: function(response) {
                    if(response) {
                        response = JSON.parse(response);
                        let user_status = response[0].User_account_status;
                        //console.log(user_status);
                        if(user_status.toLowerCase() == 'frozen') {
                            var buttons = document.getElementsByClassName("borrowButtons");
                            for(var i = 0; i < buttons.length; i++) {
                                buttons[i].disabled = true;
                            }
                        }
                    }
                }
            });

        }

        function borrowBookSubmit() {
            let formdata = $('#borrowBookForm').serializeArray();

            $.ajax({
                url: "php/BorrowBook.php",
                type: "POST",
                data: formdata,

                success: function(response) {
                    if(response) {
                        $("#titleModal").html("Info");
                        $("#content").html('Successfully Borrowed Book.');
                        $("#buttonModal").html(`<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`);
                        $("#modal").modal('show');
                    }
                    else {
                        $("#titleModal").html("Warning");
                        $("#content").html('Failed to Borrow Book.');
                        $("#buttonModal").html(`<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`);
                        $("#modal").modal('show');
                    }

                    $("#borrowBookModal").modal('hide');
                    $('#booksTable').DataTable().ajax.reload();
                    checkActiveBooks();
                    checkUserStatus();
                    setTimeout(checkNumAvailable,50);
                }
            });
        }

        function borrowBookFormClicked(element){ 
            var table = $('#booksTable').DataTable();
            
            $("#borrowBookModal").modal('toggle');
            document.getElementById("bookIsbn").value = table.row($(element).parents('tr')).data()['Book_ISBN'];
            document.getElementById("bookInfo").value = table.row($(element).parents('tr')).data()['Book_info'];

            Date.prototype.addDays = function(days) {
                var date = new Date(this.valueOf());
                date.setDate(date.getDate() + days);
                return date;
            }

            let today = new Date();
            today = new Date(today.getTime() - (today.getTimezoneOffset() * 60000));
            document.getElementById("reserveDate").value = today.toISOString().split('T')[0];
            document.getElementById("returnDate").value = today.addDays(30).toISOString().split('T')[0];
                        
        }

        function checkNumAvailable() {
            var buttons = document.getElementsByClassName("borrowButtons");
            
            for(var i = 0; i < buttons.length; i++) {
                let table = $('#booksTable').DataTable();
                let num_available = table.row($(buttons[i]).parents('tr')).data()['Num_Available'];
                
                if (num_available < 1) {
                    buttons[i].disabled = true;
                }
            }
        }

        function loadBooks() {
            $('#booksTable').DataTable({
                "processing": true,
                "ajax": {
                    "url": "php/GetBooks.php",
                    "dataSrc": ""
                }, 
                "columns":[
                    {"data":"Book_title"},
                    {"data":"Book_author"},
                    {"data":"Book_publisher"},
                    {"data":"Book_published_year"},
                    {"data":"Book_ISBN"},
                    {"data":"Book_status"}, 
                    {"data":"Book_info"}, 
                    {"data":"Num_Available"}, 
                    {"defaultContent": "<button class='borrowButtons btn greenButton' onclick='borrowBookFormClicked(this)'>Borrow</button>"}
                ], 
                "columnDefs": [
                    {
                        "targets": [6],
                        "visible": false,
                        "searchable": false
                    }, 
                    {
                        "targets": [7],
                        "visible": false,
                        "searchable": false
                    }, 
                    {
                        "targets": [5],
                        "visible": false,
                        "searchable": false
                    }
                ]
            });
        }
    </script>
</html>