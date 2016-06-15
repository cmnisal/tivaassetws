<?php

	include 'image_controller.php';
	//include 'image_service.php';
	// array for JSON response
	$response = array();
	
	/* check query string params are set and there is no nulls */
	if(isset($_GET['getdtl']) && intval($_GET['getdtl']) == 1) {
		getItemDetails();
	}else if(isset($_GET['getdtl']) && intval($_GET['getdtl']) == 2){
		getAllItemCodeAndName();
	}else{
		$response["success"] = 0;
		$response["message"] = "Service params are not valid.";
        // echoing JSON response
        header('Content-type: application/json');
        echo json_encode($response); 
	}
	
	
	function getAllItemCodeAndName(){
		$conn = local_connect();
	
		/* get supllier values */
		$query = "SELECT"
                        . "     tblItemMaster.\"ItemCode\" AS tblItemMaster_ItemCode,\n"
                        . "     tblItemMaster.\"Description\" AS tblItemMaster_Description,\n"
                        . "     tblItemMaster.\"LocCode\" AS tblItemMaster_LocCode,\n"
                        . "     tblItemMaster.\"Sub_Cat1\" AS tblItemMaster_Sub_Cat1,\n"
                        . "     tblItemMaster.\"Picture\" AS tblItemMaster_Picture,\n"
                        . "     tblBuilding.\"BuildingCode\" AS tblBuilding_BuildingCode,\n"
                        . "     tblBuilding.\"BuildingName\" AS tblBuilding_BuildingName,\n"
                        . "     tblFloor.\"FloorName\" AS tblFloor_FloorName,\n"
                        . "     tblFloor.\"Com_Code\" AS tblFloor_Com_Code,\n"
                        . "     tblPRCompany.\"Com_Code\" AS tblPRCompany_Com_Code,\n"
                        . "     tblPRCompany.\"Com_Name\" AS tblPRCompany_Com_Name,\n"
                        . "     tblLocations.\"Description\" AS tblLocations_Description,\n"
                        . "     tblLocations.\"FloorCode\" AS tblLocations_FloorCode,\n"
                        . "     tblAssetCategory.\"AssetCategoryCode\" AS tblAssetCategory_AssetCategoryCode,\n"
                        . "     tblAssetCategory.\"AssetCategoryName\" AS tblAssetCategory_AssetCategoryName,\n"
                        . "     tblCategory.\"Code\" AS tblCategory_Code,\n"
                        . "     tblCategory.\"Description\" AS tblCategory_Description,\n"
                        . "     tblSubCategory1.\"Sub_Cat1\" AS tblSubCategory1_Sub_Cat1,\n"
                        . "     tblSubCategory1.\"Description\" AS tblSubCategory1_Description,\n"
                        . "     tblSubCategory1.\"Code\" AS tblSubCategory1_Code,\n"
                        . "     tblItemMaster.\"Quantity\" AS tblItemMaster_Quantity,\n"
                        . "     tblItemMaster.\"Cost\" AS tblItemMaster_Cost,\n"
                        . "     tblItemMaster.\"SupCode\" AS tblItemMaster_SupCode,\n"
                        . "     tblItemMaster.\"SupName\" AS tblItemMaster_SupName,\n"
                        . "     tblItemMaster.\"Activate\" AS tblItemMaster_Activate,\n"
                        . "     tblItemMaster.\"PrintName\" AS tblItemMaster_PrintName,\n"
                        . "     tblItemMaster.\"Condition\" AS tblItemMaster_Condition,\n"
                        . "     tblItemCondition.\"Condition\" AS tblItemCondition_Condition,\n"
                        . "     tblItemCondition.\"ConditionWeight\" AS tblItemCondition_ConditionWeight,\n"
                        . "     tblItemMaster.\"ItemType\" AS tblItemMaster_ItemType,\n"
                        . "	    tblItemMaster.\"DepartmentCode_Onwer\" AS tblItemMaster_DepartmentCode_Onwer,\n"
                        . "	 tblItemMaster.\"DepartmentCode_Exist\" AS tblItemMaster_DepartmentCode_Exist,\n"
                        . "	 CONVERT(varchar(10), tblItemMaster.\"WarrentyExpDate\", 120)AS tblItemMaster_WarrentyExpDate,\n"
                        . "	 CONVERT(varchar(10), tblItemMaster.\"PurchasingDate\", 120)AS tblItemMaster_PurchasingDate,\n"
                        . "	 tblItemMaster.\"InvoiceNo\" AS tblItemMaster_InvoiceNo,\n"
                        . "	 tblItemMaster.\"DCode\" AS tblItemMaster_DCode,\n"
                        . "	 tblItemMaster.\"ExtraCode\" AS tblItemMaster_ExtraCode\n"
                        . " FROM\n"
                        . "     tblFloor INNER JOIN  tblBuilding ON tblFloor.\"BuildingCode\" = tblBuilding.\"BuildingCode\"\n"
                        . "     INNER JOIN tblPRCompany ON tblFloor.\"Com_Code\" = tblPRCompany.\"Com_Code\"\n"
                        . "     INNER JOIN tblLocations ON tblFloor.\"FloorCode\" = tblLocations.\"FloorCode\"\n"
                        . "     INNER JOIN  tblItemMaster ON tblLocations.\"LocCode\" = tblItemMaster.\"LocCode\"     \n"
                        . "     INNER JOIN  tblSubCategory1 ON tblItemMaster.\"Sub_Cat1\" = tblSubCategory1.\"Sub_Cat1\"\n"
                        . "     INNER JOIN  tblItemCondition ON tblItemMaster.\"Condition\" = tblItemCondition.\"Condition\"\n"
                        . "     INNER JOIN  tblCategory ON tblSubCategory1.\"Code\" = tblCategory.\"Code\"\n"
                        . "     INNER JOIN  tblAssetCategory ON tblCategory.\"AssetCategoryCode\" = tblAssetCategory.\"AssetCategoryCode\"\n"
                        . "	 full JOIN tblDepartment ON tblItemMaster.\"DepartmentCode_Onwer\"=tblDepartment.\"DepartmentCode\"\n"
                        . "	 WHERE tblItemMaster.IsDiscard='0'";
						
		error_log($query,0);
		
		$stmt = sqlsrv_query($conn,$query);
		
		if($stmt === false){
			echo "Error in query preparation/execution | Item Details.\n";
			error_log("\nError in query preparation/execution in supplier query : ".print_r( sqlsrv_errors(), true),0);
			die( print_r( sqlsrv_errors(), true));
		}
		
		$arr_resp["product"] = array();
		
		/* Retrieve Item Details as a PHP object and display the results.*/
		while($obj = sqlsrv_fetch_object($stmt)){
			
			$item_arr['ItemCode'] = $obj->tblItemMaster_ItemCode;
			$item_arr['Description'] = $obj->tblItemMaster_Description;
			/**
			$item_arr['Company'] = $obj->tblPRCompany_Com_Name;
			$item_arr['Building'] = $obj->tblBuilding_BuildingName;
			$item_arr['Floor'] = $obj->tblFloor_FloorName;
			$item_arr['Location'] = $obj->tblLocations_Description;
			$item_arr['AssetCategory'] = $obj->tblAssetCategory_AssetCategoryName;
			$item_arr['Category'] = $obj->tblCategory_Description;
			$item_arr['SubCategory'] = $obj->tblSubCategory1_Description;
			$item_arr['Quantity'] = $obj->tblItemMaster_Quantity;
			$item_arr['Condition'] = $obj->tblItemCondition_Condition;
			$item_arr['Supplier'] = $obj->tblItemMaster_SupName;
			**/
			
			array_push($arr_resp["product"],$item_arr);
			//$arr_resp["product"] = $item_arr;
		}
		
		/* Free supplier Statement */
		sqlsrv_free_stmt($stmt);
		
		/** return json object **/
		header('Content-type: application/json');
		echo json_encode($arr_resp);
		
		//error_log(print_r($megaArr,true),0);
		
		/* close connection resources. */
		sqlsrv_close($conn);	
	}
	
function getItemDetails(){
		/* get database connection */
		$itemCode = $_GET['itemCode'];
		
		if(!isset($itemCode) || $itemCode != ""){
			
		$conn = local_connect();
		
	
		/* get supllier values */
		//$query = "SELECT * FROM [tblItemMaster] WHERE ItemCode = '".$itemCode."'";
		$query = "SELECT"
                        . "     tblItemMaster.\"ItemCode\" AS tblItemMaster_ItemCode,\n"
                        . "     tblItemMaster.\"Description\" AS tblItemMaster_Description,\n"
                        . "     tblItemMaster.\"LocCode\" AS tblItemMaster_LocCode,\n"
                        . "     tblItemMaster.\"Sub_Cat1\" AS tblItemMaster_Sub_Cat1,\n"
                        . "     tblItemMaster.\"Picture\" AS tblItemMaster_Picture,\n"
                        . "     tblBuilding.\"BuildingCode\" AS tblBuilding_BuildingCode,\n"
                        . "     tblBuilding.\"BuildingName\" AS tblBuilding_BuildingName,\n"
                        . "     tblFloor.\"FloorName\" AS tblFloor_FloorName,\n"
                        . "     tblFloor.\"Com_Code\" AS tblFloor_Com_Code,\n"
                        . "     tblPRCompany.\"Com_Code\" AS tblPRCompany_Com_Code,\n"
                        . "     tblPRCompany.\"Com_Name\" AS tblPRCompany_Com_Name,\n"
                        . "     tblLocations.\"Description\" AS tblLocations_Description,\n"
                        . "     tblLocations.\"FloorCode\" AS tblLocations_FloorCode,\n"
                        . "     tblAssetCategory.\"AssetCategoryCode\" AS tblAssetCategory_AssetCategoryCode,\n"
                        . "     tblAssetCategory.\"AssetCategoryName\" AS tblAssetCategory_AssetCategoryName,\n"
                        . "     tblCategory.\"Code\" AS tblCategory_Code,\n"
                        . "     tblCategory.\"Description\" AS tblCategory_Description,\n"
                        . "     tblSubCategory1.\"Sub_Cat1\" AS tblSubCategory1_Sub_Cat1,\n"
                        . "     tblSubCategory1.\"Description\" AS tblSubCategory1_Description,\n"
                        . "     tblSubCategory1.\"Code\" AS tblSubCategory1_Code,\n"
                        . "     tblItemMaster.\"Quantity\" AS tblItemMaster_Quantity,\n"
                        . "     tblItemMaster.\"Cost\" AS tblItemMaster_Cost,\n"
                        . "     tblItemMaster.\"SupCode\" AS tblItemMaster_SupCode,\n"
                        . "     tblItemMaster.\"SupName\" AS tblItemMaster_SupName,\n"
                        . "     tblItemMaster.\"Activate\" AS tblItemMaster_Activate,\n"
                        . "     tblItemMaster.\"PrintName\" AS tblItemMaster_PrintName,\n"
                        . "     tblItemMaster.\"Condition\" AS tblItemMaster_Condition,\n"
                        . "     tblItemCondition.\"Condition\" AS tblItemCondition_Condition,\n"
                        . "     tblItemCondition.\"ConditionWeight\" AS tblItemCondition_ConditionWeight,\n"
                        . "     tblItemMaster.\"ItemType\" AS tblItemMaster_ItemType,\n"
                        . "	    tblItemMaster.\"DepartmentCode_Onwer\" AS tblItemMaster_DepartmentCode_Onwer,\n"
                        . "	 tblItemMaster.\"DepartmentCode_Exist\" AS tblItemMaster_DepartmentCode_Exist,\n"
                        . "	 CONVERT(varchar(10), tblItemMaster.\"WarrentyExpDate\", 120)AS tblItemMaster_WarrentyExpDate,\n"
                      //  . "	 CONVERT(varchar(10), tblItemMaster.\"PurchasingDate\", 120)AS tblItemMaster_PurchasingDate,\n"
                        . "	 tblItemMaster.\"InvoiceNo\" AS tblItemMaster_InvoiceNo,\n"
                        . "	 tblItemMaster.\"DCode\" AS tblItemMaster_DCode,\n"
                        . "	 tblItemMaster.\"ExtraCode\" AS tblItemMaster_ExtraCode\n"
                        . " FROM\n"
                        . "     tblFloor INNER JOIN  tblBuilding ON tblFloor.\"BuildingCode\" = tblBuilding.\"BuildingCode\"\n"
                        . "     INNER JOIN tblPRCompany ON tblFloor.\"Com_Code\" = tblPRCompany.\"Com_Code\"\n"
                        . "     INNER JOIN tblLocations ON tblFloor.\"FloorCode\" = tblLocations.\"FloorCode\"\n"
                        . "     INNER JOIN  tblItemMaster ON tblLocations.\"LocCode\" = tblItemMaster.\"LocCode\"     \n"
                        . "     INNER JOIN  tblSubCategory1 ON tblItemMaster.\"Sub_Cat1\" = tblSubCategory1.\"Sub_Cat1\"\n"
                        . "     INNER JOIN  tblItemCondition ON tblItemMaster.\"Condition\" = tblItemCondition.\"Condition\"\n"
                        . "     INNER JOIN  tblCategory ON tblSubCategory1.\"Code\" = tblCategory.\"Code\"\n"
                        . "     INNER JOIN  tblAssetCategory ON tblCategory.\"AssetCategoryCode\" = tblAssetCategory.\"AssetCategoryCode\"\n"
                        . "	 full JOIN tblDepartment ON tblItemMaster.\"DepartmentCode_Onwer\"=tblDepartment.\"DepartmentCode\"\n"
                        . "	 WHERE \n"
                        . "     tblItemMaster.\"ItemCode\" = '".$itemCode."' AND tblItemMaster.IsDiscard='0'";
						
		error_log($query,0);
		
		$stmt = sqlsrv_query($conn,$query);
		
		if($stmt === false){
			//echo "Error in query preparation/execution | Item Details.\n";
			//error_log("\nError in query preparation/execution in supplier query : ".print_r( sqlsrv_errors(), true),0);
			//die( print_r( sqlsrv_errors(), true));
		}
		
		$arr_resp = array();
		
		/* Retrieve Item Details as a PHP object and display the results.*/
		while($obj = sqlsrv_fetch_object($stmt)){
			
			$item_arr['ItemCode'] = $obj->tblItemMaster_ItemCode;
			$item_arr['Description'] = $obj->tblItemMaster_Description;
			$item_arr['Company'] = $obj->tblPRCompany_Com_Name;
			$item_arr['Building'] = $obj->tblBuilding_BuildingName;
			$item_arr['Floor'] = $obj->tblFloor_FloorName;
			$item_arr['Location'] = $obj->tblLocations_Description;
			$item_arr['AssetCategory'] = $obj->tblAssetCategory_AssetCategoryName;
			$item_arr['Category'] = $obj->tblCategory_Description;
			$item_arr['SubCategory'] = $obj->tblSubCategory1_Description;
			$item_arr['Quantity'] = $obj->tblItemMaster_Quantity;
			$item_arr['Condition'] = $obj->tblItemCondition_Condition;
			$item_arr['Supplier'] = $obj->tblItemMaster_SupName;
			$item_arr['Cost'] = $obj->tblItemMaster_Cost;
			$item_arr['image'] = $obj->tblItemMaster_Picture;
					   
			//$arr_resp["product"] = $item_arr;
			array_push($arr_resp,array('ItemCode'=> $obj->tblItemMaster_ItemCode,
									   'Description'=> $obj->tblItemMaster_Description,
									   'Company'=> $obj->tblPRCompany_Com_Name,
									   'Building'=> $obj->tblBuilding_BuildingName,
									   'Floor'=> $obj->tblFloor_FloorName,
									   'Location'=> $obj->tblLocations_Description,
									   'AssetCategory'=> $obj->tblAssetCategory_AssetCategoryName,
									   'Category'=> $obj->tblCategory_Description,
									   'SubCategory'=> $obj->tblSubCategory1_Description,
									   'Quantity'=> $obj->tblItemMaster_Quantity,
									   'Condition'=> $obj->tblItemCondition_Condition,
									   'Supplier'=> $obj->tblItemMaster_SupName,
									   'Cost'=> $obj->tblItemMaster_Cost,
									   'image'=> $obj->tblItemMaster_Picture));
		}
		
		/* Free supplier Statement */
		sqlsrv_free_stmt($stmt);
		
		}
		
		/** return json object **/
		header('Content-type: application/json');
		echo json_encode(array('product'=>$arr_resp));
		
		//error_log(print_r($megaArr,true),0);
		
		/* close connection resources. */
		sqlsrv_close($conn);
	}
	
	/**
	*
	**/
	function hasImageToSubCategory($subcat){
    $conn = local_connect();

    $hasImage = false;

    $query = "SELECT Picture FROM tblSubCategory1 WHERE Sub_Cat1 = (?)";
    $param = array($subcat);

    $stmt = sqlsrv_query($conn,$query,$param);

    if($stmt === false){
        error_log("Error in query preparation/execution in  check subcat picture : " . print_r(sqlsrv_errors(), true), 0);
        die(print_r(sqlsrv_error(), true));
    }

    if(sqlsrv_has_rows($stmt)){
        $image = null;
        while ($obj = sqlsrv_fetch_object($stmt)) {
            $image = $obj->Picture;
            //error_log("------------------".$image,0);
        }

        //error_log("Images : ".$image,0);
        if(isset($image) || $image != null || $image != "null"){
            //error_log("Images_1 : ".$image,0);
            $hasImage = true;
        }else{
            //error_log("Images_2 : ".$image,0);
        }
    }

return $hasImage;
}

?>