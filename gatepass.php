<?php
if (!session_start()) {
    session_start();
}
$_SESSION['email'] = "nisal@ticti.com";
$email = $_SESSION['email'];
include 'db_connection/db_connector.php';
$conn = local_connect();

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

                            <li><a href="#"><?php echo $email; ?> <span class="glyphicon glyphicon-log-in"></span>  Signout </a></li>
                        </ul>
                    </div>
                </nav>

                <?php
                while ($obj = sqlsrv_fetch_object($stmt)) {
                    $gatepassID = $obj->GatePassID;
                    $authorizedTo = $obj->authorizeTo;
                    $queryItems = 'SELECT a.ItemCode,b.Description,a.RequestRemark 
                        FROM [tblAssetGatePassItem] a LEFT JOIN [tblItemMaster] b ON a.ItemCode = b.ItemCode
                        WHERE a.GatePassID = ?';
                    $param = array($gatepassID);
                    $stmt2 = sqlsrv_query($conn, $queryItems, $param);
                    if ($stmt2 === false) {
                        echo "Error in item query preparation/execution.\n";
                    }
                    ?>
                    <div class="well"> 
                        <form  method="post" action="approveGatepass.php?ID=<?php echo $gatepassID;?>">
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
                            <div class="col-sm-10"></div><div class="btn-group"><button id="approve" type="submit" name="approve" value="1" class="btn btn-success">Approve</button><button  id="reject" type="submit" name="reject" value="0" class="btn btn-danger">Reject</button></div>


                        </div>
                        </form>
                    </div>
                <?php } ?>
        </div>


    </body>
</html>

