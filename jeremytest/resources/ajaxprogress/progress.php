<?php

/**
 * AJAX progress
 *
 * @version $Id$
 * @copyright (C) 2011 Jeremy O'Connor
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