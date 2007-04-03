<?php
$objIcon = $this->newObject('geticon', 'htmlelements');
$tbl = $this->newObject('htmltable', 'htmlelements');
$h3 = $this->getObject('htmlheading', 'htmlelements');
$Icon = $this->newObject('geticon', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');
$Icon->setIcon('loading_circles_big');
$objRound =$this->newObject('roundcorners','htmlelements');
$objIcon->setIcon('add_article', 'png', 'icons/cms/');
if(isset($id))
{
	$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('mod_cmsadmin_editcontentitem', 'cmsadmin');	
}
else {
	$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('mod_cmsadmin_contentitem', 'cmsadmin').':'.'&nbsp;'.$this->objLanguage->languageText('word_new');
}
/*
$tbl->startRow();
$tbl->addCell($h3->show());
$tbl->addCell($topNav);
$tbl->endRow();
*/

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$objLayer->str = $topNav;
$objLayer->border = '; float:right; align:right; margin:0px; padding:0px;';
$header .= $objLayer->show();

$objLayer->str = '';
$objLayer->border = '; clear:both; margin:0px; padding:0px;';
$headShow = $objLayer->show();

$display = $objRound->show($header.$headShow);//$tbl->show());
//Show Header
echo $display;
// Show Form
echo $addEditForm;

if ($id != '') {

    $objModuleBlocks = $this->getObject('dbmoduleblocks', 'modulecatalogue');
    $objCMSBlocks = $this->getObject('dbblocks', 'cmsadmin');
    $objBlocks = $this->getObject('blocks', 'blocks');

//    $this->objScriptaculous =& $this->getObject('scriptaculous', 'ajaxwrapper');
//    $this->objScriptaculous->show();

    $blocks = $objModuleBlocks->getBlocks('normal');
    $thisPageBlocks = $objCMSBlocks->getBlocksForPage($id);
    
    $display = '<div id="dropzone" style="border: 1px dashed black; background-color: lightyellow; position: relative"><h3>'.$this->objLanguage->languageText('mod_cmsadmin_pageblocks', 'cmsadmin', 'Page Blocks').'</h3><p>'.$this->objLanguage->languageText('mod_cmsadmin_dragaddblocks', 'cmsadmin', 'Drag and drop the blocks you want to add here.').'</p>';
    
    $usedBlocks = array();
    
    foreach ($thisPageBlocks as $block)
    {
        $str = trim($objBlocks->showBlock($block['blockname'], $block['moduleid']));
        $str = preg_replace('/type\\s??=\\s??"submit"/', 'type="button"', $str);
        $str = preg_replace('/href=".+?"/', 'href="javascript:alert(\''.$this->objLanguage->languageText('mod_cmsadmin_pageblocks', 'cmsadmin', 'Page Blocks').'</h3><p>'.$this->objLanguage->languageText('mod_cmsadmin_linkdisabled', 'cmsadmin', 'Link is Disabled.').'\');"', $str);
        
        $usedBlocks[] = $block['blockid'];
        
        $display .= '<div class="usedblock" id="'.$block['blockid'].'" style="border: 1px solid lightgray; padding: 5px; width:150px; float: left; z-index:1000;">'.$str.'</div>';
    }
    $display .= '</div>';

    $display .= '<br clear="left" /><br /><br />';
    
    $display .= '<div id="loading" style="visibility:visible; float: right;">'.$Icon->show().'</div>';
    
    $display .= '<div id="deletezone" style="border: 1px dashed black; background-color: lightyellow; position: relative"><h3>'.$this->objLanguage->languageText('mod_cmsadmin_availableblocks', 'cmsadmin', 'Available Blocks').'</h3><p>'.$this->objLanguage->languageText('mod_cmsadmin_dragremoveblocks', 'cmsadmin', 'Drag and drop the blocks you want to remove here.').'</p>';
    foreach ($blocks as $block)
    {
        if (!in_array($block['id'], $usedBlocks)) {
            $str = trim($objBlocks->showBlock($block['blockname'], $block['moduleid']));
            $str = preg_replace('/type\\s??=\\s??"submit"/', 'type="button"', $str);
            $str = preg_replace('/href=".+?"/', 'href="javascript:alert(\''.$this->objLanguage->languageText('mod_cmsadmin_linkdisabled', 'cmsadmin', 'Link is Disabled.').'\');"', $str);
            
            $display .= '<div class="addblocks" id="'.$block['id'].'" style="border: 1px solid lightgray; padding: 5px; width:150px; float: left; z-index:1000;">'.$str.'</div>';
        }
    }
    $display .= '</div>';
    //Show Blocks
    echo $display;
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
    	sendData(element.id, 'adddynamicpageblock', showAddResponse);
    }


    /*
    Function to remove a block. This function is called everytime an used block is dropped on the 'deletezone' div
    */
    function removeBlock(element, dropon, event) {
    	Droppables.remove($(element.id));
        Element.remove($(element.id));
    	sendData(element.id, 'removedynamicpageblock', showDeleteResponse);
    }

    /*
    Ajax Function - Method to send the block to the server
    */
    function sendData (prod, action, responseFunction) {
    	var url    = 'index.php';
    	var rand   = Math.random(9999);
    	var pars   = 'module=cmsadmin&action='+action+'&pageid=<?php echo $id; ?>&sectionid=<?php echo $section; ?>&blockid=' + prod + '&rand=' + rand;
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
    		new Draggable(addblocks[i].id, {ghosting:false, revert:true, zindex:2000});	
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
    		new Draggable(deleteblocks[i].id, {ghosting:false, revert:true, zindex:20})	
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
<?php } ?>