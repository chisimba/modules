<?php
/**
* Template for Assignment Managements home page.
* @package assignmentadmin
*/

/**
* Template for Assignment Managements home page.
* @param array $data The results of a search.
* @param string $title The title of the search performed.
*/

$this->setLayoutTemplate('assignmentadmin_layout_tpl.php');
$this->objAssignmentAdmin = $this->getObject('functions_assignmentadmin','assignmentadmin');
// Set up html elements
$this->loadClass('htmltable','htmlelements');
$this->loadClass('htmlheading','htmlelements');
$this->loadClass('link','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');
$objConfirm = $this->newObject('confirm','utilities');
$objMsg = $this->newObject('timeoutmessage', 'htmlelements');
$objTrim = $this->getObject('trimstr', 'strings');

// Set up language items
$assignmentsLabel = $this->objLanguage->languageText('mod_assignmentadmin_assignments','assignmentadmin');
$nameLabel = $this->objLanguage->languageText('mod_assignmentadmin_wordname','assignmentadmin');
$descriptionLabel = $this->objLanguage->languageText('mod_assignmentadmin_description','assignmentadmin');
$typeLabel = $this->objLanguage->languageText('mod_assignmentadmin_assignmenttype','assignmentadmin');
$lecturerLabel = $this->objLanguage->languageText('mod_context_author','assignmentadmin');
$dueLabel = $this->objLanguage->languageText('mod_assignmentadmin_closingdate','assignmentadmin');
$lastmodifiedLabel = $this->objLanguage->languageText('mod_assignmentadmin_lastmodified','assignmentadmin');
$noAssignLabel = $this->objLanguage->languageText('word_no').' '.$assignmentsLabel;
$searchLabel = $this->objLanguage->languageText('mod_assignmentadmin_search','assignmentadmin').' '.$assignmentsLabel;
$confirmLabel = $this->objLanguage->languageText('word_delete');
$worksheetLabel = $this->objLanguage->languageText('mod_worksheetadmin_name','assignmentadmin');
$essayLabel = $this->objLanguage->languageText('mod_essayadmin_name','assignmentadmin');
$testLabel = $this->objLanguage->languageText('mod_testadmin_name','assignmentadmin');
$markLabel = $this->objLanguage->languageText('mod_assignmentadmin_mark','assignmentadmin');
$editLabel = $this->objLanguage->languageText('word_edit');
$openLabel = $this->objLanguage->languageText('mod_assignmentadmin_open','assignmentadmin');
$viewLabel = $this->objLanguage->languageText('mod_assignmentadmin_view','assignmentadmin');
$addTopicLabel = $this->objLanguage->languageText('mod_assignmentadmin_addnewtopic','assignmentadmin');
$addWSLabel = $this->objLanguage->languageText('mod_assignmentadmin_createworksheet','assignmentadmin');
$addAssign = $this->objLanguage->languageText('mod_assignmentadmin_addassignment','assignmentadmin');
$addAssignLabel = $this->objLanguage->languageText('mod_assignmentadmin_adduploadonlineassignment','assignmentadmin');
$addTestLabel = $this->objLanguage->languageText('mod_assignmentadmin_addnewtest','assignmentadmin');
$heading = $objLanguage->languageText('mod_assignmentadmin_name','assignmentadmin');
$uploadLabel = $this->objLanguage->languageText('mod_assignmentadmin_upload','assignmentadmin');
$onlineLabel = $this->objLanguage->languageText('mod_assignmentadmin_online','assignmentadmin');
$rubricLabel = $this->objLanguage->languageText('mod_rubric_name','assignmentadmin');

// Add heading with add icon
$objIcon->title = $addAssign;
$addIcon = $objIcon->getAddIcon($this->uri(array('action'=>'add')));
$heading .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$addIcon;

$this->setVarByRef('heading',$heading);

// Put the results of a search in a table
$objTable = new htmltable();
$objTable->cellpadding="2";
$objTable->cellspacing="2";

$tableHd[] = ucwords($nameLabel);
$tableHd[] = ucwords($descriptionLabel);
$tableHd[] = ucwords($typeLabel);
$tableHd[] = ucwords($lecturerLabel);
$tableHd[] = ucwords($dueLabel);
$tableHd[] = ucwords($lastmodifiedLabel);
$tableHd[] = '&nbsp;';

if(!empty($title)){
    $objTable->addHeader($tableHd,'heading');
}

if(!empty($data)){
    $i=0;
    foreach($data as $line){
        if(!empty($line)){
            $class = ($i++%2 == 0) ? 'odd':'even';

/* - Needs to strip the html before displaying otherwise it breaks the interface
            if(strlen($line['description']) > 100){
                $pos = strpos($line['description'], ' ', 100);
                $description = $line['description'];//substr($line['description'], 0, $pos).'...';
            }else{
                $description = $line['description'];
            }
*/
	    $description = $line['description'];

            if(!empty($line['type'])){
                $type = $line['type'];
            }
            $module=$type;
            $typeLabel=$this->objLanguage->languageText('mod_assignmentadmin_'.$type, 'assignmentadmin');

            if($module == 'assignmentadmin'){
                if($line['format']){
                    $typeLabel = $uploadLabel.' '.$typeLabel;
                }else{
                    $typeLabel = $onlineLabel.' '.$typeLabel;
                }
            }

            // Link to view module
            $objLink = new link($this->uri(array('action'=>'view', 'mod'=>'back', 'id'=>$line['id']), $module));
            $objLink->link = $line['name'];
            $objLink->title = $viewLabel.' '.ucwords($typeLabel).' '.$line['name'];
            $link = $objLink->show();

            // edit, delete and mark icons
            $objIcon->title = $editLabel.' '.ucwords($typeLabel);
            $icons = $objIcon->getEditIcon($this->uri(array('action'=>'edit', 'mod'=>'back', 'id'=>$line['id']), $module));
            $objIcon->setIcon('delete');
            $objIcon->title = $confirmLabel.' '.ucwords($typeLabel);
            $objConfirm->setConfirm($objIcon->show(),$this->uri(array('action'=>'delete','id'=>$line['id'],'back'=>'assignmentadmin'),$module),$confirmLabel.' '.$typeLabel.' '.$line['name']);
            $icons .= $objConfirm->show();

            if($line['closing_date'] < date('Y-m-d H:i', time())){
                $objIcon->setIcon('comment');
                $objIcon->title = $markLabel.' '.ucwords($typeLabel);
                $objLink = new link($this->uri(array('action'=>'mark', 'id'=>$line['id']),$module));
                $objLink->link = $objIcon->show();
                $icons .= $objLink->show();
            }

            $objTable->startRow();
            $objTable->addCell($link,'','','',$class);
            $objTable->addCell($objTrim->strTrim(strip_tags($description), 50),'','','',$class);
            $objTable->addCell($typeLabel,'','','',$class);
            $objTable->addCell($this->objUser->fullname($line['userid']),'','','',$class);
            $objTable->addCell($this->objAssignmentAdmin->formatDate($line['closing_date']),'','','',$class);
            $objTable->addCell($this->objAssignmentAdmin->formatDate($line['last_modified']),'','','',$class);
            $objTable->addCell($icons,'14%','','',$class);
            $objTable->endRow();
        }
    }
}else if(!empty($title)){
    $objTable->startRow();
    $objTable->addCell($noAssignLabel,'','','','odd','colspan="7"');
    $objTable->endRow();
}

$table1 = $objTable->show();

$space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

// Set up links to create online or uploadable assignments
$objIcon->setIcon('addassignment');
$objIcon->title = $assignmentsLabel;
$objLink = new link($this->uri(''));
$objLink->link = $objIcon->show();
$objLink->title = $assignmentsLabel;
$assignmentLink = $objLink->show();
$objLink = new link($this->uri(array('action'=>'add')));
$objLink->link = $addAssignLabel;
$assignmentLink .= '&nbsp;&nbsp;'.$objLink->show();
$assLinks = '<P>'.$assignmentLink.'</P>';
$leftLinks = '';
$rightLinks = '';

// set up links to essay management, worksheets and test if registered
if($this->essay){
    $objIcon->setModuleIcon('essayadmin');
    $objIcon->title = $openLabel.' '.$essayLabel;
    $objLink = new link($this->uri(array(''),'essayadmin'));
    $objLink->link = $objIcon->show();
    $objLink->title = $openLabel.' '.$essayLabel;
    $essayLink = $objLink->show(); // icon

    $objLink = new link($this->uri(array(''),'essayadmin'));
    $objLink->link = $essayLabel; // link text
    $essayLink .= '&nbsp;&nbsp;'.$objLink->show();

    $objIcon->setIcon('addessay');
    $objIcon->title = $addTopicLabel;
    $objLink = new link($this->uri(array('action'=>'addtopic'),'essayadmin'));
    $objLink->link = $objIcon->show(); // icon
    $objLink->title = $addTopicLabel;
    $essayLink .= '<br />'.$space.$objLink->show();

    $objLink->link = $addTopicLabel; // text
    $essayLink .= '&nbsp;&nbsp;'.$objLink->show();
    $leftLinks .= '<P>'.$essayLink.'</P>';
}
if($this->ws){
    $objIcon->setModuleIcon('worksheetadmin');
    $objIcon->title = $openLabel.' '.$worksheetLabel;
    $objLink = new link($this->uri(array(''),'worksheetadmin'));
    $objLink->link = $objIcon->show(); // icon
    $objLink->title = $openLabel.' '.$worksheetLabel;
    $wsLink = $objLink->show();
    $objLink->link = $worksheetLabel;
    $wsLink .= '&nbsp;&nbsp;'.$objLink->show(); // text

    $objIcon->setIcon('addworksheet');
    $objIcon->title = $addWSLabel;
    $objLink = new link($this->uri(array('action'=>'add'),'worksheetadmin'));
    $objLink->link = $objIcon->show(); // icon
    $objLink->title = $addWSLabel;
    $wsLink .= '<br />'.$space.$objLink->show();

    $objLink->link = $addWSLabel; // text
    $wsLink .= '&nbsp;&nbsp;'.$objLink->show();
    $leftLinks .= '<p>'.$wsLink.'</p>';
}
if($this->test){
    $objIcon->setModuleIcon('testadmin');
    $objIcon->title = $openLabel.' '.$testLabel;
    $objLink = new link($this->uri(array(''),'testadmin'));
    $objLink->link = $objIcon->show(); // icon
    $objLink->title = $openLabel.' '.$testLabel;
    $testLink = $objLink->show();
    $objLink->link = $testLabel; // text
    $testLink .= '&nbsp;&nbsp;'.$objLink->show();

    $objIcon->setIcon('addtest');
    $objIcon->title = $addTestLabel;
    $objLink = new link($this->uri(array('action'=>'addtest'),'testadmin'));
    $objLink->link = $objIcon->show(); // icon
    $objLink->title = $addTestLabel;
    $testLink .= '<br />'.$space.$objLink->show();

    $objLink->link = $addTestLabel; // text
    $testLink .= '&nbsp;&nbsp;'.$objLink->show();
    if(!$this->essay && !$this->ws){
        $leftLinks .= '<P>'.$testLink.'</P>';
    }else{
        $rightLinks .= '<P>'.$testLink.'</P>';
    }
}
if($this->rubric){
    $objIcon->setModuleIcon('rubric');
    $objIcon->title = $openLabel.' '.$rubricLabel;
    $objLink = new link($this->uri(array(''),'rubric'));
    $objLink->link = $objIcon->show();
    $objLink->title = $openLabel.' '.$rubricLabel;
    $rubricLink = $objLink->show();
    $objLink->link = $rubricLabel;
    $rubricLink .= '&nbsp;&nbsp;'.$objLink->show();
    if(!$this->essay && !$this->ws){
        $leftLinks .= '<P>'.$rubricLink.'</P>';
    }else{
        $rightLinks .= '<P>'.$rubricLink.'</P>';
    }
}

$objTable2 = new htmltable();
$objTable2->startRow();
$objTable2->addCell($assLinks, '50%');
$objTable2->endRow();

/*
$objTable2->startRow();
$objTable2->addCell($leftLinks, '50%');
$objTable2->addCell($rightLinks, '50%');
$objTable2->endRow();
*/
echo $objTable2->show();

// Display Page
$objHead = new htmlheading();
$objHead->str = $searchLabel;
$objHead->type = 3;
echo $objHead->show();

echo $this->objAssignmentAdmin->putSearch();

// Display confirmation message if set
if(isset($message) && !empty($message)){
    $objMsg->setMessage($message.'&nbsp;'.date('d/m/Y H:i'));
    $objMsg->setTimeOut(10000);
    echo '<p>'.$objMsg->show().'</p>';
}

if(isset($title)){
    $objHead = new htmlheading();
    $objHead->str = $title;
    $objHead->type = 4;
    echo $objHead->show();
}

echo $table1;
?>
