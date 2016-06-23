<?php
/**
 * Created by PhpStorm.
 * User: Latitude
 * Date: 1/14/2016
 * Time: 7:59 PM
 */

class Functions {


    /**
     * @param $date
     * @return string
     */
    public function displayDateFormat($date){
        error_log("date : ".$date);
        $tempDate = date_format(date_create($date),"d-m-Y");
        $dateObj = DateTime::createFromFormat('d-m-Y', $tempDate);

        return $dateObj->format('d M Y');
    }

}