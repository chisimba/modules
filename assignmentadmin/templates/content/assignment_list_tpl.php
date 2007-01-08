<?
/**
* Template for listing submitted assignments to be marked.
* @package assignmentadmin
*/

/**
* @param array $assign The details of the assignment.
* @param array $data Array contains the details of the submitted assignments.
*/

/**************** Set Layout template ***************************/
$this->setLayoutTemplate('assignmentadmin_layout_tpl.php');

// set up html elements
$objTable = $this->newObject('htmltable','htmlelements');
$objTable2 = $this->newObject('htmltable','htmlelements');
$objLayer = $this->newObject('layer','htmlelements');
$objLink = $this->newObject('link','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');
$objPop =& $this->newObject('windowpop','htmlelements');

// Set up language items
$assignment = $this->objLanguage->languageText('mod_assignmentadmin_name','assignmentadmin');
$assignmentLabel = $this->objLanguage->languageText('mod_assignmentadmin_assignmentadmin','assignmentadmin');
$heading = $this->objLanguage->languageText('mod_assignmentadmin_submitted','assignmentadmin').' '.$this->objLanguage->languageText('mod_assignmentadmin_assignments');
$studentLabel = ucwords($this->objLanguage->languageText('mod_context_readonly'));
$submitLabel = $this->objLanguage->languageText('mod_assignmentadmin_datesubmitted');
$markLabel = $this->objLanguage->languageText('mod_assignmentadmin_mark','assignmentadmin').' (%)';
$exitLabel = $this->objLanguage->languageText('word_exit');
$downloadLabel = $this->objLanguage->languageText('mod_assignmentadmin_download').' '.$this->objLanguage->languageText('mod_assignmentadmin_assignment');
$uploadLabel = $this->objLanguage->languageText('mod_assignmentadmin_upload','assignmentadmin').' '.$this->objLanguage->languageText('mod_assignmentadmin_marks','assignmentadmin').' '.$this->objLanguage->languageText('mod_assignmentadmin_and','assignmentadmin').' '.$this->objLanguage->languageText('mod_assignmentadmin_marked','assignmentadmin').' '.$this->objLanguage->languageText('mod_assignmentadmin_assignment','assignmentadmin');
$commentLabel = $this->objLanguage->languageText('mod_assignmentadmin_view','assignmentadmin').' '.$this->objLanguage->languageText('mod_assignmentadmin_comment','assignmentadmin');
$noassignments = $this->objLanguage->languageText('mod_assignmentadmin_nosubmittedassignments');
$rubricLabel = $this->objLanguage->languageText('mod_rubric_name');

/****************** set up table headers ************************/

$this->setVarByRef('heading',$heading);

$str = '<b>'.$assignmentLabel.':</b>&nbsp;&nbsp;&nbsp;'.$assign['name'].'<br />';

$tableHd = array();
$tableHd[] = $studentLabel;
$tableHd[] = $markLabel;
$tableHd[] = $submitLabel;
$tableHd[] = '&nbsp;';

$objTable->cellspacing = 2;
$objTable->cellpadding = 5;
$objTable->addHeader($tableHd,'heading');

$objTable->row_attributes = 'height=5';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

/******************** set up table data ***********************/

if(!empty($data)){
$i = 0;
foreach($data as $item){
    $class = ($i++%2 == 0)?'odd':'even';

    $row = array();
    $row[] = $this->objUser->fullname($item['userId']);
    $row[] = $item['mark'];
    $row[] = $this->formatDate($item['dateSubmitted']);

    if($assign['format']){
        // if upload
        $objIcon->setIcon('download');
        $objLink->link($this->uri(array('action'=>'download', 'fileid'=>$item['fileId'])));
        $objLink->link = $objIcon->show();
        $icons = $objLink->show();

        $objIcon->setIcon('submit2');
        $objLink->link($this->uri(array('action'=>'upload',  'submitId'=>$item['id'],
        'id'=>$assign['id'], 'assignment'=>$assign['name'])));
        $objLink->link = $objIcon->show();
        $icons .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$objLink->show();
    }else{
        // if online
        $objIcon->setIcon('comment');
        $objLink->link($this->uri(array('action'=>'online', 'submitId'=>$item['id'],
        'id'=>$assign['id'], 'assignment'=>$assign['name'])));
        $objLink->link = $objIcon->show();
        $icons = $objLink->show();
    }

    $row[] = $icons;

    $objTable->addRow($row, $class);
}
}else{
    $objTable->startRow();
    $objTable->addCell($noassignments,'','','','odd',' colspan = 4');
    $objTable->endRow();
}
$objTable->row_attributes = 'height = 10';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

if($this->rubric){
    $objPop->resizable = 'yes';
    $objPop->scrollbars = 'yes';
    $objPop->set('location',$this->uri('','rubric'));
    $objPop->set('linktext',$rubricLabel);
    $str .= '<p>'.$objPop->show();
}

// assignment home
$objLink->link($this->uri(array('action'=>'viewbyletter','letter'=>'listall')));
$objLink->link = $assignment;
$link = $objLink->show();

$objLayer->align = 'center';
$objLayer->str = $link;

/******************* Display table *******************/

echo $str.'<br />'.$objTable->show().$objLayer->show();
?>
