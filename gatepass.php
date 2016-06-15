<?php
if (isset($_POST["submit"])) {

    // Check if reamrk has been entered
    if (!$_POST['remark']) {
        $errMessage = 'Please enter reamrk for your approval';
    }
// If there are no errors, send the email
    if (!$errMessage && !$errHuman) {
        if (mail($to, $subject, $body, $from)) {
            $result = '<div class="alert alert-success">Thank You! I will be in touch</div>';
        } else {
            $result = '<div class="alert alert-danger">Sorry there was an error sending your message. Please try again later</div>';
        }
    }
}
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
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item Code</th>
                                    <th>Description</th>
                                    <th>Reason For Removal of Item </th>
                                    <th>Status</th>
                                    <th>Remarks for Approval</th>
                                    <th>Approval</th>
                                </tr>
                                <tr>
                                    <td>ITM-0001</td>
                                    <td>Item 0001</td>
                                    <td>General Repair</td>
                                    <td><span class="label label-warning">Approved 1/2</span></td>
                                    <td><input id="remark" type="text" class="form-control" placeholder="Remark for Approval"/></td>
                                    <td><div class="btn-group"><input id="reject" type="submit" name="reject" value="Reject" class="btn btn-danger"></div></td>
                                </tr>
                            </thead>
                        </table>
                        <?php echo "<p class='text-danger'>$errMessage</p>"; ?>
                    </div>
                </div>
            </form>
        </div>


    </body>
</html>

