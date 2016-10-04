<?php
header('Content-type: application/json; charset=utf-8');
include_once('../safe/conn.php');

if(isset($_GET['id']))
    try{

        $pdo = pdo();

        $id = $_GET['id'];

        $Query = "DELETE FROM `Usuario` WHERE id=".$id;

        $go = $pdo->prepare($Query);
        $go->execute();


        if($go)
            echo json_encode(array("result" => 1));
        else
            echo json_encode(array("result" => 0));

    } catch (Exception $e) {
        echo json_encode(array("result" => 0, "exception" => "delete error"));
    }
else
    echo json_encode(array("result" => 0, "exception" => "missin data"));


?>
