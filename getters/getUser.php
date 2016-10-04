<?php
header('Content-type: application/json; charset=utf-8');
include_once('../safe/conn.php');


try{

    $pdo = pdo();
    $data = array();
    $cnt = 0;
    
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $Query = "SELECT * FROM Usuario where id = ?"; 
        $go = $pdo->prepare($Query);
        $go->bindParam(1,$id);
        }else{
        $Query = "SELECT * FROM Usuario";
        $go = $pdo->prepare($Query);
        }

   
    $go->execute();
    $results = $go->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        unset($row['senha']);
        array_push($data, $row);
        $cnt++;
    }

    if($go)
        echo json_encode(array("result" => 1,"content" => $data));
    else
        echo json_encode(array("result" => 0));


} catch (Exception $e) {
    echo json_encode(array("result" => 0, "exception" => "query error".$e));
}

?>

