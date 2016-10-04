<?php
header('Content-type: application/json; charset=utf-8');
session_start();


if(isset($_POST['email']) && isset($_POST['pass']) ){

    include_once('conn.php');

    $email = str_replace("%40", "@", trim($_POST['email']));
   // $cpf = $_POST['cpf'];
    $pass = trim($_POST['pass']);

    $pass = md5('tcc'.$pass.'estar');

    $QueryGetUser = 'SELECT * FROM `Usuario` WHERE email = "'.$email.'"';

    try {
        $pdo = pdo();
        $data = array();
        $go = $pdo->prepare($QueryGetUser);
        $go->execute();
        $do = $go->fetchAll(PDO::FETCH_ASSOC);

        if(count($do) > 0){

            if(verifyPass($do[0]['senha'], $pass)){
                foreach($do as $rows){
                    unset($rows['senha']);
                    array_push($data,$rows);
                }
                echo json_encode(array("result" => 1, "content" => $data));

            }else
                    echo json_encode(array("result" => 0, "exception" => "Senha incorreta!"));

        }else
                echo json_encode(array("result" => 0, "exception" => "Email Incorreto!"));

    }catch(PDOException $e){
        echo json_encode(array("result" => 0, "exception" => "Erro query"));
    }

}else{
        echo json_encode(array("result" => 0, "exception" => "Insira todos os dados!"));
}

function verifyPass($dbpass, $npass){
    return $dbpass == $npass ? TRUE : FALSE;
}


?>