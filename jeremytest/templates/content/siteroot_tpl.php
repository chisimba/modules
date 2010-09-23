<pre>
<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

$https = isset($_SERVER['HTTPS'])?$_SERVER['HTTPS']=='off'?FALSE:TRUE:FALSE;
$http_host = $_SERVER ['HTTP_HOST'];
$php_self = $_SERVER['PHP_SELF'];
$path = str_replace('index.php', '', $php_self);
$url = ($https?'https://':'http://').$http_host.$path;
echo 'HTTPS=>'.($https?'ON':'OFF')."\n";
echo 'PHP_SELF=>'.$_SERVER['PHP_SELF']."\n";
echo $url."\n";

?>
</pre>