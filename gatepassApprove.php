<?php
header('Location:gatepassView.php');
if (!session_start()) {
    session_start();
}
include 'db_connection/db_connector.php';
$_SESSION['logged'] = true;
$conn = local_connect();
$gatepassID = $_GET['ID'];  
$email = $_SESSION['email'];
$approve = $_POST["approve"];
if (isset($gatepassID) && isset($email) && isset($approve)) {
    if($approve==1){
        $status =3;
    }else if($approve==0){
        $status =4;
    }
    $query = 'UPDATE tblAssetgatepass SET Status = ? WHERE gatepassID = ?';
    $params = array($status,$gatepassID);
    $stmt = sqlsrv_query($conn, $query, $params);
    if ($stmt === false) {
        echo "Error in Update query preparation/execution\n";
        print_r(sqlsrv_errors(), true);
    }else{
        echo "Update Success!";
    }
    echo $gatepassID."-".$email."-".$approve;
    $query = "INSERT INTO [dbo].[tblAssetGatePassApprove]
           ([GatePassID]
           ,[email]
           ,[DateTime]
           ,[IsApproved])
     VALUES ('$gatepassID','$email',GETDATE(),$approve)";
    $params = array($gatepassID, $email, $approve);
    $stmt = sqlsrv_query($conn, $query);
    if ($stmt === false) {
        echo "Error in Insert query preparation/execution\n";
        die(print_r(sqlsrv_errors(), true));
    }else{
        echo "Insert Success!";
    }
}
?>