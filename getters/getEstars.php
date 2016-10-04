<?php
date_default_timezone_set('America/Sao_Paulo');
header('Content-type: application/json; charset=utf-8');
include_once('../safe/conn.php');

$date = date('Y-m-d H:i:s');

try{

    $pdo = pdo();
    $data = array();
    $cnt = 0;
    
    // comentando para seguir a regra de negocio da apresentação (2,3 minutos), e não a regra de negócio do app em si (1,2 horas)
    // $Query = "SELECT    *,
    //        (TIMESTAMPDIFF( MINUTE , Estar.inicio,  ?)) as diff,
    //        (SELECT (CASE WHEN (Estar.horas < (diff/60)) THEN 1 ELSE 0 END)) as vencido
    //     FROM
    //         Estar
    //         ORDER BY  `Estar`.`idEstar` DESC "; 


     $Query = "SELECT    *,
           (TIMESTAMPDIFF( MINUTE , Estar.inicio,  ?)) as diff,
           (SELECT (CASE WHEN (Estar.horas < diff) THEN 1 ELSE 0 END)) as vencido
        FROM
            Estar
            ORDER BY  `Estar`.`idEstar` DESC "; 

    $go = $pdo->prepare($Query); 
    $go->bindParam(1,$date);
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

