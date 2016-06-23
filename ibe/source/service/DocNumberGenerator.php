<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-08-06
 * Time: 7:14 PM
 */

class DocNumberGenerator {


    /**
     * @return mysqli|null
     */
    private function getConnection()
    {

        $connection = new DbConnection();
        return $connection->openConnection();

    }

    /**
     * @return string
     */
    public function getGeneratedNumber($docCode){

        $connection = $this->getConnection();

        $docNumber = '1000';

        $sql = "SELECT * FROM tbldocnumgenarator where docCode='".$docCode."'";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $docCode = $row["docCode"];
                $docNumber = $row["docNumber"];
            }

            $newDocNumber =  $docNumber + 1;

            $sql = "UPDATE tbldocnumgenarator set docNumber='" . $newDocNumber . "' where docCode ='".$docCode."'";
            $connection->query($sql);
        }

        $connection->close();
        return $docCode."".$docNumber;
    }
}