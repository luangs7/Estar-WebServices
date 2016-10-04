<?php
header('Content-type: application/json; charset=utf-8');
session_start();


if(isset($_POST['GoogleID'])){

    include_once('conn.php');

    $email = str_replace("%40", "@", trim($_POST['email']));
 
    $googleId = $_POST['GoogleID'];
    $nome = $_POST['Nome'];
    
    $QueryGetUser = 'SELECT * FROM `Usuario` WHERE googleId = "'.$googleId.'"';
    
    try {
        $pdo = pdo();
        $data = array();
        $go = $pdo->prepare($QueryGetUser);
        $go->execute();
        $do = $go->fetchAll(PDO::FETCH_ASSOC);

        if(count($do) > 0){
            foreach($do as $rows){
                    unset($rows['senha']);
                    array_push($data,$rows);
                }
                echo json_encode(array("result" => 1, "content" => $data));
            }else{
                $Query = "INSERT INTO `Usuario`(`nome`, `email`, `googleId`) VALUES (?,?,?)";
                $go = $pdo->prepare($Query);
                $go->bindParam(1, $nome);
                $go->bindParam(2, $email);
                $go->bindParam(3, $googleId);
                $go->execute();
                    
                if($go){    
                    $QueryGetUser = 'SELECT * FROM `Usuario` WHERE googleId = "'.$googleId.'"';
                    $go = $pdo->prepare($QueryGetUser);
                    $go->execute();
                    $do = $go->fetchAll(PDO::FETCH_ASSOC);
                            foreach($do as $rows){
                            array_push($data,$rows);
                            }
                   echo json_encode(array("result" => 1, "content" => $data));        
                 }
               else
                         echo json_encode(array("result" => 0, "exception" => "Erro ao cadastrar!"));
            }
    }catch(PDOException $e){
        echo json_encode(array("result" => 0, "exception" => "Erro query"));
    }

}else{
        echo json_encode(array("result" => 0, "exception" => "Erro ao sincronizar com o Google"));
}

?>