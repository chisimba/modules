<?php

/**
 * AJAX progress
 *
 * @version $Id$
 * @copyright (C) 2011 Jeremy O'Connor
 */

for ($i=0; $i<5; ++$i) {
    $p = (int)(100*$i/5);
    if (file_put_contents('progress', "{$p}%") === FALSE) {
        echo "Failure!";
        exit(0);
    }
    sleep(2);
}
echo "OK";

?>