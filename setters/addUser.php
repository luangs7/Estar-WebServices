<?php
header('Content-type: application/json; charset=utf-8');
include_once('../safe/conn.php');

if(isset($_POST['Email'])){
try {

    $pdo = pdo();
    $data = array();
    $cnt = 0;
    $results = array();
    $UserNome = $_POST['Nome'];
    $UserEmail = $_POST['Email'];
    $UserPass = $_POST['Senha'];
    $GoogleID = $_POST['GoogleID'];
    $UserPass = md5("tcc".$UserPass."estar");


    if ($_POST['Id'] != 0 || $_POST['Id'] != null) {
        $Query = "UPDATE `Usuario` SET `nome`=?, email=? WHERE `id` = ?";
        $go = $pdo->prepare($Query);
        $go->bindParam(1, $UserNome);
        $go->bindParam(2, $UserEmail);
        $go->bindParam(3, $_POST['Id']);
        $go->execute();

        if ($go)
            echo json_encode(array("result" => 1));
        else
            echo json_encode(array("result" => 0));
    } else {

        $Query = "INSERT INTO `Usuario`(`nome`, `email`, `senha`, `googleId`) VALUES (?,?,?,?)";
        $go = $pdo->prepare($Query);
        $go->bindParam(1, $UserNome);
        $go->bindParam(2, $UserEmail);
        $go->bindParam(3, $UserPass);
        $go->bindParam(4, $GoogleID);
        $go->execute();

        if ($go)
            echo json_encode(array("result" => 1));
        else
            echo json_encode(array("result" => 0));
    }


} catch (Exception $e) {
    echo json_encode(array("result" => 0, "exception" => "query error" . $e));
}
}else{
        echo json_encode(array("result" => 0, "exception" => "Insira o seu Email!"));
}

?>
