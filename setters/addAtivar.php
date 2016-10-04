<?php
date_default_timezone_set('America/Sao_Paulo');
header('Content-type: application/json; charset=utf-8');
include_once('../safe/conn.php');

$date = date('Y-m-d H:i:s ');



try {

    $pdo = pdo();
    $data = array();
    $cnt = 0;
    $results = array();

    $placa = $_POST['placa'];
    $tempo = $_POST['horas'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $address = $_POST['address'];
    $id = $_GET['id'];


        if(verificaSaldo($id)){

            $Query = "INSERT INTO `Estar`(`placa`,  `horas`, `Usuario_id`,latitude,longitude,address,`inicio`) VALUES (?,?,?,?,?,?,?)";
            $go = $pdo->prepare($Query);
            $go->bindParam(1, $placa);
            $go->bindParam(2, $tempo);
            $go->bindParam(3, $id);
            $go->bindParam(4, $latitude);
            $go->bindParam(5, $longitude);
            $go->bindParam(6, $address);
            $go->bindParam(7, $date);
            $go->execute();
            $id = $pdo->lastInsertId();
            if ($go){
                atualizaSaldo($id);
                echo json_encode(array("result" => 1,"id" => $id));
            }
            else
                echo json_encode(array("result" => 0));
        }else{
                echo json_encode(array("result" => 0,"exception" => "Sem saldo suficiente!"));
            }
} catch (Exception $e) {
    echo json_encode(array("result" => 0, "exception" => "query error" . $e));
}



function verificaSaldo($id){
    global $pdo;
    global $tempo;

    $Query = "SELECT saldo FROM Usuario WHERE id=?";
        $go = $pdo->prepare($Query);
        $go->bindParam(1, $id);
        $go->execute(); 
        $results = $go->fetchAll(PDO::FETCH_ASSOC);

           if ($tempo > $results[0]['saldo']){
             return false;
           }else{
            return true;
           }

        }

        function atualizaSaldo($id){
    global $pdo;
    global $tempo;

    $Query = "UPDATE `Usuario` SET `saldo`= saldo - ? WHERE `id` = ?";
            $go = $pdo->prepare($Query);
            $go->bindParam(1, $tempo);
            $go->bindParam(2, $id);
            $go->execute();
           }
?>
