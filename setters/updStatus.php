<?php
header('Content-type: application/json; charset=utf-8');
include_once('../safe/conn.php');

    try {
        $pdo = pdo();


        $id = $_GET['id'];
        $deviceId = $_POST['DeviceId'];
        $token = $_POST['Token'];

            $Query = "SELECT * FROM Estar WHERE idEstar = ".$id;
                $go = $pdo->prepare($Query);
                $go->execute();
                $count = $go->rowCount();
                    if($count == 0){
                    
                            echo json_encode(array("result" => 0,"exception" => "EstaR já não está mais disponivel"));
                        
                    }else{

                        $Query ="UPDATE `Estar` SET `situacao`=1 WHERE `idEstar`= ?";
                        $go = $pdo->prepare($Query);
                        $go->bindParam(1,$id);
                        $go->execute();
                    }


       

    } catch (Exception $e) {
        echo json_encode(array("result" => 0, "exception" => "insert error".$e));
    }

?>