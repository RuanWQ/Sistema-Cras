<?php
    $hostname = "localhost";
    $bancodedados = "usuarios";
    $usuario = "root";
    $senha = "";

    $mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados,);
    if ($mysqli->connect_errno) {
        echo "<script>alert('Falha ao conectar: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "');</script>";
    }
    else
        echo "<script>alert('Conexão bem-sucedida!');</script>";

?>