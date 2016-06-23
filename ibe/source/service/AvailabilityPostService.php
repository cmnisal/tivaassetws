<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-07-24
 * Time: 7:13 PM
 */

if (!session_start()) {
    session_start();
}

include "AvailabilityService.php";
include "../datasource/DbConnection.php";
include "RoomAvailability.php";


class AvailabilityPostService
{

    private $arrival_date;
    private $departure_date;
    private $adult_count;
    private $child_count;
    private $return_url;
    private $propertyCode;

    /**
     *
     */
    function __construct()
    {

        if (isset($_POST["arrival_date"]) &&
            isset($_POST["departure_date"]) &&
            isset($_POST["adult_count"]) &&
            isset($_POST["child_count"]) &&
            isset($_POST["return_url"])
        ) {

            $this->arrival_date = $_POST["arrival_date"];
            $this->departure_date = $_POST["departure_date"];
            $this->adult_count = $_POST["adult_count"];
            $this->child_count = $_POST["child_count"];
            $this->return_url = $_POST["return_url"];
            $this->propertyCode = $_SESSION["propertyCode"];

            unset($_SESSION["arrival_date"]);
            unset($_SESSION["departure_date"]);
            unset($_SESSION["adult_count"]);
            unset($_SESSION["child_count"]);

            $_SESSION["arrival_date"] = $_POST["arrival_date"];
            $_SESSION["departure_date"] = $_POST["departure_date"];
            $_SESSION["adult_count"] = $_POST["adult_count"];
            $_SESSION["child_count"] = $_POST["child_count"];

            if (isset($_SESSION['unAvailableRooms'])) {
                unset($_SESSION['unAvailableRooms']);
            }

        } else {
            //$_SESSION["error_code"] = "100";
            //echo $_SESSION["error_code"];
            $this->arrival_date = $_SESSION["arrival_date"];
            $this->departure_date = $_SESSION["departure_date"];
            $this->adult_count = $_SESSION["adult_count"];
            $this->child_count = $_SESSION["child_count"];
            $this->return_url = $_SESSION["return_url"];
            $this->propertyCode = $_SESSION["propertyCode"];
        }
    }

    /**
     *
     */
    function searchAvailability()
    {
        $convertedArrivalDate = date('Y-m-d', strtotime($this->arrival_date));
        $convertedDepartureDate = date('Y-m-d', strtotime($this->departure_date));

        $availabilityService = new AvailabilityService();
        $contracts = $availabilityService->getAvailableContract($convertedArrivalDate, $convertedDepartureDate, $this->propertyCode);

        $roomAvailability = new RoomAvailability();

        $allDetails = array();

        if ($contracts != 0 || isset($contracts["contractCode"])) {
            $availableRoomRates = $availabilityService->getRatesByContract($contracts["contractCode"], $contracts["mappedContractCode"], $this->adult_count, $this->propertyCode);

            //if there is no available rooms return to availability page.
            if ($availableRoomRates == 0) {
                $return_url = (isset($this->return_url)) ? urldecode($this->return_url) : "";
                header('Location:' . $return_url);
                exit();
            }

            foreach ($availableRoomRates as $key => $value) {
                $rooms = $roomAvailability->getAvailableRoomsWithRoomType($convertedArrivalDate, $convertedDepartureDate, $value["roomTypeCode"], $this->propertyCode);
                foreach ($rooms as $roomKey => $room) {
                    $roomDes = $availabilityService->getAvailableRoomDetailsByRoomType($roomKey, $this->propertyCode);
                    foreach ($roomDes as $roomDetails => $roomDetail) {
                        $detail["idtblRoomType"] = $roomDetail["idtblRoomType"];
                        $detail["roomTypeCode"] = $roomDetail["rtCode"];
                        $detail["roomType"] = $roomDetail["roomType"];
                        $detail["roomDescription"] = $roomDetail["roomDescription"];
                        $detail["numberOfRooms"] = $roomDetail["numberOfRooms"];
                        $detail["imagePath"] = $roomDetail["imagePath"];
                        $detail["paxCount"] = $roomDetail["paxCount"];
                        $detail["extrapaxCount"] = $roomDetail["extrapaxCount"];
                        $lowestRateAndCurrency = explode("-",$availabilityService->getLowestRatesAverages($this->arrival_date, $this->departure_date, $this->adult_count, $roomDetail["rtCode"],$this->propertyCode));
                        //$lowestRateAndCurrency = explode("-", $availabilityService->getLowestRoomRatesByRoomType($contracts["contractCode"], $contracts["mappedContractCode"], $roomDetail["rtCode"], $this->adult_count));
                        $detail["lowestRate"] = $lowestRateAndCurrency[0];
                        $detail["currency"] = $lowestRateAndCurrency[1];
                        $detail["rates"] = $availabilityService->getRatesAverageList($this->arrival_date, $this->departure_date, $this->adult_count, $roomDetail["rtCode"],$this->propertyCode);
                        //follow statement not use since 2015-11-26
                        //$detail["rates"] = $availabilityService->getRatesByContractByRoomType($contracts["contractCode"], $contracts["mappedContractCode"], $roomDetail["rtCode"], $this->adult_count);
                        array_push($allDetails, $detail);
                    }
                }
            }
        }

        if (!empty($allDetails)) {
            $_SESSION["availability"] = $allDetails;

            //------------------------------------------------------------------------------------
            if(isset($_SESSION['BRC'])){
                foreach ($_SESSION["availability"] as $availability => $avlRooms) {
                    foreach ($_SESSION['BRC'] as $bookedRooms => $rooms) {
                        $crossed = false;

                        $aDate = explode("/", $bookedRooms)[0];
                        $dDate = explode("/", $bookedRooms)[1];
                        $rType = explode("/", $bookedRooms)[2];

                        if($rType == $avlRooms['roomTypeCode']){

                            if((strtotime($aDate) >= strtotime($_SESSION['arrival_date'])) && strtotime($aDate) <= strtotime($_SESSION['departure_date'])){
                                $crossed = true;
                            } else if((strtotime($dDate) >= strtotime($_SESSION['arrival_date'])) && strtotime($dDate) <= strtotime($_SESSION['departure_date'])){
                                $crossed = true;
                            }

                            if($crossed){
                                $roomAvailability = new RoomAvailability();
                                $minVal = ($roomAvailability->getMinimumAvailableRoomCount($this->arrival_date, $this->departure_date, $avlRooms['roomTypeCode'], $_SESSION['propertyCode']) - $rooms);
                                if ($minVal <= 0) {
                                    unset($_SESSION["availability"][$availability]);
                                }

                            }
                        }
                    }
                }
            }
            //------------------------------------------------------------------------------------------------------------------------
            //error_log(print_r($_SESSION["availability"],true));
        } else {
            unset($_SESSION["availability"]);
        }

        $return_url = (isset($this->return_url)) ? urldecode($this->return_url) : "";
        header('Location:' . $return_url);
        exit();
    }


}

$availability = new AvailabilityPostService();
$availability->searchAvailability();




