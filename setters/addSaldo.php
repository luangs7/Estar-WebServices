<?php
header('Content-type: application/json; charset=utf-8');
include_once('../safe/conn.php');


try {

    $pdo = pdo();
    $data = array();
    $cnt = 0;
    $results = array();

    $idCliente = $_GET['id'];
    $saldo = $_POST['horas'];


        $Query = "UPDATE `Usuario` SET `saldo`= saldo + ? WHERE `id` = ?";
        $go = $pdo->prepare($Query);
        $go->bindParam(1, $saldo);
        $go->bindParam(2, $idCliente);
        $go->execute();


            $QueryBusca = "SELECT * FROM Usuario where id = ?"; 
            $go = $pdo->prepare($QueryBusca);
            $go->bindParam(1,$idCliente);
            $go->execute();
            $results = $go->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                unset($row['senha']);
                array_push($data, $row);
                $cnt++;
            }
                if ($go)
                    echo json_encode(array("result" => 1,"content" => $data));
                else
                    echo json_encode(array("result" => 0));
            
} catch (Exception $e) {
    echo json_encode(array("result" => 0, "exception" => "query error" . $e));
}

?>
