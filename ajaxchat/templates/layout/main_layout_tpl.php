<?php

/**

*Layout template for the commitee meeting module

*

*/

$cssLayout = &$this->newObject('cssLayout', 'htmlelements');

$context_list = $this->newObject('sidemenu','toolbar');


// Add to layout and display

$cssLayout->setNumColumns(2);


$cssLayout->setLeftColumnContent('<table width="90%"><tr><td>'.''.'</td></tr></table>');


//$cssLayout->setLeftColumnContent($leftMenu);
$cssLayout->setMiddleColumnContent($this->getContent().'<br clear="left">');



echo $cssLayout->show();


?>
