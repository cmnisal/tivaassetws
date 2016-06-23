<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-07-15
 * Time: 5:25 PM
 */
session_start();

include "../source/datasource/DbConnection.php";
include "../source/service/YourDetailsService.php";
include "../source/service/ViewBucketService.php";
include "../source/service/Functions.php";

$current_url = urlencode($url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

if (empty($_SESSION["roomBucket"])) {
    session_destroy();
    $return_url = "availability.php";
    header('Location:' . $return_url);
}

$vewBucketService = new ViewBucketService();
$yourDetails = new YourDetailsService();

$logoPath = $_SESSION["logoPath"];

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

    <title>Your Details</title>


    <style type="text/css">
        @import url('http://cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.css');
    </style>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap2-toggle.min.css" rel="stylesheet">
    <link href="bootstrap-datepicker3.min.css" rel="stylesheet">

    <link href="select2.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="customize-css.css" rel="stylesheet">
    <link href="custome-alignment.css" rel="stylesheet">




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

    <form id="form-customerDetails" class="form-horizontal" role="form" data-toggle="validator" method="post"
          action="../source/service/YourDetailsPostService.php">
        <div class="row page-frame">
            <div class="col-lg-7">
                <div class="row room-page-headers">
                    <h4>YOUR DETAILS</h4>
                    <span>To confirm your reservation please complete the details below.</span>
                </div>
                <!-- /. inner row -->


                <div class="row page-highlights">
                    <div class="row contact-details">
                        <div class="row page-subheaders-container">
                            <h4>Contact Details</h4>
                        </div>
                        <!-- /. inner row -->

                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="title">Title:</label>

                                <div class="col-sm-5">
                                    <div class="btn-group">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="title"
                                                data-toggle="dropdown">Mr.<span class="caret"></span></button>
                                        <input type="hidden" value="Mr." name="selected_title" id="sel-title"
                                               required="true"/>
                                        <ul id="ls-title" class="dropdown-menu" role="menu" aria-labelledby="title">
                                            <?php
                                            $result = $yourDetails->getTitles();
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $presentationValue = $row["title"];
                                                    echo "<li role='presentation' value='$presentationValue'><a role='menuitem' tabindex='1'>$presentationValue</a></li>";
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="firstName">First Name:</label>

                                <div class="col-sm-5">
                                    <input type="text" name="firstName" class="form-control" id="firstName"
                                           placeholder="Ex: John" required="true">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="surName">Surname:</label>

                                <div class="col-sm-5">
                                    <input type="text" name="surName" class="form-control" id="surName"
                                           placeholder="Ex: Perera" required="true">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="address_1">Address 1:</label>

                                <div class="col-sm-5">
                                    <input type="text" name="address_1" class="form-control" id="address_1"
                                           placeholder="Address" required="true">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="address_2">Address 2:</label>

                                <div class="col-sm-5">
                                    <input type="text" name="address_2" class="form-control" id="address_2"
                                           placeholder="Address (Optional)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="town-city">Town/City:</label>

                                <div class="col-sm-5">
                                    <input type="text" name="townCity" class="form-control" id="town-city"
                                           placeholder="" required="true">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="nic_passportNumber">NIC/Passport No:</label>

                                <div class="col-sm-5">
                                    <input type="text" name="nicPassportNumber" class="form-control"
                                           id="nic_passportNumber" placeholder="" required="true">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="zip_postal_code">Zip/Postal Code:</label>

                                <div class="col-sm-5">
                                    <input type="text" name="zipPostalCode" class="form-control" id="zip_postal_code"
                                           placeholder="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="country">County:</label>
                                <!--
                                <div class="col-sm-5">
                                    <div class="btn-group">
                                        <button class="btn btn-default dropdown-toggle button-country" type="button"
                                                id="country"
                                                data-toggle="dropdown">Select Country<span class="caret"></span>
                                        </button>
                                        <input type="hidden" value="" name="selected_country" id="sel-country" autocomplete="true"
                                               required="true"/>
                                        <ul id="ls-country" class="dropdown-menu dropdown-menu-country" role="menu"
                                            aria-labelledby="country">
                                            <?php
                                //$result = $yourDetails->getCountries();
                                //if ($result->num_rows > 0) {
                                //   while ($row = $result->fetch_assoc()) {
                                //       $value = $row["countryCode"];
                                //      $presentationValue = $row["countryName"];
                                //      if (isset($value) || isset($presentationValue)) {
                                //         echo "<li role='presentation' value='$value'><a role='menuitem' tabindex='1'>$presentationValue</a></li>";
                                //     }
                                //       }
                                //  }
                                ?>
                                        </ul>
                                    </div>
                                </div>
                                -->

                                <div class="col-sm-5">
                                    <select style="width: 262px;" id="countryList" name="selected_country" required="true">
                                        <option value=""></option>
                                        <?php
                                        $result = $yourDetails->getCountries();
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $value = $row["countryCode"];
                                                $presentationValue = $row["countryName"];
                                                if (isset($value) || isset($presentationValue)) {
                                                    echo "<option value='$presentationValue' >$presentationValue</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="phoneNumber">Phone Number:</label>

                                <div class="col-sm-5">
                                    <input type="text" name="phoneNumber" class="form-control" id="phoneNumber"
                                           placeholder="" required="true">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="email">Email:</label>

                                <div class="col-sm-5">
                                    <input type="email" name="email" class="form-control" id="email"
                                           placeholder="john@example.com" required="true">
                                </div>
                            </div>
                        </div>
                        <!-- /. inner row -->
                    </div>
                    <!-- /. inner row -->
                </div>
                <!-- /. inner row -->
            </div>

            <!-- col -->
            <div class="col-lg-5">
                <div class="row reservation-summery">
                    <div class="row page-subheaders-container">
                        <h4>Reservation Summary</h4>
                    </div>
                    <!-- /. inner row -->
                    <?php
                    if (!empty($_SESSION["roomBucket"])) {
                        if (count($_SESSION["roomBucket"]) == 1) {
                            $dayCount = 0;
                            $stringArrivalDate = '';
                            $stringDepartureDate = '';
                            $adultCount = 0;
                            $childCount = 0;
                            $selectedRoom = '';
                            $rate = '';
                            $totalIncludeTax = 0;
                            $currency = '';
                            foreach ($_SESSION["roomBucket"] as $roomDetails => $room) {
                                $stringArrivalDate = $room["arrival_date"];
                                $stringDepartureDate = $room["departure_date"];
                                $dayCount = $room['totalNights'];
                                $adultCount = $room["adult_count"];
                                $childCount = $room["child_count"];
                                $selectedRoom = $room["roomType"];
                                $currency = $room["currencyCode"];
                                $rate = $room["averageRoomRate"];
                                $totalIncludeTax += $rate * $dayCount;
                            }

                            ?>
                            <div class="row reserve-room-summery">
                                <div class="col-xs-7">1 Room for <?php echo $dayCount ?> Days</div>
                                <div class="col-xs-3">
                                    <a href="viewBusket.php">
                                        <button type="button" class="btn btn-primary btn-xs">Edit Rooms</button>
                                    </a>
                                </div>
                            </div>
                            <!-- /. inner row -->

                            <div class="row reserve-room-summery">
                                <table class="table table-striped">
                                    <tr>
                                        <td class="col-xs-3">Arrival Date:</td>
                                        <td class="col-xs-3"><?php echo $functions->displayDateFormat($stringArrivalDate) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="col-xs-3">Departure Date:</td>
                                        <td class="col-xs-3"><?php echo $functions->displayDateFormat($stringDepartureDate) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="col-xs-3">Nights:</td>
                                        <td class="col-xs-3"><?php echo $dayCount ?></td>
                                    </tr>
                                    <tr>
                                        <td class="col-xs-3">Adults:</td>
                                        <td class="col-xs-3"><?php echo $adultCount ?></td>
                                    </tr>
                                    <tr>
                                        <td class="col-xs-3">Children:</td>
                                        <td class="col-xs-3"><?php echo $childCount ?></td>
                                    </tr>
                                    <tr>
                                        <td class="col-xs-3">Room:</td>
                                        <td class="col-xs-3"><?php echo $selectedRoom ?></td>
                                    </tr>
                                    <tr>
                                        <td class="col-xs-3">Rate:</td>
                                        <td class="col-xs-3"><?php echo $currency . " " . number_format($rate, 2) ?></td>
                                    </tr>
                                </table>

                                <table class="table table-striped">
                                    <tr>
                                        <td class="col-xs-3">Total Price Including Taxes:</td>
                                        <td class="col-xs-3"><?php echo $currency . " " . number_format($totalIncludeTax, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="col-xs-3">Payable Today:</td>
                                        <td class="col-xs-3"><?php echo $currency . " " . number_format($totalIncludeTax, 2) ?></td>
                                    </tr>
                                </table>
                            </div>
                            <!-- /. inner row -->
                        <?php
                        } else {
                            $totalIncludeTax = 0;
                            $currency = '';
                            foreach ($_SESSION["roomBucket"] as $roomDetails => $room) {
                                $dayCount = $room["totalNights"];
                                $totalRate = $room["averageRoomRate"];
                                $totalIncludeTax += ($totalRate * $dayCount);
                                $currency = $room["currencyCode"];
                            }
                            ?>
                            <div class="row reserve-room-summery">
                                <div class="col-xs-7">Multiple Rooms</div>
                                <div class="col-xs-3">
                                    <a href="viewBusket.php">
                                        <button type="button" class="btn btn-primary btn-xs">Edit Rooms</button>
                                    </a>
                                </div>
                            </div>
                            <!-- /. inner row -->
                            <div class="row reserve-room-summery">
                                <table class="table table-striped">
                                    <tr>
                                        <td class="col-xs-3">Total Price Including Taxes:</td>
                                        <td class="col-xs-3"><?php echo $currency . " " . number_format($totalIncludeTax, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="col-xs-3">Payable Today:</td>
                                        <td class="col-xs-3"><?php echo $currency . " " . number_format($totalIncludeTax, 2) ?></td>
                                    </tr>
                                </table>
                            </div>
                            <!-- /. inner row -->
                        <?php
                        }
                    } else {

                    }
                    ?>
                </div>
                <!-- /. inner row -->
                <div class="row reserve-room-summery text-right" style="padding-top: 400px">
                    <a href="availability.php">
                        <a href="availability.php">
                            <button type="button" class="btn btn-primary btn-sm">Add More Rooms</button>
                        </a>
                    </a>
                </div>
            </div>
        </div>
        <!-- /. end of the page fram -->


        <div class="row">
            <div class="row">
                <h4 class="page-subheaders-container">Payment Information</h4>

                <p class="text-left payment-info">
                    The price is based on today's exchange rate. <?php echo $currency . " " . $totalIncludeTax ?> will
                    be
                    charged to your card at the
                    time of booking. Any currency conversion is for guidance only. A foreign currency
                    transaction fee may be charged by your card provider.
                </p>
            </div>

            <div class="row">
                <h4 class="page-subheaders-container">Select Card Type</h4>

                <div class=" row padding-top-10">
                    <div class="btn-group">
                    <table class="table-responsive">
                        <tr>
                            <td width="100">

                            </td>
                            <td>
                                 <input checked type="radio" name="cardType" value="Master">
                            </td>
                            <td style="width: 20px;">

                            </td>
                            <td width="300">
                                <img class="card-logo" src="../images/w15/card_logo/mastercard.jpg" width="100"
                                     height="60" alt="Master Card">
                            </td>
                           
                            <td>
                                <input type="radio" name="cardType" value="Visa">
                            </td>
                            <td style="width: 20px;">
				
                            </td>
                            <td width="300">
                                <img class="card-logo" src="../images/w15/card_logo/visacard.jpg" width="100"
                                     height="60" alt="Visa Card">
                            </td>
                             <!--
                            <td>
                                <input type="radio" name="cardType" value="Amex">
                            </td>
                            <td width="300">
                                <img class="card-logo" src="../images/w15/card_logo/amexcard.jpg" width="150"
                                     height="90" alt="Amex Card">
                            </td>
                           -->
                        </tr>
                    </table>
                    </div>
                     
			<!--
                    <table class="table-responsive">
                        <tr>
                            <td width="50">
                            </td>
                            <td>
                                <input type="radio" name="cardType" value="Master">
                            </td>
                            <td style="width: 20px;">

                            </td>
                            <td width="400">
                                <img class="card-logo" src="../images/w15/card_logo/mastercard.jpg" width="100"
                                     height="60" alt="Master Card">
                                <span style="padding: 15px;">OR</span>
                                <img class="card-logo" src="../images/w15/card_logo/visacard.jpg" width="100"
                                     height="60" alt="Visa Card">
                            </td>
                            <td width="100">
                            </td>
                           
                            <td>
                                <input type="radio" name="cardType" value="Amex">
                            </td>
                            <td width="100">
                                <img class="card-logo" src="../images/w15/card_logo/amexcard.jpg" width="150"
                                     height="90" alt="Amex Card">
                            </td>
                            
                        </tr>
                    </table>
                     -->
                   
                </div>
                <!-- /. inner row -->
            </div>
            <!-- payment information row -->
        </div>
        <div class="row term-condition-row">
            <!--
            <div class="row page-subheaders-container">
                <h4>Term and Conditions :</h4>
            </div>

            <div class="checkbox term-condition-agreement text-left">
                <label><input id="termCondition" type="checkbox" value="">I have read and accepted the <a href="#">Term
                        & Conditions</a>
                    including
                    the payment and cancellation policies.I agree that I am authorising a charge to my payment card as
                    outline above
                    and a further charge should any additional cancellation penalty be applicable.</label>
            </div>
            -->

            <div class="row text-right">
                <input type="hidden" name="return_url" value="<?php echo $current_url ?>"/>
                <button id="reviewReservation" type="submit" class="btn btn-primary">
                    Review Your Reservation
                </button>
            </div>
        </div>
    </form>

    <!-- Modal -->
    <div id="messageModel" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Information Message</h4>
                </div>
                <div class="modal-body">
                    <p>Please select card Type before processed.</p>
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

<!--Country Select -->
<script src="jquery-2.1.0.js"></script>

<!-- Country Select -->
<script src="select2.full.min.js"></script>

<!-- Toggle button JavaScript -->
<script src="bootstrap2-toggle.min.js"></script>

<!-- Moment Lib Javascript -->
<script src="moment.min.js"></script>

<!-- boostrap datepicker  Javascript -->
<script src="bootstrap-datepicker.min.js"></script>

<!-- Customize JavaScript -->
<script src="customize-js.js"></script>

<script type="text/javascript">
$(document).ready(function () {
    //Select2
    $.getScript('http://cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.min.js', function () {

        /* dropdown and filter select */
        var select = $('#countryList').select2();

        /* Select2 plugin as tagpicker */
        $("#tagPicker").select2({
            closeOnSelect: false
        });

    }); //script


    
    });
</script>

</body>

</html>