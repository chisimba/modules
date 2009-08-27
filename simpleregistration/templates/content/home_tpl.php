
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
$title1=$objSysConfig->getValue('LEFT_TITLE1', 'simpleregistration');
$title2=$objSysConfig->getValue('LEFT_TITLE2', 'simpleregistration');
$footer=$objSysConfig->getValue('FOOTER', 'simpleregistration');
$message='"'.$this->objLanguage->languageText('mod_simpleregistration_isopen', 'simpleregistration').'"';
if($mode == 'edit'){
    $message='<font color="red">'.$this->objLanguage->languageText('mod_simpleregistration_emailinuse', 'simpleregistration').'</font>';
}

if($mode == 'loginagain'){
    $message='<font color="red">Please sign in again to complete registration</font>';
}
$rightTitle='<h1>'.$this->objLanguage->languageText('mod_simpleregistration_registration', 'simpleregistration').'</h1>';
$rightTitle.='<h3>'.$message.'</h3>';
$leftTitle.='<h1>'.$title1.'</h1>';
$leftTitle.='<h4>'.$title2.'</h4>';

$expressLink =new link($this->uri(array('action'=>'expresssignin')));
$expressLink->link= '<h3>'.$this->objLanguage->languageText('mod_simpleregistration_express', 'simpleregistration');

$programLink =new link($this->uri(array('action'=>'expresssignin')));
$programLink->link= '<h3>The Program</h3>';


$table->startRow();
$table->addCell($leftTitle);
$table->addCell($rightTitle);
$table->endRow();

$table->startRow();
$table->addCell('');
$table->addCell($expressLink->show());//.'<img src="'.$this->getResourceUri('images/line.png').'">');
$table->endRow();

$objWashout = $this->getObject('washout', 'utilities');
$content=$objSysConfig->getValue('CONTENT', 'simpleregistration');
$pagecontent= $objWashout->parseText($content);

$table->startRow();
$table->addCell($pagecontent);
$table->addCell($regformObj->createRegisterForm($editfirstname,$editlastname,$editcompany,$editemail,$mode));
$table->endRow();

$admin = new link ($this->uri(array('action'=>'admin')));
$admin->link= $this->objLanguage->languageText('mod_simpleregistration_admin', 'simpleregistration');


if($this->objUser->isLoggedIn()){
    $table->startRow();
    $table->addCell($admin->show());
    $table->endRow();
}


$table->startRow();
$table->addCell($footer);
$table->endRow();

echo '<div id="wrap">'.$error.$table->show().'</div>';
?>
