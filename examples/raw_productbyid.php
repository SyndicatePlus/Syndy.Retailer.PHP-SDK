<?php
/**
 * This Syndy Retailer Api example uses the lower-level SyndyApiConnection
 * class to request a single product by its Syndy Product Id.
 */
define('APIKEY', 'Your Public Key Here');
define('APISECRET', 'Your Private Key Here');
define('PRODUCT_ID', 'The Product Id You Want To Request Here (GUID)');

require_once "../src/net/syndyapiconnection.class.php";

// Create a credentials object which holds the user's keys and access token
$credentials = new Syndy\Api\Auth\SyndyApiCredentials(APIKEY, APISECRET);
$connection = new Syndy\Api\Net\SyndyApiConnection($credentials);

// Important: content will only be returned if you specify the correct "culture id" here.
// Because Syndy already segments its products by market, it should be just the two-letter
// culture id without area information. E.g. instead of "en-GB", just use "en"
$connection->setCultureId("en");

// Credentials do not contain a token yet
var_dump($credentials);

// Retrieve the raw retailer assortment response, using OData paraters to fetch the 2nd
// batch of 50 products
$response = $connection->sendRequest("GET", "product/".PRODUCT_ID);
var_dump($response);

?>