<script type="text/javascript">
//<![CDATA[

function changeNav (type) {
	var url = 'index.php';
	var pars = 'module=contextcontent&action=changenavigation&id=<?php echo $currentPage; ?>&type='+type;
	var myAjax = new Ajax.Request( url, {method: 'get', parameters: pars, onComplete: showResponse} );
}

function showResponse (originalRequest) {
	var newData = originalRequest.responseText;
    
    if (newData != '') {
        $('contentnav').innerHTML = newData;
        adjustLayout();
    }
}
//]]>
</script>
<style type="text/css">
ul.htmlliststyle li {
    background: transparent url("skins/_common/icons/text.gif") no-repeat 6px 0 ;
}
</style>

<?php


$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$this->appendArrayVar('headerParams', $this->getJavaScriptFile('treemenu.js', 'tree'));

if (isset($currentChapter)) {

    
    
    if (!isset($currentChapterTitle)) {
        $currentChapterTitle = $this->objContextChapters->getContextChapterTitle($currentChapter);
    }
	
	if (!isset($currentPage)) {
        $currentPage = '';
    }
    
    $heading = new htmlheading();
    //$heading-> str = 'Chapter:<br />'.$currentChapterTitle;
    $heading->str = $currentChapterTitle;
    $heading->type = 3;
    
	$left = '<fieldset>
<legend>Search for: </legend>
<form id="form1" name="form1" method="post" action="">
  <label>
  <input type="text" name="textfield" />
  </label>
  <input name="Button" type="button" value="Go" onclick="alert(\'I dont work!\');" />
</form>
</fieldset>';
    $left .= $heading->show();

    $pageId = isset($currentPage) ? $currentPage : '';
    //$left .= $this->objContentOrder->getTree($this->contextCode, $currentChapter, 'htmllist', $pageId);
    
    $navigationType = $this->getSession('navigationType', 'twolevel');
    
    if ($navigationType == 'tree') {
        $left .= '<div id="contentnav">';
        
        
        $left .= $this->objContentOrder->getTree($this->contextCode, $currentChapter, 'htmllist', $pageId, 'contextcontent');
        
        
        $left .= '<p><a href="javascript:changeNav(\'twolevel\');">View as Index ...</a></p>';
        
        $left .= '</div>';
        
        
    
    } else {
        
        $left .= '<div id="contentnav">';
        
        
        $left .= $this->objContentOrder->getTwoLevelNav($this->contextCode, $currentChapter, $pageId);
        
        
        $left .= '<p><a href="javascript:changeNav(\'tree\');">View as Tree...</a></p>';
        
        $left .= '</div>';
    }
    
    if ($this->isValid('addpage')) {
        $addLink = new link ($this->uri(array('action'=>'addpage', 'chapter'=>$currentChapter, 'id'=>$currentPage)));
        $addLink->link = 'Add a Page';
        
        $left .= '<p>'.$addLink->show().'</p>';
    }
    
    $returnLink = new link ($this->uri(NULL));
    $returnLink->link = 'Return to Chapter List';
    
    $left .= '<p>'.$returnLink->show().'</p>';
    
    $objDBContext = $this->getObject('dbcontext', 'context');

	if($objDBContext->isInContext())
	{
	    $objModules = $this->getObject('modules', 'modulecatalogue');
        
        if ($objModules->checkIfRegistered('contextdesigner')) {
            $objContextUtils = & $this->getObject('utilities','context');
            $left .= $objContextUtils->getHiddenContextMenu('eventscalendar','show');
        }
	}	
	//add the blog block
	$objBlocks =  $this->getObject('blocks', 'blocks');
	$left .= $objBlocks->showBlock('latest', 'blog');
    
    $cssLayout = $this->newObject('csslayout', 'htmlelements');
    $cssLayout->setLeftColumnContent($left);
    $cssLayout->setMiddleColumnContent($this->getContent());
    echo $cssLayout->show();

} else {
    echo $this->getContent();
}
?>