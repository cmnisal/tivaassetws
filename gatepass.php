<?php


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sapro Asset Management - Gatepass Approval</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
    <body>

        <div class="container">
            <form class="form-horizontal" role="form" method="post" action="gatepass.php">
                <nav class="navbar navbar-inverse">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="#">Sapro Asset Management - Gatepass Approval</a>
                        </div>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Signout</a></li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid"> 
                    <div class="col-sm-3"><h4>Gatepass ID:<?php echo 123;?></h4></div>
                    <div class="col-sm-5"><h4>Authorized to:</h4></div>
                    <div class="col-sm-3"><button class="btn btn-primary text-right" style="float: right;" type="button"
                                    data-toggle="collapse" data-target="#view"
                                    aria-expanded="false" data-width="140" data-height="40"
                                    aria-controls="#">
                                View Items
                        </button></div></div>
                    <div class="collapse" id="view">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item Code</th>
                                    <th>Description</th>
                                    <th>Reason For Removal of Item </th>
                                </tr>
                                <tr>
                                    <td>ITM-0001</td>
                                    <td>Item 0001</td>
                                    <td>General Repair</td>
                                </tr>
                            </thead>
                        </table>
                        <div class="col-sm-10"></div><div class="btn-group"><button id="approve" type="submit" name="approve" value="approve" class="btn btn-success">Approve</button><button  id="reject" type="submit" name="reject" value="Reject" class="btn btn-danger">Reject</button></div>
                        <?php echo "<p class='text-danger'>$errMessage</p>"; ?>
                    </div>
                </div>
            </form>
        </div>


    </body>
</html>

