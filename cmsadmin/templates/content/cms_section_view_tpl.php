<?php
/**
* Template for viewing section details in cmsadmin
*
* @author Warren Windvogel
* @package cmsadmin
*/

//Set layout template
$this->setLayoutTemplate('cms_layout_tpl.php');

//Create htmlheading for page title
$objH = & $this->newObject('htmlheading', 'htmlelements');
$objH->type = '2';
//Create instance of geticon object
$objIcon = & $this->newObject('geticon', 'htmlelements');
//Create instance of link object
$objLink = & $this->newObject('link', 'htmlelements');

//Get section data

if (isset($section))
{
    $sectionId = $section['id'];
    $title = $section['title'];
    $menuText = $section['menutext'];
    $layout = $section['layout'];
    $published = $section['published'];
    $description = $section['description'];
}

//Get layout icon
$layoutData = $this->_objLayouts->getLayout($layout);

$img = '<img src="modules/cmsadmin/resources/'.$layoutData['imagename'].'" alt="'.$layoutData['imagename'].'"/>';

$other = '<b>'.$this->objLanguage->languageText('mod_cmsadmin_treemenuname', 'cmsadmin').':'.'</b>'.'&nbsp;'.$menuText.'<br/>';

$other .= '<b>'.$this->objLanguage->languageText('mod_cmsadmin_visibleontreemenu', 'cmsadmin').':'.'</b>&nbsp;';

if ($this->_objUtils->sectionIsVisibleOnMenu($sectionId))
{
    $other .= $this->objLanguage->languageText('mod_cmsadmin_sectionwillbevisible', 'cmsadmin');
} else
{
    $other .= $this->objLanguage->languageText('mod_cmsadmin_sectionwillnotbevisible', 'cmsadmin');
}

$other .= '<br/>';

$other .= '<br/>'.'&nbsp;'.'<br/>';


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

if (isset($subSections))
{
    $i = 0;
    foreach($subSections as $subsec) {
        //Set odd even row count variable

        $class = (($i++ % 2) == 0) ? 'odd' : 'even';
        //Get sub sec data
        $subSecId = $subsec['id'];
        $subSecTitle = $subsec['title'];
        $subSecMenuText = $subsec['menutext'];
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

        $objSubSecTable->addCell($subSecLayoutName, '', '', '', $class);

        $objSubSecTable->addCell($visibleIcon, '', '', '', $class);

        $objSubSecTable->addCell($this->_objSections->getOrderingLink($subSecId), '', '', '', $class);

        $objSubSecTable->addCell($editIcon.'&nbsp;'.$delIcon, '', '', '', $class);
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

$objPagesTable->addHeaderCell($this->objLanguage->languageText('word_number'));

$objPagesTable->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_pagetitle', 'cmsadmin'));

$objPagesTable->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_articledate', 'cmsadmin'));

$objPagesTable->addHeaderCell($this->objLanguage->languageText('word_visible'));

$objPagesTable->addHeaderCell($this->objLanguage->languageText('word_order'));

$objPagesTable->addHeaderCell($this->objLanguage->languageText('word_options'));

$objPagesTable->endHeaderRow();

if (count($pages) > '0')
{
    $i = 0;
    foreach($pages as $page) {
        //Set odd even row count variable

        $class = (($i++ % 2) == 0) ? 'odd' : 'even';

        //Get page data
        $pageId = $page['id'];
        $ordering = $page['ordering'];
        $pageTitle = $page['title'];
        $articleDate = $this->_objUtils->formatDate($page['modified']);
        $pagePublished = $page['published'];

        if ($pagePublished == '1') {
            $objIcon->setIcon('visible');
        } else {
            $objIcon->setIcon('not_visible');
        }

        $visibleIcon = $objIcon->show();

        //Create delete icon
        $delArray = array('action' => 'deletecontent', 'confirm' => 'yes', 'id' => $pageId, 'sectionid' => $sectionId);
        $deletephrase = $this->objLanguage->languageText('mod_cmsadmin_confirmdelpage', 'cmsadmin');
        $delIcon = $objIcon->getDeleteIconWithConfirm($pageId, $delArray, 'cmsadmin', $deletephrase);
        //Create edit icon
        $editIcon = $objIcon->getEditIcon($this->uri(array('action' => 'addcontent', 'id' => $pageId)));
        //Make title link to view section
        $objLink->link = $pageTitle;
        $objLink->href = $this->uri(array('action' => 'showcontent', 'id' => $pageId, 'fromadmin' => TRUE, 'sectionid' => $sectionId), 'cms');
        $viewPageLink = $objLink->show();

        //Add sub sec data to table
        $objPagesTable->startRow();

        $objPagesTable->addCell($i, '', '', '', $class);

        $objPagesTable->addCell($viewPageLink, '', '', '', $class);

        $objPagesTable->addCell($articleDate, '', '', '', $class);

        $objPagesTable->addCell($visibleIcon, '', '', '', $class);

        $objPagesTable->addCell($this->_objContent->getOrderingLink($sectionId, $pageId), '', '', '', $class);

        $objPagesTable->addCell($editIcon.'&nbsp;'.$delIcon, '', '', '', $class);
        $objPagesTable->endRow();

    }
}

//Create add sub section icon
$addSubSecIcon = $objIcon->getAddIcon($this->uri(array('action' => 'addsection', 'parentid' => $sectionId)));

//Create add page icon
$addPageIcon = $objIcon->getAddIcon($this->uri(array('action' => 'addcontent', 'parent' => $sectionId)));

//Create edit section icon
$editSectionIcon = $objIcon->getEditIcon($this->uri(array('action' => 'addsection', 'id' => $sectionId)));

//Add content to the output layer
$middleColumnContent = "";

$objH->str = $this->objLanguage->languageText('word_section').':'.'&nbsp;'.$title.'&nbsp;'.$editSectionIcon;

$middleColumnContent .= $objH->show();

//Display layout info
$middleColumnContent .= $objDetailsTable->show();

//Sub sections table
$objH->str = $this->objLanguage->languageText('mod_cmsadmin_subsections', 'cmsadmin').'&nbsp;'.'('.$this->_objSections->getNumSubSections($sectionId).')'.'&nbsp;'.$addSubSecIcon;

$middleColumnContent .= $objH->show();

$middleColumnContent .= $objSubSecTable->show();

if (empty($subSections))
{
    $middleColumnContent .= '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_cmsadmin_nosubsectionsfound', 'cmsadmin').'</div>';
}

//Pages table
$objH->str = $this->objLanguage->languageText('word_pages').'&nbsp;'.'('.$this->_objContent->getNumberOfPagesInSection($sectionId).')'.'&nbsp;'.$addPageIcon;

$middleColumnContent .= $objH->show();

$middleColumnContent .= $objPagesTable->show();

if (empty($pages))
{
    $middleColumnContent .= '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_cmsadmin_nopagesfoundinthissection', 'cmsadmin').'</div>';
}

echo $middleColumnContent;

?>
