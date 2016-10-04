<?php
session_start();

$pass = "";
$email = $_POST['email'];

if (isset($_POST['pass'])) {

    include_once('conn.php');


    $pass = trim($_POST['pass']);
    $pass = md5("opticad" . $pass . "devmaker");

    $pdo = pdo();

    $Query = "SELECT * FROM Users WHERE email = ?";
    $go = $pdo->prepare($Query);
    $go->bindParam(1, $email);
    $go->execute();
    $do = $go->fetchAll(PDO::FETCH_ASSOC);
    $count = $go->rowCount();

    if ($count > 0) {
        if (verifyPass($do[0]['UserPass'], $pass)) {
            SaveSession();
            echo json_encode(array("result" => 1));

        } else
            echo json_encode(array("result" => 0, "exception" => "senha incorreta"));
    } else
        echo json_encode(array("result" => 0, "exception" => "Email incorreto"));


} else {
    echo json_encode(array("result" => 0, "exception" => "dados incompletos"));
}

function verifyPass($dbpass, $npass)
{
    return $dbpass == $npass ? TRUE : FALSE;
}


function SaveSession()
{
    global $email;
    $_SESSION['OpticadToken'] = $email;
}


?>