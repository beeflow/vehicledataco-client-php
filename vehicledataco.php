<?php
/**
 * VehicleData.co Client - PHP
 *
 * This is the source for the PHP client for the VehicleData.co API, a service which
 * provides various pieces of vehicle-related data. It currently is focused on cars, but
 * is intended to be expanded to other vehicles types.
 *
 * For more information, please visit http://www.vehicledata.co.
 *
 * Copyright 2013 Blacktop Ventures LLC
 */

class VehicleDataCo {

    /**
     * Initiliazes the class with the required information to access the VehicleDataCo
     * service. You can request a key and secret by visting http://www.vehicledata.co.
     * 
     * @param  appkey  application key provided by service
     * @param  secret  secret provided by service
     * @param  version the version of the API to us, defaults to latest (optional)
     * @return         an initialized object to access the VehicleDataCo service
     */
    function __construct($appkey, $secret, $version = "") {
        $this->version = "0.1";
        $this->host = "api.vehicledata.co";
        $this->appkey = $appkey;
        $this->secret = $secret;

        if ($version != "")
            $this->version = $version;

        $this->initialized = true;
    }

    /**
     * Returns a list of all makes known to VehicleDataCo
     *
     * @return      JSON string of list of vehicles
     */
    public function makes_getAll() {
        if (!$this->isInitialized()) return;
        return $this->doCall("makes.getAll");
    }

    /**
     * Returns the known information about the request make (brand)
     *
     * @param  make the make for which the to retrieve data
     * @return      JSON string with known data
     */
    public function makes_getInfo($make) {
        if (!$this->isInitialized()) return;
        $args = array("make" => $make);
        return $this->doCall("makes.getInfo", $args);
    }

    /**
     * Returns a list of supported functions this version and appkey combination.
     *
     * @return JSON string of list of supported functions
     */
    public function service_getSupportedFunctions() {
        if (!$this->isInitialized()) return;
        return $this->doCall("service.getSupportedFunctions");
    }

    /**
     * Returns a list of makes built in the year provided.
     *
     * @param  year the year for which makes are desired
     * @return      JSON string of list of makes
     */
    public function vehicles_getMakes($year) {
        if (!$this->isInitialized()) return;
        $args = array("year" => $year);
        return $this->doCall("vehicles.getMakes", $args);
    }

    /**
     * Returns a list of models built by the make in the year provided.
     *
     * @param  year the year for which models are desired
     * @param  make the make for which models are desired
     * @return      JSON string of list of models
     */
    public function vehicles_getModels($year, $make) {
        if (!$this->isInitialized()) return;
        $args = array("year" => $year,
            "make" => $make);
        return $this->doCall("vehicles.getModels", $args);
    }

    /**
     * Returns a list of trims built of the model by the make in the year provided.
     *
     * @param  year  the year for which trims are desired
     * @param  make  the make for which trims are desired
     * @param  model the make for which trims are desired
     * @return       JSON string of list of trims
     */
    public function vehicles_getTrims($year, $make, $model) {
        if (!$this->isInitialized()) return;
        $args = array("year" => $year,
            "make" => $make,
            "model" => $model);
        return $this->doCall("vehicles.getTrims", $args);
    }

    /**
     * Returns a list of style and trims combinations of the model, make and year provided.
     * optionally, a trim can also be passed in, thus returning only unique styles.
     *
     * @param  year  the year for which styles & trims are desired
     * @param  make  the make for which styles & trims are desired
     * @param  model the make for which styles & trims are desired
     * @param  trim  the trim for which styles are desired
     * @return       JSON string of list of styles
     */
    public function vehicles_getStyleTrims($year, $make, $model, $trim = "") {
        if (!$this->isInitialized()) return;
        $args = array("year" => $year,
            "make" => $make,
            "model" => $model,
            "trim" => $trim);
        return $this->doCall("vehicles.getStyleTrims", $args);
    }

    /**
     * Returns a list of transmission types built of the trim of the model by the
     * make in the year provided.
     *
     * @param  year  the year for which transmission types are desired
     * @param  make  the make for which transmission types are desired
     * @param  model the make for which transmission types are desired
     * @param  trim  the trim for which transmission types are desired
     * @return       JSON string of list of trims
     */
    public function vehicles_getTransmissions($year, $make, $model, $style, $trim) {
        if (!$this->isInitialized()) return;
        $args = array("year" => $year,
            "make" => $make,
            "model" => $model,
            "style" => $style,
            "trim" => $trim);
        return $this->doCall("vehicles.getTransmissions", $args);
    }

    /**
     * Checks whether the object has been successfully initialized.
     *
     * @return true/false of object initialization status
     */
    private function isInitialized() {
        if (!isset($this->initialized) || $this->initialized != true)
            return false;
        return true;
    }

    /**
     * Does a call to the remote host server with the requested function and arguments. To do
     * so it signs the requested URI and appends that hash to the end of the URL for the server
     * to decode and check the validity of the call.
     *
     * @param  function name of the function being called
     * @param  args     associative array of arguments to pass to that function (optional)
     * @return          JSON string of the return value from server
     */
    private function doCall($function, $args = "") {

        // if args are passed, must be an array
        if ($args != "" && !is_array($args))
            return null;

        // if args are passed, encode as JSON
        if (is_array($args))
            $args = json_encode($args);

        $vars = array ("app" => $this->appkey,
            "v" => $this->version,
            "t" => time(),
             "f" => $function,
             "a" => $args);

        $uri = http_build_query($vars);
        $s = utf8_encode($uri);
        $hash = $this->getHash($uri);
        $url = "http://" . $this->host . "/?" . $uri . "&hash=" . $this->encodeURIComponent($hash);

        try {
            $json = file_get_contents($url);
        } catch (Exception $e) {
            return null;
        }

        return $json;
    }

    /**
     * Generates the hash signature of the URL parameters being sent to the server for appending
     * to that URL. The URL parameters should be as follows:<br>
     * <pre>
     *     app=<appkey>&v=<version>&t=<timestamp>&f=<function>&a=<function args>
     * </pre>
     * @param  uri string of request being sent to server
     * @return JSON string of list of supported functions
     */
    private function getHash($uri) {
        $s = utf8_encode($uri);
        return base64_encode(hash_hmac('sha256', $s, $this->secret, true));
    }

    /**
     * Properly encodes special characters for a URL to be passable via HTTP. This is a duplicate
     * of the PHP 5.4 call to allow functioning in systems with an older version of PHP. It also 
     * exactly replicates the functionality of the equivalent JavaScript call.
     *
     * @param  str string to encode
     * @return     string with properly encoded special characters
     */
    private function encodeURIComponent($str) {
        $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
        return strtr(rawurlencode($str), $revert);
    }
}
?>