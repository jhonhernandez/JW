<?php

#Funciones relacionados con la base de datos

function conectar() {

    $db = "jhon_webservices";

    $link = @mysql_pconnect("localhost", "root", "");

    if (!$link) {

        return (FALSE);
    } else {

        return($link);
    }
}
+
?>