<?php

require_once 'lib/nusoap.php';



$miURL = 'http://127.0.0.1/JhonWS/JW'; //Directorio en donde se encuentra ubicado el webservice
$server = new soap_server();
$server->configureWSDL("producto", $miURL);
$server->wsdl->schemaTargetNamespace = $miURL;

#Registro del metodo enviar_respuesta#
$server->register("enviar_respuesta", array("parametro" => "xsd:string"), array("return" => "xsd:string"), $miURL);

/**
 * enviar_respuesta();
 * 
 * 
 * Ejemplo 1: Mensaje de prueba
 * @param varchar $parametro Recibe el mensaje que le envien
 * @return varchar Retorna la respuesta del servidor con el mensaje enviado por el usuario
 */
function enviar_respuesta($parametro) {
    if ($parametro != "") {
        return new soapval('return', 'xsd:string', 'Hola, esto lo envia el servidor:' . $parametro);
    } else {
        return 'Mensaje en blanco';
    }
}

#Registro del Segundo metodo registrar_datos#
$server->register('registrar_datos', // Nombre de la funcion
        array('Cedula' => 'xsd:string', 'Nombre' => 'xsd:string', 'Apellidos' => 'xsd:string'), // Parametros de entrada
        array('return' => 'xsd:string'), // Parametros de salida
        $miURL);

/**
 * registrar_datos();
 * 
 * Ejemplo 2: guardo datos que recibo de cualquier dispositivo en la base de datos
 * @param varchar $parametro Primer parametro a insertar
 * @param varchar $parametro2 Segundo parametro a insertar
 * @param varchar $parametro3 Tercer parametro a insertar
 * @return varchar Retorna mensaje de confirmacion, si en caso de que la insercion fue satisfactoria y no en caso de que ocurra lo contrario
 */
function registrar_datos($parametro, $parametro2, $parametro3) {

//recibo el dato enviado por el celular, ahora pongo un mensaje en la variable_accion

    $indicador_registro = "No";

    include ("funciones.inc"); // llama el archivo funciones.inc donde le hace la conexion con la BD

    $link = conectar(); // Se llama la funcion conectar(); que establece la conexi?n

    mysql_select_db("jhon_webservices", $link); //Fuci?nque seleciona la base de datos

    $cad = "insert into datos values ('0','$parametro','$parametro2','$parametro3')";

    if ($result = mysql_query($cad, $link)) { //ejecut la consulta a la base de datos
        $indicador_registro = "Si";
    } else {

        print mysql_error(); //Imprime un mensaje error en el caso de algun problem
    }

    return new soapval('return', 'xsd:string', $indicador_registro);
}

#Registro del Tercer metodo buscar_datos#
$server->register('buscar_datos', // Nombre de la funcion
        array('cedula' => 'xsd:string'), // Parametros de entrada
        array('return' => 'xsd:string'), // Parametros de salida
        $miURL);

/**
 * buscar_datos();
 * 
 * Ejemplo 3: Busco la informacion en de un usuario especifico por su cedula
 * @param varchar $cedula Numero de identificacion a consultar
 * @return varchar Retorna mensaje de confirmacion, si en caso de que la insercion fue satisfactoria y no en caso de que ocurra lo contrario
 */
function buscar_datos($cedula) {

//recibo el dato enviado por el celular, ahora pongo un mensaje en la variable_accion

    $encontro = "No";

    include ("funciones.inc"); // llama el archivo funciones.inc donde le hace la conexion con la BD

    $link = conectar(); // Se llama la funcion conectar(); que establece la conexi?n

    mysql_select_db("jhon_webservices", $link); //Fuci?nque seleciona la base de datos

    $recibe = "select * from datos where cedula='$cedula';"; //string que almacena l aconsulta a ejecutar

    $result = mysql_query($recibe, $link); //ejecut la consulta a la base de datos

    while ($f = mysql_fetch_row($result)) { // Convertimos el resultado en un vector
        $encontro = "Si";
    }
    return new soapval('return', 'xsd:string', $encontro);
}

#Registro del metodo mostrar_datos#
$server->register('mostrar_datos', // Nombre de la funcion
        array('cedula' => 'xsd:string'), // Parametros de entrada
        array('cedula' => 'tns:Cliente'), // Parametros de salida
        $miURL, '', '', '', 'Muestra la informacion del usuario por cedula');

/**
 * mostrar_datos();
 * 
 * Ejemplo 4: Busco la informacion en de un usuario especifico por su cedula
 * @param varchar $cedula Numero de identificacion a consultar
 * @return varchar Retorna la informacion del usuario consultado, en caso contrario retorna el mensaje correspondiente
 */
function mostrar_datos($cedula) {

//recibo el dato enviado por el celular, ahora pongo un mensaje en la variable_accion

    $encontro = "El Usuario No Existe ";

    include ("funciones.inc"); // llama el archivo funciones.inc donde le hace la conexion con la BD

    $link = conectar(); // Se llama la funcion conectar(); que establece la conexi?n

    mysql_select_db("jhon_webservices", $link); //Fuci?nque seleciona la base de datos

    $recibe = "select * from datos where cedula='$cedula';"; //string que almacena l aconsulta a ejecutar

    $result = mysql_query($recibe, $link); //ejecut la consulta a la base de datos

    while ($f = mysql_fetch_row($result)) { // Convertimos el resultado en un vector
        $encontro = $f[1] . ' ' . $f[2] . ' ' . $f[3];
    }

    //return new soapval('cedula', 'xsd:string', $encontro);
    return new soapval('cedula', 'xsd:string', $encontro);
}

#Registro del metodo mostrar_datos#
$server->wsdl->addComplexType(
        'array_php', 'complexType', 'struct', 'all', '', array('Cedula' => array('name' => 'Cedula', 'type' => 'xsd:string'),
    'Nombre' => array('name' => 'Nombre', 'type' => 'xsd:string'),
    'Apellido' => array('name' => 'Apellido', 'type' => 'xsd:string')));


$server->wsdl->addComplexType('return_array_php', 'complexType', 'array', 'all', 'SOAP-ENC:Array', array(), array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:array_php[]')), 'tns:array_php');

$server->register("mostrar_mas_de_undato", array("parametro" => "xsd:string"), array("returnArray" => "tns:return_array_php"), $miURL, $miURL . '#get_data', 'rpc', 'encoded', 'Returns array data in php web service');

/**
 * mostrar_mas_de_undato();
 * 
 * Ejemplo 5: Busco la informacion en de un usuario especifico por su cedula
 * @param varchar $cedula Numero de identificacion a consultar
 * @return varchar Retorna la informacion del usuario consultado, en caso contrario retorna el mensaje correspondiente
 */
function mostrar_mas_de_undato($cedula) {


//recibo el dato enviado por el celular, ahora pongo un mensaje en la variable_accion

    $encontro = "El Usuario No Existe ";

    include ("funciones.inc"); // llama el archivo funciones.inc donde le hace la conexion con la BD

    $link = conectar(); // Se llama la funcion conectar(); que establece la conexi?n

    mysql_select_db("jhon_webservices", $link); //Fuci?nque seleciona la base de datos

    $recibe = "select * from datos where cedula='$cedula';"; //string que almacena l aconsulta a ejecutar

    $result = mysql_query($recibe, $link); //ejecuta la consulta a la base de datos

    while ($f = mysql_fetch_row($result)) { // Convertimos el resultado en un vector
        $cedula = $f[1];
        $nombre = $f[2];
        $apellido = $f[3];
        $encontro = $cedula;
        $arreglo[] = array('Cedula' => $cedula, 'Nombre' => $nombre, 'Apellido' => $apellido);
    }

    //return new soapval("cedula", "xsd:string", $encontro);
    return $arreglo;
}

if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');
}
$server->service($HTTP_RAW_POST_DATA);
?>
