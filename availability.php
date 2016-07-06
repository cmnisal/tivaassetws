<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 7/14/2015
 * Time: 10:02 PM
 */
//start new session
if (!session_start()) {
    session_start();
}

include "../source/service/AvailabilityService.php";
include "../source/datasource/DbConnection.php";

/**
 * if property details not in the db and still reach to this page this
 * if statement trigger and re-direct to error page.
 */
if (empty($_SESSION["propertyDetails"])) {
    $errorPageUrl = "../view/errorPage.php";
    header('Location:' . $errorPageUrl);
}


if (!empty($_SESSION["error_code"])) {
    if ($_SESSION["error_code"] == "100") {
        //return
    }
}

if (isset($_SESSION['count'])) {
    $_SESSION['count'] += 1;
} else {
    $_SESSION['count'] = 1;
}
$current_url = urlencode($url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
$logoPath = $_SESSION["logoPath"];

/**
 *
 */
if (isset($_SESSION["roomBucket"]) && !empty($_SESSION["roomBucket"]) && isset($_SESSION['availability']) && !empty($_SESSION['availability'])) {


}
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Availability</title>

    <!-- Bootstrap Core CSS -->
    <link href="bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap2-toggle.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap-datepicker3.min.css" rel="stylesheet" media="screen">

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

    <form id="availability-form" role="form" data-toggle="validator" method="post" action="../source/service/AvailabilityPostService.php">
        <div id="room-availability">
            <div class="row room-page-headers">
                <h4>ROOM AVAILABILITY</h4>
            </div>
            <!-- /.inner row -->

            <div id="availability" class="row page-highlights">
                <div class="col-lg-12">
                    <div class='col-md-3'>
                        <div class="form-group">
                            <label for="arraival-date">Arrival Date :</label>

                            <div class='input-group date' id='arraival-date'>
                                <input type='text' name="arrival_date" class="form-control" required="true"
                                       value="<?php echo $_SESSION["arrival_date"] ?>"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>
                        </div>
                    </div>

                    <div class='col-md-3'>
                        <div class="form-group">
                            <label for="departure-date">Departure Date :</label>

                            <div class='input-group date' id='departure-date'>
                                <input type='text' name="departure_date" class="form-control" required="true"
                                       value="<?php echo $_SESSION["departure_date"] ?>"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>
                        </div>
                    </div>

                    <div class='col-md-2'>
                        <div class="form-group">
                            <label for="adult-count">Adult :</label>
                            <br/>

                            <div class="btn-group">
                                <button class="btn btn-default dropdown-toggle" type="button" id="btn-adult-count"
                                        data-toggle="dropdown">
                                    <?php
                                    $adultCount = '01';
                                    if (isset($_SESSION["adult_count"]) && !empty($_SESSION["adult_count"])) {
                                        $adultCount = $_SESSION["adult_count"];
                                        echo $_SESSION["adult_count"];
                                    } else {
                                        echo "01";
                                    }
                                    ?><span class="caret"></span></button>
                                <input type="hidden" value="<?= $adultCount ?>" name="adult_count" id="adults"
                                       required="true"/>
                                <ul id="ls-adult-count" class="dropdown-menu" role="menu" aria-labelledby="adult-count">
                                    <li role="presentation" value="01"><a role="menuitem" tabindex="-1">01</a></li>
                                    <li role="presentation" value="02"><a role="menuitem" tabindex="-1">02</a></li>
                                    <!--
                                    <li role="presentation" value="03"><a role="menuitem" tabindex="-1">03</a></li>
                                    <li role="presentation" value="04"><a role="menuitem" tabindex="-1">04</a></li>
                                    -->
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class='col-md-2'>
                        <div class="form-group">
                            <label for="child-count">Child :</label>
                            <br/>

                            <div class="btn-group">
                                <button class="btn btn-default dropdown-toggle" type="button" id="child-count"
                                        data-toggle="dropdown" name="child_count">
                                    <?php
                                    $childCount = '00';
                                    if (isset($_SESSION["child_count"]) && !empty($_SESSION["child_count"])) {
                                        $childCount = $_SESSION["child_count"];
                                        echo $_SESSION["child_count"];
                                    } else {
                                        echo "00";
                                    }
                                    ?>
                                    <span class="caret"></span></button>
                                <input type="hidden" value="<?= $childCount ?>" name="child_count" id="children"
                                       required="true"/>
                                <ul id="ls-child-count" class="dropdown-menu" role="menu" aria-labelledby="child-count">
                                    <li role="presentation"><a role="menuitem" tabindex="-1">00</a></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1">01</a></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1">02</a></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1">03</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class='col-md-2 text-right'>
                        <div class="form-group search-rooms">
                            <input type="hidden" name="return_url" value="<?php echo $current_url ?>"/>
                            <button type="submit" name="searchRooms" class="btn btn-primary">Search Rooms</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.inner row -->
        </div>
        <!-- /.row -->
    </form>

    <?php
    if (isset($_SESSION["roomBucket"]) || !empty($_SESSION["roomBucket"])) {
        ?>
        <div class="row currency-type">
            <div class="col-lg-12" style="text-align: right">
                <div class="btn-group">
                    <a class="btn btn-success" href="viewBusket.php"><i class="icon-share-alt"></i>Back To Cart</a>
                </div>
            </div>
        </div>
        <!-- /.row -->
    <?php
    }
    ?>


    <?php
    if (!empty($_SESSION['availability'])){
    ?>
    <div class="row room-page-headers">
        <h4>AVAILABLE ROOM TYPES (<?php echo count($_SESSION["availability"]) ?>)</h4>
    </div>
<!-- /.row -->
    <div id="rooms" class="row page-highlights">
        <?php
        foreach ($_SESSION["availability"] as $roomTypes => $rooms) {
            ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-sm-3 room-col">
                        <img class="img-responsive room-type-img" src="<?php echo $rooms['imagePath'] ?>"
                             alt="Room Image" width="250" height="190">
                    </div>
                    <div class="col-sm-6 room-col">
                        <section class="room-type-name">
                            <p><?php echo $rooms['roomType'] ?></p>
                            <hr class="name-underline">
                        </section>

                        <section class="room-type-description">
                            <p><?php echo $rooms['roomDescription'] ?></p>
                        </section>

                        <section class="room-type-footer">
                            <!--<span class="glyphicon glyphicon-lamp" aria-hidden="true"> Sleeps <?php //echo $rooms["paxCount"] ?> people. <?php //if($rooms["extrapaxCount"] == 0) { echo ""; }else{ echo $rooms["extrapaxCount"]?> extra beds available.<?php //} ?></span>-->
                        </section>
                    </div>
                    <div class="col-sm-3 room-col">
                        <div class="row rate-header text-center">
                            <span>Lowest Rate</span>
                        </div>
                        <div class="row room-rates-unite text-center">
                            <span
                                class="rates-font-style text-center"><?php echo $rooms["currency"] . " " . number_format($rooms["lowestRate"], 2) ?></span><br/>
                            <span class="room-rates-unite-span text-center">per room per night</span>

                        </div>
                        <div class="row col-room-rate-footer">
                            <button class="btn btn-primary text-right" style="float: right;" type="button"
                                    data-toggle="collapse" data-target="<?php echo "#" . $rooms['roomTypeCode'] ?>"
                                    aria-expanded="false" data-width="140" data-height="40"
                                    aria-controls="<?php echo "#" . $rooms['roomTypeCode'] ?>">
                                Show Available Rates
                            </button>
                        </div>
                    </div>
                </div>

                <div class="collapse" id="<?php echo $rooms['roomTypeCode'] ?>">
                    <div class="well room-rates-details">
                        <table class="table">
                            <thead>
                            <tr>
                                <th width="300" >Meal Plan</th>
                                <th width="300" class="text-center">Rate</th>
                                <th width="300" class="text-center">Night(s)</th>
                                <th width="300" class="text-center">Total Rate</th>
                                <th width="300"></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            foreach ($rooms["rates"] as $rates => $rate) {
                                ?>
                                <tr>
                                    <form role="form" method="post" action="../source/controller/BucketUpdate.php">
                                        <td width="300">
                                            <input type="hidden" name="mealPlane"
                                                   value="<?php echo $rate['mealPlane'] ?>"/>
                                            <?php echo $rate["mealPlane"] ?>
                                        </td>
                                        <td width="300" class="text-center">
                                            <input type="hidden" name="rate"
                                                   value="<?php echo $rate["averageRate"] ?>"/>
                                            <input type="hidden" name="currencyCode"
                                                   value="<?php echo $rate['currencyCode'] ?>"/>
                                            <?php echo $rate["currencyCode"] . " " . number_format($rate["averageRate"], 2) ?>
                                        </td>
                                        <td width="300" class="text-center">
                                            <input type="hidden" name="totalNights"
                                                   value="<?php echo $rate['totalDates'] ?>"/>
                                            <?php echo $rate["totalDates"] ?>
                                        </td>
                                        <td width="300" class="text-center">
                                            <?php echo $rate["currencyCode"] . " " . number_format(($rate["averageRate"] * $rate['totalDates']), 2) ?>
                                        </td>
                                        <td width="300" class="text-right">
                                            <input type="hidden" name="type" value="addToBucket"/>
                                            <input type="hidden" name="roomType"
                                                   value="<?php echo $rooms["roomType"] ?>"/>
                                            <input type="hidden" name="roomTypeCode"
                                                   value="<?php echo $rooms['roomTypeCode'] ?>"/>
                                            <input type="hidden" name="mealPlan"
                                                   value="<?php echo $rate["mealPlane"] ?>"/>
                                            <input type="hidden" name="return_url" value="<?php echo $current_url ?>"/>
                                            <input type="hidden" name="currencyCode"
                                                   value="<?php echo $rate["currencyCode"] ?>"/>
                                            <button type="submit" class="btn btn-info">Select This Rate</button>
                                        </td>
                                    </form>
                                </tr>
                            <?php
                            }
                            ?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        <?php
        }
        }else{
        ?>
        <div class="row room-page-headers">
            <h4>AVAILABLE ROOM TYPES (0)</h4>
        </div>
    <!-- /.row -->
        <div id="rooms" class="row page-highlights">
            <div class="panel panel-default">
                <div class="panel-body no-room-found text-center">
                    <div class="padding-around-5">
                        <h4>Unfortunately, there are no rooms available for the selected period.</h4>
                        <br>
                        <h4>Please try another date.</h4>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
        <!-- /.row -->

        <!-- Modal -->
        <div id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Sorry...</h4>
                    </div>
                    <div class="modal-body">
                        <p>Please select card Type before processed.</p>

                        <p class="text-warning">
                            <small>Please try another date.</small>
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

    <?php
    if (isset($_SESSION['unAvailableRooms'])) {
        ?>

        <script type="text/javascript">
            $(document).ready(function () {
                $("#myModal").modal('show');
            });

        </script>

    <?php
    }
    ?>

</body>

</html>