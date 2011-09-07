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

echo $addbutton->show();

$table = $this->getObject('htmltable', 'htmlelements');
$table->startHeaderRow();
$table->addHeaderCell($this->objLanguage->languageText('mod_langadmin_addlanguage', 'langid'));
$table->addHeaderCell($this->objLanguage->languageText('mod_langadmin_addlanguage', 'langname'));

$table->endHeaderRow();

$langs = $this->objLanguage->getLangs();

foreach ($langs as $id => $name) {

    $table->startRow();
    $table->addCell($id);
    $link = new link($this->uri(array("action" => "viewLangItems")));
    $link->link = $name;
    $table->addCell($link->show());

    $table->endRow();
}
?>
<fieldset>
    <?php echo $table->show(); ?>
</fieldset>
<?php
?>
