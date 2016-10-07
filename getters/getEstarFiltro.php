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
                $sql = "AND situacao = 0 ORDER BY distancia";
                break;
            case 1: 
                $sql = "AND ( CASE  WHEN (Estar.horas < (TIMESTAMPDIFF(MINUTE, Estar.inicio, '".$date."')))THEN 1  ELSE 0 END) = 0 and situacao = 0 ORDER BY distancia";
                break;
            case 2:
                $sql = "AND ( CASE  WHEN (Estar.horas < (TIMESTAMPDIFF(MINUTE, Estar.inicio, '".$date."')))THEN 1  ELSE 0 END) = 1 and situacao = 0 ORDER BY distancia";
                break;
            case 3:
                $sql = "AND situacao = 0 ORDER BY Estar.inicio";
            default:
                $sql = "AND situacao = 0 ORDER BY distancia";
                break;
        }

$Query = "SELECT * , 
     (TIMESTAMPDIFF( MINUTE , Estar.inicio,  ? )) AS diff, (SELECT (
        CASE WHEN (Estar.horas < diff) THEN 1 ELSE 0 END)) AS vencido, 
        ( 6372 * ACOS( CAST( COS( RADIANS(  ? ) ) * COS( RADIANS( Estar.latitude ) ) * COS( RADIANS( Estar.longitude ) - RADIANS(  ? ) ) + SIN( RADIANS(  ?) ) * SIN( RADIANS( Estar.latitude ) ) AS DECIMAL( 16, 15 ) ) ) ) AS distancia
            FROM  `Estar` 
            WHERE (6372*acos(CAST(cos(radians(?))*cos(radians(Estar.latitude))*cos(radians(Estar.longitude)-radians(?))+sin(radians(?))*sin(radians(Estar.latitude)) AS decimal(16,15)))) < 1
            ". $sql; 

    $go = $pdo->prepare($Query); 
   $go->bindParam(1,$date);
    $go->bindParam(2,$latitude);
    $go->bindParam(3,$longitude);
    $go->bindParam(4,$latitude);
    $go->bindParam(5,$latitude);
    $go->bindParam(6,$longitude);
    $go->bindParam(7,$latitude);
    // $go->bindParam(5,$sql);
    $go->execute();
    $results = $go->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $row) {
        $row['inicio'] = formata($row['inicio']);    
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


function formata($ano){
            $ano = date("d/m/Y H:i:s",strtotime(str_replace('-','/',$ano)));
            return $ano;
        }
?>

