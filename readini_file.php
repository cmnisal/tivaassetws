<?php
    /**
     * Created by PhpStorm.
     * User: Yahampath
     * Date: 2015-02-11
     * Time: 12:52 PM
     */
    define('INIPATH',dirname(__FILE__).DIRECTORY_SEPARATOR."_config".DIRECTORY_SEPARATOR."_configer.ini");
    define('IMGPATH',dirname(__FILE__).DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR);
	

    /**
     * @return array
     */
    function read_ini(){
        $ini_array = parse_ini_file(INIPATH);
        //error_log(print_r($ini_array,true),0);
        //echo $ini_array['imagesavepath'];
        return $ini_array;
    }

    /**
     * @return string
     */
    function get_image_save_path(){
        $iniarr = read_ini();
        $path = $iniarr['imagesavepath'];
        //error_log("read_ini_img_path : ".$path,0);
        if(!isset($path)){
            $path = IMGPATH;
        }
        return $path;
    }

?>