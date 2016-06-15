## Syndy Retailer API

###### IMPORTANT NOTE: The PHP-SDK is still under construction. For examples on how to use the SyndyApiConnection class, have a look in the examples folder. Please note that the SyndyApiConnection class is a low-level utility and access to the API will take place through semantically more full-fledged helper classes.

#### Summary
The Syndy Retailer API allows retailers to connect programmatically to their retailer assortment on the Syndy Platform. This is the Retailer API's native PHP Client SDK, and it replaces the deprecated [SyndicatePlusApi-PHP SDK][1].

Please note that the Syndy Retailer API is only suitable for retailers and webshops who host their assortment on Syndy. It is currently not possible to look up products by EAN outside of the context of a retailer assortment on the Syndy Platform.

[1]: https://github.com/SyndicatePlus/SyndicatePlusApi-PHP 

#### API Access
For API Access it is imperative you own a Syndy Retailer account. If you do not yet have such an account, you can sign-up for [The Syndy Platform][2], making sure you select "Retailer" when asked for your account type. As soon as you have an account, you can contact our customer support team for instructions on receiving API credentials.

[2]: https://my.syndy.com/signup

#### Usage
Usage of the Syndy Retailer API is best illustrated by taking a look at the included [examples][3]. To get started quickly, however, all you need to make a successful call to the Syndy Retailer API is the following snippet of code:

```php
define('PUBLICKEY', 'Your public key here');
define('PRIVATEKEY', 'Your private key here');

// Include the Api Manager file
require "Syndy.Api.PHP-SDK/src/syndyretailerapimanager.class.php";

// Create credentials container object and construct ApiManager
$credentials = new Syndy\Api\Auth\SyndyApiCredentials(PUBLICKEY, PRIVATEKEY);
$api = new Syndy\Api\SyndyRetailerApiManager($credentials);

// Create a request that fetches only that part of the assortment in which
// changes have been made since yesterday.
$request = $api->createRetailerAssortmentRequest(time() - 86400);
$response = $request->execute();

var_dump($response);
```

[3]: https://github.com/SyndicatePlus/Syndy.Retailer.PHP-SDK/tree/master/examples

#### Preferred Mode of Operation
The preferred mode of operation for retailers or webshops who choose to use the Syndy Retailer API to get access to the latest product content within their assortment, is to request changed products on a periodic basis and then request the details for each changed product separately. A simplified version of a typical "refresh-product-data.php" cron job might then look as follows:

```php
define('PUBLICKEY', 'Your public key here');
define('PRIVATEKEY', 'Your private key here');
define('CULTUREID', 'The 2-letter language id in which i want to receive content');

// Include the Api Manager file
require "Syndy.Api.PHP-SDK/src/syndyretailerapimanager.class.php";

// Create credentials container object and construct ApiManager
$credentials = new Syndy\Api\Auth\SyndyApiCredentials(PUBLICKEY, PRIVATEKEY);
$api = new Syndy\Api\SyndyRetailerApiManager($credentials);

// Request changes made to my assortment since 1 hour ago
$request = $api->createRetailerAssortmentRequest(time() - 3600);
$assortmentProducts = $request->execute();

// Walk through the products returned by the API and fetch the details of each
// individual product.
// NOTE: This is a simplified example. The returned assortment is paged, and if
// more than 50 products are returned, the request needs to be re-executed with
// different pagination settings.
foreach ($assortmentProducts as $product) {
	// Create product request and execute.
	$productRequest = $api->getProductRequest($product->getId(), CULTUREID);
	$productDetails = $productRequest->execute();

	// TODO: Do something with the $productDetails object, e.g. store in database
}
```