<?php
/*
**	NC
**	 
*/	
include 'db_connection/db_connector.php';
include 'readini_file.php';

function generateCode($docType) {
    /* establish connection */
    $conn = local_connect();
    $default_Start_Id = 1000;
    $genCode = 0;

    $query = "SELECT AutoCode FROM tblDocCodeGenerator WHERE DocType=(?) ORDER BY AutoCode DESC";
    $param = array($docType);
    $stmt = sqlsrv_query($conn,$query,$param);

    if ($stmt === false) {
        echo "Error in Query -".$query;
        error_log("Error in Query : " .$query ."\n". print_r(sqlsrv_errors(), true), 0);
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($stmt)) {
        if ($obj = sqlsrv_fetch_object($stmt)) {
            $genCode = $obj->AutoCode;
        }
        $updateCode = $genCode+1;
        $i_query = "UPDATE [tblDocCodeGenerator] SET [AutoCode] = '".$updateCode."' WHERE [DocType] ='".$docType."'";
        $i_stmt = sqlsrv_query($conn,$i_query);
        echo $i_query;
        if ($i_stmt === false) {
            error_log("Error in Query : ".$i_query ."\n". print_r(sqlsrv_errors(), true), 0);
            die(print_r(sqlsrv_errors(), true));
        }


        /* Free main category statement */
        sqlsrv_free_stmt($i_stmt);
    } else {
        $ig_query = "Insert INTO tblDocCodeGenerator (AutoCode,AutoStartChr,DocType) VALUES (?,?,?)";
        $ig_param = array($default_Start_Id+1, $docType, $docType);
        //error_log($ig_query,0);

        $ig_stmt = sqlsrv_query($conn, $ig_query, $ig_param);
        //error_log($ig_stmt,0);

        if ($ig_stmt === false) {
            error_log("Error in Query : " .$i_query ."\n".print_r(sqlsrv_errors(), true), 0);
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
    return $docType.$genCode;
}



?>