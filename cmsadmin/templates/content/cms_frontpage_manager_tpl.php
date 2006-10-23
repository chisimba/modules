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
$h3->str = ' Front Page Manager  '.$objIcon->getAddIcon($this->uri(array('action' => 'addcontent' , 'frontpage' => 'true')));
//counter for records
$cnt = 1;



//setup the table headings
$table->startHeaderRow();
$table->addHeaderCell('#');
$table->addHeaderCell('Title');
$table->addHeaderCell('Published');
$table->addHeaderCell('Access');
$table->addHeaderCell('Section');
$table->addHeaderCell('Order');
$table->addHeaderCell('Options');
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
  $middleColumnContent .= "<span class='noRecordsMessage' >".$this->objLanguage->languageText('mod_cmsadmin_nopagesonfrontpage', 'cmsadmin')."</span>";  
}

echo  '<ul class="tree">
<li><a href="#">item 1</a>
<ul>
<li><a href="#">item 1.1</a></li>
<li class="closed"><a href="#">item 1.2</a>
<ul>
<li><a href="#">item 1.2.1</a></li>
<li class ="closed"><a href="#">item 1.2.2</a>
<ul>
<li><a href="#">item 1.2.1.1</a></li>
<li><a href="#">item 1.2.1.1</a></li>
</ul>
</li>
<li><a href="#">item 1.2.3</a></li>
</ul>
</li>
<li><a href="#">item 1.3</a></li>
</ul>
</li>
<li><a href="#">item 2</a>
<ul>
<li><a href="#">item 2.1</a></li>
<li><a href="#">item 2.2</a></li>
<li><a href="#">item 2.3</a></li>
</ul>
</li>
</ul>';

echo $middleColumnContent;

?>
