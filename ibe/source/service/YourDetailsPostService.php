<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-08-04
 * Time: 12:30 PM
 */

session_start();

include "../datasource/DbConnection.php";
include "../service/DocNumberGenerator.php";
include "../service/YourDetailsService.php";
include "../service/ViewBucketService.php";

class YourDetailsPostService
{

    private $title;
    private $firstName;
    private $surName;
    private $address_1;
    private $address_2;
    private $town_city;
    private $nic_passportNumber;
    private $zip_postalCode;
    private $country;
    private $phoneNumber;
    private $email;

    private $cardType;
    private $cardNumber;
    private $nameOnCard;
    private $expireYear;
    private $expireMonth;
    private $cardSecurityNo;
    private $propertyCode;

    private $currency;
    private $return_url;

    private $reservedRooms = array();


    /**
     *
     *
     */
    function __construct()
    {
        if (isset($_POST["selected_title"]) &&
            isset($_POST["firstName"]) &&
            isset($_POST["surName"]) &&
            isset($_POST["address_1"]) &&
            isset($_POST["address_2"]) &&
            isset($_POST["townCity"]) &&
            isset($_POST["zipPostalCode"]) &&
            isset($_POST["nicPassportNumber"]) &&
            isset($_POST["phoneNumber"]) &&
            isset($_POST["email"]) &&
            isset($_POST["cardType"]))
        {
            //
            $this->title = $_POST["selected_title"];
            $this->firstName = $_POST["firstName"];
            $this->surName = $_POST["surName"];
            $this->address_1 = $_POST["address_1"];
            $this->address_2 = $_POST["address_2"];
            $this->town_city = $_POST["townCity"];
            $this->nic_passportNumber = $_POST["nicPassportNumber"];
            $this->zip_postalCode = $_POST["zipPostalCode"];
            $this->country = $_POST["selected_country"];
            $this->phoneNumber = $_POST["phoneNumber"];
            $this->email = $_POST["email"];
            $this->cardType = $_POST["cardType"];
                //
            //$this->cardType = $_POST["card_type"];
            //$this->cardNumber = $_POST["cardNumber"];
            //$this->nameOnCard = $_POST["nameOnCard"];
            //$this->expireYear = $_POST["expire_year"];
            //$this->expireMonth = $_POST["expire_month"];
            //$this->cardSecurityNo = $_POST["cardSecNumber"];
            //
            $this->return_url = $_POST["return_url"];
            //
            $this->propertyCode = $_SESSION["propertyCode"];
            //
            $this->reservedRooms = $_SESSION['roomBucket'];

            //
            $this->getReservationCode();
            $this->addToSession();

        } else {

            $return_url = (isset($this->return_url)) ? urldecode($this->return_url) : "";
            header('Location:' . $return_url);
        }
    }


    /**
     * @return mysqli|null
     */
    private function getConnection()
    {

        $connection = new DbConnection();
        return $connection->openConnection();

    }

    /**
     *
     */
    private function getReservationCode()
    {
        if ($_SESSION["reservationCode"] == '0') {
            error_log("sesstion : 0");
            $docNumberGenerator = new DocNumberGenerator();
            $reservationCode = $docNumberGenerator->getGeneratedNumber("RES");
            $_SESSION["reservationCode"] = $reservationCode;
        } else {
            //check if exist reservation code already in the database and if not gen new code;
            error_log("sesstion not : 0");
            $yourDetailsService = new YourDetailsService();
            $exist = $yourDetailsService->checkGivenReservationCodeExist($_SESSION["reservationCode"]);
            if (!$exist) {
                $_SESSION["reservationCode"] = '0';
                $this->getReservationCode();
            }
        }
    }

    /**
     * @return string
     */
    private function getCurrentDateTime()
    {

        $now = new DateTime();
        return $now->format('Y-m-d H:i');// MySQL datetime format

    }

    /**
     * @return string
     */
    private function getPaymentCode()
    {

        $docNumberGenerator = new DocNumberGenerator();
        $paymentCode = $docNumberGenerator->getGeneratedNumber("PAY");

        return $paymentCode;
    }

    /**
     * @return int
     */
    private function getAdultCount()
    {

        $adultCount = 0;
        foreach ($_SESSION["roomBucket"] as $rooms => $room) {
            $adultCount += $room["adult_count"];
        }

        return $adultCount;

    }

    /**
     * @return int
     */
    private function getChildCount()
    {

        $childCount = 0;
        foreach ($_SESSION["roomBucket"] as $rooms => $room) {
            $childCount += $room["child_count"];
        }

        return $childCount;

    }

    /**
     *
     */
    private function getTotalAmount()
    {
        $viewBucketService = new ViewBucketService();
        $totalAmount = 0;
        foreach ($_SESSION["roomBucket"] as $rooms => $room) {
            $this->currency = $room["currencyCode"];
            $dayCount = $room["totalNights"];
            $amount = $room["averageRoomRate"];
            $totalAmount += ($amount*$dayCount);
        }
        return $totalAmount;
    }


    /**
     *
     */
    private function addToSession()
    {

        if (isset($_SESSION["customerDetails"])) {
            unset($_SESSION["customerDetails"]);

            $customer["title"] = $this->title;
            $customer["firstName"] = $this->firstName;
            $customer["surName"] = $this->surName;
            $customer["address_1"] = $this->address_1;
            $customer["address_2"] = $this->address_2;
            $customer["town_city"] = $this->town_city;
            $customer["nic_passportNumber"] = $this->nic_passportNumber;
            $customer["zip_postalCode"] = $this->zip_postalCode;
            $customer["country"] = $this->country;
            $customer["phoneNumber"] = $this->phoneNumber;
            $customer["email"] = $this->email;
            $customer["cardType"] = $this->cardType;
            //$customer["cardNumber"] = $this->cardNumber;
            //$customer["nameOnCard"] = $this->nameOnCard;
            //$customer["expireYear"] = $this->expireYear;
            //$customer["expireMonth"] = $this->expireMonth;
            //$customer["cardSecurityNo"] = $this->cardSecurityNo;

            $_SESSION["customerDetails"][$_SESSION["reservationCode"]] = $customer;

        } else {

            $customer["title"] = $this->title;
            $customer["firstName"] = $this->firstName;
            $customer["surName"] = $this->surName;
            $customer["address_1"] = $this->address_1;
            $customer["address_2"] = $this->address_2;
            $customer["town_city"] = $this->town_city;
            $customer["zip_postalCode"] = $this->zip_postalCode;
            $customer["country"] = $this->country;
            $customer["phoneNumber"] = $this->phoneNumber;
            $customer["email"] = $this->email;
            $customer["nic_passportNumber"] = $this->nic_passportNumber;
            $customer["cardType"] = $this->cardType;
            //$customer["cardNumber"] = $this->cardNumber;
            //$customer["nameOnCard"] = $this->nameOnCard;
            //$customer["expireYear"] = $this->expireYear;
            //$customer["expireMonth"] = $this->expireMonth;
            //$customer["cardSecurityNo"] = $this->cardSecurityNo;

            $_SESSION["customerDetails"][$_SESSION["reservationCode"]] = $customer;
        }

    }


    /**
     *
     */
    function saveUpdateReservation()
    {
        $reservationCode = $_SESSION["reservationCode"];

        $connection = $this->getConnection();

        $adultCount = $this->getAdultCount();
        $childCount = $this->getChildCount();
        $totalAmount = $this->getTotalAmount();

        $dateTime = date("Y-m-d G:i:s");
        $time = date('G:i:s');

        $exist = false;
        $updated = false;

        $sql = "SELECT * FROM tblreservation WHERE reservationCode = '$reservationCode'";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            $exist = true;
        }

        //insert
        if (!$exist) {
            //into reservation table

            $currentTime = $this->getCurrentDateTime();

            try {
                $sql = "INSERT INTO tblreservation(reservationCode,title,firstName,surName,address,streetAddress,city,country,email,nic_passportNumber,postalCode,phoneNumber,adult,child,emailStatus,propertyCode,transactionTime)
                 VALUES('$reservationCode','$this->title','$this->firstName','$this->surName','$this->address_1','$this->address_2','$this->town_city','$this->country','$this->email','$this->nic_passportNumber','$this->zip_postalCode','$this->phoneNumber','$adultCount','$childCount','0','$this->propertyCode','$currentTime')";
                //error_log("insert reservation : " . $sql);

                if ($connection->query($sql) == TRUE) {
                    $updated = true;
                } else {
                    $_SESSION["error_code"] = '200';
                }

                if ($updated) {

                    $paymentCode = $this->getPaymentCode();
                    $sql = "INSERT INTO tblpaymentdetails(propertyCode, reservationCode, nic_passportNumber, total, currency, zip_postalCode, paymentDate, paymentTime,paymentCode,cardType)
                        VALUES ('$this->propertyCode','$reservationCode','$this->nic_passportNumber','$totalAmount','$this->currency','$this->zip_postalCode','$dateTime','$time','$paymentCode','$this->cardType')";
                    //error_log("insert payment : " . $sql);
                    if ($connection->multi_query($sql) === TRUE) {
                        //insert reserved rooms
                        $this->insertReservedRooms($reservationCode);
                        $updated = true;
                    } else {
                        $updated = false;
                        $_SESSION["error_code"] = '200';
                    }
                } else {
                    $_SESSION["error_code"] = '200';
                }

            } catch (Exception $e) {
                error_log("SQL error : " . $e->getMessage());
            }

        } else {
            //error_log("Exsit");
            try {
                //update reservation table
                $sql = "UPDATE tblreservation SET title = '$this->title' ,firstName ='$this->firstName' ,surName ='$this->surName',address = '$this->address_1',streetAddress ='$this->address_2' ,city ='$this->town_city' ,country ='$this->country' ,email ='$this->email' ,nic_passportNumber = '$this->nic_passportNumber',postalCode ='$this->zip_postalCode' ,phoneNumber ='$this->phoneNumber' ,adult = '$adultCount',child ='$childCount' WHERE reservationCode = '$reservationCode'";
                //error_log("check reservation : " . $sql);
                if ($connection->query($sql) == TRUE) {
                    $updated = true;
                } else {
                    $_SESSION["error_code"] = '200';
                }

                if ($updated) {
                    $sql = "UPDATE tblpaymentdetails SET nic_passportNumber = '$this->nic_passportNumber' , total = '$totalAmount' , zip_postalCode = '$this->zip_postalCode' , cardType = '$this->cardType' WHERE propertyCode = '$this->propertyCode' AND reservationCode = '$reservationCode'";
                    error_log("update payment : " . $sql);
                    if ($connection->query($sql) == TRUE) {
                        // update reserved rooms
                        $this->updateReservedRooms($reservationCode);
                        $updated = true;
                    } else {
                        $updated = false;
                        $_SESSION["error_code"] = '200';
                    }
                } else {
                    $_SESSION["error_code"] = '200';
                }

            } catch (Exception $e) {
                error_log("SQL error : " . $e);
            }
        }

        $connection->close();

        if ($updated) {
            $return_url = "../../view/reviewandpay.php";
            header('Location:' . $return_url);
        } else {
            $return_url = (isset($this->return_url)) ? urldecode($this->return_url) : "";
            header('Location:' . $return_url);
        }
    }


    /**
     * @param $reservation_id
     * @return bool
     */
    function insertReservedRooms($reservation_id)
    {

        $connection = $this->getConnection();
        $viewBucketService = new ViewBucketService();
        if (isset($_SESSION["roomBucket"]) && !empty($_SESSION["roomBucket"])) {

            $currentDateTime = $this->getCurrentDateTime();

            foreach ($_SESSION["roomBucket"] as $rooms => $room) {
                $roomTypeCode = $room["roomTypeCode"];
                $arrivalDate = date('Y-m-d', strtotime($room["arrival_date"]));
                $departureDate = date('Y-m-d', strtotime($room["departure_date"]));
                $dayCount = $viewBucketService->getDayCount($arrivalDate, $departureDate);
                $adultCount = $room["adult_count"];
                $childCount = $room["child_count"];

                $rate = $room["averageRoomRate"];
                $currency = $room["currencyCode"];
                $mealPlane = $room["mealPlane"];

                $sql = "INSERT INTO tblreserved_rooms(reservation_id,roomTypeCode,arrivalDate,depatureDate,adultCount,childCount,rate,currency,mealPlane,dayCount,transactionTime) VALUES('$reservation_id','$roomTypeCode','$arrivalDate','$departureDate','$adultCount','$childCount','$rate','$currency','$mealPlane','$dayCount','$currentDateTime');";
                $connection->query($sql);
            }
        }

        $connection->close();
    }

    /**
     * @param $reservation_id
     * @return bool
     */
    function updateReservedRooms($reservation_id)
    {
        $connection = $this->getConnection();
        $viewBucketService = new ViewBucketService();
        if (isset($_SESSION["roomBucket"]) && !empty($_SESSION["roomBucket"])) {

            $sql = "SELECT * FROM tblreserved_rooms WHERE reservation_id = '$reservation_id'";
            $connection->query($sql);

            if ($connection->query($sql) == TRUE) {

                $sql = "DELETE FROM tblreserved_rooms WHERE reservation_id = '$reservation_id'";
                $connection->query($sql);

            }

            $currentDateTime = $this->getCurrentDateTime();

            foreach ($_SESSION["roomBucket"] as $rooms => $room) {
                $roomTypeCode = $room["roomTypeCode"];
                $arrivalDate = $room["arrival_date"];
                $departureDate = $room["departure_date"];
                $adultCount = $room["adult_count"];
                $childCount = $room["child_count"];
                $dayCount = $room["totalNights"];
                $rate = $room["averageRoomRate"];
                $currency = $room["currencyCode"];
                $mealPlane = $room["mealPlane"];

                $sql = "INSERT INTO tblreserved_rooms(reservation_id,roomTypeCode,arrivalDate,depatureDate,adultCount,childCount,rate,currency,mealPlane,dayCount,transactionTime) VALUES('$reservation_id','$roomTypeCode','$arrivalDate','$departureDate','$adultCount','$childCount','$rate','$currency','$mealPlane','$dayCount','$currentDateTime');";
                $connection->query($sql);

            }
        }
        $connection->close();
    }

}

$yourDetailsPostService = new YourDetailsPostService();
$yourDetailsPostService->saveUpdateReservation();




