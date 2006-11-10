<?php
/**
 * This template outputs the front page manager
 * for the cms module
 * 
 * 
 */

//initiate objects
$table = & $this->newObject('htmltable', 'htmlelements');
$h3 = &$this->newObject('htmlheading', 'htmlelements');
$link = & $this->newObject('link', 'htmlelements');
$objIcon = & $this->newObject('geticon', 'htmlelements');

//create a heading 
$h3->str = $this->objLanguage->languageText('mod_cmsadmin_frontpagemanager', 'cmsadmin').'&nbsp;'.$objIcon->getAddIcon($this->uri(array('action' => 'addcontent' , 'frontpage' => 'true')));
//counter for records
$cnt = 1;



//setup the table headings
$table->startHeaderRow();
$table->addHeaderCell($this->objLanguage->languageText('word_number'));
$table->addHeaderCell($this->objLanguage->languageText('word_title'));
$table->addHeaderCell($this->objLanguage->languageText('word_published'));
$table->addHeaderCell($this->objLanguage->languageText('word_access'));
$table->addHeaderCell($this->objLanguage->languageText('word_section'));
$table->addHeaderCell($this->objLanguage->languageText('word_order'));
$table->addHeaderCell($this->objLanguage->languageText('word_options'));
$table->endHeaderRow();   

$rowcount = 0;
//var_dump($files);
//setup the tables rows  and loop though the records
foreach($files as $file)
{
    $arrFile = $this->_objContent->getContentPage($file['content_id']);
   
    $link->link = $arrFile['title'];
	  $link->href = $this->uri(array('action' => 'addcontent', 'id' => $arrFile['id']));

	  $oddOrEven = ($rowcount == 0) ? "even" : "odd";
	
	  //Create delete icon for removing content from front page
	  $objIcon =& $this->newObject('geticon', 'htmlelements');
    $delArray = array('action' => 'removefromfrontpage', 'confirm'=>'yes', 'id'=>$file['id']);
    $deletephrase = $this->objLanguage->languageText('mod_cmsadmin_confirmremovefromfp', 'cmsadmin');
    $delIcon = $objIcon->getDeleteIconWithConfirm($file['id'], $delArray, 'cmsadmin', $deletephrase);

    $tableRow = array();
    $tableRow[]=$cnt++;
    $tableRow[]=$link->show();
    $tableRow[]=$this->_objUtils->getCheckIcon($arrFile['published'], TRUE);
    // $table->addCell($arrCategory['ordering']);
	  $tableRow[]=$this->_objUtils->getAccess($arrFile['access']);
	
	  $link->link = $this->_objSections->getMenuText($arrFile['sectionid']);
	  $link->href = $this->uri(array('action' => 'viewsection', 'id' => $arrFile['sectionid']));
	
	  $tableRow[]=$link->show();
	  $tableRow[]=$this->_objFrontPage->getOrderingLink($file['id']);
	  $tableRow[]=$delIcon;
	  //$table->addCell($this->_objCategories->getCatCount($section['id']));
	  //$table->addCell($section['created']);

  	
	  $table->addRow($tableRow, $oddOrEven);
	  $rowcount = ($rowcount == 0) ? 1 : 0;
} 


//print out the page
$middleColumnContent = "";
$middleColumnContent .= $h3->show();
$middleColumnContent .= '<br/>';
$middleColumnContent .= $table->show();
if(empty($files)){
  $middleColumnContent .= '<div class="noRecordsMessage" >'.$this->objLanguage->languageText('mod_cmsadmin_nopagesonfrontpage', 'cmsadmin').'</div>';  
}

echo $middleColumnContent;

?>
