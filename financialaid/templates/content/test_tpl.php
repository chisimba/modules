<?
$switchmenu = $this->newObject('switchblock2', 'financialaid');
$switchmenu->addBlock('block1', 'Title 1', 'Block Text 1 <br /> Block Text 1 <br /> Block Text 1');
$switchmenu->addBlock('block2', 'Title 2', 'Block Text 2 <br /> Block Text 2 <br /> Block Text 2'); // Adds a CSS Style
$switchmenu->addBlock('block3', 'Title 3', 'Block Text 3 <br /> Block Text 3 <br /> Block Text 3');
echo $switchmenu->show();

?>
