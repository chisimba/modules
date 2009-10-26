<?php
// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end security check
//Load HTMl Objet Classes

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
$objLayer = $this->newObject('layer', 'htmlelements');
$authortable =  $this->newObject('htmltable', 'htmlelements');

/*****
*New Stuff Added
*/
$objIcon = $this->newObject('geticon', 'htmlelements');
//Edit record
$objIcon->setIcon('edit');
$objIcon->alt = 'Edit';
$objIcon->title = 'Edit';
$editIcon = $objIcon->show();

//Delete record Icon
$objIcon->setIcon('delete');
$objIcon->alt = 'Delete';
$objIcon->title = 'Delete';
$deleteIcon = $objIcon->show();

//Add Record Icon
$objIcon->setIcon('add');
$objIcon->align = 'top';
$objIcon->alt = 'Add New Journal Articles';
$objIcon->title = 'Add Journal Articles';

$link = new link($this->uri(array('action'=>'DOE Accredoted Journal Articles')));
$link->link = $objIcon->show();

$addlink = new link($this->uri(array('action'=>'DOE Accredoted Journal Articles')));
$addlink->link = 'Add New Journal Articles';

/*
*End New Stuf
*/

$table = new htmltable();
$table->cellspacing = '2';
$table->cellpadding = '5';

//setup the table headings
$table->startHeaderRow();

$h3 = $this->getObject('htmlheading', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');

$h3->str =$this->objLanguage->languageText('mod_rimfhe_pgheadingdisplayjournal', 'rimfhe');

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$display = '<p>'.$header.'&nbsp;&nbsp;&nbsp; '.$link->show().'</p><hr />';

//Show Header
echo $display;
//update notification
$updateComment = $this->getParam('comment');
if(!empty($updateComment)){
    echo '<span style="color:#D00000">'.$updateComment.'</span>';
    echo '<br /><br />';
}

//delete notification
$deleteComment = $this->getParam('deletecomment');
if(!empty($deleteComment)){
    echo '<span style="color:#D00000;">'.$deleteComment.'</span>';
    echo '<br /><br />';
}

//Set up fields heading
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_categorey', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_journalname', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_atitcletitle', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_year', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_volume', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_firstpage', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_lastpage', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_pagetotal', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_authors', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_fraction', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_editlink', 'rimfhe'));
$table->addHeaderCell($this->objLanguage->languageText('mod_rimfhe_deletelink', 'rimfhe'));
$table->endHeaderRow();

$rowcount = 0;

//setup the tables rows  and loop though the records
if ( count($arrJournal) > 0) {

    foreach($arrJournal as $journal) {
        //Set odd even row colour
        $oddOrEven = ($rowcount == 0) ? "even" : "odd";
        $tableRow = array();
        $journalname = $this->objDBJournal->listSingle($journal['journalname']);      
        $tableRow[] = $journal['journalcategory'];
        $tableRow[] = $journalname[0]['journal'];
        $tableRow[] = $journal['articletitle'];
        $tableRow[] = $journal['publicationyear'];
        $tableRow[] = $journal['volume'];
        $tableRow[] = $journal['firstpageno'];
        $tableRow[] = $journal['lastpageno'];
        $tableRow[] = $journal['pagetotal'];
        $tableRow[] = $journal['authorname'];
        $tableRow[] = $journal['fractweightedavg'];

        $editlink = new link($this->uri(array('action'=>'Edit Journal Articles', 'id'=> $journal['id'])));
        $editlink->link = $editIcon;
        $tableRow[] = $editlink->show();

        $delArray = array('action' => 'deletejournalarticle', 'confirm'=>'yes', 'id'=>$journal['id']);
        $title = $journal['articletitle'];
        $rep = array('TITLE' => $title);
        $deletephrase = $this->objLanguage->code2Txt('mod_confirm_delete', 'rimfhe', $rep );
        $deleteIcon = $objIcon->getDeleteIconWithConfirm($journal['id'], $delArray,'rimfhe',$deletephrase);
        $tableRow[] = $deleteIcon;

        $table->addRow($tableRow, $oddOrEven);

        $rowcount = ($rowcount == 0) ? 1 : 0;

    }
}
else{
    echo  '<div class="noRecordsMessage">'.$objLanguage->languageText('mod_rimfhe_norecord', 'rimfhe').'</div>';

}
echo $table->show();
echo '<p>'.'&nbsp;'.'</p>';
echo '<p>'.$addlink->show().'</p>';
echo '<p>'.'&nbsp;'.'</p>';
?>

