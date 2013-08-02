<?php

require_once 'lib/nusoap.php';

function getProd($categoria) {
    if ($categoria == "libros") {
        return join(",", array(utf8_decode("El Señor de los anillos"), "Pandemonium", "Resident Evil"));
    } else {
        return 'No hay productos de esta categoria';
    }
}

$server = new soap_server();
$server->configureWSDL("producto", "urn:producto");
$server->register("getProd", array("categoria" => "xsd:string"), array("return" => "xsd:string"), "urn:producto", "urn_producto#getProd", "rpc", "encoded", "Lista de productos por categoria"
);

if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');
}
$server->service($HTTP_RAW_POST_DATA);
?>