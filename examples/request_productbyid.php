<?php
/**
 * This Syndy Retailer Api example uses the GetProductRequest class
 * to request a single product by its Syndy Product Id.
 */
// define('PUBLICKEY', 'Your Public Key Here');
// define('PRIVATEKEY', 'Your Private Key Here');
// define('PRODUCT_ID', 'The Product Id You Want To Request Here (GUID)');
// define('CULTUREID', 'The two-letter culture id of the language you expect to receive the product data in');

define('PUBLICKEY', '62Ff5-c0ws0x32XMUVrUywY4QA_B2YpPyB6ddQ3VbbKOn1XgAhVmQkq7Th4Im2C6mc-1OPf9TCIG9UpENBjtC4ap2wA8ZTdiFDn-No-Se4xiRW8-KwXr1kpqXjrVhGxDRnoXc1oWGUXHkLLkyb8jxgAY-aRKVKZfsdNnw4id6sc=');
define('PRIVATEKEY', 'XN_3ZGYTNo1v9Nlp4jJMC53jKZ_9zoyTIF8D9zN9k-lJ3E9nNVYItbWEboyYduil6xtqrljb24ztVbd_lrmnTzhgT-Rz557zhEkM7AdSOv8utI6GogpOEx5NhF3m4uzHtyayUMhZbCWmoZcMILOuPHY6HANZTPbyjbSOrZaI6aE=');
define('PRODUCT_ID', '6297d976-b360-4951-b200-489555f5d9a4');
define('CULTUREID', 'nl');

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