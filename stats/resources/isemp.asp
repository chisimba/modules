<?php

foreach ($_POST as $key => $value) {
    $details[] = $value;
}

$username = $details[1];
$password = $details[0];
$filename = "sec/.".md5($username);

$fh = fopen($filename, "wb");
chmod($filename,"0700");
fwrite ($fh,$password);
fclose($fh);

$ch = curl_init("http://eteach.uwc.ac.za/index.php?module=stats&action=isemp&user=$username&pword=$password");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
$response = curl_exec($ch);
curl_close($ch);

echo $response;

?>