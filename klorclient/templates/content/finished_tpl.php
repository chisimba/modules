<?
$this->setSession('var1', 'value1');
$this->setLayoutTemplate('klor_layout_tpl.php');
// set up html elements
    $objHeader=&$this->newObject('htmlheading','htmlelements');
    $objTable=&$this->newObject('htmltable','htmlelements');
    $objButton=&$this->newObject('button','htmlelements');
    $objForm=&$this->newObject('form','htmlelements');
    $objLink=&$this->newObject('link','htmlelements');
    $objText=&$this->newObject('textinput','htmlelements');
    $objTab=&$this->newObject('tabboxes','htmlelements');
	$objBar=&$this->newObject('klorclients','klorclient');
	
	


	$percent = $per;	
	$bargraph = $objBar->bargraph($percent);
	echo 'Bar graph!'.'<br>';
	echo $bargraph.'&nbsp;'.$percent.'%';
	

?>