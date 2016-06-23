<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 8/9/2015
 * Time: 5:55 PM
 */
session_start();

if(session_id() == ''){
    //session has not started
    session_destroy();
}

include "source/datasource/DbConnection.php";
include "source/service/PropertyService.php";


$_POST["companyCode"] = 'W15';
$_POST["propertyCode"] = 'PRT1000';
$_POST["returnUrl"] = 'localhost';

$_POST["arrival_date"]='2016/02/01';
$_POST["departure_date"] = '2016/02/05';

$_POST["adult_count"]='2';
$_POST["child_count"]='2';

/*
$companyCode = $_POST["companyCode"];
$propertyCode = $_POST["propertyCode"];
$propertyReturnUrl = $_POST["returnUrl"];

$arrivalDate  = $_POST["arrival_date"];
$departureDate = $_POST["departure_date"];
$adultCount = $_POST["adult_count"];
$childCount = $_POST["child_count"];
error_log($departureDate);
*/
//test pourpos
$companyCode = "CMP1000";
$propertyCode = "PRT1000";
$propertyReturnUrl = "";

$_SESSION['rootPath'] = $_SERVER['DOCUMENT_ROOT'];

if(isset($companyCode) && !empty($companyCode) && isset($propertyCode) && !empty($propertyCode)){

    $_SESSION["companyCode"] = $companyCode;
    $_SESSION["propertyCode"] = $propertyCode;

    $propertyService = new PropertyService();

    $_SESSION["propertyDetails"] = $propertyService->getPropertyDetails($companyCode,$propertyCode);
    $_SESSION["logoPath"] = $propertyService->getPropertyLogoPath($propertyCode,"logo");
    $_SESSION["propertyImagePath"] = $propertyService->getPropertyLogoPath($propertyCode,"img");

    if(empty($_SESSION["propertyDetails"])){
        error_log("property Details Not Found");
    }

    if(!isset($_SESSION["logoPath"]) || empty($_SESSION["logoPath"])){
        $_SESSION["logoPath"] = "http://placehold.it/200x150";
    }

    if(!isset($_SESSION["propertyImagePath"]) || empty($_SESSION["propertyImagePath"])){
        $_SESSION["propertyImagePath"] = "http://placehold.it/250x190";
    }
    
    //unset arrival / departure_date dates
    unset($_SESSION["arrival_date"]);
    unset($_SESSION['departure_date']);

    /**
     *
     */
    if(!isset($arrivalDate) || empty($arrivalDate)){
        $_SESSION["arrival_date"] = date("Y-m-d");
        //$_SESSION["arrival_date"] = date("d-m-Y");
    }else{
        //$_SESSION["arrival_date"] = date("d-m-Y",strtotime($arrivalDate));
        $_SESSION["arrival_date"] = $arrivalDate;
    }

    if(!isset($departureDate) || empty($departureDate)){
        $date = new DateTime('NOW');
        $date->modify('+1 day');
        $_SESSION['departure_date'] = $date->format('Y-m-d');
        //$_SESSION['departure_date'] = $date->format('d-m-Y');
    }else{
        //$_SESSION['departure_date'] = date("d-m-Y",strtotime($departureDate));
        $_SESSION['departure_date'] = $departureDate;
    }
    $_SESSION["reservationCode"] = '0';


    if(!isset($adultCount) || empty($adultCount)) {
        $_SESSION["adult_count"] = '01';
    }else{
        $_SESSION["adult_count"] = $adultCount;
    }

    if(!isset($childCount) || empty($childCount)) {
        $_SESSION["child_count"] = '00';
    }else{
        $_SESSION["child_count"] = $childCount;
    }

    $_SESSION["from"] = 'index';
    $_SESSION["return_url"] = '../../view/availability.php';

    /**
     *
     */
    if(isset($_SESSION["roomBucket"])){
        unset($_SESSION["roomBucket"]);
    }


    //$return_url = "view/availability.php";
    $return_url = "source/service/AvailabilityPostService.php";
    header('Location:' . $return_url);
}else{
    if(isset($propertyReturnUrl) && !empty($propertyReturnUrl)){
        header('Location:' . $propertyReturnUrl);
    }else{
        // our error page here
    }

}
