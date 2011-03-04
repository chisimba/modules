<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

if (!file_exists('progress')) {
    echo "Please wait...";
}
else {
    if (($ret = file_get_contents('progress')) === FALSE)
        echo "Failure!";
    else
        echo $ret;
}

?>