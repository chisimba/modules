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
$objH = & $this->newObject('htmlheading', 'htmlelements');
$objH->type = '2';
//Create instance of geticon object
$objIcon = & $this->newObject('geticon', 'htmlelements');
//Create instance of link object
$objLink = & $this->newObject('link', 'htmlelements');
//Setup Header Navigation objects

$objLayer =$this->newObject('layer','htmlelements');
$objRound =$this->newObject('roundcorners','htmlelements');
$headIcon = $this->newObject('geticon', 'htmlelements');
$headIcon->setIcon('section','png');
$tbl = $this->newObject('htmltable', 'htmlelements');


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
$img = "<img src=\"{$this->getResourceUri('$imageName','cmsadmin')}\" alt=\"'$imageName'\"/>";

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
$objDetailsTable = & $this->newObject('htmltable', 'htmlelements');

$objDetailsTable->cellspacing = '2';

$objDetailsTable->cellpadding = '2';

$objDetailsTable->startRow();

$objDetailsTable->addCell($img, '39%', 'top', 'center', '');

$objDetailsTable->addCell($other, '60%', 'top', 'left', '');

$objDetailsTable->endRow();

//Create table for subsections
$objSubSecTable = & $this->newObject('htmltable', 'htmlelements');

$objSubSecTable->cellpadding = '2';

$objSubSecTable->cellspacing = '2';

$objSubSecTable->width = '99%';

//Create table header row
$objSubSecTable->startHeaderRow();
$objSubSecTable->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_menuname', 'cmsadmin'));
$objSubSecTable->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_nameofsection', 'cmsadmin'));
$objSubSecTable->addHeaderCell($this->objLanguage->languageText('word_pages'));
$objSubSecTable->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_displaytype', 'cmsadmin'));
$objSubSecTable->addHeaderCell($this->objLanguage->languageText('word_visible'));
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
        //Get $visivle icon

        if ($subSecPublished == '1') {
            $objIcon->setIcon('visible');
        } else {
            $objIcon->setIcon('not_visible');
        }

        $visibleIcon = $objIcon->show();
        //Create delete icon
        $delArray = array('action' => 'deletesection', 'confirm' => 'yes', 'id' => $subSecId);
        $deletephrase = $this->objLanguage->languageText('mod_cmsadmin_confirmdelsection', 'cmsadmin');
        $delIcon = $objIcon->getDeleteIconWithConfirm($subSecId, $delArray, 'cmsadmin', $deletephrase);
        //Create edit icon
        $editIcon = $objIcon->getEditIcon($this->uri(array('action' => 'addsection', 'id' => $subSecId)));
        //Make title link to view section
        $objLink->link = $subSecMenuText;
        $objLink->href = $this->uri(array('action' => 'viewsection', 'id' => $subSecId));
        $viewSubSecLink = $objLink->show();

        //Add sub sec data to table
        $objSubSecTable->startRow();
        $objSubSecTable->addCell($viewSubSecLink, '', '', '', $class);
        $objSubSecTable->addCell($subSecTitle, '', '', '', $class);
        $objSubSecTable->addCell($this->_objContent->getNumberOfPagesInSection($subSecId), '', '', '', $class);
        $objSubSecTable->addCell($this->_objLayouts->getLayoutDescription($subSecLayoutName), '', '', '', $class);
        $objSubSecTable->addCell($visibleIcon, '', '', '', $class);
        $objSubSecTable->addCell($this->_objSections->getPageOrderType($section['ordertype']), '', '', '', $class);
        $objSubSecTable->addCell($editIcon.'&nbsp;'.$delIcon.'&nbsp;'.$this->_objSections->getOrderingLink($subSecId), '', '', '', $class);
        $objSubSecTable->endRow();
    }
}

//Create table for pages
$objPagesTable = & $this->newObject('htmltable', 'htmlelements');

$objPagesTable->cellpadding = '2';

$objPagesTable->cellspacing = '2';

$objPagesTable->width = '99%';

//Create table header row
$objPagesTable->startHeaderRow();

$objPagesTable->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_pagetitle', 'cmsadmin'));

$objPagesTable->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_articledate', 'cmsadmin'));

$objPagesTable->addHeaderCell($this->objLanguage->languageText('word_visible'));

$objPagesTable->addHeaderCell($this->objLanguage->languageText('word_order'));

$objPagesTable->addHeaderCell($this->objLanguage->languageText('word_options'));

$objPagesTable->endHeaderRow();

if (count($pages) > '0') {
    $i = 0;
    foreach($pages as $page) {
        //Set odd even row count variable

        $class = (($i++ % 2) == 0) ? 'odd' : 'even';

        //Get page data
        $pageId = $page['id'];
        $ordering = $page['ordering'];
        $pageTitle = htmlentities($page['title']);
        $articleDate = $page['modified'];
        $pagePublished = $page['published'];

        if ($pagePublished == '1') {
            $objIcon->setIcon('visible');
        } else {
            $objIcon->setIcon('not_visible');
        }

        $visibleIcon = $objIcon->show();

        //Create delete icon
        $delArray = array('action' => 'trashcontent', 'confirm' => 'yes', 'id' => $pageId, 'sectionid' => $sectionId);
        $deletephrase = $this->objLanguage->languageText('mod_cmsadmin_confirmdelpage', 'cmsadmin');
        $delIcon = $objIcon->getDeleteIconWithConfirm($pageId, $delArray, 'cmsadmin', $deletephrase);
        //Create edit icon
        $editIcon = $objIcon->getEditIcon($this->uri(array('action' => 'addcontent', 'id' => $pageId, 'parent' => $sectionId)));
        //Make title link to view section
        $objLink->link = $pageTitle;
        $objLink->href = $this->uri(array('action' => 'showcontent', 'id' => $pageId, 'fromadmin' => TRUE, 'sectionid' => $sectionId), 'cms');
        $viewPageLink = $objLink->show();

        //Icon for toggling front page status
        $frontPageLink =& $this->newObject('link', 'htmlelements');
        $frontPageLink->href = $this->uri(array('action' => 'changefpstatus', 'pageid' => $pageId, 'sectionid' => $sectionId), 'cmsadmin');
        if($this->_objFrontPage->isFrontPage($pageId)) {
            $objIcon->setIcon('greentick');
            $objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_addpagetofp', 'cmsadmin');
            $frontPageLink->title = $this->objLanguage->languageText('mod_cmsadmin_confirmremovefromfp', 'cmsadmin');
        } else {
            $frontPageLink->title = $this->objLanguage->languageText('mod_cmsadmin_addpagetofp', 'cmsadmin');
            $objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_addpagetofp', 'cmsadmin');
            $objIcon->setIcon('redcross');
        }
        $frontPageLink->link = $objIcon->show();

        // set up link to view contact details in a popup window
        $objBlocksLink = new link('#');
        $objBlocksLink -> link = $blockIcon;
        $objBlocksLink -> extra = "onclick = \"javascript:window.open('" . $this->uri(array('action' => 'addblock', 'sectionId' => $sectionId, 'pageid' => $pageId, 'blockcat' => 'content')) . "', 'branch', 'width=500, height=350, top=50, left=50, scrollbars')\"";

        //Add sub sec data to table
        $objPagesTable->startRow();
        $objPagesTable->addCell($pageTitle, '', '', '', $class);
        $objPagesTable->addCell($articleDate, '', '', '', $class);
        $objPagesTable->addCell($visibleIcon, '', '', '', $class);
        $objPagesTable->addCell($this->_objContent->getOrderingLink($sectionId, $pageId), '', '', '', $class);
        if ($isRegistered) {
            $objPagesTable->addCell($objBlocksLink->show().'&nbsp;'.$frontPageLink->show().'&nbsp;'.$editIcon.'&nbsp;'.$delIcon, '', '', '', $class);
        } else {
            $objPagesTable->addCell($frontPageLink->show().'&nbsp;'.$editIcon.'&nbsp;'.$delIcon, '', '', '', $class);
        }
        $objPagesTable->endRow();

    }
}

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
$objNewSectionLink =& $this->newObject('link', 'htmlelements');
$objNewSectionLink->link = $this->objLanguage->languageText('mod_cmsadmin_addnewsection', 'cmsadmin');
$objNewSectionLink->href = $this->uri(array('action' => 'addsection', 'parentid' => $sectionId));

//Create add page link
$objNewPageLink =& $this->newObject('link', 'htmlelements');
$objNewPageLink->link = $this->objLanguage->languageText('phrase_addanewpage');
$objNewPageLink->href = $this->uri(array('action' => 'addcontent', 'parent' => $sectionId));

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
/*
//attach heading to navigation table
$tbl->startRow();
$tbl->addCell( $objH->show());
$tbl->addCell($topNav);
$tbl->endRow();
*/

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
$middleColumnContent .= $objDetailsTable->show();

//Sub sections table
$objH->str = $this->objLanguage->languageText('mod_cmsadmin_subsections', 'cmsadmin').'&nbsp;'.'('.$this->_objSections->getNumSubSections($sectionId).')'.'&nbsp;'.$addSubSecIcon;
$middleColumnContent .= '&nbsp;'.'<br/>';
$middleColumnContent .= $objH->show();
$middleColumnContent .= $objSubSecTable->show();

if (empty($subSections)) {
    $middleColumnContent .= '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_cmsadmin_nosubsectionsfound', 'cmsadmin').'</div>';
}

//Pages table
$objH->str = $this->objLanguage->languageText('word_pages').'&nbsp;'.'('.$this->_objContent->getNumberOfPagesInSection($sectionId).')'.'&nbsp;'.$addPageIcon;
$middleColumnContent .= '&nbsp;'.'<br/>';
$middleColumnContent .= $objH->show();
$middleColumnContent .= $objPagesTable->show();

if (empty($pages)) {
    $middleColumnContent .= '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_cmsadmin_nopagesfoundinthissection', 'cmsadmin').'</div>';
}
$middleColumnContent .= '&nbsp;'.'<br/>';
$middleColumnContent .= $objNewSectionLink->show().'&nbsp;'.'/'.'&nbsp;'.$objNewPageLink->show();

echo $middleColumnContent;

?>
