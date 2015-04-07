<?php
session_start();
@session_save_path("./");

include('config.php');
require('functions.php');
include('crypto.php');

error_reporting(0);

/* Reseller Club Fields starts */

$paymentTypeId = $_GET["paymenttypeid"];  //payment type id
$transId = $_GET["transid"];      //This refers to a unique transaction ID which we generate for each transaction
$userId = $_GET["userid"];               //userid of the user who is trying to make the payment
$userType = $_GET["usertype"];       //This refers to the type of user perofrming this transaction. The possible values are "Customer" or "Reseller"
$transactionType = $_GET["transactiontype"]; //Type of transaction (ResellerAddFund/CustomerAddFund/ResellerPayment/CustomerPayment)
$invoiceIds = $_GET["invoiceids"];     //comma separated Invoice Ids, This will have a value only if the transactiontype is "ResellerPayment" or "CustomerPayment"
$debitNoteIds = $_GET["debitnoteids"];    //comma separated DebitNotes Ids, This will have a value only if the transactiontype is "ResellerPayment" or "CustomerPayment"
$description = $_GET["description"];
$sellingCurrencyAmount = $_GET["sellingcurrencyamount"]; //This refers to the amount of transaction in your Selling Currency
$accountingCurrencyAmount = $_GET["accountingcurrencyamount"]; //This refers to the amount of transaction in your Accounting Currency
$redirectUrl = $_GET["redirecturl"];  //This is the URL on our server, to which you need to send the user once you have finished charging him
$checksuma = $_GET["checksum"];   //checksum for validation

/* Reseller Club Fields ends */

$order_id = $_GET["transid"];
$amount = $_GET["sellingcurrencyamount"];
$currency = $currency;
$cancel_url = $redirect_url;
$language = "EN";
$billing_name = $_GET["name"];
$billing_address = $_GET["address1"];
$billing_city = $_GET["city"];
$billing_state = $_GET["state"];
$billing_zip = $_GET["zip"];
$billing_country = $_GET["country"];
$billing_tel = $_POST["telNoCc"] . $_GET["telNo"];
$billing_email = $_GET["emailAddr"];
$delivery_name = $billing_name;
$delivery_address = $billing_address;
$delivery_city = $billing_city;
$delivery_state = $billing_state;
$delivery_zip = $billing_zip;
$delivery_country = $billing_country;
$delivery_tel = $billing_tel;
$merchant_param1 = "";
$merchant_param2 = "";
$merchant_param3 = "";
$merchant_param4 = "";
$merchant_param5 = "";
$promo_code = "";
$customer_Id = "";

/* Reseller Club code starts */
if (verifyChecksum1($paymentTypeId, $transId, $userId, $userType, $transactionType, $invoiceIds, $debitNoteIds, $description, $sellingCurrencyAmount, $accountingCurrencyAmount, $key, $checksuma)) {
    $_SESSION['redirecturl'] = $redirectUrl;
    $_SESSION['transid'] = $transId;
    $_SESSION['sellingcurrencyamount'] = $sellingCurrencyAmount;
    $_SESSION['accountingcurencyamount'] = $accountingCurrencyAmount;
    $checksumStatus = 1;
} else {
    $checksumStatus = 0;
    $base_url = "";
}

/* Reseller Club code ends */
$merchant_data = 'merchant_id=' . $merchant_id . '&order_id=' . $order_id . '&amount=' . $amount . '&currency=' . $currency . '&redirect_url=' . $redirect_url .
        '&cancel_url=' . $cancel_url . '&language=' . $language . '&billing_name=' . $billing_name . '&billing_address=' . $billing_address .
        '&billing_city=' . $billing_city . '&billing_state=' . $billing_state . '&billing_zip=' . $billing_zip . '&billing_country=' . $billing_country .
        '&billing_tel=' . $billing_tel . '&billing_email=' . $billing_email . '&delivery_name=' . $delivery_name . '&delivery_address=' . $delivery_address .
        '&delivery_city=' . $delivery_city . '&delivery_state=' . $delivery_state . '&delivery_zip=' . $delivery_zip . '&delivery_country=' . $delivery_country .
        '&delivery_tel=' . $delivery_tel . '&merchant_param1=' . $merchant_param1 . '&merchant_param2=' . $merchant_param2 .
        '&merchant_param3=' . $merchant_param3 . '&merchant_param4=' . $merchant_param4 . '&merchant_param5=' . $merchant_param5 . '&promo_code=' . $promo_code .
        '&customer_identifier=' . $customer_identifier;

$encrypted_data = encrypt($merchant_data, $working_key); // Method for encrypting the data.
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CCAvenue Payment Gateway</title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom -->
        <link href="css/custom.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body style="background:#ecf0f1;">

        <form method="post" name="paymentpage" action="<?php echo $base_url; ?>"> 
            <?php
            echo "<input type=hidden name=command value=$command>";
            echo "<input type=hidden name=encRequest value=$encrypted_data>";
            echo "<input type=hidden name=access_code value=$access_code>";
            ?>
            <input type="hidden" value="submit">
        </form>


        <section id="transMessageSec" class="container">

            <!--GIF LOADER-->

            <div class="row"> 
                <div class="col-md-3"></div>
                <div id="loaderCol" class="col-md-6">
                    <center>
                        <img class="img-responsive" src="images/ajax-loader.gif"/>
                    </center>	
                </div>
                <div class="col-md-3"></div>
            </div>

            <!--GIF LOADER-->

<?php if ($checksumStatus) { ?>
                <!--TRANSACTION MESSAGE-->

                <div class="row">
                    <div id="messageDiv" class="col-md-12">
                        <div class="alert-message alert-message-success text-center">
                            <h4>Transaction is being processed</h4>
                            <p>Please wait while your transaction is being processed ... </p>
                            <p> (Please do not use "Refresh" or "Back" button)</p>
                        </div>
                    </div>
                </div> 

                <!--TRANSACTION MESSAGE-->	
            <?php } if (!$checksumStatus) { ?>

                <!--NOTIFICATION MESSAGE-->

                <div class="row">
                    <div class="col-sm-3"></div>

                    <div id="messageDiv" class="col-md-6">
                        <center>
                            <div id ="notificationBar" class="alert alert-danger" role="alert">
                                <b>Security Error.</b> Illegal access detected</div>
                        </center>
                    </div>

                    <div class="col-sm-3"></div>
                </div> 

                <!--NOTIFICATION MESSAGE-->	
<?php } ?>
        </section>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <?php if ($checksumStatus) { ?>
            <script language='javascript'>document.paymentpage.submit();</script>
<?php } ?>

    </body>
</html>
