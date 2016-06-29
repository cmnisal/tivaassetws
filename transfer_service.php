<?php

include 'docCodeGenerator.php';

//$response = array();

if (isset($_GET['transfer']) && intval($_GET['transfer']) == 1) {
    //error_log($_GET['insdtl'], 0);
    transferItem();
} else {
    $response["success"] = 0;
    $response["message"] = "Param Not Valid.";
    header('Content-type: application/json');
    echo json_encode($response);
}

function transferItem() {
    $conn = local_connect();
    
    $transDate = $_POST['transDate']; 
    $userID = $_POST['UserID'];
    $FYCode = $_POST['FYCode'];
    $Remark = $_POST['Remark'];
    $Department = $_POST['Department'];
    $loccode = $_POST['LocCode'];//("M d, Y");
    $itemCode = $_POST['itemCode'];
    
    if (isset($itemCode) || isset($company) || isset($wing) || isset($floor) || isset($loccode) || isset($transDate)) {
        $convertedDate = dateConversion($transDate);
        $tr_id = generateCode("TR");
        $itm_query = "  INSERT INTO tblItemTransfer ([TransferID]
                                                     ,[TransferDate]
                                                     ,[UserId]
                                                     ,[FYCode]
                                                     ,[Remark]
                                                     ,[FromDepartment]
                                                     ,[FromLocation]
                                                     ,[ToDepartment]
                                                     ,[ToLocation]
                                                     ,[ItemCode])
                                (SELECT '?' AS [TransferID]
					,'?' AS [TransferDate]
					,'?' AS [UserId]
					,'?' AS [FYCode]
					,'?' [Remark]
					,Item.DepartmentCode_Exist AS [FromDepartment]
					,Item.LocCode AS [FromLocation]
					,'?' AS [ToDepartment]
					,'?' AS[ToLocation]
					,Item.ItemCode AS [ItemCode]
					FROM tblItemMaster Item WHERE ItemCode = '?')";
       $itm_param = array($tr_id,$transDate,$userID,$FYCode,$Remark,$Department,$loccode,$itemCode);
       error_log($itm_query);
       $stmt = sqlsrv_query($conn, $itm_query,$itm_param);

        if ($stmt === false) {
            error_log("Error in Query : " . print_r(sqlsrv_errors(), true), 0);
            die(print_r(sqlsrv_errors(), true));
            $response["success"] = 0;
            $response["message"] = "Item Transfering Fail.";
            header('Content-type: application/json');
            echo json_encode($response);
        }

        /* Free item master statement */
        sqlsrv_free_stmt($stmt);

        /**         * */
        $response["success"] = 1;
        $response["message"] = $itemCode . " Item Transfer Successfull.";
        header('Content-type: application/json');
        echo json_encode($response);
        
    } else {
        $response["success"] = 2;
        $response["message"] = "Requierd params not set properly please try again.";
        header('Content-type: application/json');
        echo json_encode($response);
    }

    /* close connection resources. */
    sqlsrv_close($conn);
}

function dateConversion($date) {
    $dates = explode("-", $date);
    return $dates[2] . "-" . $dates[1] . "-" . $dates[0];
}

?>
