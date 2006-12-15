<?php
/**
* @package mcqtests
*/

/**
* Template for displaying a completed test to a student.
* The test is displayed with the question, the students answer and the correct answer.
* Along with the lecturers comment on the students choice.
* @param array $result The students mark and the test details.
* @param array $data The test questions with the correct answer and the students answer.
*/

$this->setLayoutTemplate('mcqtests_layout_tpl.php');

// set up html elements
$objTable =& $this->newObject('htmltable','htmlelements');
$objLayer =& $this->newObject('layer','htmlelements');
$objLayer1 =& $this->newObject('layer','htmlelements');
$objLayer2 =& $this->newObject('layer','htmlelements');
$objLayer3 =& $this->newObject('layer','htmlelements');
$objLayer4 =& $this->newObject('layer','htmlelements');
$objLink =& $this->newObject('link','htmlelements');
$objIcon =& $this->newObject('geticon','htmlelements');

// Parse the MathML
$objMathML =& $this->getObject('parse4mathml','filters');

// set up language items
$heading = $this->objLanguage->languageText('mod_mcqtests_testresults', 'mcqtests');
$testLabel = $this->objLanguage->languageText('mod_mcqtests_test', 'mcqtests');
$totalLabel = $this->objLanguage->languageText('mod_mcqtests_totalmarks', 'mcqtests');
$markLabel = $this->objLanguage->languageText('mod_mcqtests_mark', 'mcqtests');
$questionLabel = $this->objLanguage->languageText('mod_mcqtests_question', 'mcqtests');
$commentLabel = $this->objLanguage->languageText('mod_mcqtests_comment', 'mcqtests');
$correctAnsLabel = $this->objLanguage->languageText('mod_mcqtests_correctans', 'mcqtests');
$yourAnsLabel = $this->objLanguage->languageText('mod_mcqtests_yourans', 'mcqtests');
$noAnsLabel = $this->objLanguage->languageText('mod_mcqtests_unanswered', 'mcqtests');
$exitLabel = $this->objLanguage->languageText('word_exit');
$nextLabel = $this->objLanguage->languageText('mod_mcqtests_next', 'mcqtests');

$this->setVarByRef('heading', $heading);

$alpha = array('','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t');

$percent = round($result['mark'] / $result['totalmark'] * 100, 2);

$str = '<font size="3"><b>'.$testLabel.':</b>&nbsp;&nbsp;&nbsp;'.$result['name'].'<br />';
$str .= '<b>'.$totalLabel.':</b>&nbsp;&nbsp;&nbsp;'.$result['totalmark'].'<br />';
$str .= '<b>'.$markLabel.':</b>&nbsp;&nbsp;&nbsp;'.$result['mark'].'&nbsp;&nbsp;('.$percent.'%)<p /></font>';

$objTable->cellpadding = 5;
$objTable->width = '99%';
$objTable->startRow();
$objTable->addCell('','15%');
$objTable->endRow();

if(!empty($data)){
    $qNum = $data[0]['questionorder'];
    $objIcon->setIcon('greentick');
    $tickIcon = $objIcon->show();
    $objIcon->setIcon('redcross');
    $crossIcon = $objIcon->show();

    foreach($data as $line){
        $ansNum = '&nbsp;&nbsp;&nbsp;'.$alpha[$line['answerorder']].')';
        $content = '<b>'.$correctAnsLabel.':'.$ansNum.'</b>&nbsp;&nbsp;&nbsp;'.$line['answer'].'<br />';
        if(!$line['studcorrect']){
            if(!empty($line['studorder']) && !empty($line['studans'])){
                $ansNum = '&nbsp;&nbsp;&nbsp;'.$alpha[$line['studorder']].')';
                $content .= '<b>'.$yourAnsLabel.':'.$ansNum.'</b>&nbsp;&nbsp;&nbsp;'.$line['studans'].'<br />';
            }else{
                $content .= $noAnsLabel;
            }
            $icon = $crossIcon;
        }else{
            $icon = $tickIcon;
        }
        if(!empty($line['studcomment'])){
            $content .= '<b>'.$commentLabel.':</b>&nbsp;&nbsp;&nbsp;'.$line['studcomment'].'<br />';
        }

        $objLayer4->str = $icon;
        $objLayer4->align = 'right';
        $objLayer4->cssClass = 'forumTopic';
        $iconLayer = $objLayer4->show();

        $parsed = stripslashes($line['question']);
        $parsed = $objMathML->parseAll($parsed);

        $objLayer1->left = '; margin-right: 20px; float:left';
        $objLayer1->cssClass = 'forumTopic';
        $objLayer1->str = '<b>'.$questionLabel.' '.$line['questionorder'].':</b>&nbsp;&nbsp;&nbsp;'.$parsed;
        $question = $objLayer1->show().$iconLayer;

        $objLayer2->cssClass = 'forumContent';
        $objLayer2->str = $content;
        $answers = $objLayer2->show();

        $objLayer->cssClass = 'topicContainer';
        $objLayer->str = $question.$answers;
        $str .= $objLayer->show();

        $objLayer->cssClass = 'forumBase';
        $objLayer->str = '';
        $str .= $objLayer->show().'<br />';

        $qNum = $line['questionorder'];
    }
}
$links = '';
if($qNum < $data[0]['count']){
    $objLink->link($this->uri(array('action'=>'showtest', 'id'=>$result['testid'], 'qnum'=>$qNum)));
    $objLink->link = $nextLabel;
    $links = $objLink->show().'&nbsp;&nbsp;|&nbsp;&nbsp;';
}
$objLink->link($this->uri(''));
$objLink->link = $exitLabel;
$links .= $objLink->show();

$objLayer->str = '<p />'.$links;
$objLayer->cssClass = '';
$objLayer->align = 'center';
$str .= $objLayer->show();

echo $str;
?>