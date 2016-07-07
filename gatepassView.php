<?php
if (!session_start()) {
    session_start();
}
include 'db_connection/db_connector.php';
$conn = local_connect();
$logged = false;
if (isset($_SESSION['logged']))
    $logged = $_SESSION['logged'];
if (!$logged) {
    if ((isset($_POST["email"]) && isset($_POST["password"]))) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        //echo "email - ".$email."\nPass-".$password;
        $query = "SELECT * FROM tblAssetGatePassAuthPerson WHERE email = '$email' AND password = '$password'";
        //echo $query;
        $stmt = sqlsrv_query($conn, $query);
        if (!sqlsrv_has_rows($stmt)) {
            header('Location:gatepassLogin.php?login=0');
        }else{
        $_SESSION['logged'] = true;

        $_SESSION['email'] = $_POST["email"];
    }
    }
}
$alerttype = 'success';
$alertMsg = '';
$alertTitle = '';
$email = $_SESSION['email'];
$query = 'SELECT * FROM tblAssetGatePass WHERE GatepassID IN ('
        . ' SELECT GatepassID FROM tblAssetGatePass WHERE Status<3 EXCEPT SELECT GatepassID FROM tblAssetGatePassApprove WHERE email = ?) ';
$param = array('$email');
$stmt = sqlsrv_query($conn, $query, $param);
if ($stmt === false) {
    echo "Error in query preparation/execution\n";
}
?><!DOCTYPE html>
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
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">Sapro Asset Management - Gatepass Approval </a>
                    </div>
                    <ul class="nav navbar-nav navbar-right">

                        <li><a href="gatepassLogout.php"><?php echo $email; ?> <span class="glyphicon glyphicon-log-in"></span>  Signout </a></li>
                    </ul>
                </div>
            </nav>

            <?php
            if (!sqlsrv_has_rows($stmt)) {
                $alertTitle = "Done! ";
                $alertMsg = "No Gatepasses to Approve.";
            }
            while ($obj = sqlsrv_fetch_object($stmt)) {
                $gatepassID = $obj->GatePassID;
                $authorizedTo = $obj->authorizeTo;
                $queryItems = 'SELECT a.ItemCode,b.Description,a.RequestRemark 
                        FROM [tblAssetGatePassItem] a LEFT JOIN [tblItemMaster] b ON a.ItemCode = b.ItemCode
                        WHERE a.GatePassID = ?';
                $param = array($gatepassID);
                $stmt2 = sqlsrv_query($conn, $queryItems, $param);

                if ($stmt2 === false) {
                    $alerttype = 'danger';
                    $alertMsg = "Error in Gatepass query preparation/execution.\n";
                }
                ?>

                <div class="well"> 
                    <form  method="post" action="gatepassApprove.php?ID=<?php echo $gatepassID; ?>">
                        <div class="row">
                            <div class="col-sm-3"><h4>Gatepass ID: <?php echo $gatepassID; ?></h4></div>
                            <div class="col-sm-6"><h4>Authorized to: <?php echo $authorizedTo; ?></h4></div>
                            <div class="col-sm-3"><button class="btn btn-primary text-right" style="float: right;" type="button"
                                                          data-toggle="collapse" data-target="#<?php echo $gatepassID; ?>"  accesskey="">
                                    View Items
                                </button></div>
                        </div>
                        <div class="collapse" id="<?php echo $gatepassID; ?>">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Description</th>
                                        <th>Reason For Removal of Item </th>
                                    </tr><?php
                                    while ($obj2 = sqlsrv_fetch_object($stmt2)) {
                                        $items['itemCode'] = $obj2->ItemCode;
                                        $items['description'] = $obj2->Description;
                                        $items['remark'] = $obj2->RequestRemark;
                                        ?>
                                        <tr>
                                            <td><?php echo $items['itemCode']; ?></td>
                                            <td><?php echo $items['description']; ?></td>
                                            <td><?php echo $items['remark']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </thead>
                            </table>
                            <div class="col-sm-10"></div><div class="btn-group"><button id="approve" type="submit" name="approve" value="1" class="btn btn-success">Approve</button><button  id="reject" type="submit" name="approve" value="0" class="btn btn-danger">Reject</button></div>


                        </div>
                    </form>
                </div>
            <?php } ?>
            <div><div class="alert alert-<?php echo $alerttype; ?>">
                    <strong><?php echo $alertTitle; ?></strong><?php echo $alertMsg; ?>
                </div></div>
        </div>


    </body>
</html>

