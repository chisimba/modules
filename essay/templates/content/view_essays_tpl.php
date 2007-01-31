<?php
/*
* Template to view list of essays for a student.
* @package essay
*/

/**
* @param array $data Array containing a list of essays and their details: mark & comment or submit icon
*/

// set layout template
$this->setLayoutTemplate('essay_layout_tpl.php');

$this->objDateformat = $this->newObject('datetime','utilities');

 $this->loadclass('htmltable','htmlelements'); 
  $objPopup=&$this->loadClass('windowpop','htmlelements');

// set up html elements
//$objTable=$this->objTable;
$objTable = new htmltable();
$objLayer=$this->objLayer;

// set up language items
$list=$this->objLanguage->languageText('word_list');
$head=$list.' '.$this->objLanguage->languageText('mod_essay_of','essay').' '.$this->objLanguage->languageText('mod_essay_essay','essay').' '.$this->objLanguage->languageText('word_for').' '.$this->user;
$topichead=$this->objLanguage->languageText('mod_essay_topic','essay');
$essayhead=$this->objLanguage->languageText('mod_essay_essay','essay');
$datehead=$this->objLanguage->languageText('mod_essay_closedate','essay');
$submithead=$this->objLanguage->languageText('mod_essay_datesubmitted','essay');
$lblSubmitted=$this->objLanguage->languageText('mod_essay_submitted','essay');
$markhead=$this->objLanguage->languageText('mod_essay_mark','essay');
$submittitle=$this->objLanguage->languageText('mod_essay_upload','essay');
$downloadhead=$this->objLanguage->languageText('mod_essay_download','essay');
$loadhead=$submittitle.' / '.$downloadhead;
$submittitle.=' '.$this->objLanguage->languageText('mod_essay_essay','essay');
$downloadhead.=' '.$this->objLanguage->languageText('mod_essay_marked','essay').' '.$this->objLanguage->languageText('mod_essay_essay','essay');
$commenthead=$this->objLanguage->languageText('word_view').' '.$this->objLanguage->languageText('mod_essay_comment','essay');
$topiclist=$this->objLanguage->languageText('word_back').' '.strtolower($this->objLanguage->languageText('word_to')).' '.$topichead;
$topichome=$this->objLanguage->languageText('mod_essay_name','essay').' '.$this->objLanguage->languageText('word_home');
$lbClosed = $this->objLanguage->languageText('mod_essay_closed', 'essay');

/********************* set up table ************************/

$this->setVarByRef('heading',$head);

$tableHd=array();
$tableHd[]=$topichead;
$tableHd[]=$essayhead;
$tableHd[]=$datehead;
$tableHd[]=$submithead;
$tableHd[]=$markhead;
$tableHd[]=$loadhead;

$objTable->cellspacing=2;
$objTable->cellpadding=5;
$objTable->addHeader($tableHd,'heading');

$objTable->row_attributes='height="5"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

/********************* display data *************************/

$i=0;
foreach($data as $item){
    $class = ($i++%2) ? 'even':'odd';

    $objTable->startRow();
    $objTable->addCell($item['name'],'','','',$class);
    $objTable->addCell($item['essay'],'','','',$class);
    $objTable->addCell($this->objDateformat->formatDate($item['date']),'','','',$class);

    if(!empty($item['submitdate'])){
        $objTable->addCell($this->objDateformat->formatDate($item['submitdate']),'','','',$class);
    }else{
        $objTable->addCell('','','','',$class);
    }

    if($item['mark']=='submit'){
        // if essay hasn't been submitted: display submit icon
        // check if closing date has passed
        if($item['date'] < date('Y-m-d')){
            $mark='';
            $load = $lbClosed;
        }else{
            $this->objLink->link($this->uri(array('action'=>'upload','book'=>$item['id'])));
                $this->objIcon->setIcon('submit2');
            $this->objIcon->extra='';
                $this->objIcon->title=$submittitle;
                $this->objLink->link=$this->objIcon->show();

            $mark='';
            $load = $this->objLink->show();
        }
    }else if($item['mark']){
        // if mark exists: display mark and download icon and view comments icon
        $this->objLink->link($this->uri(array('action'=>'download','fileid'=>$item['lecturerfileid'])));
        $this->objIcon->setIcon('download');
        $this->objIcon->extra='';
        $this->objIcon->title=$downloadhead;
        $this->objLink->link=$this->objIcon->show();
        $downlink=$this->objLink->show();

        //$this->objLink->link('#');
        //$this->objIcon->setIcon('comment_view');
        
        $this->objIcon->title=$commenthead;
    	$this->objIcon->setIcon('comment_view');
   	 $commentIcon = $this->objIcon->show();
        
        $objPopup = new windowpop();
    	$objPopup->set('location',$this->uri(array('action'=>'showcomment','book'=>$item['id'],'essay'=>$item['essay'])));
    	$objPopup->set('linktext',$commentIcon);
    	$objPopup->set('width','600');
    	$objPopup->set('height','350');
    	$objPopup->set('left','200');
    	$objPopup->set('top','200');
    	$objPopup->putJs(); // you only need to do this once per page
    	//$observersEmailPopup=$objPopup->show();
 //       $this->objIcon->extra="onclick=\"javascript:window.open('" .$this->uri(array('action'=>'showcomment','book'=>$item['id'],'essay'=>$item['essay']))."', "essaycomment", "width=400", "height=200", "scrollbars=1")\" ";
        //$this->objIcon->title=$commenthead;
        //$this->objLink->link=$this->objIcon->show();

        $mark=$item['mark'].'&nbsp;&nbsp;&nbsp;'.$objPopup->show();
        $load=$downlink;
    }else{
        // if no mark
        $mark = '';
        $load = $lblSubmitted;
    }

    $objTable->addCell($mark,'','','',$class);
    $objTable->addCell($load,'','','center',$class);
    $objTable->endRow();
}

$objTable->row_attributes='height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

/********************* display table ************************/
echo $objTable->show();

// back button
$this->objLink->link($this->uri(''));
$this->objLink->link=$topichome;

$objLayer->align='center';
$objLayer->str=$this->objLink->show();
echo $objLayer->show();
?>