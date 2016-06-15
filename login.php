<?php

/*
 * Login Service
 * 14/06/2016 
 * NC
 * Texonic IT
 */
include 'db_connection/db_connector.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    userlogin($username, $password);
}

function userLogin($username, $password) {
    $conn = local_connect();
    $query = "select password from [tblUser] where username = '$username'";
    $stmt = sqlsrv_query($conn, $query);

    if ($stmt === false) {
        echo "Error in query preparation/execution | Login.\n";
        error_log("Error in login query : " . print_r(sqlsrv_errors(), true), 0);
        die(print_r(sqlsrv_errors(), true));
    } else if (sqlsrv_has_rows($stmt)) {
        $obj = sqlsrv_fetch_object($stmt);
        $request_password = $obj->password;
        if ($request_password == $password) {
            echo true;
        } else {
            echo false;
        }
    } else {
        echo"Account not exist.";
        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);
    }
}
