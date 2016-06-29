<?php
	/**
	 * @return false|null|resource
	 */
	 
	function local_connect(){
		$connection = null;
		try{
		
			$serverName = "192.168.0.24";
			$connectionInfo = array("Database"=>"AssetDB", "UID"=>"sa", "PWD"=>"saproadmin#67");
			//connect to the database
			$connection = sqlsrv_connect($serverName, $connectionInfo);
		
			if($connection){
				//echo "Connection established<br>";
			}else{
				echo "Connection could not be established.<br/>";
				die(print_r(sqlsrv_errors(),true));
			}

		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}

		return $connection;
	}

	/**
	 * @return false|null|resource
	 */
	/*
	function remote_connect(){
		$connection = null;
		try{
			$serverName = "192.168.0.1/Lanten_2015-08-04";
			$connectionInfo = array("Database"=>"Lanten_2015-08-04", "UID"=>"sa", "PWD"=>"TiVA!@#");
			//connect to the database
			$connection = sqlsrv_connect($serverName,$connectionInfo);

			if($connection){
			}else{
				echo "Connection could not be established.<br/>";
				die(print_r(sqlsrv_errors(),true));
			}
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
		return $connection;
	}*/


?>
