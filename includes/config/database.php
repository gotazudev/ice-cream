<?php

function conectarDB() : mysqli{
    $db = mysqli_connect('localhost','root','','heladeria');

    if(!$db){
        echo "Error no se pudo conectar ";
    }else{
        // echo "Si se pudo conectar ";

    }

    return $db;
}