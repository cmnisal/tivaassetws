<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 6/12/2015
 * Time: 11:35 AM
 */



class Config {

    public $hostName;
    public $userName;
    public $password;
    public $database;
    public $prefix;
    public $connector;


    /**
     * @param null $hostname
     * @param null $userName
     * @param null $password
     * @param null $database
     * @param null $prefix
     * @param null $connector
     */
    function __construct($hostname = NULL,$userName = NULL,$password = NULL,$database =NULL,$prefix = NULL,$connector = NULL){

        $this->hostName = !empty($hostname) ? $hostname : "";
        $this->userName = !empty($userName) ? $userName : "";
        $this->password = !empty($password) ? $password : "";
        $this->database = !empty($database) ? $database : "";
        $this->prefix = !empty($prefix) ? $prefix : "";
        $this->connector = !empty($connector) ? $connector : "mysqli";

    }

    function __destruct(){

    }
}