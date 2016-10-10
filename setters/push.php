<?php
include_once('../safe/conn.php');


if(!empty($_POST['url'])){
    $urlPost = $_POST['url'];
    $urlPost = 'http://'.$urlPost;
}else{
    $urlPost = "";
}

if($_POST['LojaId'] != 0){
    $LojaId = $_POST['LojaId'];
}else{
    $LojaId = "";
}


function sendPushNotification($registration_ids, $message) {
    global $LojaId;
    global $urlPost;

    $url = 'https://android.googleapis.com/gcm/send';


    $fields = array(
        'notification' => array(
            "complemento" => '1',
            'body' => $message,
            'badge' => '1',
            'sound' => 'default',
        ),
        'registration_ids' => $registration_ids,
        'content_available' => true,
        'priority' =>'high'
    );

    echo json_encode($fields);

    define('GOOGLE_API_KEY', 'AIzaSyBw5MSX8IvkPyafpwUpVBi-qrF7n5DOqvo');

    $headers = array(
        'Authorization:key=' . GOOGLE_API_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);
    if($result === false)
        die('Curl failed ' . curl_error());

    curl_close($ch);

    return $result;



}

$pushStatus = '';

$pdo = pdo();

$ids = array();
$cnt = 0;



if(isset($_GET['UserId'])){
    $Query1 = "SELECT * FROM Push WHERE Usuario_id = ? AND  Token IS NOT NULL";
    $go = $pdo->prepare($Query1);
    $go->bindParam(1,$_GET['UserId']);
    $go->execute();
    $results = $go->fetchAll(PDO::FETCH_ASSOC);
    $count = $go->rowCount();


        foreach ($results as $rows) {
            $ids[$cnt] = $rows['Token'];
            $cnt++;
        }

        $pushMessage = "Você foi notificado por um fiscal!";
        $pushStatus = sendPushNotification($ids, $pushMessage);
    
}


//$gcmRegIds = array();
//
//if(!isset($_POST['message'])) {
//    $pushMessage = 'AmoVitrine';
//}
//
//$pushMessage = $_POST['message'];
//
//
//
//
//
//echo $pushMessage;
//
//if(isset($gcmRegIds)) {
//
//    // $pushStatus = sendPushNotification($ids, $pushMessage);
//    echo $pushStatus;
//
//}

?>