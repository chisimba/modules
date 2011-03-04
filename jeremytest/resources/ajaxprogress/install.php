<?php

/**
 * AJAX progress
 *
 * @version $Id$
 * @copyright (C) 2011 Jeremy O'Connor
 */

for ($i=0; $i<5; ++$i) {
    if (file_put_contents('progress', "{$i}%") === FALSE) {
        echo "Failure!";
        exit(0);
    }
    sleep(2);
}
echo "OK";

?>