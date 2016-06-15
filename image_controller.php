<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-02-11
 * Time: 2:19 PM
 */
include 'db_connection/db_connector.php';
include 'readini_file.php';

//Define variables
define('ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);


/**
 * @param $itemCodeegory_img
 * @param $itemCode
 * @return string
 */
function saveImage($itemCodeegory_img, $itemCode){
    $img = base64_decode($itemCodeegory_img);
    $path = get_image_save_path();
    //error_log("Path : ".$path,0);
    $imgpath = $path . $itemCode . ".jpg";
    file_put_contents($imgpath, $img);
    //error_log("into save image function : ".$imgpath,0);
    return $imgpath;
}

/**
 * @param $itemCode
 * @return null|string
 */
function getImage($itemCode)
{
    $conn = local_connect();

    $query = 'SELECT Picture FROM tblitemCodeegory1 where Sub_Cat1 = (?)';
    $param = array($itemCode);

    $stmt = sqlsrv_query($conn, $query, $param);
    if ($stmt === false) {
        echo "Error in query preparation/execution | get itemCode image query.\n";
        error_log("Error in query preparation/execution in itemCode image query : " . print_r(sqlsrv_errors(), true), 0);
        die(print_r(sqlsrv_errors(), true));
    }

    $img_path = null;

    while ($obj = sqlsrv_fetch_object($stmt)) {
        $img_path = $obj->Picture;
    }

    $data = null;
    if ($img_path != null) {
        if (isset($img_path)) {
            $file = file_get_contents($img_path, true);
            $data = base64_encode($file);
            return $data;
        } else {
            return $data;
        }
    } else {
        return $data;
    }
}

?>