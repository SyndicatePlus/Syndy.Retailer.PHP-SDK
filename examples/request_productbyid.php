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
$product = $request->execute();

// Convenience methods to access data:
echo "<br /><strong>Product Name:</strong> " . $product->getName(); // Or: $product->name
echo "<br /><strong>Brand Name:</strong> " . $product->getBrand()->getName(); // Or: $product->brand->name
echo "<br /><strong>Manufacturer Name:</strong> ". $product->brand->manufacturer->name; // Or: $product->getBrand()->getManufacturer()->getName()
echo "<br /><strong>Short Description:</strong> ". $product->getShortDescription(); // Or: $product->shortDescription

foreach ($product->getFields() as $field) {
	if ($field->isArray()) {
		echo "<br /><strong>" . $field->key .":</strong>";

		foreach ($field->value as $arrayEntry) {
			echo "<br />- " . $arrayEntry->value;
		}
	}
	else {
		echo "<br /><strong>" . $field->getKey() .":</strong> ". $field->value;
	}	
}

echo "<br /><strong>Profile Image:</strong><br /><br /><img style=\"width: 400px; height: 400px;\" src=\"" . $product->image->url ."\" />";

// Find a field by name:
echo "<br /><br />Looking for field 'Gender': ";
var_dump($product->findField("Gender"));
var_dump($product->gender); // __get magic
?>