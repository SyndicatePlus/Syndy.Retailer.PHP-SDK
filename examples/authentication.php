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

// Retrieve a Token with expiration date. 
$token = $connection->authenticate();
var_dump($token);

// Verify that the credentials have been updated by the SyndyApiConnection
// class to include the retrieved token. Credentials can be shared this way
// across multiple connections without requiring authentication for every
// connection separately.
var_dump($credentials);

?>