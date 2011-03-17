<?php

/**
 * Progress
 *
 * @version $Id$
 * @copyright 2011 AVOIR
 */

$deleteprogressfile = isset($_GET['deleteprogressfile']) && $_GET['deleteprogressfile'] == 'true';

$filename = '../progress';
if (!file_exists($filename)) {
    echo "Please wait...";
}
else {
    if (($ret = file_get_contents($filename)) === FALSE)
        echo "Failure!";
    else
        echo $ret;
}

if ($deleteprogressfile && file_exists($filename)) {
        unlink($filename);
}

?>