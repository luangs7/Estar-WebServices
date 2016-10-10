<?php
header('Content-type: application/json; charset=utf-8');
include_once('../safe/conn.php');

    try {
        $pdo = pdo();


        $UserId = $_GET['UserId'];
        $deviceId = $_POST['DeviceId'];
        $token = $_POST['Token'];

            $Query = "SELECT * FROM Push WHERE Usuario_id=".$UserId;
                $go = $pdo->prepare($Query);
                $go->execute();
                $count = $go->rowCount();
                    if($count == 0){
                        $Query = "INSERT INTO `Push`(`DeviceId`, `Token`, `Usuario_id`) VALUES(?,?,?)";
                        $go = $pdo->prepare($Query);
                        $go->bindParam(1,$deviceId);
                        $go->bindParam(2,$token);
                        $go->bindParam(3,$UserId);

                        $go->execute();
                    }else{


                        $Query ="UPDATE `Push` SET `DeviceId`=?,`Token`=? WHERE `Usuario_id`= ?";
                        $go = $pdo->prepare($Query);
                        $go->bindParam(1,$deviceId);
                        $go->bindParam(2,$token);
                        $go->bindParam(3,$UserId);
                        $go->execute();
                    }


        if ($go)
            echo json_encode(array("result" => 1));
        else
            echo json_encode(array("result" => 0));

    } catch (Exception $e) {
        echo json_encode(array("result" => 0, "exception" => "insert error".$e));
    }

?>