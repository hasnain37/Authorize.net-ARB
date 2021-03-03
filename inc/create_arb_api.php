<?php

//require plugin_dir_path(__FILE__)  . 'sdk-php/autoload.php';
//require plugin_dir_path(__FILE__) . 'sdk-php/sample-code-php/constants/SampleCodeConstants.php';
//require '/www/gfl/wp-content/plugins/authorize/sdk-php/sample-code-php/constants/SampleCodeConstants.php';
require_once plugin_dir_path(__DIR__) . 'sdk-php/sample-code-php/constants/SampleCodeConstants.php';
//require 'vendor/autoload.php';
require plugin_dir_path(__DIR__) . 'sdk-php/sample-code-php/vendor/autoload.php';

//use \net\authorize\api\contract\v1 as AnetAPI;

use net\authorize\api\contract\v1\ARBCancelSubscriptionRequest;
use net\authorize\api\contract\v1\ARBCreateSubscriptionRequest;
use net\authorize\api\contract\v1\ARBGetSubscriptionRequest;
use net\authorize\api\contract\v1\ARBSubscriptionType;
use net\authorize\api\contract\v1\CreditCardType;
use net\authorize\api\contract\v1\CustomerAddressType;
use net\authorize\api\contract\v1\CustomerType;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use net\authorize\api\contract\v1\NameAndAddressType;
use net\authorize\api\contract\v1\OrderType;
use net\authorize\api\contract\v1\PaymentScheduleType;
use net\authorize\api\contract\v1\PaymentScheduleType\IntervalAType;
use net\authorize\api\contract\v1\PaymentType;
use net\authorize\api\controller as AnetController;

date_default_timezone_set('America/Los_Angeles');

define("AUTHORIZENET_LOG_FILE", "phplog");

function createSubscription1($fname, $lname, $cardnum, $cardcode, $expdate, $address, $city, $country, $state, $zip, $email, $phone)
{
        /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
        $merchantAuthentication = new MerchantAuthenticationType();
        $merchantAuthentication->setName(\SampleCodeConstants::MERCHANT_LOGIN_ID);
        $merchantAuthentication->setTransactionKey(\SampleCodeConstants::MERCHANT_TRANSACTION_KEY);

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Subscription Type Info
        $subscription = new ARBSubscriptionType();
        $subscription->setName("Reggie's Virtual Bootcamp");

        $interval = new IntervalAType();
        $interval->setLength("7");
        $interval->setUnit("days");

        $paymentSchedule = new PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new DateTime(date("Y-m-d")));
        $paymentSchedule->setTotalOccurrences("12");
        $paymentSchedule->setTrialOccurrences("0");

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount("25.00");
        $subscription->setTrialAmount("0.00");

        $creditCard = new CreditCardType();
        $creditCard->setCardNumber($cardnum);
        $creditCard->setExpirationDate($expdate);
        $creditCard->setCardCode($cardcode);

        $payment = new PaymentType();
        $payment->setCreditCard($creditCard);
        $subscription->setPayment($payment);

        $order = new OrderType();
        $order->setInvoiceNumber("123544");
        $order->setDescription("Join coach Reggie for a week full-body Virtual Bootcamp!");
        $subscription->setOrder($order);

        $billTo = new NameAndAddressType();
        $billTo->setFirstName($fname);
        $billTo->setLastName($lname);
        $billTo->setAddress($address);
        $billTo->setCity($city);
        $billTo->setCountry($country);
        $billTo->setState($state);
        $billTo->setZip($zip);

        $subscription->setBillTo($billTo);

        $customer = new CustomerType();
        $customer->setEmail($email);
        $customer->setPhoneNumber($phone);

        $subscription->setCustomer($customer);

        $request = new ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);

        // For PRODUCTION use
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
                echo "<div class='success'><h4>You are Successfully Subscribed for Reggie's Virtual Bootcamp </h4>\n Here is Subscription ID: " . $response->getSubscriptionId() . "\n <br>Please save this 'Subscription ID' for future use. \n</div>";
                //echo "Here is Subscription ID: " . $response->getSubscriptionId() . "\n";
                //echo "<br>Please save this 'Subscription ID' for future use. \n";

        } else {
                echo "<div class='fail'><h4>There is an error. Please review your details and try again\n</h4></div>";
                //$errorMessages = $response->getMessages()->getMessage();
                //echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";

        }
        return $response;
}
function createSubscription2($fname, $lname, $cardnum, $cardcode, $expdate, $address, $city, $country, $state, $zip, $email, $phone)
{
        /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
        $merchantAuthentication = new MerchantAuthenticationType();
        $merchantAuthentication->setName(\SampleCodeConstants::MERCHANT_LOGIN_ID);
        $merchantAuthentication->setTransactionKey(\SampleCodeConstants::MERCHANT_TRANSACTION_KEY);

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Subscription Type Info
        $subscription = new ARBSubscriptionType();
        $subscription->setName("Fit and Fierce with Grace");

        $interval = new IntervalAType();
        $interval->setLength("1");
        $interval->setUnit("months");

        $paymentSchedule = new PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new DateTime(date("Y-m-d")));
        $paymentSchedule->setTotalOccurrences("12");
        $paymentSchedule->setTrialOccurrences("0");

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount("20.00");
        $subscription->setTrialAmount("0.00");

        $creditCard = new CreditCardType();
        $creditCard->setCardNumber($cardnum);
        $creditCard->setExpirationDate($expdate);
        $creditCard->setCardCode($cardcode);

        $payment = new PaymentType();
        $payment->setCreditCard($creditCard);
        $subscription->setPayment($payment);

        $order = new OrderType();
        $order->setInvoiceNumber("123544");
        $order->setDescription("This program works on a whole-body transformation.");
        $subscription->setOrder($order);

        $billTo = new NameAndAddressType();
        $billTo->setFirstName($fname);
        $billTo->setLastName($lname);
        $billTo->setAddress($address);
        $billTo->setCity($city);
        $billTo->setCountry($country);
        $billTo->setState($state);
        $billTo->setZip($zip);




        $subscription->setBillTo($billTo);

        $customer = new CustomerType();
        $customer->setEmail($email);
        $customer->setPhoneNumber($phone);

        $subscription->setCustomer($customer);


        $request = new ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);

        // For PRODUCTION use
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);


        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
                echo "<div class='success'><h4>You are Successfully Subscribed for Fit and Fierce </h4>\n Here is Subscription ID: " . $response->getSubscriptionId() . "\n <br>Please save this 'Subscription ID' for future use. \n</div>";
                //echo "Here is Subscription ID: " . $response->getSubscriptionId() . "\n";
                //echo "<br>Please save this 'Subscription ID' for future use. \n";

        } else {
                echo "<div class='fail'><h4>There is an error. Please review your details and try again\n</h4></div>";
                //$errorMessages = $response->getMessages()->getMessage();
                //echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
        }
        return $response;
}
function cancelSubscription($subscriptionId)
{
        /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
        $merchantAuthentication = new MerchantAuthenticationType();
        $merchantAuthentication->setName(\SampleCodeConstants::MERCHANT_LOGIN_ID);
        $merchantAuthentication->setTransactionKey(\SampleCodeConstants::MERCHANT_TRANSACTION_KEY);

        // Set the transaction's refId
        $refId = 'ref' . time();

        $request = new ARBCancelSubscriptionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($subscriptionId);

        $controller = new AnetController\ARBCancelSubscriptionController($request);

        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
                //$successMessages = $response->getMessages()->getMessage();
                echo "<div class='success'>Your subscription againist ID: " . $subscriptionId . " is successfully cancelled!</div>";

                //echo "SUCCESS : " . $successMessages[0]->getCode() . "  " . $successMessages[0]->getText() . "\n";
        } else {
                echo "<div class='fail'>Your  subscription  ID: " . $subscriptionId . " is invaild!</div>";
                //echo "ERROR :  Invalid response\n";
                //$errorMessages = $response->getMessages()->getMessage();
                // echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
        }

        return $response;
}
