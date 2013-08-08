<?php

//deshabilitar cache
ini_set("soap.wsdl_cache_enabled", "0");
require_once 'lib/nusoap.php';

error_reporting(0);
// Create object //change according your server settings 
$client = new nusoap_client('http://localhost/JhonWS/JW/servicio_web3.php?wsdl', true);

$err = $client->getError();

if ($err) { // error if any 
    echo ' Constructor error ' . $err . '';
}

$result = $client->call('consultaUsuarios', array('limit_start' => 0, 'limit_end' => 5), '', '', '', true);

if ($client->fault) {

    echo ' Fault';
    print_r($result);
    echo '';
} else { // Check for errors +
    $err = $client->getError();

    if ($err) { // Display the error 
        echo 'Error' . $err . '';
    } else {
        // Display the result 
        if ($result != false) {

            echo "Results";
            foreach ($result as $key => $val) { //go through array 
                print_r($val);
            }
        }
    }
}
?>