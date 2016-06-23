<?php
/**
 * Created by PhpStorm.
 * User: lahiruyahampath
 * Date: 11/20/2015
 * Time: 11:18 AM
 */

class GatewayService {

    private $vpc_Version = '1';
    private $virtualPaymentClientURL = 'https://migs.mastercard.com.au/vpcpay';
    private $vpc_Amount;
    private $vpc_Command = 'pay';
    private $vpc_AccessCode = 'A0D38A5D';
    private $vpc_MerchTxnRef;
    private $vpc_Merchant = '037003099002';
    private $vpc_OrderInfo;
    private $vpc_Locale = 'en';
    private $vpc_ReturnURL = 'http://www.inhotsolutions.com/ibe/view/confirmation.php';


    /**
     *
     */
    public function __construct(){

        if(isset($_POST['vpc_Amount']) && $_POST['vpc_Amount'] != '' ||
            isset($_POST['vpc_MerchTxnRef']) && $_POST['vpc_MerchTxnRef'] != '' ||
            isset($_POST['vpc_OrderInfo']) && $_POST['vpc_MerchTxnRef'] != ''){


            $this->vpc_Amount = $_POST['vpc_Amount'];
            $this->vpc_MerchTxnRef = $_POST['vpc_MerchTxnRef'];
            $this->vpc_OrderInfo = $_POST['vpc_OrderInfo'];

        }else{

            echo "Error";
        }
    }


    /**
     * @param $url
     * @param $params
     * @return mixed
     */
    private function httpPost($url,$params)
    {
        $postData = '';
        //create name value pairs seperated by &
        foreach($params as $k => $v)
        {
            $postData .= $k . '='.$v.'&';
        }

        echo $this->httpGetWithErrors($postData);

        rtrim($postData, '&');

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output=curl_exec($ch);

        curl_close($ch);
        return $output;

    }

    function httpGetWithErrors($url)
    {
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $output=curl_exec($ch);

        if($output === false)
        {
            echo "Error Number:".curl_errno($ch)."<br>";
            echo "Error String:".curl_error($ch);
        }
        curl_close($ch);
        return $output;
    }


    /**
     *
     */
    public function sentPostRequest(){

        $params = array(
            "vpc_Version" => $this->vpc_Version,
            "virtualPaymentClientURL" => $this->virtualPaymentClientURL,
            "vpc_Amount" => $this->vpc_Amount,
            "vpc_Command" => $this->vpc_Command,
            "vpc_AccessCode" => $this->vpc_Amount,
            "vpc_MerchTxnRef" => $this->vpc_AccessCode,
            "vpc_Merchant" => $this->vpc_Merchant,
            "vpc_OrderInfo" => $this->vpc_OrderInfo,
            "vpc_Locale" => $this->vpc_Locale,
            "vpc_ReturnURL" => $this->vpc_ReturnURL
        );

        echo $this->httpPost("../VPS%20PHP/vpc_php_serverhost_do.php",$params);

    }

}

if(isset($_POST['vpc_Amount']) &&
   isset($_POST['vpc_MerchTxnRef']) &&
   isset($_POST['vpc_OrderInfo'])){

    $gatewayService = new GatewayService();
    $gatewayService->sentPostRequest();

}else{
    echo "Somthing went Wrong";
    // error
}