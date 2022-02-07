<?php 
    if ($_SESSION["type"] == 'Admin')
    {
        echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Management
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="mainPageAdmin-addBook.php">Add New Book</a>
                                <a class="dropdown-item" href="changeBookStatus.php">Manage Book And Reservation</a>
                                <a class="dropdown-item" href="register.php">Register New User</a>
                            </div>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="viewAccountAdmin.php">'.$_SESSION["userid"].'<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="php/logout.php" >Log out<span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                </div>
            </nav>';
    }
    else
    {
        echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="viewAccount.php">'.$_SESSION["userid"].'<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="php/logout.php" >Log out<span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                </div>
            </nav>';
    }
?>