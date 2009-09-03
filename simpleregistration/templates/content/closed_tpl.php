
<?php
if($this->objUser->isLoggedIn()){
//    $this->nextAction('expresssignin');
}
$maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/simpleregistration.css').'"/>';
$this->appendArrayVar('headerParams', $maincss);

$table=$this->getObject('htmltable','htmlelements');
$table->cellpadding = 5;
$table->cellpadding = 5;
$regformObj = $this->getObject('formmanager');
$objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
$allowExternalReg=$objSysConfig->getValue('ALLOW_EXTERNAL_REG', 'simpleregistration');
$eventcontent=array();

if(count($content) > 0){
    $eventcontent=$content[0];
}else{

}

$objWashout = $this->getObject('washout', 'utilities');
$title1=$objWashout->parseText($eventcontent['event_lefttitle1']);
$title2=$objWashout->parseText($eventcontent['event_lefttitle2']);
$footer=$objWashout->parseText($eventcontent['event_footer']);
$timevenue=$objWashout->parseText($eventcontent['event_timevenue']);

$message='"'.$this->objLanguage->languageText('mod_simpleregistration_isopen', 'simpleregistration').'"';
$rightTitle='<h1>'.$this->objLanguage->languageText('mod_simpleregistration_registration', 'simpleregistration').'</h1>';
$rightTitle.='<h3>'.$message.'</h3>';
$leftTitle.=$title1.'<br/>';
$leftTitle.=$title2;
$rightTitle.=$timevenue;

$programLink =new link($this->uri(array('action'=>'expresssignin')));
$programLink->link= '<h3>The Program</h3>';


$table->startRow();
$table->addCell($leftTitle);
$table->addCell($rightTitle);
$table->endRow();

$table->startRow();
$table->addCell('');
$table->endRow();

$content=$eventcontent['event_content'];
$pagecontent= $objWashout->parseText($content);

$table->startRow();
$table->addCell($pagecontent);
//$table->addCell($regformObj->createRegisterForm($editfirstname,$editlastname,$editcompany,$editemail,$mode,$allowExternalReg,$shortname));

$table->endRow();

$admin = new link ($this->uri(array('action'=>'memberlist','shortname'=>$shortname)));
$admin->link= $this->objLanguage->languageText('mod_simpleregistration_admin', 'simpleregistration');


if($this->objUser->isLoggedIn()){
    $table->startRow();
    $table->addCell($admin->show());
    $table->endRow();
}


$table->startRow();
$table->addCell('');
$table->addCell($footer);
$table->endRow();
if(count($content) > 0){
echo '<div id="wrap">'.$error.$table->show().'</div>';
}else{
    echo '<font color="red"><h1>No conference with the shortname suggested exist</h1></font>';
}
?>
