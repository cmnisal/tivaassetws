<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-07-24
 * Time: 8:13 PM
 */
//include "../datasource/DbConnection.php";

class RoomAvailability
{

    /**
     * @param $arrivalDate
     * @param $departureDate
     * @param $roomType
     * @param $propertyCode
     * @return array
     */
    public function getAvailableRoomsWithRoomType($arrivalDate, $departureDate, $roomType, $propertyCode)
    {

        $connection = $this->getConnection();

        $depDate = new DateTime($departureDate);
        $depDate->modify('+1 day');

        $period = new DatePeriod(new DateTime($arrivalDate), new DateInterval('P1D'), $depDate);

        $availableRooms = array();
        $unAvailableRooms = array();

        foreach ($period as $date) {
            $arrivalMonth = $date->format('m');
            $arrivalDay = $date->format('d');
            $arrivalYear = $date->format('Y');

            $sql = "SELECT resYear,resMonth,roomTypeCode,R" . $arrivalDay . " FROM `tblroomforcast` where roomTypeCode='" . $roomType . "' and resYear='" . $arrivalYear . "' and resMonth='" . $arrivalMonth . "' AND `propertyCode` = '" . $propertyCode . "'";
            //error_log($sql);
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row["R" . $arrivalDay] > 0) {
                        $room["roomTypeCode"] = $row["roomTypeCode"];
                        $availableRooms[$room["roomTypeCode"]] = $room["roomTypeCode"];
                    } else {
                        $roomType = $row["roomTypeCode"];
                        $unAvailableRooms[$roomType] = $roomType;
                        break;
                    }
                }
            }
        }

        $resultArr = array_diff($availableRooms, $unAvailableRooms);

        $connection->close();

        return $resultArr;
    }

    /**
     * @param $arrivalDate
     * @param $departureDate
     * @param $roomType
     * @return array

    public function getAvailableRoomsWithRoomType($arrivalDate, $departureDate, $roomType)
     * {
     *
     * $connection = $this->getConnection();
     *
     * $arrival = explode('-', $arrivalDate);
     * $departure = explode('-', $departureDate);
     *
     * $arrivalYear = $arrival[0];
     * $arrivalMonth = $arrival[1];
     * $arrivalDay = $arrival[2];
     *
     * $departureYear = $departure[0];
     * $departureMonth = $departure[1];
     * $departureDay = $departure[2];
     *
     * $dateFrom = mktime(0, 0, 0, $arrivalMonth, $arrivalDay, $arrivalYear);
     * $dateTo = mktime(0, 0, 0, $departureMonth, $departureDay, $departureYear);
     *
     * $availableRooms = array();
     *
     * if ($dateTo >= $dateFrom) {
     * while ($dateFrom <= $dateTo) {
     * error_log(date('Y-m-d',$dateFrom));
     * $sql = "SELECT resYear,resMonth,roomTypeCode,R" . $arrivalDay . " FROM `tblroomforcast` where roomTypeCode='" . $roomType . "' and resYear='" . $arrivalYear . "' and resMonth='" . $arrivalMonth . "'";
     * $result = $connection->query($sql);
     * if ($result->num_rows > 0) {
     * while ($row = $result->fetch_assoc()) {`
     * if ($row["R" . $arrivalDay] > 0) {
     * $room["roomTypeCode"] = $row["roomTypeCode"];
     * $room["roomCount"] = $row["R" . $arrivalDay];
     *
     * array_push($availableRooms, $room);
     * }
     * }
     * }
     * $dateFrom += 86400; // add 24 hours;
     * }
     * }else{}
     *
     * $connection->close();
     *
     * return $availableRooms;
     *
     * }
     * */

    /**
     * @return mysqli|null
     */
    private function getConnection()
    {

        $connection = new DbConnection();
        return $connection->openConnection();

    }

    /**
     * @param $arrivalDate
     * @param $departureDate
     * @param $roomType
     * @param $propertyCode
     * @return null
     */
    public function getMinimumAvailableRoomCount($arrivalDate, $departureDate, $roomType, $propertyCode)
    {

        $connection = $this->getConnection();

        $depDate = new DateTime($departureDate);
        $depDate->modify('+1 day');

        $period = new DatePeriod(new DateTime($arrivalDate), new DateInterval('P1D'), $depDate);

        $availableRoomCount = array();

        foreach ($period as $date) {
            $arrivalMonth = $date->format('m');
            $arrivalDay = $date->format('d');
            $arrivalYear = $date->format('Y');

            $sql = "SELECT resYear,resMonth,roomTypeCode,R" . $arrivalDay . " FROM `tblroomforcast` where roomTypeCode='" . $roomType . "' and resYear='" . $arrivalYear . "' and resMonth='" . $arrivalMonth . "' AND `propertyCode` = '" . $propertyCode . "'";
            error_log("getAvailableRoomCount : ".$sql);
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row["R" . $arrivalDay] > 0) {
                        $count = $row["R" . $arrivalDay];
                        array_push($availableRoomCount, $count);
                    } else {
                        $count = $row["R" . $arrivalDay];
                        array_push($availableRoomCount, $count);
                        break;
                    }
                }
            }
        }

        $connection->close();

        if (!in_array(0, $availableRoomCount)) {
            return min($availableRoomCount);
        } else {
            return 0;
        }
    }


    /**
     * @param $arrivalDate
     * @param $departureDate
     * @param $roomType
     * @param $propertyCode
     * @return array|int
     */
    public function getAvailableRoomCounts($arrivalDate, $departureDate, $roomType, $propertyCode)
    {

        $connection = $this->getConnection();

        $depDate = new DateTime($departureDate);
        $depDate->modify('+1 day');

        $period = new DatePeriod(new DateTime($arrivalDate), new DateInterval('P1D'), $depDate);

        $availableRoomCount = array();

        foreach ($period as $date) {
            $arrivalMonth = $date->format('m');
            $arrivalDay = $date->format('d');
            $arrivalYear = $date->format('Y');

            $sql = "SELECT resYear,resMonth,roomTypeCode,R" . $arrivalDay . " FROM `tblroomforcast` where roomTypeCode='" . $roomType . "' and resYear='" . $arrivalYear . "' and resMonth='" . $arrivalMonth . "' AND `propertyCode` = '" . $propertyCode . "'";
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $count[$arrivalDay] = $row["R" . $arrivalDay];
                }
            }
        }

        array_push($availableRoomCount,$count);

        $connection->close();

        return $availableRoomCount;
    }


    /**
     * @param $arrivalDate
     * @param $departureDate
     * @return array

    public function getAvailableRoomsWithOutRoomType($arrivalDate, $departureDate)
     * {
     * $connection = $this->getConnection();
     *
     * $arrival = explode('-', $arrivalDate);
     * $departure = explode('-', $departureDate);
     *
     * $arrivalYear = $arrival[0];
     * $arrivalMonth = $arrival[1];
     * $arrivalDay = $arrival[2];
     *
     * $departureYear = $departure[0];
     * $departureMonth = $departure[1];
     * $departureDay = $departure[2];
     *
     * $dateFrom = mktime(1, 0, 0, $arrivalDay, $arrivalMonth, $arrivalYear);
     * $dateTo = mktime(1, 0, 0, $departureDay, $departureMonth, $departureYear);
     *
     * $availableRooms = array();
     *
     * if ($dateTo >= $dateFrom) {
     * while ($dateFrom < $dateTo) {
     *
     *
     * if ($arrivalMonth < 10) {
     * $arrivalMonth = substr($arrivalMonth, 1);
     * }
     *
     *
     * $sql = "SELECT roomTypeCode,R" . $arrivalDay . " FROM `tblroomforcast` where resYear='" . $arrivalYear . "' and resMonth='" . $arrivalMonth . "' GROUP BY roomTypeCode";
     * $result = $connection->query($sql);
     * if ($result->num_rows > 0) {
     * while ($row = $result->fetch_assoc()) {
     * if ($row["R" . $arrivalDay] > 0) {
     * $room["roomTypeCode"] = $row["roomTypeCode"];
     * $room["roomCount"] = $row["R" . $arrivalDay];
     *
     * array_push($availableRooms, $room);
     * }
     * }
     * }
     * $dateFrom += 86400; // add 24 hours;
     * }
     * }
     *
     * $connection->close();
     *
     * return $availableRooms;
     *
     * }
     * */


    /**
     * @param $arrivalDate
     * @param $departureDate
     * @return array
     */
    public function getAvailableRoomsWithOutRoomType($arrivalDate, $departureDate)
    {

        $connection = $this->getConnection();

        $depDate = new DateTime($departureDate);
        $depDate->modify('+1 day');

        $period = new DatePeriod(new DateTime($arrivalDate), new DateInterval('P1D'), $depDate);

        $availableRooms = array();

        foreach ($period as $date) {
            $arrivalMonth = $date->format('m');
            $arrivalDay = $date->format('d');
            $arrivalYear = $date->format('Y');

            $sql = "SELECT resYear,resMonth,roomTypeCode,R" . $arrivalDay . " FROM `tblroomforcast` where resYear='" . $arrivalYear . "' and resMonth='" . $arrivalMonth . "'";
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row["R" . $arrivalDay] > 0) {
                        $room["roomTypeCode"] = $row["roomTypeCode"];
                        $room["roomCount"] = $row["R" . $arrivalDay];

                        array_push($availableRooms, $room);
                    }
                }
            }

        }

        $connection->close();

        return $availableRooms;

    }


    /**
     * @param $reservedRooms
     */
    public function updateRoomAvailability($reservedRooms)
    {

        $connection = $this->getConnection();

        foreach ($reservedRooms as $rooms => $room) {
            $arrivalDate = $room["arrivalDate"];
            $depatureDate = $room["depatureDate"];
            $dayRange = $this->createDateRangeArray($arrivalDate, $depatureDate);

            foreach ($dayRange as $date) {

                $day = explode('-', $date);

                $Year = $day[0];
                $Month = $day[1];
                $Day = $day[2];

                $sql = "UPDATE tblroomforcast SET R." . $Day . ". = (R." . $Day . ". - 1) WHERE resYear =  '$Year' AND resMonth =  '$Month' AND roomTypeCode =  ''";
                $connection->query($sql);

            }
        }
        $connection->close();

    }


    /**
     * @param $strDateFrom
     * @param $strDateTo
     * @return array
     */
    function createDateRangeArray($strDateFrom, $strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange = array();

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }
}


$rooms = new RoomAvailability();




