<?php

/**
 * Email Sending 
 * 14/06/2016 
 * NC
 * Texonic IT
 */

class EmailSend {

    public function sendClientMails($clientDetails, $propertyDetails, $reservedRooms) {

        $function = new Functions();

        $to = $clientDetails["email"];

        $subject = $propertyDetails["propertyName"] . " online booking confirmation - Reservation ID " . $clientDetails["reservationCode"] . " (Beta Version)";

        $subject = $propertyDetails["propertyName"] . " online booking confirmation - Reservation ID " . $clientDetails["reservationCode"] . "";

        $message = '<html>';
        $message .= '<head><style>@import url(https://fonts.googleapis.com/css?family=Muli);body{font-family: "Muli", sans-serif;}.center {margin: auto;width: 60%;border:3px solid       #B6202C;padding: 10px;}</style></head>';
        $message .= '<body style="font-family: "Muli";" >';
        $message .= '<div class="center">';
        $message .= '<p>Dear ' . $clientDetails["title"] . ' ' . $clientDetails["firstName"] . ' ' . $clientDetails["surName"] . '</p>';
        $message .= '<p> room reservation. Your reservation details are as follows:<p>';
        $message .= '<br>';
        $message .= '<table border="0">';
        $message .= '<tbody>';
        $message .= '<tr><td style="font-weight: bold;">Reservation ID:</td><td>' . $clientDetails["reservationCode"] . '</td></tr>';
        $message .= '<tr><td style="font-weight: bold;">Name:</td><td>' . $clientDetails["title"] . ' ' . $clientDetails["firstName"] . ' ' . $clientDetails["surName"] . '</td></tr>';
        $message .= '<tr><td style="font-weight: bold;">Telephone:</td><td>' . $clientDetails["phoneNumber"] . '</td></tr>';
        $message .= '<tr><td style="font-weight: bold;">Address:</td><td>' . $clientDetails["address"] . ' ' . $clientDetails["streetAddress"] . ' ' . $clientDetails["city"] . '</td></tr>';
        $message .= '<tr style="5px;"></tr>';
        $message .= '<tr><td style="font-weight: bold">Room Details: </td><td></td></tr>';
        $message .= '</tbody>';
        $message .= '</table>';
        $message .= '<table border="0"><thead><tr style="font-weight: bold"><td width="200">Room Type</td><td width="100">Arrival<br> Date</td><td width="100">Departure Date</td><td   width="100">Nights</td><td width="150">Meal Plan</td><td width="50">Adult Count</td><td width="50">Child Count</td><td width="100">Price<br> (USD)</td></tr></thead>';
        foreach ($reservedRooms as $rooms => $room) {
            $message .= '<tr>';
            $message .= '<td>' . $room["roomType"] . '</td>';
            $message .= '<td>' . $function->displayDateFormat($room["arrivalDate"]) . '</td>';
            $message .= '<td>' . $function->displayDateFormat($room["depatureDate"]) . '</td>';
            $message .= '<td>' . $this->getDayCount($room["arrivalDate"], $room["depatureDate"]) . '</td>';
            $message .= '<td>' . $room["mealPlane"] . '</td>';
            $message .= '<td>' . $room["adultCount"] . '</td>';
            $message .= '<td>' . $room["childCount"] . '</td>';
            $message .= '<td>' . number_format($room["rate"] * $this->getDayCount($room["arrivalDate"], $room["depatureDate"]), 2) . '</td>';
            $message .= '</tr>';
        }
        $message .= '</tbody>';
        $message .= '</table>';
        $message .= '<p>Please quote the above reference number should you contact us for any queries. Our hotline number is ' . $propertyDetails["propertyPhoneNumber"] . ' or email us     at ' . $propertyDetails["propertyEmail"] . '. We kindly request you to always carry a copy of this confirmation email.</p>';
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

}
