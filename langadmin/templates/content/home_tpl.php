<?php
// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('htmltable', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('link', 'htmlelements');

// setup and show heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_langadmin_title', 'langadmin');
$header->type = 2;
echo $header->show();


//echo $link->show();
$addbutton = new button('addNewLangButton', $this->objLanguage->languageText('mod_langadmin_addlanguage', 'langadmin'));
$uri = $this->uri(array('action' => 'showNewLangTemplate'));
$addbutton->setOnClick('javascript: window.location=\'' . $uri . '\'');


echo $addbutton->show().'<br/>';
$objConfig=$this->getObject("altconfig","config");
echo '<a href="'.$objConfig->getsiteRoot().'/packages/langadmin/resources/ChisimbaLangTranslator/dist/ChisimbaLangTranslator.jar">Download Translation Client</a>';

$table = $this->getObject('htmltable', 'htmlelements');
$table->startHeaderRow();
$table->addHeaderCell($this->objLanguage->languageText('mod_langadmin_langid', 'langadmin'));
$table->addHeaderCell($this->objLanguage->languageText('mod_langadmin_langname', 'langadmin'));
$table->addHeaderCell($this->objLanguage->languageText('mod_langadmin_export', 'langadmin'));
$table->addHeaderCell($this->objLanguage->languageText('mod_langadmin_import', 'langadmin'));
$table->endHeaderRow();

$langs = $this->objLanguage->getLangs();

foreach ($langs as $id => $name) {

    $table->startRow();
    $table->addCell($id);
    $table->addCell($name);
    $link = new link($this->uri(array("action" => "exportLangItems","langid"=>$id)));
    $link->link = $this->objLanguage->languageText('mod_langadmin_export', 'langadmin');
    $table->addCell($link->show());
    $link = new link($this->uri(array("action" => "uploadFile","langid"=>$id)));
    $link->link = $this->objLanguage->languageText('mod_langadmin_import', 'langadmin');
    $table->addCell($link->show());
    
    $table->endRow();
}
?>
<fieldset>
    <?php echo $table->show(); ?>
</fieldset>
<?php
?>
