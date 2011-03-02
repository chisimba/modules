<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

$objTest = $this->newObject('test');
$TIME_START = microtime(TRUE);
for ($i=0; $i<5000; ++$i) {
    $objTest->doTest();
}
$TIME_END = microtime(TRUE);
echo ($TIME_END-$TIME_START).' sec<br/>';

?>