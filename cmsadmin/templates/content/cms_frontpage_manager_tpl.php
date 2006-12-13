<?php
/**
 * This template outputs the front page manager
 * for the cms module
 * 
 * @package cmsadmin
 * @author Warren Windvogel
 * @author Wesley Nitskie
 */

//initiate objects
$table = & $this->newObject('htmltable', 'htmlelements');
$objH = &$this->newObject('htmlheading', 'htmlelements');
$link = & $this->newObject('link', 'htmlelements');
$objIcon = & $this->newObject('geticon', 'htmlelements');

//create link to add blocks to the front page
//Get blocks icon
$objIcon->setIcon('modules/blocks');
$objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_addremoveblocks', 'cmsadmin');
$blockIcon = $objIcon->show();

//Check if blocks module is registered
$this->objModule = &$this->newObject('modules', 'modulecatalogue');
$isRegistered = $this->objModule->checkIfRegistered('blocks');

//Create link
$objBlocksLink = new link('javascript:void(0)');
$objBlocksLink -> link = $blockIcon;
$objBlocksLink -> extra = "onclick = \"javascript:window.open('" . $this->uri(array('action' => 'addblock', 'blockcat' => 'frontpage')) . "', 'branch', 'width=500, height=350, top=50, left=50, scrollbars')\"";

//create a heading
if($isRegistered){
    $objH->str = $this->objLanguage->languageText('mod_cmsadmin_frontpagemanager', 'cmsadmin').'&nbsp;'.$objBlocksLink->show();
} else {
    $objH->str = $this->objLanguage->languageText('mod_cmsadmin_frontpagemanager', 'cmsadmin');
}
$objH->type = '1';
//counter for records
$cnt = 1;

//setup the table headings
$table->startHeaderRow();
$table->addHeaderCell($this->objLanguage->languageText('word_title'));
$table->addHeaderCell($this->objLanguage->languageText('word_visible'));
$table->addHeaderCell($this->objLanguage->languageText('word_section'));
$table->addHeaderCell($this->objLanguage->languageText('word_order'));
$table->addHeaderCell($this->objLanguage->languageText('word_options'));
$table->endHeaderRow();

$rowcount = 0;
//var_dump($files);
//setup the tables rows  and loop though the records
foreach($files as $file) {
    $arrFile = $this->_objContent->getContentPage($file['content_id']);

    $oddOrEven = ($rowcount == 0) ? "even" : "odd";

    //Create delete & edit icon for removing content from front page
    $objIcon = & $this->newObject('geticon', 'htmlelements');
    $objIcon->setIcon('edit');
    $link->link = $objIcon->show();
    $link->href = $this->uri(array('action' => 'addcontent', 'id' => $arrFile['id'], 'parent' => $arrFile['sectionid']));
    $editPage = $link->show();

    $delArray = array('action' => 'removefromfrontpage', 'confirm' => 'yes', 'id' => $file['content_id']);
    $deletephrase = $this->objLanguage->languageText('mod_cmsadmin_confirmremovefromfp', 'cmsadmin');
    $delIcon = $objIcon->getDeleteIconWithConfirm($file['id'], $delArray, 'cmsadmin', $deletephrase);

    $tableRow = array();
    $tableRow[] = $arrFile['title'];
    $tableRow[] = $this->_objUtils->getCheckIcon($arrFile['published'], TRUE);

    $link->link = $this->_objSections->getMenuText($arrFile['sectionid']);
    $link->href = $this->uri(array('action' => 'viewsection', 'id' => $arrFile['sectionid']));

    $tableRow[] = $link->show();
    $tableRow[] = $this->_objFrontPage->getOrderingLink($file['id']);
    $tableRow[] = $editPage.'&nbsp;'.$delIcon;

    $table->addRow($tableRow, $oddOrEven);
    $rowcount = ($rowcount == 0) ? 1 : 0;
}


//print out the page
$middleColumnContent = "";
$middleColumnContent .= $objH->show();
$middleColumnContent .= '<br/>';
$middleColumnContent .= $table->show();

if (empty($files)) {
    $middleColumnContent .= '<div class="noRecordsMessage" >'.$this->objLanguage->languageText('mod_cmsadmin_nopagesonfrontpage', 'cmsadmin').'</div>';
}

echo $middleColumnContent;

?>
