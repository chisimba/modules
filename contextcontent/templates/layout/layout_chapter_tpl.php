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
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');


if (isset($currentChapter)) {

    
    
    if (!isset($currentChapterTitle)) {
        $currentChapterTitle = $this->objContextChapters->getContextChapterTitle($currentChapter);
    }
    
    if (!isset($currentPage)) {
        $currentPage = '';
    }
    
    $heading = new htmlheading();
    
    $heading->str = $currentChapterTitle;
    $heading->type = 3;
    
    $form = new form ('searchform', $this->uri(array('action'=>'search')));
    $form->method = 'GET';
    
    $hiddenInput = new hiddeninput('module', 'contextcontent');
    $form->addToForm($hiddenInput->show());
    
    $hiddenInput = new hiddeninput('action', 'search');
    $form->addToForm($hiddenInput->show());
    
    $textinput = new textinput ('contentsearch');
    $button = new button ('searchgo', 'Go');
    $button->setToSubmit();
    
    $form->addToForm($textinput->show().' '.$button->show());
    
    $objFieldset = $this->newObject('fieldset', 'htmlelements');
    $label = new label ($this->objLanguage->languageText('mod_forum_searchfor', 'forum', 'Search for').':', 'input_contentsearch');
    
    $objFieldset->setLegend($label->show());
    $objFieldset->contents = $form->show();
    
    
    $left = $objFieldset->show();
    
    $pageId = isset($currentPage) ? $currentPage : '';
    $left .= $heading->show();
    
    $navigationType = $this->getSession('navigationType', 'tree');
    
    if ($navigationType == 'tree') {
        $left .= '<div id="contentnav">';
        $left .= $this->objContentOrder->getTree($this->contextCode, $currentChapter, 'htmllist', $pageId, 'contextcontent');
        $left .= '<p><a href="javascript:changeNav(\'twolevel\');">'.$this->objLanguage->languageText('mod_contextcontent_viewtwolevels', 'contextcontent', 'View Two Levels at a time').' ...</a><br /><a href="javascript:changeNav(\'bookmarks\');">'.$this->objLanguage->languageText('mod_contextcontent_viewbookmarkedpages', 'contextcontent', 'View Bookmarked Pages').'</a></p>';
        
        $left .= '</div>';
    }  else if ($navigationType == 'bookmarks') {
        $left .= '<div id="contentnav">';
        $left .= $this->objContentOrder->getBookmarkedPages($this->contextCode, $currentChapter, $pageId);
        $left .= '<p><a href="javascript:changeNav(\'twolevel\');">'.$this->objLanguage->languageText('mod_contextcontent_viewtwolevels', 'contextcontent', 'View Two Levels at a time').' ...</a><br /><a href="javascript:changeNav(\'tree\');">'.$this->objLanguage->languageText('mod_contextcontent_viewastree', 'contextcontent', 'View as Tree').'...</a></p>';
                
        $left .= '</div>';
    }else {
        $left .= '<div id="contentnav">';
        $left .= $this->objContentOrder->getTwoLevelNav($this->contextCode, $currentChapter, $pageId);
        $left .= '<p><a href="javascript:changeNav(\'tree\');">'.$this->objLanguage->languageText('mod_contextcontent_viewastree', 'contextcontent', 'View as Tree').'...</a>';
        $left .= '<br /><a href="javascript:changeNav(\'bookmarks\');">'.$this->objLanguage->languageText('mod_contextcontent_viewbookmarkedpages', 'contextcontent', 'View Bookmarked Pages').'</a></p>';
        
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
    
    
    $cssLayout = $this->newObject('csslayout', 'htmlelements');
    $cssLayout->setLeftColumnContent($left);
    $cssLayout->setMiddleColumnContent($this->getContent());
    echo $cssLayout->show();

} else {
    echo $this->getContent();
}
?>