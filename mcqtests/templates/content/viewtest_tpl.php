<?php

/**
 * Template for viewing a test and adding more questions.
 * @package mcqtests
 * @param $data The test information.
 * @param $questions The details of the questions on the test.
 */
// set up layout template
	$this->setLayoutTemplate('mcqtests_layout_tpl.php');

// Classes used in this module
	$objHeading = $this->loadClass('htmlheading', 'htmlelements');
	$objTable = $this->loadClass('htmltable', 'htmlelements');
	$objLink = $this->loadClass('link', 'htmlelements');
	$objLayer = $this->loadClass('layer', 'htmlelements');
	$objIcon = $this->newObject('geticon', 'htmlelements');
	$objConfirm = $this->loadClass('confirm', 'utilities');
	$objMsg = $this->newObject('timeoutmessage', 'htmlelements');

// set up language items
	$testdetailsLabel = $this->objLanguage->languageText('mod_mcqtests_testdetailsLabel', 'mcqtests');
	$addqestionslabel = $this->objLanguage->languageText('mod_mcqtests_addquestions', 'mcqtests');
	$head = $objLanguage->languageText('mod_mcqtests_test', 'mcqtests');
	$editLabel = $objLanguage->languageText('word_edit');
	$chapterLabel = $objLanguage->languageText('mod_mcqtests_chapter', 'mcqtests');
	$statusLabel = $objLanguage->languageText('mod_mcqtests_status', 'mcqtests');
	$percentLabel = $objLanguage->languageText('mod_mcqtests_finalmark', 'mcqtests');
	$startdateLabel = $objLanguage->languageText('mod_mcqtests_startdate', 'mcqtests');
	$dateLabel = $objLanguage->languageText('mod_mcqtests_closingdate', 'mcqtests');
	$totalLabel = $objLanguage->languageText('mod_mcqtests_totalmarks', 'mcqtests');
	$backLabel = $objLanguage->languageText('mod_mcqtests_name', 'mcqtests') .' '.$objLanguage->languageText('word_home');
	$questionsLabel = $objLanguage->languageText('mod_mcqtests_questions', 'mcqtests');
	$questionLabel = $objLanguage->languageText('mod_mcqtests_question', 'mcqtests');
	$markLabel = $objLanguage->languageText('mod_mcqtests_mark', 'mcqtests');
	$numansLabel = $objLanguage->languageText('mod_mcqtests_numanswers', 'mcqtests');
	$actionLabel = $objLanguage->languageText('mod_mcqtests_actions', 'mcqtests');
	$lbConfirm = $objLanguage->languageText('mod_mcqtests_deletequestion', 'mcqtests');
	$wordquestion = $objLanguage->languageText('mod_mcqtests_deletequestionword', 'mcqtests');

	$listLabel = ucwords($objLanguage->code2Txt('mod_mcqtests_liststudents', 'mcqtests', array(
		'readonlys' => 'students'
	)));
	$editIconLabel = $editLabel.' '.$head;
	$deleteLabel = $this->objLanguage->languageText('word_delete') .' '.$wordquestion;
	$addLabel = $this->objLanguage->languageText('word_add') .' '.$questionLabel;
	$upLabel = $this->objLanguage->languageText('word_up');
	$downLabel = $this->objLanguage->languageText('word_down');
	$durationLabel = $this->objLanguage->languageText('mod_mcqtests_duration', 'mcqtests');
	$hoursLabel = $this->objLanguage->languageText('mod_mcqtests_hours', 'mcqtests');
	$minLabel = $this->objLanguage->languageText('mod_mcqtests_minutes', 'mcqtests');
	$hourLabel = $this->objLanguage->languageText('mod_mcqtests_hour', 'mcqtests');
	$noRecords = $this->objLanguage->languageText('mod_mcqtests_nosetquestions', 'mcqtests');
	$testTypeLabel = $this->objLanguage->languageText('mod_mcqtests_testtype', 'mcqtests');
	$formativeLabel = $this->objLanguage->languageText('word_formative');
	$summativeLabel = $this->objLanguage->languageText('word_summative');
	$qSequenceLabel = $this->objLanguage->languageText('mod_mcqtests_questionorder', 'mcqtests');
	$aSequenceLabel = $this->objLanguage->languageText('mod_mcqtests_answerorder', 'mcqtests');
	$scrambledLabel = $this->objLanguage->languageText('word_scrambled');
	$sequentialLabel = $this->objLanguage->languageText('word_sequential');
	$computerLabel = $this->objLanguage->languageText('mod_mcqtests_comlab', 'mcqtests');
	$anyLabLabel = $this->objLanguage->languageText('mod_mcqtests_labs', 'mcqtests');

//switch between the question descriptions and adding questions
	$mode = $this->getParam('mode');

	$answers_tab = $this->newObject('tabbedbox', 'htmlelements');
	$questions_tab = $this->newObject('tabbedbox', 'htmlelements');

	$tabcontent = $this->newObject('tabcontent', 'htmlelements');


	



// Heading for test
	$editUrl = $this->uri(array(
		'action' => 'edit',
		'id' => $data['id']
	));
	$editLink = $objIcon->getEditIcon($editUrl);
	$objIcon->title = $listLabel;
	$objIcon->setIcon('comment');

	$objLink = new link($this->uri(array(
		'action' => 'liststudents',
		'id' => $data['id']
	)));
	$objLink->link = $objIcon->show();
	$editLink.= '&nbsp;'.$objLink->show();

// Show Heading
	$heading = $head.': '.$data['name'].'&nbsp;&nbsp;'.$editLink;
	$this->setVarByRef('heading', $heading);

// Create Table for the test information
	$objTable = new htmltable();
	$objTable->cellpadding = '5';
	$objTable->cellspacing = '2';
	$objTable->width = '99%';

// Add Context and Name of Chapter
	$objTable->startRow();
	$objTable->addCell('');//'<b>'.$chapterLabel.'</b>: '.$data['node'], '50%');
	$objTable->endRow();

// Add Activity Status and percentage of mark
	$objTable->startRow();
	$objTable->addCell('<b>'.$statusLabel.'</b>: '.$objLanguage->languageText('mod_mcqtests_'.$data['status'], 'mcqtests'));
	$objTable->addCell('<b>% '.$percentLabel.'</b>: '.$data['percentage']);
	$objTable->endRow();

// Add Start date
	$objTable->startRow();
	$objTable->addCell('<b>'.$startdateLabel.'</b>: '.$this->objDate->formatDate($data['startdate']));
	$objTable->addCell('<b>'.$totalLabel.'</b>: '.$data['totalmark']);
	if ($data['timed']) {
		$duration = (0) .'&nbsp;'.$hoursLabel;
		if ($data['duration'] > 0) {
		    $hours = floor($data['duration']/60);
		    $mins = $data['duration']%60;
		    if ($hours == 1) {
		        $hoursLabel = $hourLabel;
		    }
		    $duration = $hours.'&nbsp;'.$hoursLabel.'&nbsp;&nbsp;';
		    $duration.= $mins.'&nbsp;'.$minLabel;
		}
		$objTable->addCell('<b>'.$durationLabel.'</b>: '.$duration);
	}
	$objTable->endRow();

// Add Cosing date
	$objTable->addRow(array(
		'<b>'.$dateLabel.'</b>: '.$this->objDate->formatDate($data['closingdate'])
	));

// Add test type
	if (isset($data['testtype']) && !empty($data['testtype'])) {
		$testType = $data['testtype'];
	} else {
		$testType = $formativeLabel;
	}
	$objTable->addRow(array(
		"<b>".$testTypeLabel.": </b>".$testType
	));

// Add question sequence
	if (isset($data['qsequence']) && !empty($data['qsequence'])) {
		$qSequence = $data['qsequence'];
	} else {
		$qSequence = $sequentialLabel;
	}
	$objTable->addRow(array(
		"<b>".$qSequenceLabel.": </b>".$qSequence
	));

// Add answer sequence
	if (isset($data['asequence']) && !empty($data['asequence'])) {
		$aSequence = $data['asequence'];
	} else {
		$aSequence = $sequentialLabel;
	}
	$objTable->addRow(array(
		"<b>".$aSequenceLabel.": </b>".$aSequence
	));

// add computer lab
	if (isset($data['comlab']) && !empty($data['comlab'])) {
		$comLab = $data['comlab'];
	} else {
		$comLab = $anyLabLabel;
	}
	$objTable->addRow(array(
		"<b>".$computerLabel.": </b>".$comLab
	));

// Description
	$objTable->startRow();
	$objTable->addCell($data['description'], NULL, "top", NULL, NULL, ' colspan="2"'); // colspans to two
	$objTable->endRow();

// Show Table
	$contentTable = $objTable->show();

	$objLayer = new layer();
	$objLayer->cssClass = 'even';
	$objLayer->str = $contentTable;
	$str = $objLayer->show();

	$count = count($questions);
	if (empty($questions)) {
		$count = 0;
	}

// add a new question
	$objIcon->title = $addLabel;
	$addQUrl = $this->uri(array(
		'action' => 'addquestion',
		'id' => $data['id'],
		'count' => $count
	));
	$addQ = $objIcon->getAddIcon($addQUrl);




	$questions_tab->addBoxContent($str);
	$questions_tab->addTabLabel("Details");



//=======================================================SPLIT=========================================================================

	$str = null;

// Questions Header
	$objHeading = new htmlheading();
	$objHeading->type = 3;
	$objHeading->str = $questionsLabel.' ('.$count.'):
	&nbsp;&nbsp;&nbsp;&nbsp;'.$addQ;
	$qHeading = $objHeading->show();
	$str.= $qHeading;


// Confirmation message on saving a question
	$confirm = $this->getParam('confirm');
	if ($confirm == 'yes') {
		$msg = $this->getSession('confirm');
		$this->unsetSession('confirm');
		$objMsg->setMessage($msg.'&nbsp;&nbsp;'.date('d/m/Y H:i'));
		$str.= '<p>'.$objMsg->show() .'</p>';
	}

// Create a New table for the questions
$objTable = new htmltable();
$objTable->cellpadding = 4;
$objTable->cellspacing = 2;
$objTable->width = '99%';
$objTable->startRow();
$objTable->addCell('', '', '', '', 'heading');
$objTable->addCell($questionLabel, '', '', '', 'heading');
$objTable->addCell($markLabel, '', '', '', 'heading');
$objTable->addCell($actionLabel, '', '', '', 'heading', 'colspan="2"');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell('', '1%');
$objTable->addCell('');
$objTable->addCell('', '4%');
$objTable->addCell('', '4%');
$objTable->addCell('', '8%');
$objTable->endRow();

// Add questions to table
if (!empty($questions)) {
    $i = 0;
    foreach($questions AS $line) {
        $class = (($i++%2) == 0) ? "odd" : "even";
        // move a question up in the order
        if ($i > 1) {
            $objIcon->title = $upLabel;
            $url = $this->uri(array(
                'action' => 'questionup',
                'questionId' => $line['id'],
                'id' => $data['id']
            ));
            $iconsUD = $objIcon->getLinkedIcon($url, 'mvup') .'&nbsp;';
        } else {
            $iconsUD = '&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        // move a question down in the order
        if ($i < $count) {
            $objIcon->title = $downLabel;
            $url = $this->uri(array(
                'action' => 'questiondown',
                'questionId' => $line['id'],
                'id' => $data['id']
            ));
            $iconsUD.= $objIcon->getLinkedIcon($url, 'mvdown');
        }

        // edit & delete
        $objIcon->title = $editIconLabel;
        $editUrl = $this->uri(array(
            'action' => 'editquestion',
            'questionId' => $line['id']
        ));
        $icons = $objIcon->getEditIcon($editUrl);
        $objIcon->title = $deleteLabel;
        $objIcon->setIcon('delete');
        $pos = FALSE;
        $len = strlen($line['question']);
        $conQuestion = $line['question'];
        if ($len > 10) {
            $pos = strpos($line['question'], '<', 10);
        }
        if ($len > 20 && ($pos > 20 || $pos === FALSE)) {
            $pos = strpos($line['question'], ' ', 20);
        }
        $conQuestion = substr($line['question'], 0, $pos) .'...';

        $objConfirm = new confirm();
        $objConfirm->setConfirm($objIcon->show() , $this->uri(array(
            'action' => 'deletequestion',
            'questionId' => $line['id'],
            'id' => $data['id'],
            'mark' => $line['mark']
        )) , $lbConfirm);
        $icons.= $objConfirm->show();
        // link name to edit question - shorten the question to 100 characters or the first line break
        $pos = FALSE;
        if ($len > 10) {
            $pos = strpos($line['question'], '<br />', 10);
        }
        if ($len > 100 && $pos === FALSE) {
            $pos = strpos($line['question'], ' ', 100);
        }
        if (!($pos === FALSE)) {
            $strQuestion = substr($line['question'], 0, $pos) .'...';
        } else {
            $strQuestion = $line['question'];
        }
        $objLink = new link($editUrl);
        $objLink->link = $strQuestion;
        $tableRow = array();
        $tableRow[] = $i;
        $tableRow[] = $objLink->show();
        $tableRow[] = $line['mark'];
        $tableRow[] = $iconsUD;
        $tableRow[] = $icons;
        $objTable->addRow($tableRow, $class);
    }
    $str.= $objTable->show();
} else {
    $str.= '<p align="center" class="noRecordsMessage">'.$noRecords.'</p>';
}
$objLink = new link($addQUrl);
$objLink->link = $addLabel;
$homeLink = '<p>'.$objLink->show() .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$objLink = new link($this->uri(array(
    ''
)));
$objLink->link = $backLabel;
$homeLink.= $objLink->show() .'</p>';

$objLayer = new layer();
$objLayer->cssClass = '';
$objLayer->align = 'center';
$objLayer->str = $homeLink;
$back = $objLayer->show();
$str.= $back;
//echo $str;

	$answers_tab->addBoxContent($str);
	$answers_tab->addTabLabel($addqestionslabel);
	$tabcontent->addTab($addqestionslabel,$answers_tab->show());
	$tabcontent->addTab($testdetailsLabel,$questions_tab->show());


//==========Adding the new boxes here
	$tabcontent->width = '90%';
	echo  $tabcontent->show();
?>
