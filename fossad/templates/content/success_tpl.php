
<?php
$maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/fossad.css').'"/>';
$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/fossad.css').'"/>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $maincss);

$table=$this->getObject('htmltable','htmlelements');
$table->cellpadding = 5;
$table->cellpadding = 5;
$regformObj = $this->getObject('formmanager');

$objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
$contactemail=$objSysConfig->getValue('CONTACT_EMAIL', 'fossad');
$title1=$objSysConfig->getValue('LEFT_TITLE1', 'fossad');
$title2=$objSysConfig->getValue('LEFT_TITLE2', 'fossad');

$rightTitle='<h1>'.$rightTitle1.'</h1>';
$rightTitle.='<h3>'.$rightTitle2.'</h3>';
$leftTitle.='<h1>'.$title1.'</h1>';
$leftTitle.='<h4>'.$title2.'</h4>';


$home = new link ($this->uri(array('action'=>'home')));
$home->link= $this->objLanguage->languageText('mod_fossad_home', 'fossad');

$table->startRow();
$table->addCell($leftTitle);
$table->addCell($rightTitle);
$table->endRow();

$table->startRow();
$table->addCell('<img src="'.$this->getResourceUri('images/logo.png').'">','300');
$table->addCell($this->objLanguage->languageText('mod_fossad_contactemail', 'fossad').' '.$contactemail);
$table->endRow();

$table->startRow();
$table->addCell($home->show());
$table->endRow();

echo '<div id="wrap">'.$table->show().'</div>';

?>
