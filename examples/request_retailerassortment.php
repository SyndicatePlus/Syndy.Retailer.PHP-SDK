<?php
/**
 * This Syndy Retailer Api example uses the higher level SyndyRetailerApiManager
 * class to create a retailer assortment request to fetch a retailer assortment.
 */
define('PUBLICKEY', 'Your Public Key Here');
define('PRIVATEKEY', 'Your Private Key Here');

require_once "../src/syndyretailerapimanager.class.php";

// Create a credentials object which holds the user's keys and access token
$credentials = new Syndy\Api\Auth\SyndyApiCredentials(PUBLICKEY, PRIVATEKEY);
$api = new Syndy\Api\SyndyRetailerApiManager($credentials);

// Create a request that fetches only that part of the assortment in which
// changes have been made since yesterday.
$request = $api->createRetailerAssortmentRequest(time() - 3600);
// $request->setAmount(100);	// Set bigger page size
// $request->setOffset(50);		// From what offset (0-based) to start fetching results
$response = $request->execute();
var_dump($response);

?>