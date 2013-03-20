<?php
/**
 * VehicleData.co demo
 *
 * Please go to www.vehicledata.co to request an API key and secret. Once you you have
 * those plugged in below, this script can be run by calling:
 *
 *     php demo.php
 *
 * Take a look at the output of service.getSupportedFunctions or browse the API docs
 * at www.vehicledata.co to learn more.
 */

require_once "vehicledataco.php";

$appkey = "YOUR_APPKEY_HERE";
$secret = "YOUR_SECRET_HERE";

$vdc = new VehicleDataCo($appkey, $secret);

$ret = $vdc->service_getSupportedFunctions();
var_dump($ret);

echo "Retrieving all makes for the year 2008:\n";
$ret = $vdc->vehicles_getMakes(2008);
var_dump ($ret);

echo "\nRetrieving all models for Audi in 2008:\n";
$ret = $vdc->vehicles_getModels(2008, 'Audi');
var_dump ($ret);

echo "\nRetrieving all trims for an Audi A4 in 2008:\n";
$ret = $vdc->vehicles_getTrims(2008, 'Audi', 'A4');
var_dump ($ret);

echo "\nRetrieving all styles & trims for an Audi A4 in 2008:\n";
$ret = $vdc->vehicles_getStyleTrims(2008, 'Audi', 'A4');
var_dump ($ret);

echo "\nRetrieving all transmissions for an Audi A6 2.0T quattro in 2000:\n";
$ret = $vdc->vehicles_getTransmissions(2008, 'Audi', 'A4', 'sedan', '2.0T quattro');
var_dump ($ret);

?>