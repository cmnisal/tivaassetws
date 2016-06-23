<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 8/23/2015
 * Time: 11:53 PM
 */

class PaymentService {


    /**
     * @return mysqli|null
     */
    private function getConnection(){

        $connection = new DbConnection();
        return $connection->openConnection();

    }

    /**
     * @param $reservation_id
     * @param $propertyCode
     * @return int
     */
    public function getReservationTotal($reservation_id,$propertyCode){
        $connection = $this->getConnection();
        $totalAmount = 0;
        $sql = "SELECT total FROM tblpaymentdetails WHERE propertyCode = '$propertyCode' AND reservationCode = '$reservation_id'";
        //error_log($sql);
        $result = $connection->query($sql);
        if($result->num_rows >0) {
            while ($row = $result->fetch_assoc()) {
                $totalAmount = $row["total"];
            }
        }

        $connection->close();
        error_log($totalAmount*100);
        return ($totalAmount*100);
    }

}