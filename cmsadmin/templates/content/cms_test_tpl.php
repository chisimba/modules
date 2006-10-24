<?php

//Template to view the different admin functions

$tree = & $this->newObject('cmstree', 'cmsadmin');


$str = $tree->show(null, true);

echo $str;

?>
