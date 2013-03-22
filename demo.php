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
echo json_pretty_print($ret);

/*
echo "\nRetrieving all makes for the year 2008:\n";
$ret = $vdc->vehicles_getMakes(2008);
echo json_pretty_print($ret);

echo "\nRetrieving all models for Audi in 2008:\n";
$ret = $vdc->vehicles_getModels(2008, 'Audi');
echo json_pretty_print($ret);

echo "\nRetrieving all trims for an Audi A4 in 2008:\n";
$ret = $vdc->vehicles_getTrims(2008, 'Audi', 'A4');
echo json_pretty_print($ret);

echo "\nRetrieving all styles & trims for an Audi A4 in 2008:\n";
$ret = $vdc->vehicles_getStyleTrims(2008, 'Audi', 'A4');
echo json_pretty_print($ret);

echo "\nRetrieving all transmissions for an Audi A6 2.0T quattro in 2000:\n";
$ret = $vdc->vehicles_getTransmissions(2008, 'Audi', 'A4', 'sedan', '2.0T quattro');
echo json_pretty_print($ret);
*/

function json_pretty_print($json) {

    $result      = '';
    $pos         = 0;
    $strLen      = strlen($json);
    $indentStr   = '  ';
    $newLine     = "\n";
    $prevChar    = '';
    $outOfQuotes = true;

    for ($i=0; $i<=$strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;

        // If this character is the end of an element,
        // output a new line and indent the next line.
        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $result .= $indentStr;
            }
        }

        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element,
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        $prevChar = $char;
    }

    return $result;
}
?>