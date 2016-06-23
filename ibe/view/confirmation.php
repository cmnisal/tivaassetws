<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-07-20
 * Time: 2:10 PM
 */

include "../source/datasource/DbConnection.php";
include "../source/service/confirmationService.php";
include "../source/service/PropertyService.php";
include "../source/service/ViewBucketService.php";
include "../source/service/RoomUpdateService.php";
include "../source/service/Functions.php";

$amount = null2unknown($_GET["vpc_Amount"]);
$locale = null2unknown($_GET["vpc_Locale"]);
$batchNo = null2unknown($_GET["vpc_BatchNo"]);
$command = null2unknown($_GET["vpc_Command"]);
$message = null2unknown($_GET["vpc_Message"]);
$version = null2unknown($_GET["vpc_Version"]);
$cardType = null2unknown($_GET["vpc_Card"]);
$orderInfo = null2unknown($_GET["vpc_OrderInfo"]);
$receiptNo = null2unknown($_GET["vpc_ReceiptNo"]);
$merchantID = null2unknown($_GET["vpc_Merchant"]);
$authorizeID = null2unknown($_GET["vpc_AuthorizeId"]);
$merchTxnRef = null2unknown($_GET["vpc_MerchTxnRef"]);
$transactionNo = null2unknown($_GET["vpc_TransactionNo"]);
$acqResponseCode = null2unknown($_GET["vpc_AcqResponseCode"]);
$txnResponseCode = null2unknown($_GET["vpc_TxnResponseCode"]);


// If input is null, returns string "No Value Returned", else returns input
function null2unknown($data)
{
    if ($data == "") {
        return "No Value Returned";
    } else {
        return $data;
    }
}

// Show 'Error' in title if an error condition
$errorTxt = "";

// Show this page as an error page if vpc_TxnResponseCode equals '7'
if ($txnResponseCode == "No Value Returned" || $errorExists) {
    $errorTxt = "Error";
}

// This is the display title for 'Receipt' page
$title = $_GET["Title"];

$confirmationService = new confirmationService();

$reservation_id = $orderInfo;

$reservationDetails = $confirmationService->getReservationDetails($reservation_id);

$reservedRooms = $confirmationService->getReservedRoomDetails($reservation_id);

$property = new PropertyService();

$functions = new Functions();

$logoPath = $property->getPropertyLogoPath($reservationDetails["propertyCode"], "logo");

$imgPath = $property->getPropertyLogoPath($reservationDetails["propertyCode"], "img");

if (!isset($imgPath) || empty($imgPath)) {
    $imgPath = "http://placehold.it/250x190";
}

$propertyPolicy = $property->getPropertyPolicy($reservationDetails["propertyCode"]);
$propertyDetails = $property->getPropertyDetailsByPropertyCode($reservationDetails["propertyCode"]);
$viewBucketService = new ViewBucketService();

if ($txnResponseCode == 0) {

    $confirmationService->sendClientMails($reservationDetails, $propertyDetails, $reservedRooms);
    $confirmationService->sendHotelMails($reservationDetails, $propertyDetails, $reservedRooms);
    $confirmationService->confirmedReservation($reservation_id, $transactionNo);

    //$updateAndConfirm = new RoomUpdateService();
    //$updateAndConfirm->updateAndConfirmRoomAvailability($reservedRooms, $reservationDetails["propertyCode"], "M");

} else {

    if(isset($reservedRooms) && !empty($reservedRooms)) {
      $updateAndConfirm = new RoomUpdateService();
       $updateAndConfirm->updateAndConfirmRoomAvailability($reservedRooms,$reservationDetails["propertyCode"],"A");
    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Confirmation</title>

    <!-- Bootstrap Core CSS -->
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap2-toggle.min.css" rel="stylesheet">
    <link href="bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="custome-alignment.css" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="customize-css.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<!-- Page Content -->
<div class="container">

    <!-- include header -->
    <?php
    include "header.php";
    ?>

    <div class="row room-page-headers">
        <h4>CONFIRMATION</h4>

        <?php
        if ($txnResponseCode == "0"){
        ?>
        <p>Your reservation has been confirmed successfully. Thank you for choosing to stay at <?php echo $propertyDetails["propertyName"] ?>. Your reservation number is <?php echo $reservation_id ?>. Your transaction number
            is <?php echo $transactionNo ?>.

        <p>

        <p>Please find the details of your reservation below. A confirmation email has also been sent
            to <?php echo $reservationDetails["email"] ?><p>
            <?php
            }else if ($txnResponseCode == "1" || $txnResponseCode == "3" || $txnResponseCode == "6" || $txnResponseCode == "7"){
            ?>
            <p>Reservation unsuccessful.</p>
            <p style="color: firebrick">Transaction unsuccessful, please try again.<p>
            
            <?php
            } else{
            ?>
        <p>Reservation unsuccessful.</p>
        <p style="color: firebrick">Transaction rejected, please contact your bank.<p>
            <?php
            }
            ?>
    </div>
    <!-- /.row -->
    <div class="row page-highlights">
        <div class="row confirm-info">
            <div class="row room-page-headers">
                <h4>Your Reservation</h4>
            </div>

            <div class="row">
                <div class="col-sm-3 padding-left-30">
                    <img class="img-responsive room-type-img" src="<?php echo $imgPath ?>" alt="Room Image">
                </div>
                <div class="col-sm-8">
                    <div class="page-subheaders-container">
                        <h4><?php echo $propertyDetails["propertyName"]?></h4>
                        <span
                            class="subheader-description"><?php echo $propertyDetails["streetAddress"] . " " . $propertyDetails["city"] . " " . $propertyDetails["country"] ?></span>
                        <hr class="name-underline">
                    </div>
                    <div>
                        <h6 class="unite-subheaders">Check In / Check Out Times:</h6>
                        <p class="padding-left-30 unite-subdescription">
                            <span>Check In After 2:00 PM</span><br/>
                            <span>Check Out Before 11:00 AM</span>
                        </p>
                    </div>

                </div>
            </div>

            <!-- /.row -->
            <div class="row col-lg-13 padding-around-20">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Guest Name</th>
                        <th class="text-center">Dates</th>
                        <th class="text-center">Nights</th>
                        <th class="text-center">Room & Meal Plan</th>
                        <th class="text-center">Adult</th>
                        <th class="text-center">Child</th>
                        <th class="text-center">Total Price <br/>Including Taxes</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $totalRoomBill = 0.0;
                    $currency = '';

                    foreach ($reservedRooms as $rooms => $room) {
                        $stringArrivalDate = $room["arrivalDate"];
                        $stringDepartureDate = $room["depatureDate"];
                        $dayCount = $viewBucketService->getDayCount(trim($room['arrivalDate']), trim($room['depatureDate']));
                        $totalRoomBill += ($room["rate"]*$dayCount);
                        $currency = $room["currency"];
                        ?>
                        <tr>
                            <td><?php echo $reservationDetails["title"] . " " . $reservationDetails["firstName"]." ".$reservationDetails["surName"] ?>
                                <br/>Reservation Number : <?php echo $reservation_id ?>
                            </td>
                            <td class="text-center"><?php echo $functions->displayDateFormat($stringArrivalDate) . "<br/> to <br/>" . $functions->displayDateFormat($stringDepartureDate) ?></td>
                            <td class="text-center"><?php echo $dayCount ?></td>
                            <td class="text-center">
                                Room Type: <?php echo $room["roomType"] ?> <br/>
                                Meal Plan: <?php echo $room["mealPlane"] ?></td>
                            <td class="text-center"><?php echo $room["adultCount"] ?></td>
                            <td class="text-center"><?php echo $room["childCount"] ?></td>
                            <td class="text-center"><?php echo $room["currency"] . " " . number_format(($room["rate"]*$dayCount), 2) ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <th colspan="6" class="text-right">
                            Total Price Including Taxes:
                        </th>
                        <td class="text-center"><?php echo $currency . " " . number_format($totalRoomBill, 2) ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-right">
                            Total Paid:
                        </th>
                        <td class="text-center"><?php echo $currency . " " . number_format(($amount / 100), 2) ?></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.row -->
        </div>
    </div>
    <!-- /.row -->

    <div class="row room-page-headers padding-top-20">
        <h4>YOUR DETAILS</h4>
    </div>
    <!-- /.row -->

    <div class="row page-highlights">
        <div class="row unite-container">
            <div class="page-subheaders-container">
                <h5 class="unite-headers-text">Contact Details</h5>
            </div>
            <div class="col-lg-6">
                <div class="padding-around-5">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>Name:</td>
                            <td><?php echo $reservationDetails["title"] . " " . $reservationDetails["firstName"] . " " . $reservationDetails["surName"] ?></td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td><?php echo $reservationDetails["address"] . " " . $reservationDetails["streetAddress"] . " " . $reservationDetails["city"] . ", " . $reservationDetails["country"] ?></td>
                        </tr>
                        <tr>
                            <td>Phone:</td>
                            <td><?php echo $reservationDetails["phoneNumber"] ?></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><?php echo $reservationDetails["email"] ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="padding-around-5">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>Zip/Postal Code:</td>
                            <td><?php echo $reservationDetails["postalCode"] ?></td>
                        </tr>
                        <tr>
                            <td>Country:</td>
                            <td><?php echo $reservationDetails["country"] ?></td>
                        </tr>
                        <tr>
                            <td>NIC/Passport Number:</td>
                            <td><?php echo $reservationDetails["nic_passportNumber"] ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->

    <div class="row room-page-headers padding-top-20">
        <h4>TERMS & CONDITIONS</h4>
    </div>
    <!-- /.row -->

    <div class="row page-highlights">
        <div class="row unite-container">
            <table class="table table-striped">
                <tbody>
                <?php
                if (!empty($propertyPolicy)) {
                    ?>
                    <tr>
                        <td width="200">Cancellation Policy:</td>
                        <td><p class="text-justify"> <?php echo $propertyPolicy["cancellationPolicy"] ?><br/></p>

                            <p class="text-justify">A non-refundable deposit
                                of <?php echo number_format($totalRoomBill, 2) . " " . $currency ?> is charged at
                                the time of booking.</p>
                        </td>
                    </tr>
                    <tr>
                        <td width="200">Child Policy:</td>
                        <td><p class="text-justify"><?php echo $propertyPolicy["childPolicy"] ?></p></td>
                    </tr>
                    <tr>
                        <td width="200">Pet Policy:</td>
                        <td><p class="text-justify"><?php echo $propertyPolicy["petPolicy"] ?></p></td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.row -->

    <div class="row padding-around-20 text-right">
        <a href="http://www.w15.lk">
            <button type="button" class="btn btn-primary">Book Again</button>
        </a>
    </div>

    <hr>

    <!-- include footer  -->
    <?php
    include "footer.php";
    ?>

</div>
<!-- /.container -->


<!-- jQuery -->
<script src="jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="bootstrap.min.js"></script>

<!-- Toggle button JavaScript -->
<script src="bootstrap2-toggle.min.js"></script>

<!-- Moment Lib Javascript -->
<script src="moment.min.js"></script>

<!-- boostrap datepicker  Javascript -->
<script src="bootstrap-datepicker.min.js"></script>

<!-- Customize JavaScript -->
<script src="customize-js.js"></script>

</body>

</html>