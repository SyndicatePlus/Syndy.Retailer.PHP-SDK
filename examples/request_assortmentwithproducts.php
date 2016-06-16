<?php
/** 
 * This Syndy Retailer API example more closely resembles real world usage
 * by combining the GetRetailerAssortment call and the GetProductById call
 * to request the product details for all products that have been changed
 * in the retailer assortment since yesterday.
 */

define('PUBLICKEY', 'Your public key here');
define('PRIVATEKEY', 'Your private key here');
define('CULTUREID', 'The 2-letter language id in which i want to receive content');

// Include the Api Manager file
require "../src/syndyretailerapimanager.class.php";

// Create credentials container object and construct ApiManager
$credentials = new Syndy\Api\Auth\SyndyApiCredentials(PUBLICKEY, PRIVATEKEY);
$api = new Syndy\Api\SyndyRetailerApiManager($credentials);

// Request changes made to my assortment since 1 hour ago
$request = $api->createRetailerAssortmentRequest(time() - 86400);
$assortment = $request->execute();

// Walk through the products returned by the API and fetch the details of each
// individual product.
// NOTE: This is a simplified example. The returned assortment is paged, and if
// more than 50 products are returned, the request needs to be re-executed with
// different pagination settings.
$counter = 1;
foreach ($assortment as $product) {
    // Create product request and execute.
    $productRequest = $api->createGetProductRequest($product->getId(), CULTUREID);
    $productDetails = $productRequest->execute();

    // TODO: Do something with the $productDetails object, e.g. store in database
    echo "Product ". $counter++ .": ". $productDetails->getBarcode() ." - ". $productDetails->getName() . "<br />";
}
?>