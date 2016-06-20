<?php
/**
 * This Syndy Retailer Api example uses the GetProductRequest class
 * to request a single product by its Syndy Product Id.
 */
define('PUBLICKEY', 'Your Public Key Here');
define('PRIVATEKEY', 'Your Private Key Here');
define('PRODUCT_ID', 'The Product Id You Want To Request Here (GUID)');
define('CULTUREID', 'The two-letter culture id of the language you expect to receive the product data in');

require_once "../src/syndyretailerapimanager.class.php";

// Create a credentials object which holds the user's keys and access token
$credentials = new Syndy\Api\Auth\SyndyApiCredentials(PUBLICKEY, PRIVATEKEY);
$api = new Syndy\Api\SyndyRetailerApiManager($credentials);

// Create a request that fetches only that part of the assortment in which
// changes have been made since yesterday.
$request = $api->createGetProductRequest(PRODUCT_ID, CULTUREID);
$response = $request->execute();
var_dump($response);

// Convenience methods to access data:
echo "Product Name: " . $response->getName();
echo "<br />Brand Name: " . $response->getBrand()->getName();
echo "<br />Manufacturer Name: ". $response->getBrand()->getManufacturer()->getName();
echo "<br />Short Description: ". $response->getShortDescription();

?>