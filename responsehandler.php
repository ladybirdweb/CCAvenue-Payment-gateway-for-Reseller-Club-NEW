<?php
session_start();
@session_save_path("./");  
include('config.php');
require('functions.php');
include('crypto.php');

error_reporting(0);

$encResponse = $_POST["encResp"];   //This is the response sent by the CCAvenue Server
$rcvdString = decrypt($encResponse, $working_key);  //Crypto Decryption used as per the specified working key.
$order_status = "";
$decryptValues = explode('&', $rcvdString);
$dataSize = sizeof($decryptValues);
for ($i = 0; $i < $dataSize; $i++) {
    $information = explode('=', $decryptValues[$i]);
    if ($i == 3)
        $order_status = $information[1];
}

/* Reseller Club Code Starts */
$redirectUrl = $_SESSION['redirecturl'];  // redirectUrl received from foundation
//$Redirect_Urlb=$_SESSION['Redirect_Urlb'];
$transId = $_SESSION['transid'];   //Pass the same transid which was passsed to your Gateway URL at the beginning of the transaction.
$sellingCurrencyAmount = $_SESSION['sellingcurrencyamount'];
$accountingCurrencyAmount = $_SESSION['accountingcurencyamount'];
$status = $order_status; //$_REQUEST["status"];	 // Transaction status received from your Payment Gateway
//This can be either 'Y' or 'N'. A 'Y' signifies that the Transaction went through SUCCESSFULLY and that the amount has been collected.
//An 'N' on the other hand, signifies that the Transaction FAILED.

/* * HERE YOU HAVE TO VERIFY THAT THE STATUS PASSED FROM YOUR PAYMENT GATEWAY IS VALID.
 * And it has not been tampered with. The data has not been changed since it can * easily be done with HTTP request. 
 *
 * */

srand((double) microtime() * 1000000);
$rkey = rand();


/* Reseller Club Code Ends */

$PayuchecksumStatus = 1;
if ($order_status === "Success") {
    $status = "Y";
    $checksumStatus = 1;
} else if ($order_status === "Aborted") {
    $status = "N";
    $checksumStatus = 0;
} else if ($order_status === "Failure") {
    $status = "N";
    $checksumStatus = 0;
} else {
    $status = "N";
    $checksumStatus = 0;
}


$checksuma = generateChecksum($transId, $sellingCurrencyAmount, $accountingCurrencyAmount, $status, $rkey, $key);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">


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
        <title> CCAvenue Payment Gateway </title>

    </head>
    <body style="background:#ecf0f1;" >
        <form name="responsepage" id="responsepage" action="<?php echo $redirectUrl; ?>">
            <input type="hidden" name="url" value="<?php echo $redirectUrl; ?>">
            <input type="hidden" name="transid" value="<?php echo $transId; ?>">
            <input type="hidden" name="status" value="<?php echo $status; ?>">
            <input type="hidden" name="rkey" value="<?php echo $rkey; ?>">
            <input type="hidden" name="checksum" value="<?php echo $checksuma; ?>">
            <input type="hidden" name="sellingamount" value="<?php echo $sellingCurrencyAmount; ?>">
            <input type="hidden" name="accountingamount" value="<?php echo $accountingCurrencyAmount; ?>">
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

            <?php if ($PayuchecksumStatus) { ?>
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
            <?php }  if (!$checksumStatus) { ?>

                <!--NOTIFICATION MESSAGE-->

                <div class="row">
                    <div class="col-sm-3"></div>

                    <div id="messageDiv" class="col-md-6">
                        <center>
                            <div id ="notificationBar" class="alert alert-danger" role="alert"><?php if (isset($redirectUrl)) { ?>
                                    <b>Alert &nbsp;</b>Transcation Failed <?php } else { ?><b>Security Error.</b> Illegal access detected<?php } ?></div>
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

        <?php if (isset($redirectUrl)) { ?>
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#responsepage").submit();
                });
            </script>
        <?php } ?>
    </body>
</html>