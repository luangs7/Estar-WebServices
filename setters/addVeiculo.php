<?php
header('Content-type: application/json; charset=utf-8');
include_once('../safe/conn.php');


try {

    $pdo = pdo();
    $data = array();
    $cnt = 0;
    $results = array();

    $Placa = $_POST['Placa'];
    $Modelo = $_POST['modelo'];
    $Tipo = $_POST['tipo'];
    $id = $_POST['id'];



    if ($_POST['Id'] != 0 || $_POST['Id'] != null) {
        $Query = "UPDATE `Veiculo` SET `placa`=?,`modelo`=?,`tipo`=?,`Usuario_id`=? WHERE placa = ?";
        $go = $pdo->prepare($Query);
        $go->bindParam(1, $Placa);
        $go->bindParam(2, $Modelo);
        $go->bindParam(3, $Tipo);
        $go->bindParam(4, $id);
        $go->bindParam(5, $Placa);

        $go->execute();

        if ($go)
            echo json_encode(array("result" => 1));
        else
            echo json_encode(array("result" => 0));

    } else {

        $Query = "INSERT INTO `Veiculo`(`placa`, `modelo`, `tipo`, `Usuario_id`) VALUES (?,?,?,?)";
        $go = $pdo->prepare($Query);
        $go->bindParam(1, $Placa);
        $go->bindParam(2, $Modelo);
        $go->bindParam(3, $Tipo);
        $go->bindParam(4, $id);
        $go->execute();

        if ($go){

               $Query = "SELECT * FROM Veiculo where Usuario_id = ?";

                $go = $pdo->prepare($Query);
                $go->bindParam(1,$id);
                $go->execute();
                $results = $go->fetchAll(PDO::FETCH_ASSOC);

                foreach ($results as $row) {
                    unset($row['senha']);
                    array_push($data, $row);
                    $cnt++;
                }

            echo json_encode(array("result" => 1,"content" => $data));   
        }
        else
            echo json_encode(array("result" => 0));
    }


} catch (Exception $e) {
    echo json_encode(array("result" => 0, "exception" => "query error" . $e));
}

?>
