<?php
header('Content-type: application/json; charset=utf-8');
include_once('../safe/conn.php');

$jsonString = utf8_encode(file_get_contents('php://input'));
$estar = json_decode($_POST['estar'], true);
$i =0;


 foreach ($estar as $row) {
        $idEstar = $estar[0]['idEstar'];
    }

try {

    $pdo = pdo();
    $data = array();
    $cnt = 0;
    $results = array();

    $tempo = 1;

    $id = $_GET['id'];


        if(verificaSaldo($id)){

            $Query = "UPDATE Estar SET horas = horas + 1 WHERE idEstar = ?";
            $go = $pdo->prepare($Query);
            $go->bindParam(1, $idEstar);
            $go->execute();

            if ($go){
                atualizaSaldo($id);
                echo json_encode(array("result" => 1));
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

    $Query = "UPDATE `Usuario` SET `saldo`= saldo - 1 WHERE `id` = ?";
            $go = $pdo->prepare($Query);
            $go->bindParam(1, $id);
            $go->execute();
           }
?>
