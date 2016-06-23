<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-07-15
 * Time: 1:42 PM
 */
session_start();
include "../source/datasource/DbConnection.php";
include "../source/service/ViewBucketService.php";
include "../source/service/PropertyService.php";
include "../source/service/Functions.php";

$logoPath = $_SESSION["logoPath"];

$current_url = urlencode($url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

if (empty($_SESSION["roomBucket"])) {
    session_destroy();
    $return_url = "availability.php";
    header('Location:' . $return_url);
}

$vewBucketService = new ViewBucketService();

$propertyService = new PropertyService();
$policies = $propertyService->getPropertyPolicy($_SESSION["propertyCode"]);

//date functions
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

    <title>Room Bucket</title>

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
        <div class="col-lg-4">
            <h4>YOUR RESERVATION</h4>
        </div>
        <div class="col-lg-4">
        </div>
        <div class="col-lg-4 text-right button-more-rooms">
            <a href="availability.php">
                <button type="button" class="btn btn-primary">Add More Rooms</button>
            </a>
        </div>
    </div>
    <!-- /.row -->

    <div class="row page-highlights">
        <div class="row bucket-table text-center">
            <table class="table">
                <thead>
                <tr>
                    <th class="text-center">Dates</th>
                    <th width="50" class="text-center">Nights</th>
                    <th class="text-center">Room & Meal Plan</th>
                    <th width="60" class="text-center">Adults</th>
                    <th width="60" class="text-center">Children</th>
                    <th class="text-center">Total Price <br/> Including Taxes</th>
                    <th class="text-center"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $totalRoomBill = 0.0;
                $currency = '';

                foreach ($_SESSION["roomBucket"] as $rooms => $room) {
                    $stringArrivalDate = $room["arrival_date"];

                    $stringDepartureDate = $room["departure_date"];
                    //$dayCount = $vewBucketService->getDayCount($room["arrival_date"], $room["departure_date"]);
                    $totalRoomBill += ($room["averageRoomRate"]*$room['totalNights']);
                    $currency = $room["currencyCode"];
                    ?>
                    <tr>
                        <form role="form" method="post" action="../source/controller/BucketUpdate.php">
                            <td>
                                <?php echo $functions->displayDateFormat($stringArrivalDate) ?>
                                <br/> to <br/>
                                <?php echo $functions->displayDateFormat($stringDepartureDate) ?>
                            </td>
                            <td><?php echo $room['totalNights'] ?></td>
                            <td>
                                Room Type: <?php echo $room["roomType"] ?> <br/>
                                Meal Plan: <?php echo $room["mealPlane"] ?>
                            </td>
                            <td><?php echo $room["adult_count"] ?></td>
                            <td><?php echo $room["child_count"] ?></td>
                            <td class="text-center"><?php echo $room["currencyCode"] . " " . number_format($room["averageRoomRate"]*$room['totalNights'],2) ?></td>
                            <td class="text-center">
                                <input type="hidden" name="removeRoomCode" value="<?php echo $rooms ?>">
                                <input type="hidden" name="arrival_date" value="<?php echo $room["arrival_date"] ?>">
                                <input type="hidden" name="departure_date" value="<?php echo $room["departure_date"] ?>">
                                <input type="hidden" name="return_url" value="<?php echo $current_url ?>">
                                <input type="hidden" name="type" value="removeFromBucket">
                                <button type="submit" class="btn btn-primary btn-xs">Remove</button>
                            </td>
                        </form>
                    </tr>
                <?php

                }
                ?>
                <tr>
                    <th colspan="5" class="text-right">
                        Total Price Including Taxes:
                    </th>
                    <td class="text-center"><?php echo $currency . " " . number_format($totalRoomBill,2) ?></td>
                    <td></td>
                </tr>
                <tr>
                    <th colspan="5" class="text-right">
                        Payable Today:
                    </th>
                    <td class="text-center"><?php echo $currency . " " . number_format($totalRoomBill,2) ?></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.row -->

    <div class="row room-page-headers padding-top-20">
        <div class="col-lg-4 ">
            <h4>TERMS & CONDITIONS</h4>
        </div>
        <div class="col-lg-4">
        </div>
        <div class="col-lg-4">
        </div>
    </div>
    <!-- /.row -->
    <div class="row page-highlights">
        <div class="row term-condition">
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
                            <?php

                            ?>
                            <td width="200">Cancellation Policy:</td>
                            <td><p class="text-justify"> <?php echo $policies["cancellationPolicy"] ?></p></td>
                        </tr>
                    <?php
                    }
                    if (!empty($policies["refundPolicy"])) {
                        ?>
                        <tr>
                            <td width="200">Refund Policy:</td>
                            <td><p class="text-justify"> <?php echo $policies["refundPolicy"] ?></p></td>
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
                    if (!empty($policies["petPolicy"])) {
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
    <!-- /.row -->
    <form id="viewBusket-form" role="form" data-toggle="validator" method="post" action="../view/yourDetails.php">
        <div class="row">
            <div class="col-lg-4">
            </div>
            <div class="col-lg-4">
            </div>
            <div class="col-lg-4 text-right booking-buton">
                <button type="submit" class="btn btn-primary">Book Now</button>
            </div>
        </div>
        <!-- /.row -->
    </form>
    <!-- /.form -->

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
