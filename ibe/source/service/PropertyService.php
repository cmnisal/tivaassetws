<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 8/9/2015
 * Time: 7:01 PM
 */

//include "../datasource/DbConnection.php";

class PropertyService {

    /**
     * @return mysqli|null
     */
    private function getConnection()
    {

        $connection = new DbConnection();
        return $connection->openConnection();

    }


    /**
     * @param $companyCode
     * @return array
     */
    public function getPropertyDetails($companyCode,$propertyCode){

        $connection = $this->getConnection();

        if(isset($companyCode) && !empty($companyCode) && isset($propertyCode) && !empty($propertyCode)){

            $sql = "SELECT * FROM tblproperty WHERE companyCode = '$companyCode' AND propertyCode = '$propertyCode'";
            //error_log($sql);
            $result = $connection->query($sql);

            $property[] = '';

            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){

                    $property["propertyCode"] = $row["propertyCode"];
                    $property["propertyName"] = $row["propertyName"];
                    $property["propertyPhoneNumber"] = $row["propertyPhoneNumber"];
                    $property["propertyFaxNumber"] = $row["propertyFaxNumber"];
                    $property["Address"] = $row["Address"];
                    $property["streetAddress"] = $row["streetAddress"];
                    $property["city"] = $row["city"];
                    $property["country"] = $row["country"];
                    $property["propertyEmail"] = $row["propertyEmail"];
                    $property["companyCode"] = $row["companyCode"];

                }
            }
        }else{
            // need to add error page.
        }
        $connection->close();
        return $property;
    }

    /**
     * @param $propertyCode
     * @return array
     */
    public function getPropertyDetailsByPropertyCode($propertyCode){

        $connection = $this->getConnection();

        if(isset($propertyCode) && !empty($propertyCode)){

            $sql = "SELECT * FROM tblproperty WHERE propertyCode = '$propertyCode'";
            $result = $connection->query($sql);

            $property[] = '';

            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){

                    $property["propertyCode"] = $row["propertyCode"];
                    $property["propertyName"] = $row["propertyName"];
                    $property["propertyPhoneNumber"] = $row["propertyPhoneNumber"];
                    $property["propertyFaxNumber"] = $row["propertyFaxNumber"];
                    $property["Address"] = $row["Address"];
                    $property["streetAddress"] = $row["streetAddress"];
                    $property["city"] = $row["city"];
                    $property["country"] = $row["country"];
                    $property["propertyEmail"] = $row["propertyEmail"];
                    $property["companyCode"] = $row["companyCode"];

                }
            }
        }else{
            // need to add error page.
        }

        $connection->close();
        return $property;

    }


    /**
     * @param $propertyCode
     * @return array
     */
    public function getPropertyPolicy($propertyCode)
        {

        $connection = $this->getConnection();

        if (isset($propertyCode) && !empty($propertyCode)) {

            $sql = "SELECT * FROM tblpropertypolicy WHERE propertyCode = '$propertyCode'";
            $result = $connection->query($sql);

            $propertyPolicy[] = '';

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    $propertyPolicy["propertyCode"] = $row["propertyCode"];
                    $propertyPolicy["cancellationPolicy"] = $row["cancellationPolicy"];
                    $propertyPolicy["refundPolicy"] = $row["refundPolicy"];
                    $propertyPolicy["depositPolicy"] = $row["depocityPolicy"];
                    $propertyPolicy["childPolicy"] = $row["childPolicy"];
                    $propertyPolicy["petPolicy"] = $row["petPolicy"];

                }
            }

        }


        $connection->close();
        return $propertyPolicy;
    }


    /**
     * @param $propertyCode
     * @return string
     */
    public function getPropertyLogoPath($propertyCode,$imageType){

        $connection = $this->getConnection();

        $sql = "SELECT * FROM tblpropertyimage WHERE propertyCode = '$propertyCode' AND imgType = '$imageType'";
        $result = $connection->query($sql);

        $imgPath = '';

        if($result->num_rows > 0){

            while($row = $result->fetch_assoc()){

                $imgPath = $row["imgePath"];
            }

        }else{
            //need to add default logo;
        }

        $connection->close();
        return $imgPath;

    }

}

/**
 *
$ps = new PropertyService();
$pol = $ps->getPropertyPolicy("W15-1000");
echo $pol["cancellationPolicy"];
**/