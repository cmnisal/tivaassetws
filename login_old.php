<?php 
/*
** NC
** 08/06/2016 15:00
*/	
	include 'db_connection/db_connector.php';
	
	if (isset($_POST['login']) && intval($_POST['login']) == 1) {
		
		$username = $_POST['username'];
		$password = $_POST['password'];
		userlogin($username, $password);
	}
	
function userLogin($username, $password){
		$conn = local_connect();
		$query = "select * from tblUser where username = '$username' AND password = '$password'";
		$stmt = sqlsrv_query($conn,$query);
		
		if($stmt === false){
			echo "Error in query preparation/execution | Login.\n";
			error_log("Error in query preparation/execution in login query : ".print_r( sqlsrv_errors(), true),0);
			die( print_r( sqlsrv_errors(), true));
			
		}else if(sqlsrv_has_rows($stmt)){
			
			$obj = sqlsrv_fetch_object($stmt);
			$request_password = $obj->password;
			if($request_password==$password){
				
				$response["login"] = true;
			}else{
				
				$response["login"] = false;
			}
		}else{
				$response["login"] = "Account not exist.";			
		}
		
        // echoing JSON response
        header('Content-type: application/json');
        echo json_encode($response);
		
		sqlsrv_free_stmt($stmt);
		sqlsrv_close($conn);		
				
		}
	
?>