<?php

// Show Form
echo $addEditForm;


$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('loading_circles_big');

if ($id != '') {

    $objModuleBlocks =& $this->getObject('dbmoduleblocks', 'modulecatalogue');
    $objCMSBlocks =& $this->getObject('dbblocks', 'cmsadmin');
    $objBlocks =& $this->getObject('blocks', 'blocks');

    $this->objScriptaculous =& $this->getObject('scriptaculous', 'ajaxwrapper');
    $this->objScriptaculous->show();

    $blocks = $objModuleBlocks->getBlocks('normal');
    $thisPageBlocks = $objCMSBlocks->getBlocksForPage($id);
    
    echo '<div id="dropzone" style="border: 1px dashed black; background-color: lightyellow; position: relative"><h3>'.$this->objLanguage->languageText('mod_cmsadmin_pageblocks', 'cmsadmin', 'Page Blocks').'</h3><p>'.$this->objLanguage->languageText('mod_cmsadmin_dragaddblocks', 'cmsadmin', 'Drag and drop the blocks you want to add here.').'</p>';
    
    $usedBlocks = array();
    
    foreach ($thisPageBlocks as $block)
    {
        $str = trim($objBlocks->showBlock($block['blockname'], $block['moduleid']));
        $str = preg_replace('/type\\s??=\\s??"submit"/', 'type="button"', $str);
        $str = preg_replace('/href=".+?"/', 'href="javascript:alert(\''.$this->objLanguage->languageText('mod_cmsadmin_pageblocks', 'cmsadmin', 'Page Blocks').'</h3><p>'.$this->objLanguage->languageText('mod_cmsadmin_linkdisabled', 'cmsadmin', 'Link is Disabled.').'\');"', $str);
        
        $usedBlocks[] = $block['blockid'];
        
        echo '<div class="usedblock" id="'.$block['blockid'].'" style="border: 1px solid lightgray; padding: 5px; width:150px; float: left; z-index:1000;">'.$str.'</div>';
    }
    echo '</div>';

    echo '<br clear="left" /><br /><br />';
    
    echo '<div id="loading" style="visibility:visible; float: right;">'.$objIcon->show().'</div>';
    
    echo '<div id="deletezone" style="border: 1px dashed black; background-color: lightyellow; position: relative"><h3>'.$this->objLanguage->languageText('mod_cmsadmin_availableblocks', 'cmsadmin', 'Available Blocks').'</h3><p>'.$this->objLanguage->languageText('mod_cmsadmin_dragremoveblocks', 'cmsadmin', 'Drag and drop the blocks you want to remove here.').'</p>';
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