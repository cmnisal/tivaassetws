<?php

include 'image_controller.php';

$response = array();

if (isset($_GET['insdtl']) && intval($_GET['insdtl']) == 1) {
    //error_log($_GET['insdtl'], 0);
    insertItems();
} else {
    // successfully inserted into database
    $response["success"] = 0;
    $response["message"] = "Param Not Valid.";

    header('Content-type: application/json');
    echo json_encode($response);
}

/**
 * insert item function
 */
function insertItems() {
    /* establish connection */
    $conn = local_connect();

    $company = $_POST['Company'];
    $wing = $_POST['Wing'];
    $floor = $_POST['Floor'];
    $loccode = $_POST['LocCode'];
    $assetcategory = $_POST['AssetCategory'];
    $maincat = $_POST['MainCat'];
    $subcat = $_POST['SubCat'];
    $itemname = $_POST['ItemName'];
    //$itemtype = $_POST['ItemType'];
    $cost = $_POST['Cost'];
    $condition = $_POST['Condition'];
    $supplierCode = $_POST['SupplierCode'];
    $supplierName = $_POST['SupplierName'];
    $quantity = $_POST['Quantity'];
    $item_img = $_POST['image'];
    //image path variable.
    $imagepath = "null";

    //error_log($company, 0);

    if (isset($company) || isset($wing) || isset($floor) || isset($loccode) || isset($maincat) || isset($subcat) || isset($itemname) || isset($suppler) || isset($assetcategory) || isset($quantity)) {

        if (strcmp($supplierName, "Select") == 0) {
            $supplierCode = "SupplierCode";
            $supplierName = "SupplierName";
        }
        
          if (hasImageToSubCategory($subcat)) {
          $imagepath = saveImage($item_img, $subcat);
          if (!isset($imagepath)) {
          $imagepath = "null";
          }
          } 
        //error_log("inito isset statment : ".$imagepath,0);

        error_log("Loc_code : " . $loccode, 0);
        $tmpLccd = getLocationCode($loccode);
        error_log("tmp_Loc_code : " . $tmpLccd, 0);

        $printName = $floor . "-" . $loccode . "-" . $assetcategory . "-" . $maincat . "-" . $subcat . "-" . $tmpLccd;
        $itemCode = $loccode . "-" . $tmpLccd;
        //error_log("itemCode : ".$itemCode,0);
        //error_log($company."-".$wing."-".$floor."-".$loccode."-".$assetcategory."-".$maincat."-".$subcat."-".$itemCode."-".$cost."-".$condition."-".$suppler."-".$quantity,0);


        $itm_query = "INSERT INTO [tblItemMaster] ([ItemCode],[Description],[Sub_Cat1],[U_O_MPurchase],[Cost],[Quantity],[QtyMeasure],[U_O_MSell],[SellingPrice],[Reorder_Qty],[Min_Qty],[Max_Qty],[Lead_Time],[LocCode],[ItemType],[taxId],[MostSuitItem],[PrintName],[InsantCookedItem],[Picture],[Meal],[OpenQuantity],[OpenQtyMeasure],[CostPresentage],[SellingPresentage],[SubPrice],[MinusIssue],[Estimate_time],[Currency],[DirectCost],[IndirectCost],[Activate],[SupCode],[SupName],[discount_type],[discount_amount],[Condition],[UsePeriod],[PrintStatus])
	 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $itm_params = array($itemCode, $itemname, $subcat, "NOS", $cost, $quantity, 0, "NOS", 0, 0, 0, 0, 0, $loccode, "BOTH", "0", "nosuit", $printName, "N", "", $imagepath, 0, 0, 0, 0, 0, 0, 0, "LKR", 0, 0, 1, $supplierCode, $supplierName, "p", 0, $condition, "2", "NO");
        //error_log($itm_query." \n",0);
        //error_log(print_r($itm_params,true),0);

        $stmt = sqlsrv_query($conn, $itm_query, $itm_params);

        if ($stmt === false) {
            error_log("Error in query preparation/execution in insert item query : " . print_r(sqlsrv_errors(), true), 0);
            die(print_r(sqlsrv_errors(), true));

            $response["success"] = 0;
            $response["message"] = "Item Inserting Fail.";
            header('Content-type: application/json');
            echo json_encode($response);
        }

        /* Free item master statement */
        sqlsrv_free_stmt($stmt);

        /**         * */
        if ($imagepath != "null") {

            $s_query = "UPDATE tblItemMaster  SET Picture = (?) WHERE Sub_Cat1 = (?)";
            $s_param = array($imagepath, $subcat);
            $s_stmt = sqlsrv_query($conn, $s_query, $s_param);
            //error_log($s_query,0);
            //error_log(print_r($s_param,true),0);

            if ($s_stmt === false) {
                error_log("Error in query preparation/execution in insert subcategory imagepath query : " . print_r(sqlsrv_errors(), true), 0);
                die(print_r(sqlsrv_errors(), true));

                $response["success"] = 0;
                $response["message"] = "Item image insert Fail.";
                header('Content-type: application/json');
                echo json_encode($response);
            }

            /* Free subcategory image insert statement. */
            sqlsrv_free_stmt($s_stmt);
        }

        $response["success"] = 1;
        $response["message"] = "Item Insert Successfull. Item code is " . $itemCode;
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

/**
 * @param $loccode
 * @return int
 */
function getLocationCode($loccode) {
    /* establish connection */
    $conn = local_connect();
    $default_Start_Id = 1000;
    $genCode = 0;

    $query = "SELECT [AutoCode],[AutoStartChr],[DocType],[descrp] FROM [tblDocCodeGeneratorForItems] WHERE DocType = (?)";
    $param = array($loccode);
    //error_log($query."----".$param,0);

    $stmt = sqlsrv_query($conn, $query, $param);

    if ($stmt === false) {
        echo "Error in query preparation/execution | Location Code.\n";
        error_log("Error in query preparation/execution in Location Code query : " . print_r(sqlsrv_errors(), true), 0);
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($stmt)) {
        $autoCode = "";
        while ($obj = sqlsrv_fetch_object($stmt)) {
            $genCode = $obj->AutoCode;
            $autoCode = $obj->AutoCode;
        }

        $i_query = "UPDATE [tblDocCodeGeneratorForItems] SET [AutoCode] = (?) WHERE [DocType] = (?)";
        $i_param = array($autoCode + 1, $loccode);
        $i_stmt = sqlsrv_query($conn, $i_query, $i_param);

        if ($i_stmt === false) {
            error_log("Error in query preparation/execution in update doc_number query : " . print_r(sqlsrv_errors(), true), 0);
            die(print_r(sqlsrv_errors(), true));
        }


        /* Free main category statement */
        sqlsrv_free_stmt($i_stmt);
    } else {
        $ig_query = "INSERT INTO [tblDocCodeGeneratorForItems]
						([AutoCode]
						,[AutoStartChr]
						,[DocType]
						,[descrp]) VALUES (?,?,?,?)";
        $ig_param = array($default_Start_Id + 1, $loccode, $loccode, "");
        //error_log($ig_query,0);

        $ig_stmt = sqlsrv_query($conn, $ig_query, $ig_param);
        //error_log($ig_stmt,0);

        if ($ig_stmt === false) {
            error_log("Error in query preparation/execution in  insert new doc number : " . print_r(sqlsrv_errors(), true), 0);
            die(print_r(sqlsrv_error(), true));
        }

        if ($genCode == 0) {
            $genCode = $default_Start_Id;
        }

        /* Free main category statement */
        sqlsrv_free_stmt($ig_stmt);
    }

    /* close connection resources. */
    sqlsrv_close($conn);

    return $genCode;
}

/**
 * @param $subcat
 * @return bool
 */
function hasImageToSubCategory($subcat) {
    $conn = local_connect();

    $hasImage = false;

    $query = "SELECT Picture FROM tblSubCategory1 WHERE Sub_Cat1 = (?)";
    $param = array($subcat);

    $stmt = sqlsrv_query($conn, $query, $param);

    if ($stmt === false) {
        error_log("Error in query preparation/execution in  check subcat picture : " . print_r(sqlsrv_errors(), true), 0);
        die(print_r(sqlsrv_error(), true));
    }

    if (sqlsrv_has_rows($stmt)) {
        $image = null;
        while ($obj = sqlsrv_fetch_object($stmt)) {
            $image = $obj->Picture;
            //error_log("------------------".$image,0);
        }

        //error_log("Images : ".$image,0);
        if (isset($image) || $image != null || $image != "null") {
            //error_log("Images_1 : ".$image,0);
            $hasImage = true;
        } else {
            //error_log("Images_2 : ".$image,0);
        }
    }

    return $hasImage;
}

?>
