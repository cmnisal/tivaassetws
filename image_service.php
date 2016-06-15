<?php
    /**
     * Created by PhpStorm.
     * User: Yahampath
     * Date: 2015-02-11
     * Time: 3:41 PM
     */
    include 'image_controller.php';

    // array for JSON response
    $response = array();

    if(isset($_POST['subcat'])) {
        $subcat = $_POST['subcat'];
        //error_log("SubCat : ".$subcat,0);
        $megaarr = array();
        if (isset($subcat)) {
            $image = getImage($subcat);
            //
            $imgarr = array(
                    'image'=>$image
            );

            $imar = array();
            array_push($imar,$imgarr);
            $megaarr['subcat'] = $imar;
            header('Content-type: application/json');
            echo json_encode($megaarr);
        }else{
            $response["success"] = 0;
            $response["message"] = "Subcategory param not set.";
            // echoing JSON response
            header('Content-type: application/json');
            echo json_encode($response);
        }
    }else{
        error_log("subcat failed",0);
        $response["success"] = 0;
        $response["message"] = "Service params are not valid.";
        // echoing JSON response
        header('Content-type: application/json');
        echo json_encode($response);
    }
?>