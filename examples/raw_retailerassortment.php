<?php
/**
 * This Syndy Retailer Api example uses the lower-level SyndyApiConnection
 * class to authenticate with the API and retrieve a token which may be
 * used for subsequent API calls.
 */
define('APIKEY', 'Your Public Key Here');
define('APISECRET', 'Your Private Key Here');

require_once "../src/net/syndyapiconnection.class.php";

// Create a credentials object which holds the user's keys and access token
$credentials = new Syndy\Api\Auth\SyndyApiCredentials(APIKEY, APISECRET);
$connection = new Syndy\Api\Net\SyndyApiConnection($credentials);

// Credentials do not contain a token yet
var_dump($credentials);

// Retrieve the raw retailer assortment response, using OData paraters to fetch the 2nd
// batch of 50 products
$response = $connection->sendRequest("GET", "retailer/assortment", "\$skip=50&\$top=50");
var_dump($response);

?>