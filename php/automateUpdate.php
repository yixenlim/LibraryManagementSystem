<?php 
    echo "constantUpdate();

    //temporarily put here only
    function constantUpdate()
    {
        var now_date = new Date();
        
        $.ajax
        ({
            url: 'php/getAllReservations.php',
            success: function(data)
            {
                var array = JSON.parse(data);
                
                array.forEach
                (
                    function (item)
                    {
                        if (item.Reservation_status == 'Waiting')
                        {
                            //calculate difference in days
                            var date = item.Reservation_date;
                            var date_array = date.split('-');
                            var res_date = new Date(date_array[0],date_array[1]-1,date_array[2]);
                            var dif_day = Math.floor((now_date.getTime() - res_date.getTime())/ (1000 * 3600 * 24));

                            if (dif_day >= 3)
                            {
                                $.ajax
                                ({
                                    url: 'php/changeReservationStatus.php',
                                    data: {resID:item.Reservation_ID, bookID:item.Book_ID},
                                    success: function(data)
                                    {
                                        console.log('Automate updating finished.');
                                    },
                                    error: function()
                                    {
                                        console.log('PHP error in automate updating.');
                                    }
                                });
                            }
                        }
                    }
                );
            },
            error: function()
            {
                $('#titleModal').html('Error');
                $('#content').html('<p>There is an error!<p>');
                $('#buttonModal').html(`<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>`);
                $('#modal').modal('show');
            }
        });

        console.log('Automate checking finished.');

        // setTimeout(constantUpdate, 3600000);//exec myself every hour (1000ms = 1s)
        setTimeout(constantUpdate, 10000);//exec myself every 10 secs (1000ms = 1s)
    }";

?>