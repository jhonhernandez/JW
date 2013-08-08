<?php

// Deshabilitar cache
ini_set("soap.wsdl_cache_enabled", "0");

//llamada a librería nusoap
require_once('lib/nusoap.php');
// Create server object 
$server = new soap_server();
$ns = 'http://127.0.0.1/JhonWS/JW'; //Directorio en donde se encuentra ubicado el webservice
// configure WSDL 
$server->configureWSDL('PHP Web Services return array', 'urn:returnArray');
// Complex Type Struct for return array - See more at: http://my-source-codes.blogspot.com/2011/10/php-web-service-return-array.html#sthash.9tM3IYMn.dpuf

$server->wsdl->addComplexType('array_php', 'complexType', 'struct', 'all', '', array('id' => array('id' => 'id', 'type' => 'xsd:string'), 'firstname' => array('name' => 'firstname', 'type' => 'xsd:string'), 'lastname' => array('name' => 'lastname', 'type' => 'xsd:string'), 'email' => array('name' => 'email', 'type' => 'xsd:string')));
$server->wsdl->addComplexType('return_array_php', 'complexType', 'array', 'all', 'SOAP-ENC:Array', array(), array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:array_php[]')), 'tns:array_php');
$server->register('consultaUsuarios', array('limit_start' => 'xsd:int', 'limit_end' => 'xsd:int'), array('return' => 'tns:return_array_php'), $ns, $ns . '#get_data', 'rpc', 'encoded', 'Returns array data in php web service');

function consultaUsuarios($limit_start, $limit_end) { /* you can use mysql and your logic here this is sample array */
    $array_rtr = array();
    $array_rtr[0]['id'] = 0;
    $array_rtr[0]['firstname'] = 'Nikunj';
    $array_rtr[0]['lastname'] = 'Gandhi';
    $array_rtr[0]['email'] = 'Nik_gandhi007@yahoo.com';
    $array_rtr[1]['id'] = 1;
    $array_rtr[1]['firstname'] = 'ABC';
    $array_rtr[1]['lastname'] = 'EGF';
    $array_rtr[1]['email'] = 'ABC@yahoo.com';
    $array_rtr[2]['id'] = 2;
    $array_rtr[2]['firstname'] = 'XYZ';
    $array_rtr[2]['lastname'] = 'ZYX';
    $array_rtr[2]['email'] = 'XYZ@yahoo.com';
    $array_rtr[3]['id'] = 3;
    $array_rtr[3]['firstname'] = 'zcds';
    $array_rtr[3]['lastname'] = 'asdsa';
    $array_rtr[3]['email'] = 'dxds@yahoo.com';
    $array_rtr[4]['id'] = 4;
    $array_rtr[4]['firstname'] = 'zxyctuzy';
    $array_rtr[4]['lastname'] = 'zxkjch';
    $array_rtr[4]['email'] = 'xyx@yahoo.com';
    $array_rtr[5]['id'] = 5;
    $array_rtr[5]['firstname'] = 'sdd';
    $array_rtr[5]['lastname'] = 'dss';
    $array_rtr[5]['email'] = 'dss@yahoo.com';
    $array_rtr[6]['id'] = 6;
    $array_rtr[6]['firstname'] = 'sa';
    $array_rtr[6]['lastname'] = 'aZz';
    $array_rtr[6]['email'] = 'axz@yahoo.com';
    $array_rtr[7]['id'] = 7;
    $array_rtr[7]['firstname'] = 'xxz';
    $array_rtr[7]['lastname'] = 'xcx';
    $array_rtr[7]['email'] = 'zxz@yahoo.com';
    $array_rtr[8]['id'] = 8;
    $array_rtr[8]['firstname'] = 'zxx';
    $array_rtr[8]['lastname'] = 'eee';
    $array_rtr[8]['email'] = 'eee@yahoo.com';
    $array_rtr[9]['id'] = 9;
    $array_rtr[9]['firstname'] = 'xxxs';
    $array_rtr[9]['lastname'] = 'ssa';
    $array_rtr[9]['email'] = 'sssw@yahoo.com';
    $return = array();
    for ($i = $limit_start; $i <= $limit_end; $i++) {
        $return[$i] = $array_rtr[$i];
    } return $return;
}


if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');
}
$server->service($HTTP_RAW_POST_DATA);
?>
