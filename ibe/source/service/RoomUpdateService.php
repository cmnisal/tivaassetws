<?php
/**
 * Created by PhpStorm.
 * User: lahiruyahampath
 * Date: 11/20/2015
 * Time: 1:09 PM
 */

include "RoomAvailability.php";

class RoomUpdateService {


    /**
     * @return mysqli|null
     */
    private function getConnection()
    {

        $connection = new DbConnection();
        return $connection->openConnection();

    }

    /**
     * @param $reservationList
     * @param $propertyCode
     * @return bool
     */
    public function checkRoomAvailability($reservationList,$propertyCode){
        if(count($reservationList) > 0){
            $roomAvailability = new RoomAvailability();
            foreach($reservationList as $reservations){


                $roomCount = $roomAvailability->getMinimumAvailableRoomCount($reservations['arrival_date'],$reservations['departure_date'],$reservations['roomTypeCode'],$propertyCode);

                if($roomCount == 0){
                    return false;
                    break;
                }
            }

            return true;
        }
    }


    /**
     * @param $reservationList
     * @param $propertyCode
     * @return bool
     */
    public function checkRoomAvailabilityFinal($reservationList,$propertyCode){

        if(count($reservationList) > 0){
            $roomAvailability = new RoomAvailability();
            foreach ($reservationList as $bookedRooms => $rooms) {
                $aDate = explode("/", $bookedRooms)[0];
                $dDate = explode("/", $bookedRooms)[1];
                $rType = explode("/", $bookedRooms)[2];
                $bkRooms = $rooms;

                $roomCount = $roomAvailability->getMinimumAvailableRoomCount($aDate,$dDate,$rType,$propertyCode);
                if(($roomCount-$bkRooms) < 0){
                    return false;
                    break;
                }
            }
            return true;
        }

    }

    /**
     * @param $reservedRooms
     */
    public function updateRoomAvailability($reservedRooms, $propertyCode, $updateStatus)
    {

        $connection = $this->getConnection();


        foreach ($reservedRooms as $rooms) {
            $roomTypeCode = $rooms['roomTypeCode'];
            $arrivalDate = $rooms["arrival_date"];
            $departureDate = $rooms["departure_date"];
            $dayRange = $this->createDateRangeArray($arrivalDate, $departureDate);

            foreach ($dayRange as $date) {
                $day = explode('-', $date);

                $Year = $day[0];
                $Month = $day[1];
                $Day = $day[2];

                if ($updateStatus == "M") {
                    $sql = "UPDATE tblroomforcast SET R$Day = (R$Day- 1) WHERE resYear =  '$Year' AND resMonth =  '$Month' AND roomTypeCode =  '$roomTypeCode' AND propertyCode = '$propertyCode'";
                    error_log("updateRoomAvailability-(M) : " . $sql);
                    $connection->query($sql);
                } else if ($updateStatus == "A") {
                    $sql = "UPDATE tblroomforcast SET R$Day = (R$Day+ 1) WHERE resYear =  '$Year' AND resMonth =  '$Month' AND roomTypeCode =  '$roomTypeCode' AND propertyCode = '$propertyCode'";
                    error_log("updateRoomAvailability-(A) : " . $sql);
                    $connection->query($sql);
                }

            }
        }


        $connection->close();

    }


    /**
     * @param $reservedRooms
     * @param $propertyCode
     * @param $updateStatus
     */
    public function updateAndConfirmRoomAvailability($reservedRooms, $propertyCode, $updateStatus)
    {
        $connection = $this->getConnection();

        //error_log(print_r($reservedRooms,true));

        foreach ($reservedRooms as $rooms) {
            $roomTypeCode = $rooms['roomTypeCode'];
            $arrivalDate = $rooms["arrivalDate"];
            $departureDate = $rooms["depatureDate"];
            $dayRange = $this->createDateRangeArray($arrivalDate, $departureDate);

            foreach ($dayRange as $date) {
                $day = explode('-', $date);

                $Year = $day[0];
                $Month = $day[1];
                $Day = $day[2];

                if ($updateStatus == "M") {
                    $sql = "UPDATE tblroomforcast SET R$Day = (R$Day- 1) WHERE resYear =  '$Year' AND resMonth =  '$Month' AND roomTypeCode =  '$roomTypeCode' AND propertyCode = '$propertyCode'";
                    error_log("updateRoomAvailability-(M) : " . $sql);
                    $connection->query($sql);
                } else if ($updateStatus == "A") {
                    $sql = "UPDATE tblroomforcast SET R$Day = (R$Day+ 1) WHERE resYear =  '$Year' AND resMonth =  '$Month' AND roomTypeCode =  '$roomTypeCode' AND propertyCode = '$propertyCode'";
                    error_log("updateRoomAvailability-(A) : " . $sql);
                    $connection->query($sql);
                }
            }
        }
        $connection->close();
    }


    /**
     * @param $strDateFrom
     * @param $strDateTo
     * @return array
     */
    function createDateRangeArray($strDateFrom,$strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }
}