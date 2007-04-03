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
//$table =  $this->newObject('htmltable', 'htmlelements');
$this->loadClass('htmltable', 'htmlelements');
$objH = $this->newObject('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$objIcon =  $this->newObject('geticon', 'htmlelements');
$objRound =$this->newObject('roundcorners','htmlelements');
$objLayer =$this->newObject('layer','htmlelements');

//create link to add blocks to the front page
//Get blocks icon
$objIcon->setIcon('modules/blocks');
$objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_addremoveblocks', 'cmsadmin');
$blockIcon = $objIcon->show();

//Check if blocks module is registered
$this->objModule = &$this->newObject('modules', 'modulecatalogue');
$isRegistered = $this->objModule->checkIfRegistered('blocks');

//Create link
$objBlocksLink = new link('#');
$objBlocksLink -> link = $blockIcon;
$objBlocksLink -> extra = "onclick = \"javascript:window.open('" . $this->uri(array('action' => 'addblock', 'blockcat' => 'frontpage')) . "', 'branch', 'width=500, height=350, top=50, left=50, scrollbars')\"";

//Heading box
$objIcon->setIcon('frontpage', 'png', 'icons/cms/');
$objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_sectionmanager', 'cmsadmin');
//$objH->str =  $objIcon->show().'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_sectionmanager', 'cmsadmin');

//create a heading
if($isRegistered){
    $objH->str = $objIcon->show().'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_frontpagemanager', 'cmsadmin').'&nbsp;'.$objBlocksLink->show();
} else {
    $objH->str = $objIcon->show().'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_frontpagemanager', 'cmsadmin');
}
$objH->type = '1';

$objLayer->str = $objH->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$objLayer->str = $topNav;
$objLayer->border = '; float:right; align:right; margin:0px; padding:0px;';
$header .= $objLayer->show();

$objLayer->str = '';
$objLayer->border = '; clear:both; margin:0px; padding:0px;';
$headShow = $objLayer->show();

//Get Selectall js
echo $this->getJavascriptFile('selectall.js');

$header = $objRound->show($header.$headShow);//$tbl->show());

//counter for records
$cnt = 1;

$objCheck = new checkbox('toggle');
$objCheck->extra = "onclick=\"javascript:ToggleCheckBoxes('select', 'arrayList[]', 'toggle');\"";

$table = new htmltable();
$table->cellspacing = '2';
$table->cellpadding = '5';

//setup the table headings
$table->startHeaderRow();
$table->addHeaderCell($objCheck->show());
$table->addHeaderCell($this->objLanguage->languageText('word_title'));
$table->addHeaderCell($this->objLanguage->languageText('word_visible'));
$table->addHeaderCell($this->objLanguage->languageText('word_section'));
$table->addHeaderCell($this->objLanguage->languageText('word_order'));
$table->addHeaderCell($this->objLanguage->languageText('word_options'));
$table->endHeaderRow();

$rowcount = 0;
//var_dump($files);
//setup the tables rows  and loop though the records
if(!empty($files)){
    foreach($files as $file) {
        $arrFile = $this->_objContent->getContentPage($file['content_id']);
    
        $oddOrEven = ($rowcount == 0) ? "even" : "odd";
    
        //Create delete & edit icon for removing content from front page
        $objIcon = & $this->newObject('geticon', 'htmlelements');
        $objIcon->setIcon('edit');
        $link = new link($this->uri(array('action' => 'addcontent', 'id' => $arrFile['id'], 'parent' => $arrFile['sectionid'])));
        $link->link = $objIcon->show();
        $editPage = $link->show();
    
        $delArray = array('action' => 'removefromfrontpage', 'confirm' => 'yes', 'id' => $file['content_id']);
        $deletephrase = $this->objLanguage->languageText('mod_cmsadmin_confirmremovefromfp', 'cmsadmin');
        $delIcon = $objIcon->getDeleteIconWithConfirm($file['id'], $delArray, 'cmsadmin', $deletephrase);
    
        $objCheck = new checkbox('arrayList[]');
        $objCheck->setValue($file['content_id']);
        $objCheck->extra = "onclick=\"javascript: ToggleMainBox('select', 'toggle', this.checked);\"";
            
    
        $tableRow = array();
        $tableRow[] = $objCheck->show();
        $tableRow[] = $arrFile['title'];
        $tableRow[] = $this->_objUtils->getCheckIcon($arrFile['published'], TRUE);
    
        $link = new link($this->uri(array('action' => 'viewsection', 'id' => $arrFile['sectionid'])));
        $link->link = $this->_objSections->getMenuText($arrFile['sectionid']);
    
        $tableRow[] = $link->show();
        $tableRow[] = $this->_objFrontPage->getOrderingLink($file['id']);
        $tableRow[] = $editPage.'&nbsp;'.$delIcon;
    
        $table->addRow($tableRow, $oddOrEven);
        $rowcount = ($rowcount == 0) ? 1 : 0;
    }
}

$objInput = new textinput('task', '', 'hidden');
$hidden = $objInput->show();
    
$objForm = new form('select', $this->uri(array('action' => 'publishfrontpage')));
$objForm->addToForm($table->show().$hidden);

//print out the page
$middleColumnContent = "";
$middleColumnContent .= $header;//objH->show();
$middleColumnContent .= '<br/>';
$middleColumnContent .= $objForm->show();

if (empty($files)) {
    $middleColumnContent .= '<div class="noRecordsMessage" >'.$this->objLanguage->languageText('mod_cmsadmin_nopagesonfrontpage', 'cmsadmin').'</div>';
}

echo $middleColumnContent;

echo '<br />';
echo '<h2>'.$this->objLanguage->languageText('mod_cmsadmin_frontpageblocks', 'cmsadmin', 'Front Page Blocks').'</h2>';

    $objModuleBlocks =& $this->getObject('dbmoduleblocks', 'modulecatalogue');
    $objCMSBlocks =& $this->getObject('dbblocks', 'cmsadmin');
    $objBlocks =& $this->getObject('blocks', 'blocks');
    
    

//    $this->objScriptaculous =& $this->getObject('scriptaculous', 'ajaxwrapper');
//    $this->objScriptaculous->show();

    $blocks = $objModuleBlocks->getBlocks('normal');
    $thisPageBlocks = $objCMSBlocks->getBlocksForFrontPage();
    
    
    echo '<div id="dropzone" style="border: 1px dashed black; background-color: lightyellow; z-index:1; position: relative; padding: 4px;"><h4>'.$this->objLanguage->languageText('mod_cmsadmin_addedblocks', 'cmsadmin', 'Added Blocks').'</h4>'.$this->objLanguage->languageText('mod_cmsadmin_dragaddblocks', 'cmsadmin', 'Drag and drop the blocks you want to add here.');
    
    $usedBlocks = array();
    
    foreach ($thisPageBlocks as $block)
    {
        $str = trim($objBlocks->showBlock($block['blockname'], $block['moduleid']));
        $str = preg_replace('/type\\s??=\\s??"submit"/', 'type="button"', $str);
        $str = preg_replace('/href=".+?"/', 'href="javascript:alert(\''.$this->objLanguage->languageText('mod_cmsadmin_pageblocks', 'cmsadmin', 'Page Blocks').$this->objLanguage->languageText('mod_cmsadmin_linkdisabled', 'cmsadmin', 'Link is Disabled.').'\');"', $str);
        
        $usedBlocks[] = $block['blockid'];
        
        echo '<div class="usedblock" id="'.$block['blockid'].'" style="border: 1px solid lightgray; padding: 5px; width:150px; float: left; z-index:1000;">'.$str.'</div>';
    }
    echo '</div>';

    echo '<br clear="left" /><br /><br />';
    
    echo '<div id="loading" style="visibility:visible; float: right;">'.$objIcon->show().'</div>';
    
    echo '<div id="deletezone" style="border: 1px dashed black; background-color: lightyellow; position: relative; padding: 4px;"><h4>'.$this->objLanguage->languageText('mod_cmsadmin_availableblocks', 'cmsadmin', 'Available Blocks').'</h4>'.$this->objLanguage->languageText('mod_cmsadmin_dragremoveblocks', 'cmsadmin', 'Drag and drop the blocks you want to remove here.');
    
    foreach ($blocks as $block)
    {
        if (!in_array($block['id'], $usedBlocks)) {
            $str = trim($objBlocks->showBlock($block['blockname'], $block['moduleid']));
            $str = preg_replace('/type\\s??=\\s??"submit"/', 'type="button"', $str);
            $str = preg_replace('/href=".+?"/', 'href="javascript:alert(\''.$this->objLanguage->languageText('mod_cmsadmin_linkdisabled', 'cmsadmin', 'Link is Disabled.').'\');"', $str);
            
            echo '<div class="addblocks" id="'.$block['id'].'" style="border: 1px solid lightgray; padding: 5px; width:150px; float: left; z-index:1000;">'.$str.'</div>';
        }
    }
    echo '</div>';
?>

<br clear="left" />


<script language="javascript" type="text/javascript">
//<![CDATA[
    /*
    Function to add a block. This function is called everytime an unused block is dropped on the 'dropzone' div
    */
    function addBlock(element, dropon, event) {
        Droppables.remove($(element.id));
        Element.remove($(element.id));
    	sendData(element.id, 'adddynamicfrontpageblock', showAddResponse);
    }


    /*
    Function to remove a block. This function is called everytime an used block is dropped on the 'deletezone' div
    */
    function removeBlock(element, dropon, event) {
    	Droppables.remove($(element.id));
        Element.remove($(element.id));
    	sendData(element.id, 'removedynamicfrontpageblock', showDeleteResponse);
    }

    /*
    Ajax Function - Method to send the block to the server
    */
    function sendData (prod, action, responseFunction) {
    	var url    = 'index.php';
    	var rand   = Math.random(9999);
    	var pars   = 'module=cmsadmin&action='+action+'&blockid=' + prod + '&rand=' + rand;
    	var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onLoading: showLoad, onComplete: responseFunction} );
    }

    /*
    Method to show the loading icon, once the ajax function is processed
    */
    function showLoad () {
    	$('loading').style.visibility = "visible";
    }
    /*
    Method to show the Ajax Response once a block is added
    */
    function showAddResponse (originalRequest) {
    	$('loading').style.visibility = "hidden";
    	$('dropzone').innerHTML += originalRequest.responseText;
        setupAddBlocks();
        setupDeleteBlocks();
        adjustLayout();
    }
    /*
    Method to show the Ajax Response once a block is removed
    */
    function showDeleteResponse (originalRequest) {
    	$('loading').style.visibility = "hidden";
    	$('deletezone').innerHTML += originalRequest.responseText;
        setupAddBlocks();
        setupDeleteBlocks();
        adjustLayout();
    }
    /*
    Method to make the unused blocks draggable. Also sets up drop zone
    */
    function setupAddBlocks()
    {
    	var addblocks = document.getElementsByClassName('addblocks');
    	for (var i = 0; i < addblocks.length; i++) {
    		new Draggable(addblocks[i].id, {ghosting:false, revert:true, zIndex:100});	
    	}
    	Droppables.add('dropzone', {onDrop:addBlock, accept:'addblocks'});
    }
    /*
    Method to make the used blocks draggable. Also sets up drop zone
    */
    function setupDeleteBlocks()
    {   
        var deleteblocks = document.getElementsByClassName('usedblock');
    	for (var i = 0; i < deleteblocks.length; i++) {
    		new Draggable(deleteblocks[i].id, {ghosting:false, revert:true, zIndex:1000})	
    	}
    	Droppables.add('deletezone', {onDrop:removeBlock, accept:'usedblock'});
    }

    // Run for first time
    setupAddBlocks();
    setupDeleteBlocks();

//]]>
</script>
<style type="text/css">
div.addblocks div.featurebox, div.usedblock div.featurebox {
    margin: 0;
}
</style>
