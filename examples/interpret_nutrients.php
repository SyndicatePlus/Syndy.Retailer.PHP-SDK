<?php
/**
 * This Syndy Retailer Api example uses the GetProductRequest class
 * to request a single product by its Syndy Product Id and then calls
 * upon the NutrientsInterpeter to display a nutrients table.
 */
define('PUBLICKEY', 'Your Public Key Here');
define('PRIVATEKEY', 'Your Private Key Here');
define('PRODUCT_ID', '304af782-8774-4e80-9fb0-67b410c4a402');
define('CULTUREID', 'nl');

require_once "../src/syndyretailerapimanager.class.php";

// We must include the interpreter separately.
require_once "../src/interpreters/food/nutrientsinterpreter.class.php";

// Create a credentials object which holds the user's keys and access token
// NOTE: it makes sense to persist credentials across executions of individual scripts.
// A token is typically valid for ~24 hours, so depending on your use of the API, you
// may save a lot of unnecessary authenticate calls.
$credentials = new Syndy\Api\Auth\SyndyApiCredentials(PUBLICKEY, PRIVATEKEY);
$api = new Syndy\Api\SyndyRetailerApiManager($credentials);

// Create a request that retrieves a single product identified by PRODUCT_ID and
// using the specified CULTUREID as requested language
$request = $api->createGetProductRequest(PRODUCT_ID, CULTUREID);
$product = $request->execute();

// Convenience methods to access data:
echo "<br /><strong>Product Name:</strong> " . $product->getName(); // Or: $product->name
echo "<br /><strong>Profile Image:</strong><br /><br /><img style=\"width: 200px; height: 200px;\" src=\"" . $product->image->url ."\" />";

// Not every template names its fields the same way. In this particular case, the field is named "Nutrients",
// but other templates may use different names. An easy way to find out all the fieldnames for a particular template is
// to iterate over them like so:
// foreach ($product->getFields() as $field) {
//		echo "<br />". $field->key;	
// }
$field = $product->findField("Nutrients");

// Use the NutrientsInterpreter
$nutrients = new Syndy\Api\Interpreters\Food\NutrientsInterpreter($field);

// The following terms are handy to keep in mind:
// uom 			= Unit of Measurement (e.g. mg, g, mL)
// gda 			= Guideline Daily Amount (% of daily amount needed for an adult body)
?>

<table border="1" cellpadding="2">
<thead>
	<tr>
		<th colspan="<?=($nutrients->hasPortion ? '3' : '2');?>">
			<?=$nutrients->count;?> Nutrient(s)
		</th>
	</tr>
	<tr>
		<th>Nutrient</th>
		<th>Per 100<?=$nutrients->portion->uom;?></th>
		<?php if ($nutrients->hasPortion()) { ?>
			<th>Per portion (<?=$nutrients->portion->size."".$nutrients->portion->uom;?>)</th>
		<?php } ?>
	</tr>
</thead>
<tbody>

	<?php foreach ($nutrients as $nutrient) { ?>
		<tr>
		<td><?=$nutrient->displayName;?></td>
		<td><?=$nutrient->amounts[0]->amount ."". $nutrient->amounts[0]->uom ." (".$nutrient->amounts[0]->gda."%)";?></td>
		<?php if ($nutrients->hasPortion()) { ?>
			<td><?=$nutrient->amounts[1]->amount ."". $nutrient->amounts[1]->uom ." (".$nutrient->amounts[1]->gda."%)";?></td>
		<?php } ?>
		</tr>
	<?php } ?>
	
</tbody>
</table>