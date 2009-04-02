<?php

foreach ($_POST as $key => $value) {
    $post[] = urlencode($value);
}

$test = $post[0];
$user = $post[1];
$mark = $post[2];
$time = $post[3];

$filename = "sec/.".md5($user);
chmod($filename,"0400");
$password = file_get_contents($filename);
chmod($filename,"0700");

$filename = "sec/.".sha1($user);
chmod($filename,"0400");
$nonce = file_get_contents($filename);
chmod($filename,"0700");


$ch = curl_init("http://localhost/index.php?module=stats&action=testwrite&user=$user&test=$test&mark=$mark&time=$time&passwd=$password&nonce=$nonce");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
$response = curl_exec($ch);

echo $response;
?>