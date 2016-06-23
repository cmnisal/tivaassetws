<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-07-28
 * Time: 3:23 PM
 */
if (!session_start()) {
    session_start();
}

include "../datasource/DbConnection.php";
include "../service/AvailabilityService.php";
include "../service/RoomAvailability.php";

/**
 *
 */
if ($_POST["type"] == "addToBucket"
    && isset($_SESSION["arrival_date"])
    && isset($_SESSION["departure_date"])
    && isset($_SESSION["adult_count"])
    && isset($_SESSION["child_count"])
) {

    foreach ($_POST as $key => $value) {
        $selected_product[$key] = filter_var($value, FILTER_SANITIZE_STRING);
    }

    $overBooking = false;

    //following statement not needed any more
    /**
    if (isset($_SESSION["roomBucket"]) && !empty($_SESSION["roomBucket"])) {

        $availRooms = [];

        foreach ($_SESSION["roomBucket"] as $bookedRooms) {
            $crossed = false;

            //error_log("bucket arrival date : ".$bookedRooms['arrival_date']." / bucket departure date : ".$bookedRooms['departure_date']);
            //error_log("session arrival date : ".$_SESSION["arrival_date"]." / session departure date : ".$_SESSION["departure_date"]);

            if ($bookedRooms['roomTypeCode'] == $selected_product['roomTypeCode']) {

                if ((strtotime($bookedRooms['arrival_date']) < strtotime($_SESSION['departure_date'])) && (strtotime($bookedRooms['departure_date']) < strtotime($_SESSION['arrival_date']))) {
                    $crossed = false;
                } else {
                    $crossed = true;
                }

                if ($crossed) {
                    $roomAvailability = new RoomAvailability();
                    $availableRoomCount = $roomAvailability->getAvailableRoomCounts($bookedRooms['arrival_date'], $bookedRooms['departure_date'], $bookedRooms['roomTypeCode'], $_SESSION['propertyCode']);

                    foreach ($availableRoomCount as $availableRooms => $roomCount) {
                        foreach ($roomCount as $day => $count) {
                            $newCount[$day] = ($count - 1);
                        }
                    }
                    //
                    array_push($availRooms, $newCount);
                    /**
                     *

                    $depDate = new DateTime($_SESSION["departure_date"]);
                    $depDate->modify('+1 day');
                    $period = new DatePeriod(new DateTime($_SESSION["arrival_date"]), new DateInterval('P1D'), $depDate);
                    foreach ($period as $date) {
                        $arrivalMonth = $date->format('m');
                        $arrivalDay = $date->format('d');
                        $arrivalYear = $date->format('Y');

                        foreach ($availRooms as $rooms => $rmCount) {
                            foreach ($rmCount as $day => $cnt) {
                                if ($arrivalDay == $day) {
                                    if (($cnt - 1) <= 0) {
                                        $overBooking = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }//
        }
    }
    ///////////////////////////////--- Finish the Coding ---/////////////////////////////////////////////
     * **/

    $availabilityService = new AvailabilityService();
    $contracts = $availabilityService->getContractListForBilling($_SESSION["arrival_date"], $_SESSION["departure_date"], $_SESSION["propertyCode"]);

    if (empty($contracts)) {
        //redirect to error page
    }

    $totalRate = 0;

    $contractCount = count($contracts);

    if ($contractCount > 1) {
        foreach ($contracts as $key => $contract) {
            if ($availabilityService->getDateDifferentCount($contract["startDate"], $_SESSION["arrival_date"]) != 0) {
                $endDate = date('Y-m-d', strtotime("+1 day", strtotime($contract["endDate"])));
                $dayCount = $availabilityService->getDateDifferentCount($_SESSION["arrival_date"], $endDate);
                $rateArray = $availabilityService->getRatesForBilling($key, $selected_product['roomTypeCode'], $_SESSION["adult_count"], $selected_product['mealPlan']);
                $totalRate += $dayCount * $rateArray['rate'];
            } else {
                if ($availabilityService->getDateDifferentCount($contract["startDate"], $_SESSION["departure_date"]) != 0) {
                    $dayCount = $availabilityService->getDateDifferentCount($contract["startDate"], $_SESSION["departure_date"]);
                    $rateArray = $availabilityService->getRatesForBilling($key, $selected_product['roomTypeCode'], $_SESSION["adult_count"], $selected_product['mealPlan']);
                    $totalRate += $dayCount * $rateArray['rate'];
                }
            }
        }
    } else {

        foreach ($contracts as $key => $contract) {

            $dayCount = $availabilityService->getDateDifferentCount($_SESSION["arrival_date"], $_SESSION["departure_date"]);
            $rateArray = $availabilityService->getRatesForBilling($key, $selected_product['roomTypeCode'], $_SESSION["adult_count"], $selected_product['mealPlan']);
            $totalRate += $dayCount * $rateArray['rate'];

        }

    }

    //$rateArray = $availabilityService->getRatesForBillingByRateCode($selected_product['rateCode']);

    if (!$overBooking) {
        if (!empty($rateArray)) {
            $selectedRoom["roomTypeCode"] = $selected_product['roomTypeCode'];
            //$selectedRoom["rateCode"] = $selected_product['rateCode'];
            $selectedRoom["arrival_date"] = $_SESSION["arrival_date"];
            $selectedRoom["departure_date"] = $_SESSION["departure_date"];
            $selectedRoom["adult_count"] = $_SESSION["adult_count"];
            $selectedRoom["child_count"] = $_SESSION["child_count"];
            $selectedRoom["averageRoomRate"] = $selected_product["rate"];
            $selectedRoom["mealPlane"] = $selected_product['mealPlan'];
            $selectedRoom["roomType"] = $selected_product['roomType'];
            $selectedRoom["currencyCode"] = $selected_product["currencyCode"];
            $selectedRoom["totalNights"] = $selected_product["totalNights"];

            $_SESSION["roomBucket"][$selectedRoom['roomTypeCode'] . "-" . $_SESSION['count']] = $selectedRoom;


            $roomAdded = false;

            if (isset($_SESSION['BRC'])) {
                foreach ($_SESSION['BRC'] as $bookedRooms => $rooms) {

                    //error_log(print_r($bookedRooms, true));
                    $aDate = explode("/", $bookedRooms)[0];
                    $dDate = explode("/", $bookedRooms)[1];
                    $rType = explode("/", $bookedRooms)[2];

                    //error_log($aDate." / ".$dDate." / ".$rType);

                    if ($rType == $selectedRoom["roomTypeCode"]) {

                        $crossed = false;

                        if((strtotime($aDate) >= strtotime($_SESSION['arrival_date'])) && strtotime($aDate) <= strtotime($_SESSION['departure_date'])){
                            $crossed = true;
                        } else if((strtotime($dDate) >= strtotime($_SESSION['arrival_date'])) && strtotime($dDate) <= strtotime($_SESSION['departure_date'])){
                            $crossed = true;
                        }

                        if ($crossed) {
                            $_SESSION['BRC'][$aDate . '/' . $dDate . '/' . $rType] += 1;
                            //error_log("c---------".$selectedRoom["roomTypeCode"]);
                            //error_log("cross / in loop :- ".print_r($_SESSION['BRC'],true));
                        } else {
                            $_SESSION['BRC'][$_SESSION["arrival_date"] . '/' . $_SESSION["departure_date"] . '/' . $selectedRoom["roomTypeCode"]] = 1;
                            //error_log("nc---------".$selectedRoom["roomTypeCode"]);
                            //error_log("not cross / in loop :- ".print_r($_SESSION['BRC'],true));
                        }
                        $roomAdded = true;
                    }
                }

                //
                if(!$roomAdded){
                    $_SESSION['BRC'][$_SESSION["arrival_date"] . '/' . $_SESSION["departure_date"] . '/' . $selectedRoom["roomTypeCode"]] = 1;
                }
            } else {
                //error_log("first");
                //
                $_SESSION['BRC'][$_SESSION["arrival_date"] . '/' . $_SESSION["departure_date"] . '/' . $selectedRoom["roomTypeCode"]] = 1;

            }

            //
            updateSessionRoomAvailability($selectedRoom["arrival_date"], $selectedRoom["departure_date"], $selectedRoom["roomTypeCode"]);

            $bucket_url = "../../view/viewBusket.php";
            header('Location:' . $bucket_url);
        } else {
            $return_url = (isset($selected_product["return_url"])) ? urldecode($selected_product["return_url"]) : "";
            header('Location:' . $return_url);
        }
    } else {

        $selectedRoom["roomTypeCode"] = $selected_product['roomTypeCode'];
        //$selectedRoom["rateCode"] = $selected_product['rateCode'];
        $selectedRoom["arrival_date"] = $_SESSION["arrival_date"];
        $selectedRoom["departure_date"] = $_SESSION["departure_date"];
        $selectedRoom["adult_count"] = $_SESSION["adult_count"];
        $selectedRoom["child_count"] = $_SESSION["child_count"];
        $selectedRoom["roomRate"] = $totalRate;
        $selectedRoom["mealPlane"] = $selected_product['mealPlan'];
        $selectedRoom["roomType"] = $selected_product['roomType'];
        $selectedRoom["currencyCode"] = $selected_product["currencyCode"];

        $_SESSION["unAvailableRooms"][$selectedRoom['roomTypeCode'] . "-" . $_SESSION['count']] = $selectedRoom;

        $return_url = (isset($selected_product["return_url"])) ? urldecode($selected_product["return_url"]) : "";
        header('Location:' . $return_url);
    }

}


/**
 * remove rooms from session
 */
if ($_POST["type"] == "removeFromBucket" && isset($_POST["removeRoomCode"]) && !empty($_POST["return_url"])) {

    if (!empty($_SESSION["roomBucket"])) {
        unset($_SESSION["roomBucket"][$_POST["removeRoomCode"]]);

        $rmCode = explode("-", $_POST["removeRoomCode"])[0];
        $arDate = $_POST['arrival_date'];
        $dpDate = $_POST['departure_date'];
        $code = $arDate."/".$dpDate."/".$rmCode;
        //remove room category wise count
        if (isset($_SESSION['BRC'])) {
            foreach ($_SESSION['BRC'] as $bookedRooms => $rooms) {
                if($bookedRooms == $code){
                    if($rooms == 1){
                        unset($_SESSION['BRC'][$code]);
                    }else{
                        $_SESSION['BRC'][$code] -= 1;
                    }
                }
            }
        }
    }

    if (isset($_SESSION["unAvailableRooms"])) {
        unset($_SESSION["unAvailableRooms"]);
    }

    $return_url = "";

    if (empty($_SESSION["roomBucket"])) {
        unset($_SESSION["roomBucket"]);
        //$return_url = "../../view/availability.php";
        $return_url = "../service/AvailabilityPostService.php";
    } else {



        $return_url = (isset($_POST["return_url"])) ? urldecode($_POST["return_url"]) : ''; //return url
    }


    header('Location:' . $return_url);
}

/**
 * @param $arrival_date
 * @param $departure_date
 * @param $roomType_code
 */
function updateSessionRoomAvailability($arrival_date, $departure_date, $roomType_code)
{
    //error_log(print_r($_SESSION['BRC'],true));
    if (isset($_SESSION["availability"]) || !empty($_SESSION["availability"])) {
        foreach ($_SESSION["availability"] as $availability => $rooms) {
            if ($rooms['roomTypeCode'] == $roomType_code) {
                $rmCnt = 1;

                //following statement must be replace by proper one
                if (isset($_SESSION['BRC'])) {

                    foreach ($_SESSION['BRC'] as $bookedRooms => $rooms) {
                        $aDate = explode("/", $bookedRooms)[0];
                        $dDate = explode("/", $bookedRooms)[1];
                        $rType = explode("/", $bookedRooms)[2];

                        if($rType = $roomType_code) {
                            $crossed = false;

                            if((strtotime($aDate) >= strtotime($_SESSION['arrival_date'])) && strtotime($aDate) <= strtotime($_SESSION['departure_date'])){
                                $crossed = true;
                            } else if((strtotime($dDate) >= strtotime($_SESSION['arrival_date'])) && strtotime($dDate) <= strtotime($_SESSION['departure_date'])){
                                $crossed = true;
                            }

                            if($crossed){
                                $roomAvailability = new RoomAvailability();
                                $minVal = ($roomAvailability->getMinimumAvailableRoomCount($arrival_date, $departure_date, $roomType_code, $_SESSION['propertyCode']) - $rooms);
                                if ($minVal <= 0) {
                                    unset($_SESSION["availability"][$availability]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

