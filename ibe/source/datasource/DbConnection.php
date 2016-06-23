<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-07-23
 * Time: 9:55 AM
 */

class DbConnection {

    private $connection = null;

    /**
     *
     */
    function connect(){

        try {
            $connect = new mysqli("localhost", "root", "TiVA!@#", "inhotsol_ibebeta");
            //Check connection
            if ($connect->connect_error) {
                die("Connection failed: " . $connect->connect_error);
            }

            return $connect;
        }catch(mysqli_sql_exception $e){
            error_log($e);
            echo $e;
            return null;
        }

    }


    /**
     * @return mysqli|null
     */
    public function openConnection(){

        if($this->connection == null){

            $this->connection = $this->connect();

            return $this->connection;

        }else{

            return $this->connection;

        }

    }

}
