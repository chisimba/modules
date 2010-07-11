<?php
// Load classes.
$this->loadClass("form", "htmlelements");
$this->loadClass("textinput", "htmlelements");
$this->loadClass('textarea', 'htmlelements');
$this->loadClass("button", "htmlelements");
$this->loadClass("htmltable", 'htmlelements');
//reflectId
$singleView = $this->objGetall->viewSingleReflection($reflectId);
echo $singleView;
echo $this->objGetall->viewPartForm('singlereflection', 'reflectId', $reflectId );
//Get Object
$this->objIcon = &$this->newObject('geticon', 'htmlelements');
$objLayer3 = $this->newObject('layer', 'htmlelements');
$this->objIcon->setIcon('close');
$this->objIcon->extra = " onclick='javascript:window.close()'";
$objLayer3->align = 'center';
$objLayer3->str = $this->objIcon->show();
echo $objLayer3->show();
?>
