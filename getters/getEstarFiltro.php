<?php
date_default_timezone_set('America/Sao_Paulo');
header('Content-type: application/json; charset=utf-8');
include_once('../safe/conn.php');

$date = date('Y-m-d H:i:s');

try{

    $pdo = pdo();
    $data = array();
    $cnt = 0;
    $filter = $_GET['filter'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $sql = "";
    
    // comentando para seguir a regra de negocio da apresentação (2,3 minutos), e não a regra de negócio do app em si (1,2 horas)
    // $Query = "SELECT    *,
    //        (TIMESTAMPDIFF( MINUTE , Estar.inicio,  ?)) as diff,
    //        (SELECT (CASE WHEN (Estar.horas < (diff/60)) THEN 1 ELSE 0 END)) as vencido
    //     FROM
    //         Estar
    //         ORDER BY  `Estar`.`idEstar` DESC "; 
    //         
    //         

        switch ($filter) {
            case 0:
                $sql = "ORDER BY distancia";
                break;
            case 1: 
                $sql = "AND ( CASE  WHEN (Estar.horas < (TIMESTAMPDIFF(MINUTE, Estar.inicio, ".$date.")))THEN 1  ELSE 0 END) = 0 ORDER BY distancia";
                break;
            case 2:
                $sql = "AND ( CASE  WHEN (Estar.horas < (TIMESTAMPDIFF(MINUTE, Estar.inicio, ".$date.")))THEN 1  ELSE 0 END) = 1 ORDER BY distancia";
                break;
            case 3:
                $sql = "ORDER BY distancia, Estar.inicio";
                break;
            default:
                $sql = "ORDER BY distancia";
                break;
        }

     $Query = "SELECT *,
            (TIMESTAMPDIFF( MINUTE , Estar.inicio,  ?)) as diff,
                       (SELECT (CASE WHEN (Estar.horas < diff) THEN 1 ELSE 0 END)) as vencido
            (6372*acos(CAST(cos(radians(?))*cos(radians(Estar.latitude))*cos(radians(Estar.longitude)-radians(?))+sin(radians(?))*sin(radians(Estar.latitude)) AS decimal(16,15)))) AS distancia
                FROM `Estar`
                (6372*acos(CAST(cos(radians(?))*cos(radians(Estar.latitude))*cos(radians(Estar.longitude)-radians(?))+sin(radians(?))*sin(radians(Estar.latitude)) AS decimal(16,15)))) < 1
                 " .$sql; 

    $go = $pdo->prepare($Query); 
    $go->bindParam(1,$date);
    $go->bindParam(2,$latitude);
    $go->bindParam(3,$longitude);
    $go->bindParam(4,$latitude);
    $go->bindParam(5,$sql);
    $go->bindParam(6,$latitude);
    $go->bindParam(7,$longitude);
    $go->bindParam(8,$latitude);
    $go->execute();
    $results = $go->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {

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

