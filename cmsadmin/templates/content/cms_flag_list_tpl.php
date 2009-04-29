<?php
/**
 * This template will list all the Flags
 */

//initiate objects
$table =  $this->newObject('htmltable', 'htmlelements');
$objH = $this->newObject('htmlheading', 'htmlelements');
$link =  $this->newObject('link', 'htmlelements');
$objIcon =  $this->newObject('geticon', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$objLayer =$this->newObject('layer','htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('htmltable', 'htmlelements');

if (!isset($middleColumnContent)) {
	$middleColumnContent = '';
}

$objIcon = $this->newObject('geticon', 'htmlelements');
$tbl = $this->newObject('htmltable', 'htmlelements');
$h3 = $this->getObject('htmlheading', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');
$objIcon->setIcon('Flags_small', 'png', 'icons/cms/');
$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('mod_cmsadmin_Flags', 'cmsadmin');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$objLayer->str = $topNav;
$objLayer->border = '; float:right; align:right; margin:0px; padding:0px;';
$header .= $objLayer->show();

$objLayer->str = '';
$objLayer->border = '; clear:both; margin:0px; padding:0px;';
$headShow = $objLayer->show();

$display = '<p>'.$header.$headShow.'</p><hr />';
//Show Header
echo $display;
// Show Form

echo $objLayer->show();//$tbl->show());
$table = new htmltable();
$table->cellspacing = '2';
$table->cellpadding = '5';

//setup the table headings
$table->startHeaderRow();
//$table->addHeaderCell("<input type=\"checkbox\" name=\"toggle\" value=\"\" onclick=\"javascript:ToggleCheckBoxes('select', 'arrayList[]', 'toggle');\" />" . " " . $this->objLanguage->languageText("mod_cms_selectall", "cmsadmin"));
//$table->addHeaderCell($this->objLanguage->languageText('word_image', 'system'));
$table->addHeaderCell($this->objLanguage->languageText('word_title', 'system'));
$table->addHeaderCell($this->objLanguage->languageText('word_description'));
$table->addHeaderCell($this->objLanguage->languageText('word_publish', 'system'));
$table->addHeaderCell($this->objLanguage->languageText('word_options'));
$table->endHeaderRow();

$rowcount = 0;

if (!isset($arrFlagOptions)) {
	$arrFlagOptions = array();
}

//setup the tables rows  and loop though the records
if (count($arrFlagOptions) > 0) {
	foreach($arrFlagOptions as $template) {
	    //Set odd even row colour
	    $oddOrEven = ($rowcount == 0) ? "even" : "odd";
		
        //$templateThumb = '<img src="'.$template['image'].'" width="100px" height="70px"/>';

		//Set up select form
		$objCheck = new checkbox('arrayList[]');
		$objCheck->setValue($template['id']);
		$objCheck->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";
		
	    //publish, visible
	    if($template['published']){
	       $url = $this->uri(array('action' => 'templatepublish', 'id' => $template['id'], 'mode' => 'unpublish'));
	       $icon = $this->_objUtils->getCheckIcon(TRUE);
	    }else{
	       $url = $this->uri(array('action' => 'templatepublish', 'id' => $template['id'], 'mode' => 'publish'));
	       $icon = $this->_objUtils->getCheckIcon(FALSE);
	    }
	    $objLink = new link($url);
	    $objLink->link = $icon;
	    $visibleLink = $objLink->show();
	
	    //Create delete icon
        //TODO: Enable Security
		//if ($this->_objSecurity->canUserWriteSection($template['id'])){
		    $delArray = array('action' => 'deletetemplate', 'confirm'=>'yes', 'id'=>$template['id']);
		    $deletephrase = $this->objLanguage->languageText('mod_cmsadmin_confirmdeltemplate', 'cmsadmin');
		    $delIcon = $objIcon->getDeleteIconWithConfirm($template['id'], $delArray,'cmsadmin',$deletephrase);
		//} else {
		//	$delIcon = '';
		//}
		
	    
	    //edit icon
        //TODO: Enable Security
		//if ($this->_objSecurity->canUserWriteSection($template['id'])){
	    	$editIcon = $objIcon->getEditIcon($this->uri(array('action' => 'addtemplate', 'id' => $template['id'])));
		//} else {
		//	$editIcon = '';
		//}
	    
	
	    $tableRow = array();
	    //$tableRow[] = $objCheck->show();
        //$tableRow[] = $templateThumb;
	    $tableRow[] = html_entity_decode($template['title']);
        $tableRow[] = $template['description'];
	    $tableRow[] = $visibleLink;
        if (!$this->_objSecurity->canUserWriteSection($template['id'])){
            $editIcon = '';
            $deleteIcon = '';
            $visibleLink = '';
        }
        $tableRow[] = '<nobr>'.$editIcon.$delIcon.'</nobr>';

	    $table->addRow($tableRow, $oddOrEven);
	
	    $rowcount = ($rowcount == 0) ? 1 : 0;

	}
    $noRecords = false;
}else{
    echo  '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_cmsadmin_noFlagsfound', 'cmsadmin').'</div>';
	$noRecords = true;
}


$frm_select = new form('select', $this->uri(array('action' => 'select'), 'cmsadmin'));
$frm_select->id = 'select';

$objLayer = new layer();
$objLayer->id = 'templateListTable';
$objLayer->str = $table->show();

$frm_select->addToForm($objLayer->show());
//print out the page
//$middleColumnContent = "<hr />";

if (!$noRecords) {
    $middleColumnContent .= $frm_select->show();
}

$middleColumnContent .= '&nbsp;'.'<br/>';

echo $middleColumnContent;

?>
