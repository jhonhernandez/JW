<?php

require_once 'lib/nusoap.php';



$miURL = 'http://127.0.0.1/JhonWS/JW';
$server = new soap_server();
$server->configureWSDL("producto", $miURL);
$server->wsdl->schemaTargetNamespace = $miURL;

$server->register("enviar_respuesta", array("parametro" => "xsd:string"), array("return" => "xsd:string"), $miURL
);

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

/*
  Ejemplo 2: guardo datos que recibo de cualquier dispositivo en la base de datos
 */

$server->register('registrar_datos', // Nombre de la funcion
        array('Cedula' => 'xsd:string', 'Nombre' => 'xsd:string', 'Apellidos' => 'xsd:string'), // Parametros de entrada
        array('return' => 'xsd:string'), // Parametros de salida
        $miURL);

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

/*

  Ejemplo 3: Busco los datos a traves de la cedula que recibo como parametro

 */
$server->register('buscar_datos', // Nombre de la funcion
        array('cedula' => 'xsd:string'), // Parametros de entrada
        array('return' => 'xsd:string'), // Parametros de salida
        $miURL);

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

/*

  Ejemplo 4: Busco los datos a traves de la cedula que recibo como parametro

 */
$server->register('mostrar_datos', // Nombre de la funcion
        array('cedula' => 'xsd:string'), // Parametros de entrada
        array('cedula' => 'tns:Cliente'), // Parametros de salida
        $miURL, '', '', '', 'Muestra la informacion del usuario por cedula');

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

if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');
}
$server->service($HTTP_RAW_POST_DATA);
?>
