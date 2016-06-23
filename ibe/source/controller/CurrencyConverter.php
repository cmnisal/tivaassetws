<?php
/**
 * Created by PhpStorm.
 * User: Yahampath
 * Date: 2015-08-24
 * Time: 6:38 PM
 */

function getCurrencyRate(){

    $url = "http://www.webservicex.net/currencyconvertor.asmx/ConversionRate?FromCurrency=USD&ToCurrency=LKR";
    echo $url."<br>";
    $xml = file_get_contents($url);

    return $xml;

}