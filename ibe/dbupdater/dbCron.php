<?php
/**
 * Created by PhpStorm.
 * User: lahiruyahampath
 * Date: 12/2/2015
 * Time: 10:08 AM
 */

include "DbConnection.php";

/*
 *
 */
function getDBConnection()
{

    $connection = new DbConnection();
    if ($connection != null) {
        return $connection->openConnection();
    } else {
        die("Connection failed(null) : " . $connection->connect_error);
    }

}

function readEmail()
{
    error_log("\n\n--------------------------------------------------------------Read Mail--------------------------------------------------------------\n\n");
    $connection = getDBConnection();

    if ($connection != null) {
    
    	error_log("\n\n--------------------------------------------------------------Connection Successful--------------------------------------------------------------\n\n");
    
        $server = 'mail.inhotsolutions.com';
        $user = 'w15ibe@inhotsolutions.com';
        $password = 'reservation123';
        $post = 143;

        //connect to the email
        $emailConnect = imap_open('{' . $server . '/notls}', $user, $password);

        //count emails
        $emailCount = imap_num_msg($emailConnect);

        if ($emailCount > 0) {
            for ($i = 1; $i <= $emailCount; $i++) {

                $header = imap_headerinfo($emailConnect, $i);
                $structure = imap_fetchstructure($emailConnect, $i);
                $subjectArray = explode("-", $header->subject);
                $subject = $subjectArray[0];
                $propertyCode = $subjectArray[1];
                $companyCode = $subjectArray[2];

                if (!isset($propertyCode) || empty($propertyCode)) {

                    break;
                }

                if ($subject == "Rooms") {

                    $emailBody = imap_body($emailConnect, $i);
                    if (strlen($emailBody) > 0) {
                        $lines = explode("\n", $emailBody);
                        foreach ($lines as $line) {

                            $data = explode(",", $line);
                            $roomType = $data[0];
                            $year = $data[1];
                            $month = $data[2];

                            if ($roomType != '') {

                                $selectQuery = "SELECT `roomTypeCode` FROM `tblroomforcast` WHERE `roomTypeCode` = '" . $roomType . "' AND `resYear` = '" . $year . "' AND `resMonth` = '" . $month . "' AND propertyCode = '" . $propertyCode . "'";
                                error_log("Room Select Query :- " . $selectQuery);
                                $result = $connection->query($selectQuery);

                                if ($result->num_rows > 0) {

                                    $roomTypes = array();

                                    $updateQuery = "UPDATE `tblroomforcast`
							SET `R01`=" . $data[3] . ",
							`R02`=" . $data[4] . ",
							`R03`=" . $data[5] . ",
							`R04`=" . $data[6] . ",
							`R05`=" . $data[7] . ",
							`R06`=" . $data[8] . ",
							`R07`=" . $data[9] . ",
							`R08`=" . $data[10] . ",
							`R09`=" . $data[11] . ",
							`R10`=" . $data[12] . ",
							`R11`=" . $data[13] . ",
							`R12`=" . $data[14] . ",
							`R13`=" . $data[15] . ",
							`R14`=" . $data[16] . ",
							`R15`=" . $data[17] . ",
							`R16`=" . $data[18] . ",
							`R17`=" . $data[19] . ",
							`R18`=" . $data[20] . ",
							`R19`=" . $data[21] . ",
							`R20`=" . $data[22] . ",
							`R21`=" . $data[23] . ",
							`R22`=" . $data[24] . ",
							`R23`=" . $data[25] . ",
							`R24`=" . $data[26] . ",
							`R25`=" . $data[27] . ",
							`R26`=" . $data[28] . ",
							`R27`=" . $data[29] . ",
							`R28`=" . $data[30] . ",
							`R29`=" . $data[31] . ",
							`R30`=" . $data[32] . ",
							`R31`=" . $data[33] . "
							WHERE
							`resYear` = '" . $year . "' AND
							`resMonth` = '" . $month . "' AND
							`roomTypeCode` = '" . $roomType . "' AND propertyCode = '" . $propertyCode . "'";

                                    error_log("Room Update Query :- " . $updateQuery);

                                    if ($connection->query($updateQuery) != TRUE) {
                                        echo "Update Rooms not complete <br>";
                                    }

                                } else {

                                    $insertQuery = "INSERT INTO `tblroomforcast`
							 VALUES ('" . $year . "',
							 '" . $month . "',
							 '" . $roomType . "',
							 " . $data[3] . ",
							 " . $data[4] . ",
							 " . $data[5] . ",
							 " . $data[6] . ",
							 " . $data[7] . ",
							 " . $data[8] . ",
							 " . $data[9] . ",
							 " . $data[10] . ",
							 " . $data[11] . ",
							 " . $data[12] . ",
							 " . $data[13] . ",
							 " . $data[14] . ",
							 " . $data[15] . ",
							 " . $data[16] . ",
							 " . $data[17] . ",
							 " . $data[18] . ",
							 " . $data[19] . ",
							 " . $data[20] . ",
							 " . $data[21] . ",
							 " . $data[22] . ",
							 " . $data[23] . ",
							 " . $data[24] . ",
							 " . $data[25] . ",
							 " . $data[26] . ",
							 " . $data[27] . ",
							 " . $data[28] . ",
							 " . $data[29] . ",
							 " . $data[30] . ",
							 " . $data[31] . ",
							 " . $data[32] . ",
							 " . $data[33] . ",
							 '" . $propertyCode . "')";

                                    error_log("Insert Room :- " . $insertQuery);


                                    if ($connection->query($insertQuery) != TRUE) {
                                        echo "Rooms Inserting not complete <br>";
                                    }
                                }
                            }
                        }
                    }
                } else if ($subject == "Contracts") {

                    $emailBody = imap_body($emailConnect, $i);
                    if (strlen($emailBody) > 0) {
                        $lines = explode("\n", $emailBody);
                        foreach ($lines as $line) {
                            $data = explode(",", $line);
                            $contractCode = $data[2];

                            if ($contractCode != '') {

                                $selectQuery = "SELECT `toCode` FROM `tblcontract` WHERE `contractCode` = '" . $contractCode . "' AND `propertyCode` = '" . $propertyCode . "'";
                                error_log(" Select Contract : " . $selectQuery);

                                $result = $connection->query($selectQuery);

                                if ($result->num_rows > 0) {
                                    $updateQuery = "UPDATE `tblcontract`
							                    SET `toCode`='" . $data[0] . "',
							                        `marketCode`='" . $data[1] . "',
							                        `contractStartDate`='" . $data[3] . "',
							                        `contractEndDate`='" . $data[4] . "',
							                        `description`='" . $data[5] . "',
							                        `mapContarct`='" . $data[6] . "'
                                                     WHERE `contractCode` = '" . $contractCode . "' AND `propertyCode` = '" . $propertyCode . "'";
                                    error_log("update Contract : -" . $updateQuery);

                                    if ($connection->query($updateQuery) != TRUE) {
                                        echo "Contract update not complete <br>";
                                    }

                                } else {

                                    $insertQuery = "INSERT INTO `tblcontract`(`toCode`, `marketCode`, `contractCode`, `contractStartDate`, `contractEndDate`, `description`, `mapContarct`,`companyCode`,`propertyCode`)  VALUES ('" . $data[0] . "','" . $data[1] . "','" . $data[2] . "','" . $data[3] . "','" . $data[4] . "','" . $data[5] . "','" . $data[6] . "','" . $companyCode . "','" . $propertyCode . "')";
                                    error_log(" Insert Contract : -" . $insertQuery);
                                    if ($connection->query($insertQuery) != TRUE) {
                                        echo "Contract inserting not complete <br>";
                                    }
                                }
                            }

                        }
                    }

                } else if ($subject == "Rates") {

                    $emailBody = imap_body($emailConnect, $i);
                    if (strlen($emailBody) > 0) {

                        $lines = explode("\n", $emailBody);
                        $lineNo = 0;

                        foreach ($lines as $line) {

                            $data = explode(",", $line);

                            if ($lineNo == 0) {
                                foreach ($data as $contractNo) {
                                    if ($contractNo != "") {
                                        $deleteQuery = "DELETE FROM `tblrates` WHERE `contractCode` = '" . $contractNo . "' AND `propertyCode` = '" . $propertyCode . "'";
                                        if ($connection->query($deleteQuery) != TRUE) {
                                            echo "Contract inserting not complete <br>";
                                        }
                                    } else {
                                        break;
                                    }
                                }
                                $lineNo++;
                            } else {
                                $toCode = $data[0];
                                if ($toCode != "") {
                                    $insertQuery = "INSERT INTO `tblrates`
									VALUES ('','" . $data[8] . "',
									'" . $data[0] . "',
									'" . $data[1] . "',
									'" . $data[2] . "',
									'" . $data[3] . "',
									'" . $data[4] . "',
									'" . $data[5] . "',
									'" . $data[6] . "',
									'" . $data[7] . "',
									'" . $propertyCode . "')";

                                    error_log("Rates inserting : -" . $insertQuery);

                                    if ($connection->query($insertQuery) != TRUE) {
                                        echo "Contract inserting not complete <br>";
                                    }
                                }
                                $lineNo++;
                            }
                        }
                    }
                }
                imap_delete($emailConnect, $i);
            }

            $connection->close();

            imap_expunge($emailConnect);
            imap_close($emailConnect, CL_EXPUNGE);
        }

        //$connection->close();
        exit(0);

    }else{
    	error_log("\n\n--------------------------------------------------------------Connection error--------------------------------------------------------------\n\n");
        die("Connection failed(null) : " . $connection->connect_error);
    }
    
    
    
  
}

/**
 *
 */
readEmail();