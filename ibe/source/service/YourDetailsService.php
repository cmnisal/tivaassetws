<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-07-23
 * Time: 2:37 PM
 */

class YourDetailsService {

    private $numOfYears = 10;
    private $numOfMonth = 12;


    /**
     * @return mysqli|null
     */
    private function getConnection(){

        $connection = new DbConnection();
        return $connection->openConnection();

    }


    /**
     * @return array
     */
    public function getExpireYears(){
        $currentYear =  date("Y");
        $yearArray[] = array();

        for($i=0;$i<$this->numOfYears;$i++){

            $yearArray[$i] = $currentYear++;

        }

        return$yearArray;

    }

    /**
     * @return mixed
     */
    public function getExpireMonth(){
        $monthArray[] = array();

        $monthCount = 1;
        for($i=0;$i<$this->numOfMonth;$i++){
            $monthArray[$i] = $monthCount++;
        }

        return $monthArray;

    }

    /**
     * @return bool|mysqli_result
     */
    public function getTitles(){

        $connection = $this->getConnection();
        $sql = "SELECT * FROM tbltitle";
        $result = $connection->query($sql);
        $connection->close();

        return $result;

    }


    /**
     * @return bool|mysqli_result
     */
    public function getCountries(){

        $connection = $this->getConnection();
        $sql = "SELECT countryName,countryCode FROM tblcountry ORDER BY countryName ASC";
        $result = $connection->query($sql);
        $connection->close();

        return $result;

    }

    /**
     * @return bool|mysqli_result
     */
    public function getCardTypes(){

        $connection = $this->getConnection();
        $sql = "SELECT * FROM tblcardtype";
        $result = $connection->query($sql);
        $connection->close();

        return $result;


    }

    /**
     * @param $reservationCode
     * @return bool
     */
    public function checkGivenReservationCodeExist($reservationCode){

        $connection = $this->getConnection();

        $sql = "SELECT * FROM tblreservation WHERE reservationCode = '$reservationCode'";
        //error_log($sql);
        $result = $connection->query($sql);

        $connection->close();

        if($result->num_rows > 0){
            return true;
        }else{
            return false;
        }

    }
}