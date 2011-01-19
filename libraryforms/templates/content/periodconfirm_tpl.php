<?php
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

$objLayer = $this->newObject('layer', 'htmlelements');
$objILLperiodical = $this->getObject('illperiodical', 'libraryforms');
$tab = $this->newObject('tabbedbox', 'htmlelements');
$table = $this->newObject('htmltable', 'htmlelements');
$tabcontent = $this->newObject('tabcontent', 'htmlelements');
$objTable = $this->newObject('htmltable', 'htmlelements');
$this->loadClass('form', 'htmlelements');

$tab->tabbedbox();
$tab->addTabLabel($this->objLanguage->languageText("category_resource_three", "libraryforms"));
$tab->addBoxContent($objILLperiodical->show());

$tabcontent->addTab('Periodical Request Form', $tab->show());

$tabcontent->width = '90%';
echo '<br/><center>' . $tabcontent->show() . '</center>';

?>
