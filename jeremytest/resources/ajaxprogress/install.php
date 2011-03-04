<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
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