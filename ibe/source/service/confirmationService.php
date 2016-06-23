<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 8/23/2015
 * Time: 10:17 PM
 */
//include "../datasource/DbConnection.php";
//include "Functions.php";

class confirmationService
{

    /**
     * @return mysqli|null
     */
    private function getConnection()
    {

        $connection = new DbConnection();
        return $connection->openConnection();

    }


    /**
     * @param $clientDetails
     * @param $propertyDetails
     * @param $reservedRooms
     */
    public function sendClientMails($clientDetails, $propertyDetails, $reservedRooms)
    {

        $function = new Functions();

        $to = $clientDetails["email"];

        $subject = $propertyDetails["propertyName"] . " online booking confirmation - Reservation ID " . $clientDetails["reservationCode"]." (Beta Version)";

        $subject = $propertyDetails["propertyName"] . " online booking confirmation - Reservation ID " . $clientDetails["reservationCode"]."";

        $message = '<html>';
        $message .= '<head><style>@import url(https://fonts.googleapis.com/css?family=Muli);body{font-family: "Muli", sans-serif;}.center {margin: auto;width: 60%;border:3px solid 		  #B6202C;padding: 10px;}</style></head>';
        $message .= '<body style="font-family: "Muli";" >';
        $message .= '<div class="center">';
        $message .= '<p>Dear ' . $clientDetails["title"] . ' ' . $clientDetails["firstName"] . ' ' . $clientDetails["surName"] . '</p>';
        $message .= '<p>Thank you for choosing to stay with us. We are delighted to confirm your room reservation. Your reservation details are as follows:<p>';
        $message .= '<br>';
        $message .= '<table border="0">';
        $message .= '<tbody>';
        $message .= '<tr><td style="font-weight: bold;">Reservation ID:</td><td>'.$clientDetails["reservationCode"].'</td></tr>';
        $message .= '<tr><td style="font-weight: bold;">Name:</td><td>' . $clientDetails["title"] . ' ' . $clientDetails["firstName"] . ' ' . $clientDetails["surName"] . '</td></tr>';
        $message .= '<tr><td style="font-weight: bold;">Telephone:</td><td>' . $clientDetails["phoneNumber"] . '</td></tr>';
        $message .= '<tr><td style="font-weight: bold;">Address:</td><td>' . $clientDetails["address"] . ' ' . $clientDetails["streetAddress"] . ' ' . $clientDetails["city"] . '</td></tr>';
        $message .= '<tr style="5px;"></tr>';
        $message .= '<tr><td style="font-weight: bold">Room Details: </td><td></td></tr>';
        $message .= '</tbody>';
        $message .= '</table>';
        $message .= '<table border="0"><thead><tr style="font-weight: bold"><td width="200">Room Type</td><td width="100">Arrival<br> Date</td><td width="100">Departure Date</td><td 	width="100">Nights</td><td width="150">Meal Plan</td><td width="50">Adult Count</td><td width="50">Child Count</td><td width="100">Price<br> (USD)</td></tr></thead>';
        foreach ($reservedRooms as $rooms => $room) {
            $message .= '<tr>';
            $message .= '<td>' . $room["roomType"] . '</td>';
            $message .= '<td>' . $function->displayDateFormat($room["arrivalDate"]) . '</td>';
            $message .= '<td>' . $function->displayDateFormat($room["depatureDate"]) . '</td>';
            $message .= '<td>' . $this->getDayCount($room["arrivalDate"],$room["depatureDate"])  . '</td>';
            $message .= '<td>' . $room["mealPlane"]  . '</td>';
            $message .= '<td>' . $room["adultCount"] . '</td>';
            $message .= '<td>' . $room["childCount"] . '</td>';
            $message .= '<td>' . number_format($room["rate"]*$this->getDayCount($room["arrivalDate"],$room["depatureDate"]),2). '</td>';
            $message .= '</tr>';
        }
        $message .= '</tbody>';
        $message .= '</table>';
        $message .= '<p>Please quote the above reference number should you contact us for any queries. Our hotline number is ' . $propertyDetails["propertyPhoneNumber"] . ' or email us     at ' . $propertyDetails["propertyEmail"] .'. We kindly request you to always carry a copy of this confirmation email.</p>';
        $message .= '<span>Thank you.</span><br>';
        $message .= '<span>' . $propertyDetails["propertyName"] . '</span>';
        $message .= '<br>';
        $message .= '<p>Please do not reply to this automatically generated email, as your response will not reach us.</p>';
        $message .= '</div>';
        $message .= '</body></html>';
   

        $from = "onlinereservation@inhotsolutions.com";
        $headers = "From:" . $from . "\r\n";
        $headers .= "Bcc:support@inhotsolutions.com" . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        error_log($message);
        mail($to, $subject, $message, $headers);
    }

    /**
     * @param $clientDetails
     * @param $propertyDetails
     * @param $reservedRooms
     */
    public function sendHotelMails($clientDetails, $propertyDetails, $reservedRooms)
    {
        $function = new Functions();


        $to = $propertyDetails["propertyEmail"];
        $subject = $propertyDetails["propertyName"] . " Booking Confirmation | Reservation ID " . $clientDetails["reservationCode"]."";

        $message = '<html>';
        $message .= '<head><style>@import url(https://fonts.googleapis.com/css?family=Muli);body{font-family: "Muli", sans-serif;}.center {margin: auto;width: 60%;border:3px solid #B6202C;padding: 10px;}</style></head>';
        $message .= '<body style="font-family: "Muli";" >';
        $message .= '<div class="center">';
        $message .= '<p>Dear W15 Team</p>';
        $message .= '<p>The following online booking was received today:<p>';
        $message .= '<br>';
        $message .= '<table border="0">';
        $message .= '<tbody>';
        $message .= '<tr><td style="font-weight: bold;">Reservation ID:</td><td>'.$clientDetails["reservationCode"].'</td></tr>';
        $message .= '<tr><td style="font-weight: bold;">Name:</td><td>' . $clientDetails["title"] . ' ' . $clientDetails["firstName"] . ' ' . $clientDetails["surName"] . '</td></tr>';
        $message .= '<tr><td style="font-weight: bold;">Email:</td><td>'.$clientDetails["email"].'</td></tr>';
        $message .= '<tr><td style="font-weight: bold;">Telephone:</td><td>' . $clientDetails["phoneNumber"] . '</td></tr>';
        $message .= '<tr><td style="font-weight: bold;">Address:</td><td>' . $clientDetails["address"] . ' ' . $clientDetails["streetAddress"] . ' ' . $clientDetails["city"] . '</td></tr>';
        $message .= '<tr style="5px;"></tr>';
        $message .= '<tr><td style="font-weight: bold;">Room Details: </td><td></td></tr>';
        $message .= '</tbody>';
        $message .= '</table>';
        $message .= '<table border="0"><thead><tr style="font-weight: bold"><td width="200">Room Type</td><td width="100">Arrival<br> Date</td><td width="100">Departure Date</td><td width="100">Nights</td><td width="150">Meal Plan</td><td width="50">Adult Count</td><td width="50">Child Count</td><td width="100">Price<br> (USD)</td></tr></thead>';
        $message .= '<tbody>';
        foreach ($reservedRooms as $rooms => $room) {
            $message .= '<tr>';
            $message .= '<td>' . $room["roomType"] . '</td>';
            $message .= '<td>' . $function->displayDateFormat($room["arrivalDate"]) . '</td>';
            $message .= '<td>' . $function->displayDateFormat($room["depatureDate"]) . '</td>';
            $message .= '<td>' . $this->getDayCount($room["arrivalDate"],$room["depatureDate"])  . '</td>';
            $message .= '<td>' . $room["mealPlane"]  . '</td>';
            $message .= '<td>' . $room["adultCount"] . '</td>';
            $message .= '<td>' . $room["childCount"] . '</td>';
            $message .= '<td>' . number_format($room["rate"]*$this->getDayCount($room["arrivalDate"],$room["depatureDate"]),2). '</td>';
            $message .= '</tr>';
        }
        $message .= '</tbody>';
        $message .= '</table>';

        $from = "onlinereservation@inhotsolutions.com";
        $headers = "From:" . $from . "\r\n";
        $headers .= "Bcc:support@inhotsolutions.com" . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        error_log($message);
        mail($to, $subject, $message, $headers);
    }


    /**
     * @param $reservation_id
     * @return array
     */
    public function getReservationDetails($reservation_id)
    {

        $connection = $this->getConnection();

        $reserved[] = '';

        $sql = "SELECT * FROM tblreservation a INNER JOIN tblpaymentdetails b ON a.reservationCode = b.reservationCode WHERE a.reservationCode = '$reservation_id'";
        error_log("getReservationDetails :- ".$sql);
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reserved["propertyCode"] = $row["propertyCode"];
                $reserved["reservationCode"] = $row["reservationCode"];
                $reserved["title"] = $row["title"];
                $reserved["firstName"] = $row["firstName"];
                $reserved["surName"] = $row["surName"];
                $reserved["address"] = $row["address"];
                $reserved["streetAddress"] = $row["streetAddress"];
                $reserved["city"] = $row["city"];
                $reserved["country"] = $row["country"];
                $reserved["email"] = $row["email"];
                $reserved["nic_passportNumber"] = $row["nic_passportNumber"];
                $reserved["postalCode"] = $row["postalCode"];
                $reserved["phoneNumber"] = $row["phoneNumber"];
                $reserved["adult"] = $row["adult"];
                $reserved["child"] = $row["child"];
                $reserved["child"] = $row["child"];
                //
                $reserved["total"] = $row["total"];
                $reserved["currency"] = $row["currency"];
                $reserved["paymentDate"] = $row["paymentDate"];
                $reserved["paymentTime"] = $row["paymentTime"];
                $reserved["paymentCode"] = $row["paymentCode"];

            }
        }

        $connection->close();
        return $reserved;
    }


    /**
     * @param $reservation_id
     * @return array
     */
    public function getReservedRoomDetails($reservation_id)
    {

        $connection = $this->getConnection();

        $reservedRooms = array();

        $sql = "SELECT * FROM tblreserved_rooms a INNER JOIN tblroomtype b ON a.roomTypeCode = b.rtCode WHERE reservation_id = '$reservation_id'";
        error_log('getReservedRoomDetails : '.$sql);
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reserved["reservation_id"] = $row["reservation_id"];
                $reserved["roomTypeCode"] = $row["roomTypeCode"];
                $reserved["arrivalDate"] = $row["arrivalDate"];
                $reserved["depatureDate"] = $row["depatureDate"];
                $reserved["adultCount"] = $row["adultCount"];
                $reserved["childCount"] = $row["childCount"];
                $reserved["rate"] = $row["rate"];
                $reserved["currency"] = $row["currency"];
                $reserved["mealPlane"] = $row["mealPlane"];
                $reserved["rtCode"] = $row["rtCode"];
                $reserved["roomType"] = $row["roomType"];
                $reserved["roomDescription"] = $row["roomDescription"];
                $reserved["numberOfRooms"] = $row["numberOfRooms"];
                $reserved["paxCount"] = $row["paxCount"];
                $reserved["extrapaxCount"] = $row["extrapaxCount"];
                array_push($reservedRooms, $reserved);

            }
        }

        $connection->close();
        return $reservedRooms;
    }


    /**
     * @param $reservation_id
     * @param $transactionId
     * @return bool|mysqli_result
     */
    public function confirmedReservation($reservation_id,$transactionId)
    {
        $connection = $this->getConnection();
        $sql = "UPDATE tblreservation SET emailStatus = '1', confirmed = '1',transactionId = '$transactionId' WHERE reservationCode = '$reservation_id'";
        $result = $connection->query($sql);
        $connection->close();
        return $result;

    }

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