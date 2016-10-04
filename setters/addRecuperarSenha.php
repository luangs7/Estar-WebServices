<?php
header('Content-type: application/json; charset=utf-8');
include_once('../safe/conn.php');


try {

    $pdo = pdo();
    $data = array();

    $email = $_POST['email'];

    $UserId = $_POST['UserId'];
    $num = rand(11111,99999);

    $SenhaNova = md5('tcc'.$num.'estar');


    $headers = "Content-type: text/html; charset=utf-8\r\n";
    $headers .= 'From: <contato@opticad.com>' . "\r\n";
    $headers .= 'Cc: contato@opticad.com' . "\r\n";

    $Query = "SELECT * FROM Users WHERE email =?";
    $do = $pdo->prepare($Query);
    $do->bindParam(1,$email);
    $do->execute();
    $cnt = $do->rowCount();
    if($cnt == 1) {
        $QueryUpd = "UPDATE Users SET UserPass = ? WHERE email = ?";
        $do = $pdo->prepare($QueryUpd);
        $do->bindParam(1,$SenhaNova);
        $do->bindParam(2,$email);
        $do->execute();
        if($do){
            echo json_encode(array("result" => 1));
            $html = "
                        <html>
                        <body>
                          Olá!
                                <br /><br />
                                    O procedimento para recuperação de senha foi efetuado com sucesso!
                                <br /><br />
                                Seu login é o: " . $email . " <br />
                                E sua nova senha é: " . $num . "
                                 <br /><br />
                            <br /><br />
                            Atenciosamente,
                            <br />
                                   <p style='color: #f0415f'> Equipe Opticad.</p>
                        </body>
                        </html>";

            $envio = mail($email , "Recuperação de Senha: EstaR", $html, $headers);
        }else{
                echo json_encode(array("result" => 0,"exception" => "Tente novamente."));
        }
    }else {
            echo json_encode(array("result" => 0,"exception" => "Email não registrado."));
    }

} catch (Exception $e) {
    echo json_encode(array("result" => 0, "exception" => "insert error" . $e));
}


?>