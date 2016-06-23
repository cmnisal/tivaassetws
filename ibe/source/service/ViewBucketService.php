<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-07-29
 * Time: 12:05 PM
 */

class ViewBucketService {

    /**
     * @param $startDate
     * @param $endDate
     * @return float|int
     */
    public function getDayCount($startDate,$endDate){

        $startTimeStamp = strtotime($startDate);
        $endTimeStamp = strtotime($endDate);
        $timeDiff = abs($endTimeStamp - $startTimeStamp);
        $numberDays = $timeDiff/86400;  // 86400 seconds in one day

        // convert to integer
        $numberDays = intval($numberDays);
        if($numberDays == 0){
            $numberDays = 1;
        }

        return $numberDays;
    }

}

/**
$a = new ViewBucketService();
echo $a->getDayCount("2015-08-19","2015-08-21");
 * **/
