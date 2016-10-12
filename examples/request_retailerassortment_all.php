<?php
/**
 * This Syndy Retailer Api example uses the higher level SyndyRetailerApiManager
 * class to create a retailer assortment request to walk through all the pages of
 * a retailer assortment response.
 */
define('PUBLICKEY', 'Your public key here');
define('PRIVATEKEY', 'Your private key here');

require_once "../src/syndyretailerapimanager.class.php";

// Create a credentials object which holds the user's keys and access token
$credentials = new Syndy\Api\Auth\SyndyApiCredentials(PUBLICKEY, PRIVATEKEY);
$api = new Syndy\Api\SyndyRetailerApiManager($credentials);

// We configure the page size first:
define('PAGE_SIZE', 250);

// Create a request that fetches only that part of the assortment in which
// changes have been made since the specified date. A large timespan is chosen
// to increase the likelihood that there will be multiple pages of results.
$request = $api->createRetailerAssortmentRequest("2015-01-01T00:00:00Z");
$request->setAmount(PAGE_SIZE);
$request->setOffset(0);

// As long as the result count is equal to the page size, we have to check the
// the next page
$batchCounter = 0;
do {
	// Request the currently configured page and walk through the results
	$assortmentPage = $request->execute();
	foreach ($assortmentPage as $productReference) {
	    
	    // TODO: Do something here, like requesting the product details for the returned reference, or maybe
	    // marking the product as "mutated" in your own local database.

	}

	// Print the current batch and increase the offset with the PAGE_SIZE
	echo "Batch ". (++$batchCounter) ." requested: ". $assortmentPage->getResultCount() ." results of ". $assortmentPage->getTotalCount() ." total.<br />";
	flush();

	$request->setOffset($request->getOffset() + PAGE_SIZE);
}
while ($assortmentPage->getResultCount() == PAGE_SIZE);

?>