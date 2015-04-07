<?php

/**
 * Plugin Name: CCAvenue Payment Gateway for Reseller Club
 * Plugin URI: http://codecanyon.net/item/ccavenue-payment-gateway-for-reseller-club/6858951
 * Description: This extends Reseller Club to accepts money/payments through CCAvenues Payment gateway on your supersite. 
 * File Description: The base configurations of the plugin.
 * This file has the following configurations:  Reseller Club Key, CCAvenue Merchant ID, CCAVenue Working Key and CCAvenue Access Code
 * Author: Ladybird Web Solution
 * Author URI: http://www.ladybirdweb.com
 * Version: 1.2.5
 * Copyright 2014 Ladybird Web Solution
 * @package  CCAvenue Payment Gateway for Reseller Club
 * License: Envato/codecanyon Regular License
 * License URI: http://codecanyon.net/licenses/regular
 */
/** Reseller Club Key */
$key = "key_here"; //replace ur 32 bit secure key , Get your secure key from your Reseller Control panel

/** CCAvenue Merchant ID */
$merchant_id = "merchant_id_here"; //This id(also User Id)  available at "Generate Working Key" of "Settings & Options" 

/** CCAvenue Working Key or Encryption Key */
$working_key = "working_keycc_here"; //Shared by CCAVENUES

/** CCAvenue Access Code */
$access_code = "access_code_here"; //Shared by CCAVENUES

/** Directry URL */
$directory_url = "directory_url_here";

/** Redirection URL */
$redirect_url = "redirect_url_here";

/** Action URL from Chekout page */
$action_url = "action_url_here";

/** Currency */
$currency = "currency_here";

/** Gateway Post URL */
$base_url = "https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction";
// 	Test: https://test.ccavenue.com
?>




