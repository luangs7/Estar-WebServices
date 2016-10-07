<?php
date_default_timezone_set('America/Sao_Paulo');
header('Content-type: application/json; charset=utf-8');
include_once('../safe/conn.php');

$date = date('Y-m-d H:i:s');

try{

    $pdo = pdo();
    $data = array();
    $cnt = 0;
    $idUser = $_GET['idUser'];

     $Query = "SELECT *,
        (TIMESTAMPDIFF( HOUR , Estar.inicio,  ?)) as diff
      FROM `Estar` WHERE Usuario_id = ? ORDER BY idEstar DESC"; 

    $go = $pdo->prepare($Query); 
    $go->bindParam(1,$date);
    $go->bindParam(2,$idUser);
    $go->execute();
    $results = $go->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        if($row['diff'] >= 24){
            $row['days'] = $row['diff'] / 24;
            $row['hours'] = $row['diff'] % 24;
            $row['days'] = floor($row['days']);
        }else{
            $row['days'] = 0;
            $row['hours'] = $row['diff'];
        }
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

