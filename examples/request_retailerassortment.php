<?php
/**
 * This Syndy Retailer Api example uses the higher level SyndyRetailerApiManager
 * class to create a retailer assortment request to fetch a retailer assortment.
 */
define('APIKEY', 'Your Public Key Here');
define('APISECRET', 'Your Private Key Here');

require_once "../src/syndyretailerapimanager.class.php";

// Create a credentials object which holds the user's keys and access token
$credentials = new Syndy\Api\Auth\SyndyApiCredentials(APIKEY, APISECRET);
$api = new Syndy\Api\SyndyRetailerApiManager($credentials);

$request = $api->createRetailerAssortmentRequest(time() - 86400);
$response = $request->execute();
var_dump($response);

?>