<?php
/**
 * This template will list all the sections 
 */

//initiate objects
$table = & $this->newObject('htmltable', 'htmlelements');
$objH = &$this->newObject('htmlheading', 'htmlelements');
$link = & $this->newObject('link', 'htmlelements');
$objIcon = & $this->newObject('geticon', 'htmlelements');


//create a heading
$objH->type = '1';
$objH->str = $this->objLanguage->languageText('mod_cmsadmin_sectionmanager', 'cmsadmin').'&nbsp;'.$objIcon->getAddIcon($this->uri(array('action' => 'addsection')));
//counter for records
$cnt = 1;

//get the sections
if($viewType == 'root')
{
    $arrSections = $this->_objSections->getRootNodes();
} else
{
    $arrSections = $this->_objUtils->getSectionLinks(TRUE);
}

//Get cms type
$cmsType = 'treeMenu';

//setup the table headings
$table->startHeaderRow();
$table->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_menuname', 'cmsadmin'));
$table->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_nameofsection', 'cmsadmin'));
$table->addHeaderCell($this->objLanguage->languageText('word_pages'));
$table->addHeaderCell($this->objLanguage->languageText('mod_cmsadmin_displaytype', 'cmsadmin'));
$table->addHeaderCell($this->objLanguage->languageText('word_order'));
$table->addHeaderCell($this->objLanguage->languageText('word_visible'));
$table->addHeaderCell($this->objLanguage->languageText('word_options'));
$table->endHeaderRow();

$rowcount = 0;

//setup the tables rows  and loop though the records
foreach($arrSections as $section)
{
    //Set odd even row colour
    $oddOrEven = ($rowcount == 0) ? "even" : "odd";
    if($viewType == 'all') {
        $pref = "";
        $matches = split('<', $section['title']);
        $img = split('>', $matches[1]);
        $image = '<'.$img[0].'>';
        $linkText = $img[1];
        $noSpaces = strlen($matches[0]);

        for ($i = 1; $i < $noSpaces; $i++) {
            $pref .= '&nbsp;&nbsp;';
        }
        $pref .= $image;

        $section = $this->_objSections->getSection($section['id']);
        //View section link
        $link->link = $linkText;
        $link->href = $this->uri(array('action' => 'viewsection', 'id' => $section['id']));
        $viewSectionLink = $pref.$link->show();
    } else {
        $link->link = $section['menutext'];
        $link->href = $this->uri(array('action' => 'viewsection', 'id' => $section['id']));
        $viewSectionLink = $link->show();
    }

    //publish, visible
    $link->href = $this->uri(array('action' => 'sectionpublish', 'id' => $section['id']));
    $link->link = $this->_objUtils->getCheckIcon($section['published']);
    $visibleLink = $link->show();

    //Create delete icon
    $delArray = array('action' => 'deletesection', 'confirm'=>'yes', 'id'=>$section['id']);
    $deletephrase = $this->objLanguage->languageText('mod_cmsadmin_confirmdelsection', 'cmsadmin');
    $delIcon = $objIcon->getDeleteIconWithConfirm($section['id'], $delArray,'cmsadmin',$deletephrase);

    //edit icon
    $editIcon = $objIcon->getEditIcon($this->uri(array('action' => 'addsection', 'id' => $section['id'])));

    $tableRow = array();
    $tableRow[] = $viewSectionLink;
    $tableRow[] = $section['title'];
    $tableRow[] = $this->_objContent->getNumberOfPagesInSection($section['id']);
    $tableRow[] = $this->_objLayouts->getLayoutDescription($section['layout']);
    $tableRow[] = $this->_objSections->getPageOrderType($section['ordertype']);
    $tableRow[] = $visibleLink;
    if($viewType == 'root') {
        $tableRow[] = $editIcon.'&nbsp;'.$delIcon.'&nbsp;'.'&nbsp;'.'&nbsp;'.$this->_objSections->getOrderingLink($section['id']);
    } else {
        $tableRow[] = $editIcon.'&nbsp;'.$delIcon;
    }

    $table->addRow($tableRow, $oddOrEven);

    $rowcount = ($rowcount == 0) ? 1 : 0;

}

//Link to switch between root nodes and all nodes
$objViewAllLink =& $this->newObject('link', 'htmlelements');
if($viewType == 'root')
{
    $objViewAllLink->link = $this->objLanguage->languageText('mod_cmsadmin_viewsummaryallsections', 'cmsadmin');
    $objViewAllLink->href = $this->uri(array('action' => 'sections', 'viewType' => 'all'), 'cmsadmin');
} else
{
    $objViewAllLink->link = $this->objLanguage->languageText('mod_cmsadmin_viewrootsectionsonly', 'cmsadmin');
    $objViewAllLink->href = $this->uri(array('action' => 'sections', 'viewType' => 'root'), 'cmsadmin');
}
//Create new section link
$objAddSectionLink =& $this->newObject('link', 'htmlelements');
$objAddSectionLink->href = $this->uri(array('action' => 'addsection'), 'cmsadmin');
$objAddSectionLink->link = $this->objLanguage->languageText('mod_cmsadmin_createnewsection', 'cmsadmin');

//print out the page
$middleColumnContent = "";
$middleColumnContent .= $objH->show();
$middleColumnContent .= '&nbsp;'.'<br/>';
$middleColumnContent .= $table->show();
$middleColumnContent .= '&nbsp;'.'<br/>';
$middleColumnContent .= $objViewAllLink->show().'&nbsp;'.'/'.'&nbsp;'.$objAddSectionLink->show();

echo $middleColumnContent;

?>
