<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-07-21
 * Time: 3:36 PM
 */

if (!session_start()) {
    session_start();
}

include "../source/service/PropertyService.php";
include "../source/datasource/DbConnection.php";
include "../source/service/ViewBucketService.php";
include "../source/service/PaymentService.php";
include "../source/service/Functions.php";

if (!isset($_SESSION["roomBucket"]) || empty($_SESSION["roomBucket"]) || empty($_SESSION["customerDetails"])) {
    session_destroy();
    $return_url = "../index.php";
    header('Location:' . $return_url);
    exit();
}

$logoPath = $_SESSION["logoPath"];
$propertyImagePath = $_SESSION["propertyImagePath"];

$reservationId = '';
$title = '';
$firstName = '';
$surName = '';
$address_1 = '';
$address_2 = '';
$town_city = '';
$nic_passportNumber = '';
$zip_postalCode = '';
$country = '';
$phoneNumber = '';
$email = '';
$cardType = '';

$current_url = urlencode($url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

foreach ($_SESSION["customerDetails"] as $customers => $cus) {
    $reservationId = $customers;
    $title = $cus["title"];
    $firstName = $cus["firstName"];
    $surName = $cus["surName"];
    $address_1 = $cus["address_1"];
    $address_2 = $cus["address_2"];
    $town_city = $cus["town_city"];
    $nic_passportNumber = $cus["nic_passportNumber"];
    $zip_postalCode = $cus["zip_postalCode"];
    $country = $cus["country"];
    $phoneNumber = $cus["phoneNumber"];
    $email = $cus["email"];
    $cardType = $cus["cardType"];
}

$propertyService = new PropertyService();
$policies = $propertyService->getPropertyPolicy($_SESSION["propertyCode"]);

$viewBucketService = new ViewBucketService();
$paymentService = new PaymentService();
//
$functions = new Functions();

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Review and Pay</title>

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
        <h4>REVIEW AND PAY</h4>
        <span>Please check the details below carefully and if you're happy confirm the booking.</span>
    </div>
    <!-- /.row -->
    <div class="row page-highlights">
        <div class="row confirm-info">
            <div class="row room-page-headers">
                <h4>YOUR BOOKING SUMMARY</h4>
            </div>

            <div class="row">
                <div class="col-sm-3 padding-left-30">
                    <img class="img-responsive room-type-img"
                         src="<?php echo $propertyImagePath ?>" alt='Property Image'>
                </div>
                <div class="col-sm-8">
                    <div class="page-subheaders-container">
                        <h4><?php echo $_SESSION["propertyDetails"]["propertyName"] ?></h4>
                        <span
                            class="subheader-description"><?php echo $_SESSION["propertyDetails"]["streetAddress"] . " | " . $_SESSION["propertyDetails"]["city"] . " | " . $_SESSION["propertyDetails"]["country"] ?></span>
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
                <table class="table table-font-size">
                    <thead>
                    <tr>
                        <th class="text-left">Guest Name</th>
                        <th class="text-center">Dates</th>
                        <th class="text-center">Nights</th>
                        <th class="text-center">Room & Meal Plan</th>
                        <th class="text-center">Adult</th>
                        <th class="text-center">Child</th>
                        <th class="text-center">Total Price <br/> Including Taxes</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $totalRoomBill = 0.0;
                    $currency = '';

                    foreach ($_SESSION["roomBucket"] as $rooms => $room) {
                        $stringArrivalDate = $room["arrival_date"];
                        $stringDepartureDate = $room["departure_date"];
                        $dayCount = $room["totalNights"];
                        $totalRate = $room["averageRoomRate"] * $dayCount;
                        $totalRoomBill += ($room["averageRoomRate"] * $dayCount);
                        $currency = $room["currencyCode"];
                        ?>
                        <tr>
                            <td><?php echo $title . " " . $firstName." ".$surName ?>
                                <br/>Reservation Number: <?php echo $reservationId ?>
                            </td>
                            <td class="text-center"><?php echo $functions->displayDateFormat($stringArrivalDate) . "<br/> to <br/>" . $functions->displayDateFormat($stringDepartureDate) ?></td>
                            <td class="text-center"><?php echo $dayCount ?></td>
                            <td class="text-center">Room Type: <?php echo $room["roomType"] ?> <br/>
                                Meal Plan: <?php echo $room["mealPlane"] ?></td>
                            <td class="text-center"><?php echo $room["adult_count"] ?></td>
                            <td class="text-center"><?php echo $room["child_count"] ?></td>
                            <td class="text-center"><?php echo $room["currencyCode"] . " " . number_format($totalRate, 2) ?></td>
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
                            Payable Today:
                        </th>
                        <td class="text-center"><?php echo $currency . " " . number_format($totalRoomBill, 2) ?></td>
                        <td></td>
                    </tr>
                    <!--
                    <tr>
                        <th colspan="6" class="text-right">
                            Remaining amount to pay at hotel :
                        </th>
                        <td>USD 00.00</td>
                        <td></td>
                    </tr>
                    -->
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

            <div class="row text-right padding-right-10">
                <a href="yourDetails.php">
                    <button type="button" class="btn btn-primary btn-xs">Edit Details</button>
                </a>
            </div>
            <!-- /.row -->

            <div class=" row page-subheaders-container">
                <h5 class="unite-headers-text">Contact Details</h5>
            </div>

            <div class="col-lg-6">

                <div class="padding-around-5">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>Name:</td>
                            <td><?php echo $title . " " . $firstName . " " . $surName ?></td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td><?php echo $address_1 . " " . $address_2 . " " . $town_city . ", " . $country ?></td>
                        </tr>
                        <tr>
                            <td>Phone:</td>
                            <td><?php echo $phoneNumber ?></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><?php echo $email ?></td>
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
                            <td><?php echo $zip_postalCode ?></td>
                        </tr>
                        <tr>
                            <td>Country:</td>
                            <td><?php echo $country ?></td>
                        </tr>
                        <tr>
                            <td>NIC/Passport Number:</td>
                            <td><?php echo $nic_passportNumber ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
             <div class="row"></div>

            <div class=" row page-subheaders-container">
                <h5 class="unite-headers-text">Payment Details</h5>
            </div>
            
            <div class="col-lg-12">
                <div class="padding-around-5">
                    <table class="table-responsive">
                        <tr>
                            <td width="200">Selected Card Type :</td>
                            <td></td>
                            <td width="200">
                                <?php if ($cardType == 'Master') { ?>
                                    <img src="../images/w15/card_logo/mastercard.jpg" width="100" height="60" alt="Master Card">
                                <?php }else if($cardType == 'Visa'){ ?>
                                    <img src="../images/w15/card_logo/visacard.jpg"  width="100" height="60"alt="Visa Card" >
                                <?php }else if($cardType == 'Amex'){ ?>
                                    <img src="../images/w15/card_logo/amexcard.jpg"  width="100" height="60" alt="Amex Card">
                                <?php } ?>
                            </td>
                        </tr>
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
            <!--<h4>Booking information for <?php //echo $title . "" . $firstName . " " . $surName ?> The W15 Hotel, <?php //echo $reservationId ?></h4>-->

            <div class="row padding-around-5">
                <table class="table table-striped">
                    <tbody>
                    <?php
                    if (!empty($policies)) {
                        if (!empty($policies["depositPolicy"])) {
                            ?>
                            <tr>
                                <td width="200">Deposit Policy:</td>
                                <td><p class="text-justify"><?php echo $policies["depositPolicy"] ?></p></td>
                            </tr>
                        <?php
                        }
                        if (!empty($policies["cancellationPolicy"])) {
                            ?>
                            <tr>
                                <td width="200">Cancellation Policy:</td>
                                <td><p class="text-justify"> <?php echo $policies["cancellationPolicy"] ?></p></td>
                            </tr>
                        <?php
                        }
                        if (!empty($policies["childPolicy"])) {
                            ?>
                            <tr>
                                <td width="200">Child Policy:</td>
                                <td><p class="text-justify"><?php echo $policies["childPolicy"] ?></p></td>
                            </tr>
                        <?php
                        }

                        if (!empty($policies["childPolicy"])) {
                            ?>
                            <tr>
                                <td width="200">Pet Policy:</td>
                                <td><p class="text-justify"><?php echo $policies["petPolicy"] ?></p></td>
                            </tr>
                        <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->

    <div class="row term-condition-row">
        <!--
        <div class="row page-subheaders-container">
            <h4>Term and Conditions:</h4>
        </div> -->

        <div class="checkbox term-condition-agreement text-left">
            <label><input id="termCondition" type="checkbox" value="">I have read and accepted the <a href="#">Term & Conditions</a>
                including
                the payment and cancellation policies.I agree that I am authorising a charge to my payment card as
                outline above
                and a further charge should any additional cancellation penalty be applicable.</label>
        </div>
    </div>

    <!-- <div class="row padding-around-20"></div> -->
    <?php
    if ($cardType == 'Master' || $cardType == 'Visa') {
    	$selectedCardType = '';
        if ($cardType == "Master") {
            $selectedCardType = "Mastercard";
        } else {
            $selectedCardType = "Visa";
        }
        ?>
        <form action="../VPS%20PHP/vpc_php_serverhost_do.php" method="POST" id="paymentForm" name="paymentForm">
            <div class="row padding-around-20 text-right">
                <div>
                    <input type="hidden" name="vpc_Version" value='1'/>
                    <input type="hidden" name="vpc_gateway" value='<?php echo "ssl" ?>' />
                    <input type="hidden" name="vpc_Command" value='pay' />
                    <!-- this is the virtual payment client URL -->
                    <input type="hidden" name="virtualPaymentClientURL" value='https://migs.mastercard.com.au/vpcpay' />
                   <input type="hidden" name="vpc_Amount" value='<?php echo $paymentService->getReservationTotal($reservationId, $_SESSION["propertyCode"]); ?>' /> 

                    <input type="hidden" name="vpc_card" value='<?php echo $selectedCardType ?>' />
                    <input type="hidden" name="vpc_AccessCode" value='AF877E14' />
                    <input type="hidden" name="vpc_Merchant" value='037003099005' />
                    <input type="hidden" name="vpc_MerchTxnRef" value='<?php echo $reservationId ?>' />
                    <input type="hidden" name="vpc_OrderInfo" value='<?php echo $reservationId; ?>' />
                    <input type="hidden" name="vpc_Locale" value='en' />
                    <input type="hidden" name="vpc_Currency" value='USD' />
                    <!-- this is the return URL -->
                    <input type="hidden" name="vpc_ReturnURL"
                           value='http://www.inhotsolutions.com/ibe/view/confirmation.php' />
                </div>
                <button type="submit" name="prscd" class="btn btn-primary" disabled="true" >Confirm Your Reservation</button>
            </div>
        </form>

    <?php
    } else if ($cardType == 'Amex') {
        ?>
         <form action="#" method="POST" id="paymentForm" name="paymentForm">
        <!-- <form action="#" method="POST" id="paymentForm" name="paymentForm"> -->
            <div class="row padding-around-20 text-right">
                <input type="hidden" name="action" value='SaleTxn' />
                <input type="hidden" name="cur" value='USD' />
                <input type="hidden" name="txn_amt" value='<?php echo $paymentService->getReservationTotal($reservationId, $_SESSION["propertyCode"]); ?>' />
                <input type="hidden" name="mer_id" value='IPGTEST2' />
                <input type="hidden" name="mer_txn_id" value=<?php echo $reservationId ?> />
                <input type="hidden" name="mer_var1" value='' />
                <input type="hidden" name="mer_var2" value='' />
                <input type="hidden" name="mer_var3" value='' />
                <input type="hidden" name="mer_var4" value='' />
                <!-- this is the return URL -->
                <input type="hidden" name="ret_url" value='http://www.inhotsolutions.com/ibe/view/confirmation.php' />
                <input type="hidden" name="ipg_server_url" value='https://www.IPGServer.com'/>

                <button type="submit" name="prscd" id="amex_submit" class="btn btn-primary" disabled="true" >Confirm Your Reservation</button>
                <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#infoModal">Confirm Your Reservation</button> -->

            </div>
        </form>
    <?php
    }
    ?>
    <!-- /.row -->

    <!-- Modal -->
    <div id="infoModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Information Message</h4>
                </div>
                <div class="modal-body">
                    <p>IPG payment gateway still under construction.</p>

                    <p class="text-warning">
                        <small>this payment gateway still has not permission for processed.</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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