<?php

foreach ($_POST as $key => $value) {
    $cleanPost[trim($key)] = urlencode($value);
}

$json = json_encode($cleanPost);

$ch = curl_init("http://eteach.uwc.ac.za/index.php?module=stats&action=postquestionnaire&json=$json");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
$response = curl_exec($ch);
curl_close($ch);

?>
