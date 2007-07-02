<?php
/**
* Template for viewing section details in cmsadmin
*
* @author Warren Windvogel
* @package cmsadmin
*/

//Set layout template
$this->setLayoutTemplate('cms_layout_tpl.php');

//Load the link class
$this->loadClass('link', 'htmlelements');

//Create htmlheading for page title
$objH = $this->newObject('htmlheading', 'htmlelements');
$objH->type = '2';
//Create instance of geticon object
$objIcon = & $this->newObject('geticon', 'htmlelements');
//Setup Header Navigation objects

$objLayer =$this->newObject('layer','htmlelements');
$objRound =$this->newObject('roundcorners','htmlelements');
$headIcon = $this->newObject('geticon', 'htmlelements');
$headIcon->setIcon('section','png');
$this->loadClass('htmltable', 'htmlelements');


//Get blocks icon
$objIcon->setIcon('modules/blocks');
$objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_addremoveblocks', 'cmsadmin');
$blockIcon = $objIcon->show();

//Check if blocks module is registered
$this->objModule = &$this->newObject('modules', 'modulecatalogue');
$isRegistered = $this->objModule->checkIfRegistered('blocks');

//Get section data

if (isset($section)) {
    $sectionId = $section['id'];
    $title = $section['title'];
    $menuText = $section['menutext'];
    $layout = $section['layout'];
    $published = $section['published'];
    $description = $section['description'];
} else {
    $sectionId = '';
    $title = '';
    $menuText = '';
    $layout = '';
    $published = '';
    $description = '';
}

//Get layout icon
$layoutData = $this->_objLayouts->getLayout($layout);
$imageName = $layoutData['imagename'];
$imgPath = $this->getResourceUri($imageName,'cmsadmin');
$img = "<img src=\"{$imgPath}\" alt=\"'$imageName'\"/>";

$other = '<b>'.$this->objLanguage->languageText('mod_cmsadmin_treemenuname', 'cmsadmin').':'.'</b>'.'&nbsp;'.$menuText.'<br/>';

$other .= '<b>'.$this->objLanguage->languageText('mod_cmsadmin_visibleontreemenu', 'cmsadmin').':'.'</b>&nbsp;';

if ($this->_objUtils->sectionIsVisibleOnMenu($sectionId)) {
    $other .= $this->objLanguage->languageText('mod_cmsadmin_sectionwillbevisible', 'cmsadmin');
} else {
    $other .= $this->objLanguage->languageText('mod_cmsadmin_sectionwillnotbevisible', 'cmsadmin');
}

$other .= '<br/>';

$other .= '<br/>'.'&nbsp;'.'<br/>';

$other .= '<b>'.$this->objLanguage->languageText('mod_cmsadmin_pagesorderedby', 'cmsadmin').':'.'</b>&nbsp;'.$this->_objSections->getPageOrderType($section['ordertype']);


//Create table contain layout, visible, etc details
$objDetailsTable = new htmltable();
$objDetailsTable->cellspacing = '2';
$objDetailsTable->cellpadding = '2';
$objDetailsTable->startRow();
$objDetailsTable->addCell($img, '39%', 'top', 'center', '');
$objDetailsTable->addCell($other, '60%', 'top', 'left', '');
$objDetailsTable->endRow();

$tblDetails = $objDetailsTable->show();

//Create table for subsections
$objSubSecTable =  new htmltable();
$objSubSecTable->cellpadding = '2';
$objSubSecTable->cellspacing = '2';
$objSubSecTable->width = '99%';

//Create table header row
$objSubSecTable->startHeaderRow();
$objSubSecTable->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_menuname', 'cmsadmin'));
$objSubSecTable->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_nameofsection', 'cmsadmin'));
$objSubSecTable->addHeaderCell($this->objLanguage->languageText('word_pages'));
$objSubSecTable->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_displaytype', 'cmsadmin'));
$objSubSecTable->addHeaderCell($this->objLanguage->languageText('word_published'));
$objSubSecTable->addHeaderCell($this->objLanguage->languageText('word_order'));
$objSubSecTable->addHeaderCell($this->objLanguage->languageText('word_options'));
$objSubSecTable->endHeaderRow();

if (isset($subSections)) {
    $i = 0;
    foreach($subSections as $subsec) {
        //Set odd even row count variable

        $class = (($i++ % 2) == 0) ? 'odd' : 'even';
        //Get sub sec data
        $subSecId = $subsec['id'];
        $subSecTitle = htmlentities($subsec['title']);
        $subSecMenuText = htmlentities($subsec['menutext']);
        $subSecPublished = $subsec['published'];
        $subSecLayout = $this->_objLayouts->getLayout($subsec['layout']);
        $subSecLayoutName = $subSecLayout['name'];

	    //publish, visible
	    if($subSecPublished){
	       $url = $this->uri(array('action' => 'sectionpublish', 'id' => $subsec['id'], 'mode' => 'unpublish', 'sectionid' => $sectionId));
	       $icon = $this->_objUtils->getCheckIcon(TRUE);
	    }else{
	       $url = $this->uri(array('action' => 'sectionpublish', 'id' => $subsec['id'], 'mode' => 'publish', 'sectionid' => $sectionId));
	       $icon = $this->_objUtils->getCheckIcon(FALSE);
	    }
	    $objLink = new link($url);
	    $objLink->link = $icon;
	    $visibleIcon = $objLink->show();
	    
        //Create delete icon
        $delArray = array('action' => 'deletesection', 'confirm' => 'yes', 'id' => $subSecId);
        $deletephrase = $this->objLanguage->languageText('mod_cmsadmin_confirmdelsection', 'cmsadmin');
        $delIcon = $objIcon->getDeleteIconWithConfirm($subSecId, $delArray, 'cmsadmin', $deletephrase);
        //Create edit icon
        $editIcon = $objIcon->getEditIcon($this->uri(array('action' => 'addsection', 'id' => $subSecId)));
        //Make title link to view section
        $objLink = new link($this->uri(array('action' => 'viewsection', 'id' => $subSecId)));
        $objLink->link = $subSecMenuText;
        $viewSubSecLink = $objLink->show();

        //Add sub sec data to table
        $objSubSecTable->startRow();
        $objSubSecTable->addCell($viewSubSecLink, '', '', '', $class);
        $objSubSecTable->addCell($subSecTitle, '', '', '', $class);
        $objSubSecTable->addCell($this->_objContent->getNumberOfPagesInSection($subSecId), '', '', '', $class);
        $objSubSecTable->addCell($this->_objLayouts->getLayoutDescription($subSecLayoutName), '', '', '', $class);
        $objSubSecTable->addCell($visibleIcon, '', '', '', $class);
        $objSubSecTable->addCell($this->_objSections->getPageOrderType($section['ordertype']), '', '', '', $class);
        $objSubSecTable->addCell('<nobr>'.$editIcon.$delIcon.$this->_objSections->getOrderingLink($subSecId).'</nobr>', '', '', '', $class);
        $objSubSecTable->endRow();
    }
}
$tblSubSec = $objSubSecTable->show();

//Create table for pages
$objPagesTable = new htmltable();
$objPagesTable->cellpadding = '2';
$objPagesTable->cellspacing = '2';
$objPagesTable->width = '99%';

//Create table header row
$objPagesTable->startHeaderRow();
$objPagesTable->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_pagetitle', 'cmsadmin'));
$objPagesTable->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_articledate', 'cmsadmin'));
$objPagesTable->addHeaderCell($this->objLanguage->languageText('word_published'));
$objPagesTable->addHeaderCell($this->objLanguage->languageText('word_order'));
$objPagesTable->addHeaderCell($this->objLanguage->languageText('word_options'));
$objPagesTable->endHeaderRow();

if (!empty($pages)) {
    $i = 0;
    foreach($pages as $page) {
        //Set odd even row count variable

        $class = (($i++ % 2) == 0) ? 'odd' : 'even';

        //Get page data
        $pageId = $page['page_id'];
        $ordering = $page['co_order'];
        $pageTitle = htmlentities($page['title']);
        $articleDate = $page['modified'];
        $pagePublished = $page['published'];

        //publish, visible
	    if($pagePublished){
	       $url = $this->uri(array('action' => 'contentpublish', 'id' => $page['page_id'], 'mode' => 'unpublish', 'sectionid' => $sectionId));
	       $icon = $this->_objUtils->getCheckIcon(TRUE);
	    }else{
	       $url = $this->uri(array('action' => 'contentpublish', 'id' => $page['page_id'], 'mode' => 'publish', 'sectionid' => $sectionId));
	       $icon = $this->_objUtils->getCheckIcon(FALSE);
	    }
	    $objLink = new link($url);
	    $objLink->link = $icon;
	    $visibleIcon = $objLink->show();

        //Create delete icon
        $delArray = array('action' => 'trashcontent', 'confirm' => 'yes', 'id' => $pageId, 'sectionid' => $sectionId);
        $deletephrase = $this->objLanguage->languageText('mod_cmsadmin_confirmdelpage', 'cmsadmin');
        $delIcon = $objIcon->getDeleteIconWithConfirm($pageId, $delArray, 'cmsadmin', $deletephrase);
        //Create edit icon
        $editIcon = $objIcon->getEditIcon($this->uri(array('action' => 'addcontent', 'id' => $pageId, 'parent' => $sectionId)));
        //Make title link to view section
        $objLink = new link($this->uri(array('action' => 'showcontent', 'id' => $pageId, 'fromadmin' => TRUE, 'sectionid' => $sectionId), 'cms'));
        $objLink->link = $pageTitle;
        $viewPageLink = $objLink->show();

        //Icon for toggling front page status
        if(isset($page['front_id']) && !empty($page['front_id'])) {
            $objIcon->setIcon('greentick');
            $objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_confirmremovefromfp', 'cmsadmin');
            $url = array('action' => 'changefpstatus', 'id' => $page['front_id'], 'sectionid' => $sectionId, 'mode' => 'remove');
        } else {
            $objIcon->setIcon('redcross');
            $objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_addpagetofp', 'cmsadmin');
            $url = array('action' => 'changefpstatus', 'id' => $pageId, 'sectionid' => $sectionId, 'mode' => 'add');
        }
        $frontPageLink = new link($this->uri($url, 'cmsadmin'));
        $frontPageLink->link = $objIcon->show();

        // set up link to view contact details in a popup window
        $objBlocksLink = new link('#');
        $objBlocksLink->link = $blockIcon;
        $objBlocksLink->extra = "onclick = \"javascript:window.open('" . $this->uri(array('action' => 'addblock', 'sectionId' => $sectionId, 'pageid' => $pageId, 'blockcat' => 'content')) . "', 'branch', 'width=500, height=350, top=50, left=50, scrollbars')\"";

        //Add sub sec data to table
        $objPagesTable->startRow();
        $objPagesTable->addCell($pageTitle, '', '', '', $class);
        $objPagesTable->addCell($articleDate, '', '', '', $class);
        $objPagesTable->addCell($visibleIcon, '', '', '', $class);
        $objPagesTable->addCell($this->_objContent->getOrderingLink($sectionId, $pageId), '', '', '', $class);
        if ($isRegistered) {
            $objPagesTable->addCell('<nobr>'.$objBlocksLink->show().$frontPageLink->show().$editIcon.$delIcon.'</nobr>', '', '', '', $class);
        } else {
            $objPagesTable->addCell('<nobr>'.$frontPageLink->show().$editIcon.$delIcon.'</nobr>', '', '', '', $class);
        }
        $objPagesTable->endRow();

    }
}
$tblPages = $objPagesTable->show();

//Create add sub section icon
$objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_addsubsection','cmsadmin');
$addSubSecIcon = $objIcon->getLinkedIcon($this->uri(array('action' => 'addsection', 'parentid' => $sectionId)), 'create_folder');



//Create add page icon
$objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_addpage','cmsadmin');
$addPageIcon = $objIcon->getLinkedIcon($this->uri(array('action' => 'addcontent', 'parent' => $sectionId)), 'create_page');

//Create edit section icon
$editSectionIcon = $objIcon->getEditIcon($this->uri(array('action' => 'addsection', 'id' => $sectionId)));

//Create delete section icon
$delArray = array('action' => 'deletesection', 'confirm' => 'yes', 'id' => $sectionId);
$deletephrase = $this->objLanguage->languageText('mod_cmsadmin_confirmdelsection', 'cmsadmin');
$delIcon = $objIcon->getDeleteIconWithConfirm($sectionId, $delArray, 'cmsadmin', $deletephrase);

//Create add section link
$objNewSectionLink = new link($this->uri(array('action' => 'addsection', 'parentid' => $sectionId)));
$objNewSectionLink->link = $this->objLanguage->languageText('mod_cmsadmin_addnewsection', 'cmsadmin');

//Create add page link
$objNewPageLink = new link($this->uri(array('action' => 'addcontent', 'parent' => $sectionId)));
$objNewPageLink->link = $this->objLanguage->languageText('phrase_addanewpage');

//Add content to the output layer
$middleColumnContent = "";
if($isRegistered){
    if($layoutData['name'] == 'summaries' || $layoutData['name'] == 'list'){
        //Create add block link
        $objAddSectionBlockLink = new link('javascript:void(0)');
        $objAddSectionBlockLink->link = $blockIcon;
        $objAddSectionBlockLink->extra = "onclick = \"javascript:window.open('" . $this->uri(array('action' => 'addblock', 'sectionid' => $sectionId, 'blockcat' => 'section')) . "', 'branch', 'width=500, height=350, top=50, left=50, scrollbars')\"";
        //Set heading
        $objH->str = $headIcon->show().'&nbsp;'.$this->objLanguage->languageText('word_section').':'.'&nbsp;'.$title.'&nbsp;'.$objAddSectionBlockLink->show().'&nbsp;'.$editSectionIcon.'&nbsp;'.$delIcon;
    } else {
        $objH->str = $headIcon->show().'&nbsp;'.$this->objLanguage->languageText('word_section').':'.'&nbsp;'.$title.'&nbsp;'.$editSectionIcon.'&nbsp;'.$delIcon;
    }
} else {
    //Set heading
    $objH->str = $this->objLanguage->languageText('word_section').':'.'&nbsp;'.$title.'&nbsp;'.$editSectionIcon.'&nbsp;'.$delIcon;
}

$objLayer->str = $objH->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$objLayer->str = $topNav;
$objLayer->border = '; float:right; align:right; margin:0px; padding:0px;';
$header .= $objLayer->show();

$objLayer->str = '';
$objLayer->border = '; clear:both; margin:0px; padding:0px;';
$headShow = $objLayer->show();

$middleColumnContent .= $objRound->show($header.$headShow);//$tbl->show());

//Display layout info
$middleColumnContent .= $tblDetails;

//Sub sections table
$objH->str = $this->objLanguage->languageText('mod_cmsadmin_subsections', 'cmsadmin').'&nbsp;'.'('.$this->_objSections->getNumSubSections($sectionId).')'.'&nbsp;'.$addSubSecIcon;
$middleColumnContent .= '&nbsp;'.'<br/>';
$middleColumnContent .= $objH->show();
$middleColumnContent .= $tblSubSec;

if (empty($subSections)) {
    $middleColumnContent .= '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_cmsadmin_nosubsectionsfound', 'cmsadmin').'</div>';
}

//Pages table
$objH->str = $this->objLanguage->languageText('word_pages').'&nbsp;'.'('.$this->_objContent->getNumberOfPagesInSection($sectionId).')'.'&nbsp;'.$addPageIcon;
$middleColumnContent .= '&nbsp;'.'<br/>';
$middleColumnContent .= $objH->show();
$middleColumnContent .= $tblPages;

if (empty($pages)) {
    $middleColumnContent .= '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_cmsadmin_nopagesfoundinthissection', 'cmsadmin').'</div>';
}
$middleColumnContent .= '&nbsp;'.'<br/>';
$middleColumnContent .= $objNewSectionLink->show().'&nbsp;'.'/'.'&nbsp;'.$objNewPageLink->show();

echo $middleColumnContent;

?>