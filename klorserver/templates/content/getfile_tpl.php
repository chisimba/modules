<?
/**
* This template returns a file, in which 
* gets encoded into base64
*/
/**
* Local getfile template  page for kLOR module of KEWL.NextGen
* @author Jameel Adam
* 
*/
	//Set layout template
	$rowClass = 'odd';
	$this->objFile =& $this->getObject('dbcoursewarefile', 'klorserver');
	
	$objTable = &$this->getObject('htmltable','htmlelements');
	$objHeading = &$this->getObject('htmlheading','htmlelements');
	$objIcon = &$this->getObject('geticon','htmlelements');
	$objLink = & $this->getObject('link','htmlelements');
	
	$tblCourseWare =& $this->newObject('htmltable','htmlelements');
//-------------------------THE END---------------------//

	$data = $this->objFile->getAll();
	//print_r($data);
	foreach($data as $item){
	print $item['path'].'&nbsp;'.'&nbsp;'.$item['name'].'<br>';
	}

?>