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
		<title>Manage Book And Reservation | Pro Library</title>
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
			<!-- Modal -->
			<?php include 'php/viewModal-UI.php';?>

			<h1>Manage Book And Reservation</h1>
            <p>Please enter the ID below.</p>
            <hr class="hr">
		
			<!-- input ID -->
			<form id="idForm" onsubmit="validate();return false">
				<fieldset>
					<p class="form-group">
						<label>ID type:</label>
						<select name="idType" id="idType" form="idForm" class="form-control" required>
							<option value="" disabled selected>Please select ID type</option>
							<option value="User_ID">User ID</option>
							<option value="Book_ID">Book ID</option>
						</select>
					</p>
					<p class="form-group">
						<label>ID:</label>
						<input name="idContent" id="idContent" class="form-control" type="text" required/>
					</p>
					<p class="form-group">
						<input class="btn btn-light" type="submit" value="Search">
					</p>
				</fieldset>
			</form>
		</div>

		<div class="container-fluid">
			<hr class="hr">
			<!-- show reserved books info and status -->
			<div id="bookInfoDiv">
			</div>
		</div>
	</body>
	
	<script>
		total_array = [];
		<?php require_once 'php/automateUpdate.php'; ?>

		function showBookInfoDiv(array,type)
		{
			total_array = array;

			//clear the div first
			$("#bookInfoDiv").empty();
			
			//set header
			if (type == 'Book')
			{
				$("#bookInfoDiv").html(`<table class="table table-bordered" id="table">
										<thead class="thead-dark">
										<tr>
											<th class="col-sm-0.7">Book ID</th>
											<th class="col-sm-3">Book Title</th>
											<th class="col-sm-2">Author</th>
											<th class="col-sm-2">Publisher</th>
											<th class="col-sm-0.7">Published Year</th>
											<th class="col-sm-1">ISBN</th>
											<th class="col-sm-2">Status</th>
										</tr>
										</thead>
										<tbody></tbody></table>`) ;

				array.forEach
				(
					function(item)
					{
						var html = `<tr>
									<td>`+item.Book_ID+`</td>
									<td>`+item.Book_title+`</td>
									<td>`+item.Book_author+`</td>
									<td>`+item.Book_publisher+`</td>
									<td>`+item.Book_published_year+`</td>
									<td>`+item.Book_ISBN+`</td>
									<td>`;

						if (item.Book_status == 'Borrowed' || item.Book_status == 'Reserved')
						{
							html += `		<select id="`+item.Book_ID+`" disabled class="form-control">
												<option value="Available">Available</option>
												<option value="Unavailable">Unavailable</option>
												<option value="`+item.Book_status+`" selected>`+item.Book_status+`</option>
											</select>
										</td>
									</tr>`;
						}
						else
						{
							html += `		<select id="`+item.Book_ID+`" class="form-control">
												<option value="Available">Available</option>
												<option value="Unavailable">Unavailable</option>
											</select>
										</td>
									</tr>`;
						}

						$("#table tbody").append(html);

						//set the status
						if (item.Book_status != 'Borrowed' && item.Book_status != 'Reserved')
						{
							$("#"+item.Book_ID).val(item.Book_status).attr("selected", "selected");
							$("#bookInfoDiv").append(`<p><button type="button" class="btn btn-light" onclick="confirm('Book')">Update</button></p>`);
						}
						
					}
				);
			}
			else
			{
				//header
				$("#bookInfoDiv").html(`<table class="table table-bordered" id="table">
										<thead class="thead-dark">
										<tr>
											<th class="col-sm-0.7">Book ID</th>
											<th class="col-sm-2">Book Title</th>
											<th class="col-sm-1">Author</th>
											<th class="col-sm-2">Publisher</th>
											<th class="col-sm-0.7">Published Year</th>
											<th class="col-sm-1">ISBN</th>
											<th class="col-sm-1">Reservation_date</th>
											<th class="col-sm-1">Return_date</th>
											<th class="col-sm-1">Late Fees</th>
											<th class="col-sm-2">Status</th>
										</tr>
										</thead>
										<tbody></tbody></table>`) ;

				array.forEach
				(
					function(book_item)
					{
						book_item.forEach
						(
							function(item)
							{
								var html = `<tr>
												<td>`+item.Book_ID+`</td>
												<td>`+item.Book_title+`</td>
												<td>`+item.Book_author+`</td>
												<td>`+item.Book_publisher+`</td>
												<td>`+item.Book_published_year+`</td>
												<td>`+item.Book_ISBN+`</td>
												<td>`+item.Reservation_date+`</td>
												<td>`+item.Return_date+`</td>
												<td>`+item.Late_fees+`</td>
												<td>
													<select id="`+item.Book_ID+`" class="form-control">
														<option value="Available">Available</option>
														<option value="Unavailable">Unavailable</option>
														<option value="Reserved">Reserved</option>
														<option value="Borrowed">Borrowed</option>
														<option value="Overdue" disabled>Overdue</option>
													</select>
												</td>
											</tr>`;

								$("#table tbody").append(html);
								
								//set the status
								if (item.Reservation_status != 'Overdue')
									$("#"+item.Book_ID).val(item.Book_status).attr("selected", "selected");
								else
									$("#"+item.Book_ID).val(item.Reservation_status).attr("selected", "selected");
								
							}
						);
					}
				);

				//set the update button
				$("#bookInfoDiv").append(`<p><button type="button" class="btn btn-light" onclick="confirm('Reservation')">Update</button></p>`);
			}
		}
	
		function validate()
		{
			var idType = $("#idType").val();
			var idContent = $("#idContent").val();

			if (idType == "Book_ID")
			{
				$.ajax(
				{
					url: 'php/getFromDatabase.php',
					data: {idType:idType, idContent:idContent},
					async: false,
					cache: false,
					timeout: 5000,
					success: function(data)
					{
						var array = JSON.parse(data);
						
						if (array.length == 0)
						{
							$("#titleModal").html("Warning");
							$("#content").html('<p>Book ID not exist! Please recheck the ID!<p>');
							$("#buttonModal").html(`<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`);
							$("#modal").modal('show');

							$("#bookInfoDiv").empty();
						}
						else
							showBookInfoDiv(array,'Book');
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
			else //reservation
			{
				// find the reservation of this user ID
				$.ajax(
				{
					url: 'php/getFromDatabase.php',
					data: {idType:idType, idContent:idContent},
					async: false,
					cache: false,
					timeout: 5000,
					success: function(res_data) {
						var res_array = JSON.parse(res_data);
						
						if (res_array.length == 0)
						{
							$("#titleModal").html("Warning");
							$("#content").html('<p>No ongoing reservation of this User ID!<p>');
							$("#buttonModal").html(`<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`);
							$("#modal").modal('show');

							$("#bookInfoDiv").empty();
						}
						else
						{
							var total_book_array = [];

							// loop to find the book info
							res_array.forEach(
								function(res_item)
								{
									$.ajax(
									{
										url: 'php/getFromDatabase.php',
										data: {idType:"Book_ID", idContent:parseInt(res_item.Book_ID)},
										async: false,
										cache: false,
										timeout: 5000,
										success: function(book_data) {
											var book_array = JSON.parse(book_data);
											
											book_array[0]['Reservation_ID'] = res_item.Reservation_ID;
											book_array[0]['Reservation_date'] = res_item.Reservation_date;
											book_array[0]['Return_date'] = res_item.Return_date;
											book_array[0]['Reservation_status'] = res_item.Reservation_status;
											book_array[0]['Late_fees'] = res_item.Late_fees;
											book_array[0]['User_ID'] = res_item.User_ID;
											
											total_book_array.push(book_array);
										},
										error: function()
										{
											$("#title").html("Error");
											$("#content").html('<p>There is an error!<p>');
											$("#buttonModal").html(`<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>`);
											$("#modal").modal('show');
										}
									});
								}
							);
							
							showBookInfoDiv(total_book_array,'Reservation');
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

		function confirm(type)
		{
			$("#titleModal").html("Confirmation");
			$("#content").html('<p>Are you sure to update the book status?</p>');
			$("#buttonModal").html(`<button type="button" class="btn greenButton"  data-dismiss="modal" onclick="update('`+type+`')">Yes</button>
									<button type="button" class="btn btn-light" data-dismiss="modal">No</button>`);
			$("#modal").modal('show');
		}

		function update(type)
		{
			var resID;
			if (type == 'Book')
			{
				resID = "0";

				var total_array2 = []
				total_array2.push(total_array);
				total_array = total_array2;
			}
			else//reservation
			{
				var overdue = false;
				var userID;
			}
				
			total_array.forEach
			(
				function(book_item)
				{
					book_item.forEach
					(
						function(item)
						{
							if (type == 'Reservation')
							{
								resID = item.Reservation_ID;
								userID = item.User_ID;
								if ($("#"+item.Book_ID).val() == null)//overdue but no changes made
								{
									overdue = true;
									return;
								}
							}
							
							$.ajax(
							{
								url: 'php/changeBookStatussql.php',
								data: {bookID:item.Book_ID, status:$("#"+item.Book_ID).val(), resID:resID},
								async: false,
								cache: false,
								timeout: 5000,
								success: function(data)
								{
									$("#titleModal").html("Info");
									$("#content").html('<p>Book status updated!<p>');
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

							if ((type == 'Reservation') && (item.Reservation_status == 'Overdue') && ($("#"+item.Book_ID).val() != null))//overdue book is not overdue anymore
							{
								$.ajax(
								{
									url: 'php/changeLateFees.php',
									data: {resID:resID, fees:0},
									async: false,
									cache: false,
									timeout: 5000,
									success: function(data)
									{console.log('Updated late fees for reservation no: '+resID);},
									error: function()
									{console.log('Error updating late fees for'+resID);}
								});
							}
							
						}
					)
				}
			);
			
			//unfreeze user account
			if (type == 'Reservation' && !overdue)
			{
				$.ajax
				({
					url: 'php/changeUserAccountStatus.php',
					data: {userID:userID,status:'Active'},
					async: false,
					cache: false,
					timeout: 5000,
					success: function(data)
					{console.log('Unfreeze user account status success.');},
					error: function()
					{console.log('Unfreeze user account status error.');}
				});
			}
		}

		function reload()
		{
			location.reload();
		}
	</script>
</html>