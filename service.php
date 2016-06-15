<?php

/*
  import database connection file
  this statement not needed. because image controller already include database connection and
  php this version automatically
 */
//include 'db_connection/db_connector.php';
include 'image_controller.php';

// array for JSON response
$response = array();

/* check query string params are set and there is no nulls */
if (isset($_GET['alldtl']) && intval($_GET['alldtl']) == 1) {
    getAllDetailsAtOnce();
    //test();
} else {
    $response["success"] = 0;
    $response["message"] = "Service params are not valid.";
    // echoing JSON response
    header('Content-type: application/json');
    echo json_encode($response);
}

/**
 * 	Bulk details function 
 * 
 * */
function getAllDetailsAtOnce() {
    /* get database connection */
    $conn = local_connect();

    /* create maga array for hold all sub arrays */
    $megaArr = array();

    /* get supllier values */
    $sup_query = "SELECT [supplierID],[supName],[Add1],[Add2],[Add3],[Tel1],[Tel2],[Fax],[Email],[ContactPer] FROM [tblSupplier] ORDER BY supName ASC";

    $stmt = sqlsrv_query($conn, $sup_query);

    if ($stmt === false) {
        echo "Error in query preparation/execution | supplier.\n";
        error_log("Error in query preparation/execution in supplier query : " . print_r(sqlsrv_errors(), true), 0);
        die(print_r(sqlsrv_errors(), true));
    }

    $supplierArray = array();
    /* Retrieve supplier each row as a PHP object and display the results. */
    while ($obj = sqlsrv_fetch_object($stmt)) {
        //echo $obj->supplierID.", ".$obj->supName.", ".$obj->Add1."<br>";
        $sup_arr = array(
            'supplierID' => $obj->supplierID,
            'supName' => $obj->supName,
            'Add1' => $obj->Add1,
            'Add2' => $obj->Add2,
            'Add3' => $obj->Add3,
            'Tel1' => $obj->Tel1,
            'Tel2' => $obj->Tel2,
            'Fax' => $obj->Fax,
            'Email' => $obj->Email,
            'ContactPer' => $obj->ContactPer);
        array_push($supplierArray, $sup_arr);
    }

    //array_push($megaArr,$supplierArray);
    $megaArr['Supplier'] = $supplierArray;

    /* Free supplier Statement */
    sqlsrv_free_stmt($stmt);

    /* get company values */
    $cmp_query = "SELECT [Com_Code],[Com_Name] FROM [tblPRCompany] ORDER BY Com_Name ASC";

    $stmt = sqlsrv_query($conn, $cmp_query);

    if ($stmt === false) {
        echo "Error in query preparation/execution | Company.\n";
        error_log("Error in query preparation/execution in company query : " . print_r(sqlsrv_errors(), true), 0);
        die(print_r(sqlsrv_errors(), true));
    }

    $companyArray = array();
    /* Retrieve company each row as a PHP object and display the results. */
    while ($obj = sqlsrv_fetch_object($stmt)) {
        //echo $obj->Com_Code.", ".$obj->Com_Name."<br>";
        $cmp_arr = array(
            'Com_code' => $obj->Com_Code,
            'Com_Name' => $obj->Com_Name);


        /* get building values */
        $build_query = "SELECT [BuildingCode],[BuildingName],[Com_Code] FROM [tblBuilding] WHERE Com_Code = (?) ORDER BY BuildingName ASC";
        $build_param = array($obj->Com_Code);

        $b_stmt = sqlsrv_query($conn, $build_query, $build_param);

        if ($b_stmt === false) {
            echo "Error in query preparation/execution | Building.\n";
            error_log("Error in query preparation/execution in Building query : " . print_r(sqlsrv_errors(), true), 0);
            die(print_r(sqlsrv_errors(), true));
        }

        $buildingArray = array();
        /* Retrieve building each row as a PHP object and display the results. */
        while ($obj = sqlsrv_fetch_object($b_stmt)) {
            //echo $obj->BuildingCode.", ".$obj->BuildingName.", ".$obj->Com_Code."<br>";
            $bld_arr = array(
                'BuildingCode' => $obj->BuildingCode,
                'BuildingName' => $obj->BuildingName,
                'Com_Code' => $obj->Com_Code);

            /** inner query for get floores* */
            $floor_query = "SELECT [FloorCode],[FloorName],[Com_Code],[BuildingCode] FROM [tblFloor] WHERE BuildingCode = (?) ORDER BY FloorName ASC";
            $floor_param = array($obj->BuildingCode);

            $f_stmt = sqlsrv_query($conn, $floor_query, $floor_param);

            if ($f_stmt === false) {
                echo "Error in query preparation/execution | Floor.\n";
                error_log("Error in query preparation/execution in Floor query : " . print_r(sqlsrv_errors(), true), 0);
                die(print_r(sqlsrv_errors(), true));
            }

            $floorArray = array();
            while ($f_obj = sqlsrv_fetch_object($f_stmt)) {
                //echo $f_obj->FloorCode.", ".$f_obj->FloorName.", ".$f_obj->Com_Code.", ".$f_obj->BuildingCode."<br>";
                $flr_arr = array(
                    'FloorCode' => $f_obj->FloorCode,
                    'FloorName' => $f_obj->FloorName,
                    'Com_Code' => $f_obj->Com_Code,
                    'BuildingCode' => $f_obj->BuildingCode);

                /* get Location  values */
                $loc_query = "SELECT [LocCode],[Description],[Type],[Remarks],[PrintDef],[KotOrBot],[LocTag],[LocIndex],[Com_Code],[BuildingCode],[FloorCode] FROM [tblLocations] WHERE [FloorCode] = (?) ORDER BY Description ASC";

                $loc_param = array($f_obj->FloorCode);
                //error_log($loc_query." - ".$f_obj->FloorCode,0);

                $l_stmt = sqlsrv_query($conn, $loc_query, $loc_param);

                if ($l_stmt === false) {
                    echo "Error in query preparation/execution | Location.\n";
                    error_log("Error in query preparation/execution in Location query : " . print_r(sqlsrv_errors(), true), 0);
                    die(print_r(sqlsrv_errors(), true));
                }

                $locationArray = array();
                /* Retrieve location each row as a PHP object and display the results. */
                while ($l_obj = sqlsrv_fetch_object($l_stmt)) {
                    //echo $l_obj->LocCode.", ".$l_obj->Description.", ".$l_obj->Type.", ".$l_obj->Remarks.", ".$l_obj->PrintDef.", ".$l_obj->KotOrBot.", ".$l_obj->LocTag.", ".$l_obj->LocIndex."\n";
                    $loc_arr = array(
                        'LocCode' => $l_obj->LocCode,
                        'Description' => $l_obj->Description,
                        'Type' => $l_obj->Type,
                        'Remarks' => $l_obj->Remarks,
                        'PrintDef' => $l_obj->PrintDef,
                        'KotOrBot' => $l_obj->KotOrBot,
                        'LocTag' => $l_obj->LocTag,
                        'LocIndex' => $l_obj->LocIndex,
                        'Com_Code' => $l_obj->Com_Code,
                        'BuildingCode' => $l_obj->BuildingCode,
                        'FloorCode' => $l_obj->FloorCode);

                    array_push($locationArray, $loc_arr);
                }

                $flr_arr['Location'] = $locationArray;
                array_push($floorArray, $flr_arr);

                /* Free Location Statement */
                sqlsrv_free_stmt($l_stmt);
            }

            $bld_arr['floores'] = $floorArray;
            array_push($buildingArray, $bld_arr);
        }

        $cmp_arr['building'] = $buildingArray;
        array_push($companyArray, $cmp_arr);
    }

    //array_push($megaArr,$companyArray);
    $megaArr['Company'] = $companyArray;

    /* Free building statement */
    sqlsrv_free_stmt($stmt);

    /*     * ************************************************************************************************************************************************** */

    /* get asset catgory values */
    $asset_query = "SELECT [AssetCategoryCode],[AssetCategoryName] FROM [tblAssetCategory] ORDER BY AssetCategoryName ASC";

    $stmt = sqlsrv_query($conn, $asset_query);

    if ($stmt === false) {
        echo "Error in query preparation/execution | Asset Category.\n";
        error_log("Error in query preparation/execution in Asset Category query : " . print_r(sqlsrv_errors(), true), 0);
        die(print_r(sqlsrv_errors(), true));
    }

    $assetCategoryArray = array();
    /* Retrieve asset category each row as a PHP object and display the results. */
    while ($a_obj = sqlsrv_fetch_object($stmt)) {
        //echo $a_obj->AssetCategoryCode.", ".$a_obj->AssetCategoryName."<br>";
        $ast_arr = array(
            'AssetCategoryCode' => $a_obj->AssetCategoryCode,
            'AssetCategoryName' => $a_obj->AssetCategoryName);


        /* inner query for get main category values by assetCategory Code */
        $cat_query = "SELECT Code,Description,Type,AllocatedMeal,Picture,AssetCategoryCode FROM tblCategory WHERE AssetCategoryCode = (?) ORDER BY Description ASC";
        $cat_param = array($a_obj->AssetCategoryCode);

        //echo "SELECT Code,Description,Type,AllocatedMeal,Picture,AssetCategoryCode FROM tblCategory WHERE AssetCategoryCode =".$a_obj->AssetCategoryCode."\n";

        $c_stmt = sqlsrv_query($conn, $cat_query, $cat_param);

        if ($c_stmt === false) {
            echo "Error in query preparation/execution | Main Category.\n";
            error_log("Error in query preparation/execution in Main Category query : " . print_r(sqlsrv_errors(), true), 0);
            die(print_r(sqlsrv_errors(), true));
        }

        $mainCategoryArray = array();
        /* Retrieve main category each row as a PHP object and display the results. */
        while ($m_obj = sqlsrv_fetch_object($c_stmt)) {
            //echo $m_obj->Code.", ".$m_obj->Description.", ".$m_obj->Type.", ".$m_obj->AllocatedMeal."<br>";
            $cat_arr = array(
                'Code' => $m_obj->Code,
                'Description' => $m_obj->Description,
                'Type' => $m_obj->Type,
                'AllocatedMeal' => $m_obj->AllocatedMeal,
                'AssetCategoryCode' => $m_obj->AssetCategoryCode);


            /* inner query get Sub catgory values */
            $subcat_query = "SELECT [Sub_Cat1],[Description],[Code],[Type],[KOTPrint],[Picture],[Printdef],[KotOrBot],[BalanceView] FROM [tblSubCategory1] WHERE Code = (?) ORDER BY Description ASC";
            $subcat_param = array($m_obj->Code);

            //echo "SELECT [Sub_Cat1],[Description],[Code],[Type],[KOTPrint],[Picture],[Printdef],[KotOrBot],[BalanceView] FROM [tblSubCategory1] WHERE Code =".$m_obj->Code."\n";

            $s_stmt = sqlsrv_query($conn, $subcat_query, $subcat_param);

            if ($s_stmt === false) {
                echo "Error in query preparation/execution | Sub Category.\n";
                error_log("Error in query preparation/execution in Sub Category query : " . print_r(sqlsrv_errors(), true), 0);
                die(print_r(sqlsrv_errors(), true));
            }

            $subCategoryArray = array();
            /* Retrieve sub category each row as a PHP object and display the results. */
            while ($s_obj = sqlsrv_fetch_object($s_stmt)) {
                //echo $s_obj->Sub_Cat1.", ".$s_obj->Description.", ".$s_obj->Type.", ".$s_obj->KOTPrint.", ".$s_obj->Picture.", ".$s_obj->Printdef.", ".$s_obj->KotOrBot.", ".$s_obj->BalanceView."<br>";
                $sub_arr = array(
                    'Sub_Cat1' => $s_obj->Sub_Cat1,
                    'Description' => $s_obj->Description,
                    'Type' => $s_obj->Type,
                    'KOTPrint' => $s_obj->KOTPrint,
                    'Picture' => $s_obj->Picture,
                    'Printdef' => $s_obj->Printdef,
                    'KotOrBot' => $s_obj->KotOrBot,
                    'BalanceView' => $s_obj->BalanceView);

                array_push($subCategoryArray, $sub_arr);
            }

            $cat_arr['subCategory'] = $subCategoryArray;
            array_push($mainCategoryArray, $cat_arr);

            /* Free sub category statement */
            sqlsrv_free_stmt($s_stmt);
        }

        $ast_arr['mainCategory'] = $mainCategoryArray;
        array_push($assetCategoryArray, $ast_arr);

        /* Free main category statement */
        sqlsrv_free_stmt($c_stmt);
    }

    $megaArr['assetCategory'] = $assetCategoryArray;

    /* Free asset category statement */
    sqlsrv_free_stmt($stmt);

    /* get condition values */
    $cond_query = "SELECT [Condition],[ConditionWeight] FROM [tblItemCondition] ORDER BY Condition ASC";

    $stmt = sqlsrv_query($conn, $cond_query);

    if ($stmt === false) {
        echo "Error in query preparation/execution | Condition .\n";
        error_log("Error in query preparation/execution in condition query : " . print_r(sqlsrv_errors(), true), 0);
        die(print_r(sqlsrv_errors(), true));
    }

    $conditionArray = array();
    /* Retrieve condition each row as a PHP object and display the results. */
    while ($obj = sqlsrv_fetch_object($stmt)) {
        //echo $obj->Condition.", ".$obj->ConditionWeight."<br>";
        $cnd_arr = array(
            'Condition' => $obj->Condition,
            'ConditionWeight' => $obj->ConditionWeight);
        array_push($conditionArray, $cnd_arr);
    }
    $megaArr['condition'] = $conditionArray;

    /* Free asset category statement */

    sqlsrv_free_stmt($stmt);

    /** return json object * */
    header('Content-type: application/json');
    echo json_encode($megaArr);

    //error_log(print_r($megaArr,true),0);

    /* close connection resources. */
    sqlsrv_close($conn);
}

/**
 * not use any more
 * Test functoin for catch json object and display contain data
 * 
 * */
/**
  function displayTestParam(){
  error_log("init function",0);
  //echo 'init function....<br>';
  $j_obj = file_get_contents('php://input');

  $data = json_decode($j_obj,true);
  error_log("1 ".$data,0);


  error_log("finish function",0);

  // successfully inserted into database
  $response["success"] = 1;
  $response["message"] = "Json Test successfully.";
  // echoing JSON response
  header('Content-type: application/json');
  echo json_encode($response);
  }
 * */
?>
