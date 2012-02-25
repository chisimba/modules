<?php
/**
* @package assignmentadmin
*/

/**
* Template to view the assignment.
* Students can submit their assignments.
* @param array $data The details of the assignment.
*/

$this->setLayoutTemplate('assignmentadmin_layout_tpl.php');

// set up html elements
$this->loadClass('htmltable','htmlelements');
$this->loadClass('layer','htmlelements');
$this->loadClass('link','htmlelements');

// set up language items
$assignmentLabel = $this->objLanguage->languageText('mod_assignmentadmin_assignment','assignmentadmin');
$heading = $this->objLanguage->languageText('mod_assignmentadmin_view','assignmentadmin').' '.$assignmentLabel;
$nameLabel = $this->objLanguage->languageText('mod_assignmentadmin_wordname','assignmentadmin');
$descriptionLabel = $this->objLanguage->languageText('mod_assignmentadmin_description','assignmentadmin');
$lecturerLabel = ucwords($this->objLanguage->languageText('mod_context_author'));
$resubmitLabel = $this->objLanguage->languageText('mod_assignmentadmin_allowresubmit','assignmentadmin');
$markLabel = $this->objLanguage->languageText('mod_assignmentadmin_mark','assignmentadmin');
$dateLabel = $this->objLanguage->languageText('mod_assignmentadmin_closingdate','assignmentadmin');
$noLabel = $this->objLanguage->languageText('word_no');
$yesLabel = $this->objLanguage->languageText('word_yes');
$submitLabel = $this->objLanguage->languageText('word_submit');
$completeLabel = $this->objLanguage->languageText('mod_assignmentadmin_completeonline');
$uploadLabel = $this->objLanguage->languageText('mod_assignmentadmin_upload','assignmentadmin').' '.$assignmentLabel;
$exitLabel = $this->objLanguage->languageText('word_exit');
$percentLabel = $this->objLanguage->languageText('mod_assignmentadmin_percentyrmark','assignmentadmin');

$this->setVarByRef('heading', $heading);

$str = '';
$objTable = new htmltable();
$objTable->cellpadding=5;
$objTable->width='99%';

if(!empty($data)){
    // Display the assignment
    $objTable->startRow();
    $objTable->addCell('<b>'.$nameLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$data[0]['name'],'45%');
    $objTable->addCell('<b>'.$lecturerLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$this->objUser->fullname($data[0]['userid']),'55%');
    $objTable->endRow();

    $objTable->startRow();
    $objTable->addCell('<b>'.$markLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$data[0]['mark']);
    $objTable->addCell('<b>'.$percentLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$data[0]['percentage'].' %');
    $objTable->endRow();

    if($data[0]['resubmit']){
        $resubmit = $yesLabel;
    }else{
        $resubmit = $noLabel;
    }
    $objTable->startRow();
    $objTable->addCell('<b>'.$resubmitLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$resubmit);
    $objTable->addCell('<b>'.$dateLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$this->objAssignmentAdmin->formatDate($data[0]['closing_date']));
    $objTable->endRow();

    $objTable->startRow();
    $objTable->addCell('<b>'.$descriptionLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;');
    $objTable->endRow();

    $objTable->startRow();
    $objTable->addCell($data[0]['description'],'','','','','colspan="2"');
    $objTable->endRow();

    $objLayer = new layer();
    $objLayer->cssClass = 'odd';
    $objLayer->str = $objTable->show();

    $str = $objLayer->show();

    $objLink = new link($this->uri(''));
    $objLink->link = $exitLabel;
    $layerStr = '<p>'.$objLink->show().'</p>';

    $objLayer1 = new layer();
    $objLayer1->str = $layerStr;
    $objLayer1->align = 'center';
    $str .= $objLayer1->show();
}

echo $str;
?>
