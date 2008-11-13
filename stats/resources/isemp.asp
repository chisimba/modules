<?php

foreach ($_POST as $key => $value) {
    $details[] = $value;
}

$username = $details[1];
$password = md5($details[0]);

$ch = curl_init("http://localhost/chisimba/index.php?module=stats&action=isemp&user=$username&pword=$password");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
$response = curl_exec($ch);
echo $response;

?>