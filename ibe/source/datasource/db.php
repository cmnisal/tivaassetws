<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 6/12/2015
 * Time: 12:52 PM
 */

class db {

    private $connection;
    private $selectdb;
    private $lastQuery;
    private $config;

    function __construct($config){

        $this->config = $config;

    }

    function __destruct(){

    }


    /**
     * @return Exception
     */
    public function openConnection(){

        try{

            if($this->config->connector == "mysql"){

                $this->connection = mysql_connect($this->config->hostName,$this->config->userName,$this->config->password);
                $this->selectdb =mysql_select_db($this->config->database);

            }else if($this->config->connector == "mysqli"){

                error_log($this->config->hostName,0);
                error_log($this->config->password,0);
                error_log($this->config->userName,0);
                error_log($this->config->database,0);

                $this->connection = mysqli_connect($this->config->hostName,$this->config->userName,$this->config->password);
                $this->selectdb = mysqli_select_db($this->connection,$this->config->database);

            }

        }catch (exception $e){
            return $e;
        }

    }

    /**
     * @return Exception
     */
    public function closeConnection(){

        try{

        if($this->config->connector == "mysql"){

            mysql_close($this->connection);

        }else if($this->config->connector == "mysqli"){

            mysqli_close($this->connection);

        }

        }catch (exception $e){
            return $e;
        }

    }


    /**
     * @param $string
     * @return string
     */
    public function escapeString($string){

        if($this->config->connector == "mysql"){

            return mysql_real_escape_string($string);

        }else if($this->config->connector == "mysqli"){

            return mysqli_real_escape_string($this->connection,$string);

        }

    }


    /**
     * @param $query
     * @return bool|Exception|mysqli_result|resource
     */
    public function query($query){

        $query = str_replace("}","",$query);
        $query = str_replace("{",$this->config->prefix,$query);

        try{

            if(empty($this->connection)){

                $this->openConnection();

                if($this->config->connector == "mysql"){

                    $this->lastQuery = mysql_query($this->escapeString($query));

                }else if($this->config->connector = "mysqli"){

                    $this->lastQuery = mysqli_query($this->connection,$this->escapeString($query));

                }

                $this->closeConnection();

                return $this->lastQuery;

            }else{

                if($this->config->connector == "mysql"){

                    $this->lastQuery = mysql_query($this->escapeString($query));

                }else if($this->config->connector = "mysqli"){

                    $this->lastQuery = mysqli_query($this->connection,$this->escapeString($query));

                }

                return $this->lastQuery;

            }

        }catch(exception $e){
            return $e;
        }

    }

    /**
     * @return mixed
     */
    public function lastQuery(){

        return $this->lastQuery;

    }


    /**
     * @return bool|Exception
     */
    public function pingServer(){

        try{

            if($this->config->connector == "mysql"){

                if(!mysql_ping($this->connection)){

                    return false;

                }else{

                    return true;

                }

            }else if($this->config->connector == "mysqli"){

                if(!mysqli_ping($this->connection)){

                    return false;

                }else{

                    return true;

                }

            }

        }catch(exception $e){
            return $e;
        }

    }


    /**
     * @param $result
     * @return bool|Exception
     */
    public function hasRow($result){

        try{

            if($this->config->connector == "mysql") {

                if(mysql_num_rows($result) > 0){

                    return true;

                }else{

                    return false;

                }

            }else if($this->config->connector == "mysqli"){

                if(mysqli_num_rows($result) > 0){

                    return true;

                }else{

                    return false;

                }

            }

        }catch (exception $e){
            return $e;
        }

    }


    /**
     * @param $result
     * @return Exception|int
     */
    public function countRow($result){

        try{

            if($this->config->connector == "mysql"){

                return mysql_num_rows($result);

            }else if($this->config->connector == "mysqli"){

                return mysqli_num_rows($result);

            }

        }catch(exception $e){

            return $e;
        }

    }


    /**
     * @param $result
     * @return array|null
     */
    public function fetchAssoc($result){

        try {
            if ($this->config->connector == "mysql") {

                return mysql_fetch_assoc($result);

            } else if ($this->config->connector == "mysqli") {

                return mysqli_fetch_assoc($result);

            }
        }catch (exception $e){
            return $e;
        }

    }

    /**
     * @param $result
     * @return array|Exception|null
     */
    public function fetchArray($result){
        try{
            if($this->config->connector == "mysql"){

                return mysql_fetch_array($result);

            }else if($this->config->connector == "mysqli"){

                return mysqli_fetch_array($result);

            }
        }catch(exception $e){
            return $e;
        }

    }
}