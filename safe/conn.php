<?php

function pdo(){
    $pdo = new PDO('mysql:host=mysql.hostinger.com.br;dbname=u952299965_estar','u952299965_tcc','estartcc',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => true,PDO::MYSQL_ATTR_LOCAL_INFILE => true)) or die ("Erro ao Conectar ao db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

?>
