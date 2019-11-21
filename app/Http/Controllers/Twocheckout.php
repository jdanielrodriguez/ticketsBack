<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

abstract class Twocheckout extends Controller
{
    public static $sid;
    public static $privateKey;
    public static $username;
    public static $password;
    public static $sandbox;
    public static $verifySSL = true;
    public static $baseUrl = 'https://www.2checkout.com';
    public static $error;
    public static $format = 'array';
    const VERSION = '0.3.1';

    public static function sellerId($value = null) {
        self::$sid = $value;
    }

    public static function privateKey($value = null) {
        self::$privateKey = $value;
    }

    public static function username($value = null) {
        self::$username = $value;
    }

    public static function password($value = null) {
        self::$password = $value;
    }

    public static function sandbox($value = null) {
        if ($value == 1 || $value == true) {
            self::$sandbox = true;
            self::$baseUrl = 'https://sandbox.2checkout.com';
        } else {
            self::$sandbox = false;
            self::$baseUrl = 'https://www.2checkout.com';
        }
    }

    public static function verifySSL($value = null) {
        if ($value == 0 || $value == false) {
            self::$verifySSL = false;
        } else {
            self::$verifySSL = true;
        }
    }

    public static function format($value = null) {
        self::$format = $value;
    }
}
class Twocheckout_Company extends Twocheckout
{

    public static function retrieve()
    {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix = '/api/acct/detail_company_info';
        $result = $request->doCall($urlSuffix);
        return Twocheckout_Util::returnResponse($result);
    }
}

class Twocheckout_Contact extends Twocheckout
{

    public static function retrieve()
    {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix = '/api/acct/detail_contact_info';
        $result = $request->doCall($urlSuffix);
        return Twocheckout_Util::returnResponse($result);
    }
}
class Twocheckout_Payment extends Twocheckout
{

    public static function retrieve()
    {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix = '/api/acct/list_payments';
        $result = $request->doCall($urlSuffix);
        $response = Twocheckout_Util::returnResponse($result);
        return $response;
    }

    public static function pending()
    {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix = '/api/acct/detail_pending_payment';
        $result = $request->doCall($urlSuffix);
        $response = Twocheckout_Util::returnResponse($result);
        return $response;
    }

}
class Twocheckout_Api_Requester
{
    public $baseUrl;
    public $environment;
    private $user;
    private $pass;
    private $sid;
    private $privateKey;

	function __construct() {
        $this->user = Twocheckout::$username;
        $this->pass = Twocheckout::$password;
        $this->sid = Twocheckout::$sid;
        $this->baseUrl = Twocheckout::$baseUrl;
        $this->verifySSL = Twocheckout::$verifySSL;
        $this->privateKey = Twocheckout::$privateKey;
    }

	function doCall($urlSuffix, $data=array())
    {
        $url = $this->baseUrl . $urlSuffix;
        $ch = curl_init($url);
        if (isset($data['api'])) {
            unset( $data['api'] );
            $data['privateKey'] = $this->privateKey;
            $data['sellerId'] = $this->sid;
            $data = json_encode($data);
            $header = array("content-type:application/json","content-length:".strlen($data));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        } else {
            $header = array("Accept: application/json");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "{$this->user}:{$this->pass}");
        }
        if ($this->verifySSL == false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, "2Checkout PHP/0.1.0%s");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $resp = curl_exec($ch);
        curl_close($ch);
        if ($resp === FALSE) {
            throw new Twocheckout_Error("cURL call failed", "403");
        } else {
            return utf8_encode($resp);
        }
	}

}
class Twocheckout_Sale extends Twocheckout
{

    public static function retrieve($params=array())
    {
        $request = new Twocheckout_Api_Requester();
        if(array_key_exists("sale_id",$params) || array_key_exists("invoice_id",$params)) {
            $urlSuffix = '/api/sales/detail_sale';
        } else {
            $urlSuffix = '/api/sales/list_sales';
        }
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

    public static function refund($params=array()) {
        $request = new Twocheckout_Api_Requester();
        if(array_key_exists("lineitem_id",$params)) {
            $urlSuffix ='/api/sales/refund_lineitem';
            $result = $request->doCall($urlSuffix, $params);
        } elseif(array_key_exists("invoice_id",$params) || array_key_exists("sale_id",$params)) {
            $urlSuffix ='/api/sales/refund_invoice';
            $result = $request->doCall($urlSuffix, $params);
        } else {
            $result = Twocheckout_Message::message('Error', 'You must pass a sale_id, invoice_id or lineitem_id to use this method.');
        }
        return Twocheckout_Util::returnResponse($result);
    }

    public static function stop($params=array()) {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix ='/api/sales/stop_lineitem_recurring';
        if(array_key_exists("lineitem_id",$params)) {
            $result = $request->doCall($urlSuffix, $params);
        } elseif(array_key_exists("sale_id",$params)) {
            $result = Twocheckout_Sale::retrieve($params);
            if (!is_array($result)) {
                $result = Twocheckout_Util::returnResponse($result, 'array');
            }
            $lineitemData = Twocheckout_Util::getRecurringLineitems($result);
            if (isset($lineitemData[0])) {
                $stoppedLineitems = array();
                foreach( $lineitemData as $value )
                {
                    $params = array('lineitem_id' => $value);
                    $result = $request->doCall($urlSuffix, $params);
                    $result = json_decode($result, true);
                    if ($result['response_code'] == "OK") {
                        $stoppedLineitems[] = $value;
                    }
                }
                $result = Twocheckout_Message::message('OK', $stoppedLineitems);
            } else {
                throw new Twocheckout_Error("No recurring lineitems to stop.");
            }
        } else {
            throw new Twocheckout_Error('You must pass a sale_id or lineitem_id to use this method.');
        }
        return Twocheckout_Util::returnResponse($result);
    }

    public static function active($params=array()) {
        if(array_key_exists("sale_id",$params)) {
            $result = Twocheckout_Sale::retrieve($params);
            if (!is_array($result)) {
                $result = Twocheckout_Util::returnResponse($result, 'array');
            }
            $lineitemData = Twocheckout_Util::getRecurringLineitems($result);
            if (isset($lineitemData[0])) {
                $result = Twocheckout_Message::message('OK', $lineitemData);
                return Twocheckout_Util::returnResponse($result);
            } else {
                throw new Twocheckout_Error("No active recurring lineitems.");
            }
        } else {
            throw new Twocheckout_Error("You must pass a sale_id to use this method.");
        }
    }

    public static function comment($params=array()) {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix ='/api/sales/create_comment';
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

    public static function ship($params=array()) {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix ='/api/sales/mark_shipped';
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

    public static function reauth($params=array()) {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix ='/api/sales/reauth';
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

}
class Twocheckout_Product extends Twocheckout
{

    public static function create($params=array())
    {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix = '/api/products/create_product';
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

    public static function retrieve($params=array())
    {
        $request = new Twocheckout_Api_Requester();
        if(array_key_exists("product_id",$params)) {
            $urlSuffix = '/api/products/detail_product';
        } else {
            $urlSuffix = '/api/products/list_products';
        }
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

    public static function update($params=array())
    {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix = '/api/products/update_product';
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

    public static function delete($params=array())
    {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix = '/api/products/delete_product';
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

}
class Twocheckout_Coupon extends Twocheckout
{

    public static function create($params=array())
    {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix = '/api/products/create_coupon';
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

    public static function retrieve($params=array())
    {
        $request = new Twocheckout_Api_Requester();
        if(array_key_exists("coupon_code",$params)) {
            $urlSuffix = '/api/products/detail_coupon';
        } else {
            $urlSuffix = '/api/products/list_coupons';
        }
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

    public static function update($params=array())
    {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix = '/api/products/update_coupon';
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

    public static function delete($params=array())
    {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix = '/api/products/delete_coupon';
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

}

class Twocheckout_Option extends Twocheckout
{

    public static function create($params=array())
    {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix = '/api/products/create_option';
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

    public static function retrieve($params=array())
    {
        $request = new Twocheckout_Api_Requester();
        if(array_key_exists("option_id",$params)) {
            $urlSuffix = '/api/products/detail_option';
        } else {
            $urlSuffix = '/api/products/list_options';
        }
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

    public static function update($params=array())
    {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix = '/api/products/update_option';
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

    public static function delete($params=array())
    {
        $request = new Twocheckout_Api_Requester();
        $urlSuffix = '/api/products/delete_option';
        $result = $request->doCall($urlSuffix, $params);
        return Twocheckout_Util::returnResponse($result);
    }

}
class Twocheckout_Util extends Twocheckout
{

    static function returnResponse($contents, $format=null) {
        $format = $format == null ? Twocheckout::$format : $format;
        switch ($format) {
            case "array":
                $response = self::objectToArray($contents);
                self::checkError($response);
                break;
            case "force_json":
                $response = self::objectToJson($contents);
                break;
            default:
                $response = self::objectToArray($contents);
                self::checkError($response);
                $response = json_encode($contents);
                $response = json_decode($response);
        }
        return $response;
    }

    public static function objectToArray($object)
    {
        $object = json_decode($object, true);
        $array=array();
        foreach($object as $member=>$data)
        {
            $array[$member]=$data;
        }
        return $array;
    }

    public static function objectToJson($object)
    {
        return json_encode($object);
    }

    public static function getRecurringLineitems($saleDetail) {
        $i = 0;
        $invoiceData = array();

        while (isset($saleDetail['sale']['invoices'][$i])) {
            $invoiceData[$i] = $saleDetail['sale']['invoices'][$i];
            $i++;
        }

        $invoice = max($invoiceData);
        $i = 0;
        $lineitemData = array();

        while (isset($invoice['lineitems'][$i])) {
            if ($invoice['lineitems'][$i]['billing']['recurring_status'] == "active") {
                $lineitemData[] = $invoice['lineitems'][$i]['billing']['lineitem_id'];
            }
            $i++;
        };

        return $lineitemData;

    }

    public static function checkError($contents)
    {
        if (isset($contents['errors'])) {
            throw new Twocheckout_Error($contents['errors'][0]['message']);
        } elseif (isset($contents['exception'])) {
            throw new Twocheckout_Error($contents['exception']['errorMsg'], $contents['exception']['errorCode']);
        }
    }

}
class Twocheckout_Error extends Exception
{
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
class Twocheckout_Return extends Twocheckout
{

    public static function check($params=array(), $secretWord)
    {
        $hashSecretWord = $secretWord;
        $hashSid = $params['sid'];
        $hashTotal = $params['total'];
        $hashOrder = $params['order_number'];
        $StringToHash = strtoupper(md5($hashSecretWord . $hashSid . $hashOrder . $hashTotal));
        if ($StringToHash != $params['key']) {
            $result = Twocheckout_Message::message('Fail', 'Hash Mismatch');
        } else {
            $result = Twocheckout_Message::message('Success', 'Hash Matched');
        }
        return Twocheckout_Util::returnResponse($result);
    }

}
class Twocheckout_Notification extends Twocheckout
{

    public static function check($insMessage=array(), $secretWord)
    {
        $hashSid = $insMessage['vendor_id'];
        $hashOrder = $insMessage['sale_id'];
        $hashInvoice = $insMessage['invoice_id'];
        $StringToHash = strtoupper(md5($hashOrder . $hashSid . $hashInvoice . $secretWord));
        if ($StringToHash != $insMessage['md5_hash']) {
            $result = Twocheckout_Message::message('Fail', 'Hash Mismatch');
        } else {
            $result = Twocheckout_Message::message('Success', 'Hash Matched');
        }
        return Twocheckout_Util::returnResponse($result);
    }

}
class Twocheckout_Charge extends Twocheckout
{

    public static function form($params, $type='Checkout')
    {
        echo '<form id="2checkout" action="'.Twocheckout::$baseUrl.'/checkout/purchase" method="post">';

        foreach ($params as $key => $value)
        {
            echo '<input type="hidden" name="'.htmlspecialchars($key, ENT_QUOTES, 'UTF-8').'" value="'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'"/>';
        }
        if ($type == 'auto') {
            echo '<input type="submit" value="Click here if you are not redirected automatically" /></form>';
            echo '<script type="text/javascript">document.getElementById("2checkout").submit();</script>';
        } else {
            echo '<input type="submit" value="'.$type.'" />';
            echo '</form>';
        }
    }

    public static function direct($params, $type='Checkout')
    {
        echo '<form id="2checkout" action="'.Twocheckout::$baseUrl.'/checkout/purchase" method="post">';

        foreach ($params as $key => $value)
        {
            echo '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
        }

        if ($type == 'auto') {
            echo '<input type="submit" value="Click here if the payment form does not open automatically." /></form>';
            echo '<script type="text/javascript">
                    function submitForm() {
                        document.getElementById("tco_lightbox").style.display = "block";
                        document.getElementById("2checkout").submit();
                    }
                    setTimeout("submitForm()", 2000);
                  </script>';
        } else {
            echo '<input type="submit" value="'.$type.'" />';
            echo '</form>';
        }

        echo '<script src="'.Twocheckout::$baseUrl.'/static/checkout/javascript/direct.min.js"></script>';
    }

    public static function link($params)
    {
        $url = Twocheckout::$baseUrl.'/checkout/purchase?'.http_build_query($params, '', '&amp;');
        return $url;
    }

    public static function redirect($params)
    {
        $url = Twocheckout::$baseUrl.'/checkout/purchase?'.http_build_query($params, '', '&amp;');
        header("Location: $url");
    }

    public static function auth($params=array())
    {
        $params['api'] = 'checkout';
        $request = new Twocheckout_Api_Requester();
        $result = $request->doCall('/checkout/api/1/'.self::$sid.'/rs/authService', $params);
        return Twocheckout_Util::returnResponse($result);
    }

}

class Twocheckout_Message
{
    public static function message($code, $message)
    {
        $response = array();
        $response['response_code'] = $code;
        $response['response_message'] = $message;
        $response = json_encode($response);
        return $response;
    }
}
