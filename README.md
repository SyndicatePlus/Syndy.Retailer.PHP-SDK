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

```
$credentials = new Syndy\Api\Auth\SyndyApiCredentials(APIKEY, APISECRET);
$api = new Syndy\Api\SyndyRetailerApiManager($credentials);

// Create a request that fetches only that part of the assortment in which
// changes have been made since yesterday.
$request = $api->createRetailerAssortmentRequest(time() - 86400);
$response = $request->execute();
```

[3]: https://github.com/SyndicatePlus/Syndy.Retailer.PHP-SDK/tree/master/examples