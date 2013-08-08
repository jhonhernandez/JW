<?php

require_once ('lib/nusoap.php');

$url = "http://192.168.0.152/JhonWS/JW/servicio_web2.php?wsdl";

$client = new nusoap_client($url, 'wsdl');
$err = $client->getError();

if ($err) {
    echo '<strong>' . $err . '</strong>';
}

$param = array('cedula' => '79878879');
$result = $client->call('mostrar_datos', $param);

if ($client->fault) {
    echo '<h2>Fault</h2><pre>';
    print_r($result);
    echo '</pre>';
} else {
    // Check for errors
    $err = $client->getError();
    if ($err) {
        // Display the error
        echo '<h2>Error</h2><pre>' . $err . '</pre>';
    } else {
        // Display the result
        echo '<h2>Result</h2><pre>';
        print($result);
        echo '</pre>';
    }
}

//echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
?>