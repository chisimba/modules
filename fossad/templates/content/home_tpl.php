
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

$rightTitle='<h1>'.$this->objLanguage->languageText('mod_fossad_registration', 'fossad').'</h1>';
$rightTitle.='<h3>"'.$this->objLanguage->languageText('mod_fossad_isopen', 'fossad').'"</h3>';
$leftTitle.='<h1>'.$title1.'</h1>';
$leftTitle.='<h4>'.$title2.'</h4>';
$table->cssClass="page_shadows";
$table->startRow();
$table->addCell($leftTitle);
$table->addCell($rightTitle);
$table->endRow();

$table->startRow();
$table->addCell('<img src="'.$this->getResourceUri('images/logo.png').'">');
$table->addCell($regformObj->createRegisterForm());
$table->endRow();

//$rightContent.='<div id="main"><img src="'.$this->getResourceUri('images/logo.png').'"></div>';
//$rightContent.='<div id="sidebar">'.$regformObj->createRegisterForm().'</div>';
//$rightContent.='<div id ="main">'.$table->show().'</div>';
echo '<div id="wrap">'.$table->show().'</div>';
//echo $rightContent;

//echo $table->show();
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);
$cssLayout->setLeftColumnContent($regformObj->getLeftContent());
$cssLayout->setMiddleColumnContent($rightContent);
//echo $cssLayout->show();
?>
