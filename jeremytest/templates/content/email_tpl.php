<?php

//echo "Email";
//die;

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

/*
// The message
$message = "Line 1\nLine 2\nLine 3";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70);

// Send
mail('joconnor@uwc.ac.za', 'My Subject', $message);

echo "OK";
*/


$body = 'TEST';
$to = 'joconnor@uwc.ac.za';
$subject = 'TEST';
$from  = 'joconnor@uwc.ac.za';

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: '.$from . "\r\n" .
	'Reply-To: '.$from . "\r\n" .
	'X-Mailer: PHP/' . phpversion();

$res = mail($to, $subject, $body, $headers);

echo $res?'OK':'FAIL';

?>