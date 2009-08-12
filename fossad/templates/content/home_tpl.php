
<?php
$maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/fossad.css').'"/>';
$this->appendArrayVar('headerParams', $maincss);

$table=$this->getObject('htmltable','htmlelements');
$table->cellpadding = 5;
$table->cellpadding = 5;
$regformObj = $this->getObject('formmanager');
$objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
$title1=$objSysConfig->getValue('LEFT_TITLE1', 'fossad');
$title2=$objSysConfig->getValue('LEFT_TITLE2', 'fossad');
$message='"'.$this->objLanguage->languageText('mod_fossad_isopen', 'fossad').'"';
if($mode == 'edit'){
    $message='<font color="red">'.$this->objLanguage->languageText('mod_fossad_emailinuse', 'fossad').'</font>';
}
$rightTitle='<h1>'.$this->objLanguage->languageText('mod_fossad_registration', 'fossad').'</h1>';
$rightTitle.='<h3>'.$message.'</h3>';
$leftTitle.='<h1>'.$title1.'</h1>';
$leftTitle.='<h4>'.$title2.'</h4>';

$table->startRow();
$table->addCell($leftTitle);
$table->addCell($rightTitle);
$table->endRow();

$table->startRow();
$table->addCell('<img src="'.$this->getResourceUri('images/logo.png').'">');
$table->addCell($regformObj->createRegisterForm($editfirstname,$editlastname,$editcompany,$editemail,$mode));
$table->endRow();

$admin = new link ($this->uri(array('action'=>'admin')));
$admin->link= $this->objLanguage->languageText('mod_fossad_admin', 'fossad');
$table->startRow();
$table->addCell($admin->show());
$table->endRow();


echo '<div id="wrap">'.$error.$table->show().'</div>';
?>
