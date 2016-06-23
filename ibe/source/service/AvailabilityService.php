<?php

/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-07-23
 * Time: 10:03 AM
 */
class AvailabilityService
{

    /**
     * @return bool|mysqli_result
     */
    public function getAvailableRoomDetails()
    {

        $connection = $this->getConnection();
        $sql = "SELECT a.idtblRoomType,a.rtCode,a.roomType,a.roomDescription,a.numberOfRooms,b.idtblRoomTypeImage,b.idtblRoomTypeImage,b.imagePath FROM tblroomtype a INNER JOIN tblroomtypeimage b ON a.rtCode = b.rtCode";
        $result = $connection->query($sql);
        $connection->close();
        return $result;
    }

    /**
     * @return mysqli|null
     */
    private function getConnection()
    {

        $connection = new DbConnection();
        if ($connection != null) {
            return $connection->openConnection();
        } else {
            // redirect to error page
        }

    }

    /**
     * @param $roomType
     * @return array
     */
    public function getAvailableRoomDetailsByRoomType($roomType, $propertyCode)
    {

        $connection = $this->getConnection();
        $sql = "SELECT a.idtblRoomType,a.rtCode,a.roomType,a.roomDescription,a.numberOfRooms,a.paxCount,a.extrapaxCount,b.idtblRoomTypeImage,b.idtblRoomTypeImage,b.imagePath FROM tblroomtype a INNER JOIN tblroomtypeimage b ON a.rtCode = b.rtCode WHERE a.rtCode = '$roomType' AND a.propertyCode = '$propertyCode'";
        //echo $sql."\n";
        $result = $connection->query($sql);

        $roomTypes = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $room["idtblRoomType"] = $row["idtblRoomType"];
                $room["rtCode"] = $row["rtCode"];
                $room["roomType"] = $row["roomType"];
                $room["roomDescription"] = $row["roomDescription"];
                $room["numberOfRooms"] = $row["numberOfRooms"];
                $room["idtblRoomTypeImage"] = $row["idtblRoomTypeImage"];
                $room["imagePath"] = $row["imagePath"];
                $room["paxCount"] = $row["paxCount"];
                $room["extrapaxCount"] = $row["extrapaxCount"];

                array_push($roomTypes, $room);

            }
        }

        $connection->close();

        return $roomTypes;
    }


    /**
     * @return bool|mysqli_result
     **/
    public function getCurrencyTypes()
    {
        $connection = $this->getConnection();
        $sql = "SELECT currencyName,currencyCode FROM tblcountry ORDER BY currencyName";
        $result = $connection->query($sql);
        $connection->close();

        return $result;

    }


    /**
     * @param $arrival_date
     * @param $departure_date
     * @return array
     */
    public function getAvailableContract($arrival_date, $departure_date, $propertyCode)
    {
        //$contractArray[] = '';

        $connection = $this->getConnection();
        //
        $sql = "SELECT * FROM `tblcontract` where (`contractStartDate` <= '" . $arrival_date . "' AND `contractEndDate` >= '" . $arrival_date . "' AND `propertyCode` = '" . $propertyCode . "') OR (`contractStartDate`  <='" . $departure_date . "' AND `contractEndDate`  >='" . $departure_date . "' AND `propertyCode` = '" . $propertyCode . "')";
        //error_log('getAvailableContract : ' . $sql);
        $c_result = $connection->query($sql);
        if ($c_result->num_rows > 0) {
            while ($row = $c_result->fetch_assoc()) {
                $contractArray["contractCode"] = $row["contractCode"];
                $contractArray["startDate"] = $row["contractStartDate"];
                $contractArray["endDate"] = $row["contractEndDate"];
                $contractArray["mappedContractCode"] = $row["mapContarct"];
            }
        }// if contract didn't found within date range, then find it from arraival date as decsending and then get the mapped contract
        else {
            $sql = "SELECT * FROM `tblcontract` where `contractStartDate` <= '" . $arrival_date . "' AND `propertyCode` = '" . $propertyCode . "' ORDER BY contractStartDate DESC LIMIT 1";
            //error_log('getAvailableContract : ' . $sql);
            $m_result = $connection->query($sql);

            while ($row = $m_result->fetch_assoc()) {
                $contractArray["contractCode"] = $row["contractCode"];
                $contractArray["startDate"] = $row["contractStartDate"];
                $contractArray["endDate"] = $row["contractEndDate"];
                $contractArray["mappedContractCode"] = $row["mapContarct"];
            }

        }

        $connection->close();

        //error_log(print_r($contractArray,true));

        return $contractArray;
    }

    /**
     * @param $arrival_date
     * @param $departure_date
     * @param $propertyCode
     */
    public function getContractListForBilling($arrival_date, $departure_date, $propertyCode)
    {

        $connection = $this->getConnection();

        $sql = "SELECT * FROM `tblcontract` where (`contractStartDate` <= '" . $arrival_date . "' AND `contractEndDate` >= '" . $arrival_date . "' AND `propertyCode` = '" . $propertyCode . "') OR (`contractStartDate`  <='" . $departure_date . "' AND `contractEndDate`  >='" . $departure_date . "' AND `propertyCode` = '" . $propertyCode . "')";
        //error_log('getContractListForBilling : ' . $sql);
        $c_result = $connection->query($sql);
        if ($c_result->num_rows > 0) {
            while ($row = $c_result->fetch_assoc()) {
                $contractArray["contractCode"] = $row["contractCode"];
                $contractArray["startDate"] = $row["contractStartDate"];
                $contractArray["endDate"] = $row["contractEndDate"];
                $contractArray["mappedContractCode"] = $row["mapContarct"];
                $contracts[$row["contractCode"]] = $contractArray;
            }
        }// if contract didn't found within date range, then find it from arraival date as decsending and then get the mapped contract
        else {
            $sql = "SELECT * FROM `tblcontract` where `contractStartDate` <= '" . $arrival_date . "' AND `propertyCode` = '" . $propertyCode . "' ORDER BY contractStartDate DESC LIMIT 1";
            //error_log('getContractListForBilling : ' . $sql);
            $m_result = $connection->query($sql);

            while ($row = $m_result->fetch_assoc()) {
                $contractArray["contractCode"] = $row["contractCode"];
                $contractArray["startDate"] = $row["contractStartDate"];
                $contractArray["endDate"] = $row["contractEndDate"];
                $contractArray["mappedContractCode"] = $row["mapContarct"];
                $contracts[$row["contractCode"]] = $contractArray;
            }

        }

        $connection->close();

        return $contracts;

    }

    /**
     * @param $contractCode
     * @return int|string
     */
    public function getRatesByContract($contractCode, $contractMappedCode, $adultCount, $propertyCode)
    {
        $rates = array();
        $connection = $this->getConnection();
        $sql = "SELECT * FROM `tblrates` WHERE contractCode='" . $contractCode . "' AND roomCapacity='" . $adultCount . "' AND propertyCode = '" . $propertyCode . "' GROUP BY roomTypeCode";
        error_log('getRatesByContract_1 : ' . $sql);
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array()) {
                $rate["idtblRates"] = $row["idtblRates"];
                $rate["rateCode"] = $row["rateCode"];
                $rate["toCode"] = $row["toCode"];
                $rate["marketCode"] = $row["marketCode"];
                $rate["contractCode"] = $row["contractCode"];
                $rate["roomTypeCode"] = $row["roomTypeCode"];
                $rate["mealPlane"] = $row["mealPlane"];
                $rate["rate"] = $row["rate"];
                $rate["currencyCode"] = $row["currencyCode"];

                //error_log($rate["idtblRates"]." - ".$rate["rateCode"]." - ".$rate["roomTypeCode"]." - ".$rate["currencyCode"]." - ".$rate["rate"]);

                array_push($rates, $row);
            }

        } else {
            $sql = "SELECT * FROM `tblrates` where contractCode='" . $contractMappedCode . "' AND roomCapacity='" . $adultCount . "' AND propertyCode = '" . $propertyCode . "' GROUP BY roomTypeCode";
            error_log('getRatesByContract_2 : ' . $sql);
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rate["idtblRates"] = $row["idtblRates"];
                    $rate["rateCode"] = $row["rateCode"];
                    $rate["toCode"] = $row["toCode"];
                    $rate["marketCode"] = $row["marketCode"];
                    $rate["contractCode"] = $row["contractCode"];
                    $rate["roomTypeCode"] = $row["roomTypeCode"];
                    $rate["mealPlane"] = $row["mealPlane"];
                    $rate["rate"] = $row["rate"];
                    $rate["currencyCode"] = $row["currencyCode"];

                    //error_log($rate["rateCode"]." - ".$rate["roomTypeCode"]." - ".$rate["currencyCode"]." - ".$rate["rate"]);

                    array_push($rates, $row);
                }
            } else {
                return 0;
            }
        }

        $connection->close();

        return $rates;
    }

    /**
     * @param $contractList
     * @param $adultCount
     * @param $propertyCode
     * @return mixed
     */
    public function getContractWiseRate($contractList, $adultCount, $propertyCode)
    {

        $connection = $this->getConnection();

        if ($connection != null) {

            foreach ($contractList as $contract) {
                $contractCode = $contract["contractCode"];

                $sql = "SELECT * FROM `tblrates` WHERE contractCode='" . $contractCode . "' AND roomCapacity='" . $adultCount . "' AND propertyCode = '" . $propertyCode . "' GROUP BY roomTypeCode";
                //error_log("getRatesWiseContract : ".$sql);
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $rate["idtblRates"] = $row["idtblRates"];
                        $rate["rateCode"] = $row["rateCode"];
                        $rate["toCode"] = $row["toCode"];
                        $rate["marketCode"] = $row["marketCode"];
                        $rate["contractCode"] = $row["contractCode"];
                        $rate["roomTypeCode"] = $row["roomTypeCode"];
                        $rate["mealPlane"] = $row["mealPlane"];
                        $rate["rate"] = $row["rate"];
                        $rate["currencyCode"] = $row["currencyCode"];

                        $rates[$i] = $rate;
                        $i++;
                    }
                } else {
                    return null;
                    //no rates found for selected contract rage
                }
            }

            return $rates;

        } else {

            return null;
            //error page
        }

    }

    /**
     * @param $contractCode
     * @param $adultCount
     * @param $propertyCode
     * @return null
     */
    public function getRatesByContractCode($contractCode, $adultCount, $roomTypeCode, $propertyCode)
    {
        $connection = $this->getConnection();

        if ($connection != null) {

            $sql = "SELECT * FROM `tblrates` WHERE contractCode='" . $contractCode . "' AND roomCapacity='" . $adultCount . "' AND propertyCode = '" . $propertyCode . "' AND roomTypeCode = '" . $roomTypeCode . "' ORDER BY roomTypeCode,mealPlane";
            //error_log("getRatesByContractCode : " . $sql);
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $rate["idtblRates"] = $row["idtblRates"];
                    $rate["rateCode"] = $row["rateCode"];
                    $rate["toCode"] = $row["toCode"];
                    $rate["marketCode"] = $row["marketCode"];
                    $rate["contractCode"] = $row["contractCode"];
                    $rate["roomTypeCode"] = $row["roomTypeCode"];
                    $rate["roomCapacity"] = $row["roomCapacity"];
                    $rate["mealPlane"] = $row["mealPlane"];
                    $rate["rate"] = $row["rate"];
                    $rate["currencyCode"] = $row["currencyCode"];

                    $rates[$i] = $rate;
                    $i++;
                }
            } else {
                return null;
                //no rates found for selected contract rage
            }
        }

        return $rates;
    }


    public function getRatesByContractByRoomType($contractCode, $contractMappedCode, $roomType, $adultCount)
    {
        $rates = array();
        $connection = $this->getConnection();

        $sql = "SELECT * FROM `tblrates` where contractCode='" . $contractCode . "' AND roomTypeCode = '" . $roomType . "' AND roomCapacity ='" . $adultCount . "'  ORDER BY rate ASC";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rate["idtblRates"] = $row["idtblRates"];
                $rate["rateCode"] = $row["rateCode"];
                $rate["toCode"] = $row["toCode"];
                $rate["marketCode"] = $row["marketCode"];
                $rate["contractCode"] = $row["contractCode"];
                $rate["roomTypeCode"] = $row["roomTypeCode"];
                $rate["mealPlane"] = $row["mealPlane"];
                $rate["rate"] = $row["rate"];
                $rate["currencyCode"] = $row["currencyCode"];
                //echo $rate["rateCode"]." - ".$rate["roomTypeCode"]." - ".$rate["currencyCode"]." - ".$rate["rate"]."<br>";

                array_push($rates, $rate);
            }
        } else {
            $sql = "SELECT * FROM `tblrates` where contractCode='" . $contractMappedCode . "' AND roomTypeCode = '" . $roomType . "' ORDER BY rate ASC";
            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rate["idtblRates"] = $row["idtblRates"];
                    $rate["rateCode"] = $row["rateCode"];
                    $rate["toCode"] = $row["toCode"];
                    $rate["marketCode"] = $row["marketCode"];
                    $rate["contractCode"] = $row["contractCode"];
                    $rate["roomTypeCode"] = $row["roomTypeCode"];
                    $rate["mealPlane"] = $row["mealPlane"];
                    $rate["rate"] = $row["rate"];
                    $rate["currencyCode"] = $row["currencyCode"];

                    //echo $rate["rateCode"]." - ".$rate["roomTypeCode"]." - ".$rate["currencyCode"]." - ".$rate["rate"]."<br>";

                    array_push($rates, $rate);
                }
            }
        }
        $connection->close();
        return $rates;
    }

    /**
     * @param $contractCode
     * @param $contractMappedCode
     * @param $roomType
     * @return array
     */
    //follow statement not use since 2015-11-26.
    /**
     * @param $arrivalDate
     * @param $departureDate
     * @param $adultCount
     * @param $roomTypeCode
     * @param $propertyCode
     * @return array|int|string
     */
    public function getLowestRatesAverages($arrivalDate, $departureDate, $adultCount, $roomTypeCode, $propertyCode)
    {

        $totalDateCount = 0;

        $contractList = $this->getAvailableContractList($arrivalDate, $departureDate, "PRT1000");

        if (count($contractList) != 1) {

            foreach ($contractList as $contracts => $contract) {
                if (strtotime($contract['startDate']) <= strtotime($arrivalDate) && strtotime($contract['endDate']) <= strtotime($departureDate)) {
                    $DateCount = $this->getDateDifferentCount($arrivalDate, $contract['endDate']);
                    if ($DateCount != 0) {
                        $contractDateCount[$contract['contractCode']] = $DateCount;
                        $totalDateCount += $DateCount;
                    } else {
                        $DateCount = 1;
                        $totalDateCount += $DateCount;
                        $contractDateCount[$contract['contractCode']] = $DateCount;
                    }

                    //echo $contract['contractCode'] . "-" . $DateCount."<br>";
                } else {
                    if (strtotime($contract['startDate']) <= strtotime($departureDate)) {
                        $DateCount = $this->getDateDifferentCount($contract['startDate'], $departureDate);
                        $contractDateCount[$contract['contractCode']] = $DateCount;
                        $totalDateCount += $DateCount;
                        //echo $contract['contractCode'] . "-" . $DateCount."<br>";
                    }
                }
            }

            $ratesList = $this->getContractAndRatesForCalcRateAverage($arrivalDate, $departureDate, $adultCount, $roomTypeCode, $propertyCode);

            foreach ($ratesList as $rates => $rate) {
                if (!isset($ratesArray)) {
                    $nextCurrency = $rate['currencyCode'];
                    $rooms['contractCode'] = $rate['contractCode'];
                    $rooms['roomTypeCode'] = $rate['roomTypeCode'];
                    $rooms['mealPlane'] = $rate['mealPlane'];
                    $rooms['roomCapacity'] = $rate['roomCapacity'];
                    $rooms['rate'] = round($rate['rate'],0,PHP_ROUND_HALF_UP);
                    $rooms['totalDates'] = $totalDateCount;
                    $rooms['currencyCode'] = $rate['currencyCode'];

                    $rooms['averageRate'] = round($contractDateCount[$rate['contractCode']] * $rate['rate'],0,PHP_ROUND_HALF_UP);

                    $ratesArray[$rate['mealPlane']] = $rooms;

                } else {

                    if (array_key_exists($rate['mealPlane'], $ratesArray)) {

                        $nextCurrency = $rate['currencyCode'];
                        $currentAvr = $ratesArray[$rate['mealPlane']]["averageRate"];
                        $nextAvr = $contractDateCount[$rate['contractCode']] * $rate['rate'];
                        $avr = round((($currentAvr+$nextAvr)/$totalDateCount),0,PHP_ROUND_HALF_UP);
                        //$totalAvr = round((($currentAvr+$nextAvr)/$totalDateCount)*$totalDateCount,0,PHP_ROUND_HALF_UP);
                        $ratesArray[$rate['mealPlane']]["averageRate"] = $avr;

                    } else {

                        $nextCurrency = $rate['currencyCode'];
                        $rooms['contractCode'] = $rate['contractCode'];
                        $rooms['roomTypeCode'] = $rate['roomTypeCode'];
                        $rooms['mealPlane'] = $rate['mealPlane'];
                        $rooms['roomCapacity'] = $rate['roomCapacity'];
                        $rooms['rate'] = $rate['rate'];
                        $rooms['totalDates'] = $totalDateCount;
                        $rooms['averageRate'] = round($contractDateCount[$rate['contractCode']] * $rate['rate'],0,PHP_ROUND_HALF_UP);
                        $rooms['currencyCode'] = $rate['currencyCode'];
                        $ratesArray[$rate['mealPlane']] = $rooms;

                    }
                }
            }

            foreach($ratesArray as $rates){
                $lowestRate[$rates['mealPlane'] . "-" . $rates['currencyCode']] = $rates['averageRate'];
            }

            //echo print_r($lowestRate)."<br>";

        } else {

            $totalDateCount = $this->getDateDifferentCount($arrivalDate, $departureDate);
            //error_log($totalDateCount);
            if($totalDateCount == 0){
                $totalDateCount = 1;
            }

            $ratesList = $this->getContractAndRatesForCalcRateAverage($arrivalDate, $departureDate, $adultCount, $roomTypeCode, $propertyCode);

            if (isset($ratesList)) {
                foreach ($ratesList as $rates => $rate) {

                    $rooms['roomTypeCode'] = $rate['roomTypeCode'];
                    $rooms['mealPlane'] = $rate['mealPlane'];
                    $rooms['totalDates'] = $totalDateCount;
                    $rooms['roomCapacity'] = $rate['roomCapacity'];
                    $roomRate = $rate['rate'];
                    $rooms['averageRate'] = ($roomRate * $totalDateCount) / $totalDateCount;
                    $rooms['currencyCode'] = $rate['currencyCode'];
                    $nextCurrency = $rate['currencyCode'];

                    $lowestRate[$rooms['mealPlane'] . "-" . $rooms['currencyCode']] = $rooms['averageRate'];

                }
            } else {
                // redirect to error page.
            }

        }

        return min($lowestRate) . "-" . $nextCurrency;
    }
    //----- not use function finish.

    /**
     * @param $arrival_date
     * @param $departure_date
     * @param $propertyCode
     * @return mixed
     */
    public function getAvailableContractList($arrival_date, $departure_date, $propertyCode)
    {
        $connection = $this->getConnection();
        //
        $sql = "SELECT * FROM `tblcontract` where (`contractStartDate` <= '" . $arrival_date . "' AND `contractEndDate` >= '" . $arrival_date . "' AND `propertyCode` = '" . $propertyCode . "') OR (`contractStartDate`  <='" . $departure_date . "' AND `contractEndDate`  >='" . $departure_date . "' AND `propertyCode` = '" . $propertyCode . "')";
        //error_log('getAvailableContract : ' . $sql);
        $c_result = $connection->query($sql);
        if ($c_result->num_rows > 0) {
            while ($row = $c_result->fetch_assoc()) {
                $contractArray["contractCode"] = $row["contractCode"];
                $contractArray["startDate"] = $row["contractStartDate"];
                $contractArray["endDate"] = $row["contractEndDate"];
                $contractArray["mappedContractCode"] = $row["mapContarct"];

                $contractList[$row["contractCode"]] = $contractArray;

            }
        }// if contract didn't found within date range, then find it from arraival date as decsending and then get the mapped contract
        else {
            $sql = "SELECT * FROM `tblcontract` where `contractStartDate` <= '" . $arrival_date . "' AND `propertyCode` = '" . $propertyCode . "' ORDER BY contractStartDate DESC LIMIT 1";
            //error_log('getAvailableContract : ' . $sql);
            $m_result = $connection->query($sql);

            while ($row = $m_result->fetch_assoc()) {
                $contractArray["contractCode"] = $row["contractCode"];
                $contractArray["startDate"] = $row["contractStartDate"];
                $contractArray["endDate"] = $row["contractEndDate"];
                $contractArray["mappedContractCode"] = $row["mapContarct"];

                $contractList[$row["contractCode"]] = $contractArray;
            }

        }

        $connection->close();


        return $contractList;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return DatePeriod
     */
    public function getDateDifferentCount($startDate, $endDate)
    {

        $period = new DatePeriod(new DateTime($startDate), new DateInterval('P1D'), new DateTime($endDate));

        $dateCount = 0;
        foreach ($period as $date) {
            $dateCount++;
        }

        return $dateCount;
    }

    /**
     * @param $arrivalDate
     * @param $departureDate
     * @param $roomCapacity
     * @param $roomTypeCode
     * @param $propertyCode
     * @return mixed
     */
    public function getContractAndRatesForCalcRateAverage($arrivalDate, $departureDate, $roomCapacity, $roomTypeCode, $propertyCode)
    {

        $connection = $this->getConnection();

        $sql = "SELECT a.contractCode,a.contractStartDate,a.contractEndDate,b.roomTypeCode,b.roomCapacity,b.mealPlane,b.rate,b.currencyCode FROM tblcontract a INNER JOIN tblrates b ON a.contractCode = b.contractCode WHERE (a.contractStartDate <= '" . $arrivalDate . "' AND a.contractEndDate >= '" . $arrivalDate . "' AND a.propertyCode = '" . $propertyCode . "' AND b.roomCapacity = '" . $roomCapacity . "' AND b.roomTypeCode = '" . $roomTypeCode . "') OR (a.contractStartDate  <='" . $departureDate . "' AND a.contractEndDate  >='" . $departureDate . "' AND a.propertyCode = '" . $propertyCode . "' AND b.roomCapacity = '" . $roomCapacity . "' AND b.roomTypeCode = '" . $roomTypeCode . "') ORDER BY a.contractCode,b.rate";
        error_log('getContractAndRatesForCalcRateAverage : ' . $sql);
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $detail['contractCode'] = $row['contractCode'];
                $detail['contractStartDate'] = $row['contractStartDate'];
                $detail['contractEndDate'] = $row['contractEndDate'];
                $detail['roomTypeCode'] = $row['roomTypeCode'];
                $detail['roomCapacity'] = $row['roomCapacity'];
                $detail['mealPlane'] = $row['mealPlane'];
                $detail['rate'] = $row['rate'];
                $detail['currencyCode'] = $row['currencyCode'];

                $details[$i] = $detail;
                $i++;
            }
        }

        return $details;
    }

    /**
     * @param $arrivalDate
     * @param $departureDate
     * @param $adultCount
     * @param $roomTypeCode
     * @param $propertyCode
     * @return array
     */
    public function getRatesAverageList($arrivalDate, $departureDate, $adultCount, $roomTypeCode, $propertyCode)
    {

        $totalDateCount = 0;
        $ratesArray = array();

        $contractList = $this->getAvailableContractList($arrivalDate, $departureDate, "PRT1000");

        if (count($contractList) != 1) {

            foreach ($contractList as $contracts => $contract) {
                if (strtotime($contract['startDate']) <= strtotime($arrivalDate) && strtotime($contract['endDate']) <= strtotime($departureDate)) {
                    $DateCount = $this->getDateDifferentCount($arrivalDate, $contract['endDate']);
                    if ($DateCount != 0) {
                        $contractDateCount[$contract['contractCode']] = $DateCount;
                        $totalDateCount += $DateCount;
                    } else {
                        $DateCount = 1;
                        $totalDateCount += $DateCount;
                        $contractDateCount[$contract['contractCode']] = $DateCount;
                    }

                    //echo $contract['contractCode'] . "-" . $DateCount."<br>";
                } else {
                    if (strtotime($contract['startDate']) <= strtotime($departureDate)) {
                        $DateCount = $this->getDateDifferentCount($contract['startDate'], $departureDate);
                        $contractDateCount[$contract['contractCode']] = $DateCount;
                        $totalDateCount += $DateCount;
                        //echo $contract['contractCode'] . "-" . $DateCount."<br>";
                    }
                }
            }


            //
            $ratesList = $this->getContractAndRatesForCalcRateAverage($arrivalDate, $departureDate, $adultCount, $roomTypeCode, $propertyCode);

            foreach ($ratesList as $rates => $rate) {

                if (!isset($ratesArray)) {
                    $rooms['contractCode'] = $rate['contractCode'];
                    $rooms['roomTypeCode'] = $rate['roomTypeCode'];
                    $rooms['mealPlane'] = $rate['mealPlane'];
                    $rooms['roomCapacity'] = $rate['roomCapacity'];
                    $rooms['rate'] = round($rate['rate'],0,PHP_ROUND_HALF_UP);
                    $rooms['totalDates'] = $totalDateCount;
                    $rooms['currencyCode'] = $rate['currencyCode'];

                    $rooms['averageRate'] = round($contractDateCount[$rate['contractCode']] * $rate['rate'],0,PHP_ROUND_HALF_UP);

                    $ratesArray[$rate['mealPlane']] = $rooms;

                } else {

                    if (array_key_exists($rate['mealPlane'], $ratesArray)) {

                        //echo "true <br>";
                        $currentAvr = $ratesArray[$rate['mealPlane']]["averageRate"];
                        $nextAvr = $contractDateCount[$rate['contractCode']] * $rate['rate'];
                        $avr = round((($currentAvr+$nextAvr)/$totalDateCount),0,PHP_ROUND_HALF_UP);
                        $totalAvr = round((($currentAvr+$nextAvr)/$totalDateCount)*$totalDateCount,0,PHP_ROUND_HALF_UP);
                        $ratesArray[$rate['mealPlane']]["averageRate"] = $avr;

                        error_log($currentAvr." - ".$nextAvr." - ".$avr." - ".$totalAvr." - ".$totalDateCount);

                    } else {
                        $rooms['contractCode'] = $rate['contractCode'];
                        $rooms['roomTypeCode'] = $rate['roomTypeCode'];
                        $rooms['mealPlane'] = $rate['mealPlane'];
                        $rooms['roomCapacity'] = $rate['roomCapacity'];
                        $rooms['rate'] = $rate['rate'];
                        $rooms['totalDates'] = $totalDateCount;
                        $rooms['averageRate'] = round($contractDateCount[$rate['contractCode']] * $rate['rate'],0,PHP_ROUND_HALF_UP);
                        $rooms['currencyCode'] = $rate['currencyCode'];
                        $ratesArray[$rate['mealPlane']] = $rooms;
                    }
                }
            }

        } else {

            $totalDateCount = $this->getDateDifferentCount($arrivalDate, $departureDate);
            if($totalDateCount == 0){
                $totalDateCount = 1;
            }

            $ratesList = $this->getContractAndRatesForCalcRateAverage($arrivalDate, $departureDate, $adultCount, $roomTypeCode, $propertyCode);

            if (isset($ratesList)) {
                foreach ($ratesList as $rates => $rate) {

                    $rooms['roomTypeCode'] = $rate['roomTypeCode'];
                    $rooms['mealPlane'] = $rate['mealPlane'];
                    $rooms['totalDates'] = $totalDateCount;
                    $rooms['roomCapacity'] = $rate['roomCapacity'];
                    $roomRate = $rate['rate'];
                    $rooms['averageRate'] = round((($roomRate * $totalDateCount) / $totalDateCount),0,PHP_ROUND_HALF_UP);
                    $rooms['currencyCode'] = $rate['currencyCode'];

                    array_push($ratesArray, $rooms);

                }
            } else {
                // redirect to error page.
            }
        }

        //error_log(print_r($ratesArray,true));

        return $ratesArray;
    }

    /**
     * @param $contractCode
     * @param $contractMappedCode
     * @param $roomType
     * @param $adultCount
     * @return string
     */
    public function getLowestRoomRatesByRoomType($contractCode, $contractMappedCode, $roomType, $adultCount)
    {
        $connection = $this->getConnection();
        $rateAndCurrency = '';

        $sql = "SELECT roomTypeCode,rate,currencyCode FROM `tblrates` where contractCode='$contractCode' AND roomTypeCode = '$roomType' AND roomCapacity ='$adultCount' ORDER BY rate ASC LIMIT 1";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rateAndCurrency = $row["rate"] . "-" . $row["currencyCode"];
            }
        } else {
            $sql = "SELECT roomTypeCode,rate,currencyCode FROM `tblrates` where contractCode='$contractMappedCode' AND roomTypeCode = '$roomType' AND roomCapacity ='$adultCount' ORDER BY rate ASC LIMIT 1";
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rateAndCurrency = $row["rate"] . "-" . $row["currencyCode"];
                }
            }
        }

        $connection->close();
        return $rateAndCurrency;
    }

    /**
     * @param $contractCode
     * @param $roomType
     * @param $adultCount
     * @param $mealPlan
     * @return array
     */
    public function getRatesForBilling($contractCode, $roomType, $adultCount, $mealPlan)
    {

        $connection = $this->getConnection();
        $sql = "SELECT * FROM `tblrates` WHERE contractCode = '" . $contractCode . "' AND roomTypeCode = '" . $roomType . "' AND mealPlane = '" . $mealPlan . "' AND roomCapacity = '" . $adultCount . "'";
        //error_log('getRatesForBilling : ',$sql);
        $result = $connection->query($sql);

        $rate[] = '';
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rate["idtblRates"] = $row["idtblRates"];
                $rate["rateCode"] = $row["rateCode"];
                $rate["toCode"] = $row["toCode"];
                $rate["marketCode"] = $row["marketCode"];
                $rate["contractCode"] = $row["contractCode"];
                $rate["roomTypeCode"] = $row["roomTypeCode"];
                $rate["mealPlane"] = $row["mealPlane"];
                $rate["rate"] = $row["rate"];
                $rate["currencyCode"] = $row["currencyCode"];
            }
        }

        $connection->close();
        return $rate;
    }

    /**
     * @param $rateCode
     * @return array
     */
    public function getRatesForBillingByRateCode($rateCode)
    {

        $connection = $this->getConnection();
        $sql = "SELECT * FROM `tblrates` WHERE rateCode = '$rateCode'";
        $result = $connection->query($sql);

        $rate[] = '';
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rate["idtblRates"] = $row["idtblRates"];
                $rate["rateCode"] = $row["rateCode"];
                $rate["toCode"] = $row["toCode"];
                $rate["marketCode"] = $row["marketCode"];
                $rate["contractCode"] = $row["contractCode"];
                $rate["roomTypeCode"] = $row["roomTypeCode"];
                $rate["mealPlane"] = $row["mealPlane"];
                $rate["rate"] = $row["rate"];
                $rate["currencyCode"] = $row["currencyCode"];
            }
        }

        $connection->close();
        return $rate;
    }

    /**
     * @param $contractCode
     * @param $propertyCode
     * @return mixed
     */
    public function getContractDetailsByContractCode($contractCode, $propertyCode)
    {

        $connection = $this->getConnection();

        $sql = "SELECT * FROM tblcontract where contractCode = '$contractCode' AND propertyCode = '$propertyCode'";
        //error_log('getContractDetailsByContractCode : '.$sql);
        $c_result = $connection->query($sql);
        if ($c_result->num_rows > 0) {
            while ($row = $c_result->fetch_assoc()) {
                $contractArray["contractCode"] = $row["contractCode"];
                $contractArray["startDate"] = $row["contractStartDate"];
                $contractArray["endDate"] = $row["contractEndDate"];
                $contractArray["mappedContractCode"] = $row["mapContarct"];
            }
        }
        return $contractArray;
    }

}

