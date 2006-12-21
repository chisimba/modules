<?php
/**
* Template for listing submitted essays to be marked.
* @package essayadmin
*/

/**
* @param array $topicdata Array contains details of topic to be marked. Set in controller action=marktopic
* @param array $data Array contains the details of the essays in the topic. Set in controller action=marktopic
*/

/**************** Set Layout template ***************************/
$this->setLayoutTemplate('essayadmin_layout_tpl.php');

$this->objDateformat =  $this->newObject('datetime', 'utilities');

$topic=$topicdata[0]['name'];
$duedate=0;
$duedate=$topicdata[0]['closing_date'];

// set up html elements
$objTable=$this->objTable;
$objTable2=$this->objTable;
$objLayer=$this->objLayer;
$objPop =& $this->newObject('windowpop','htmlelements');

// Set up language items
$studenthead=ucwords($this->objLanguage->languageText('mod_context_readonly'));
$topichead=$this->objLanguage->languageText('mod_essayadmin_topic','essayadmin');
$essayhead=$this->objLanguage->languageText('mod_essayadmin_essay','essayadmin');
$essayhead.=' '.$this->objLanguage->languageText('mod_essayadmin_title','essayadmin');
$submithead=$this->objLanguage->languageText('mod_essayadmin_datesubmitted','essayadmin');
$markhead=$this->objLanguage->languageText('mod_essayadmin_mark','essayadmin').' (%)';
$btnexit=$this->objLanguage->languageText('word_exit');
$head=$this->objLanguage->languageText('mod_essayadmin_submitted','essayadmin').' '.$this->objLanguage->languageText('mod_essayadmin_essays', 'essayadmin').' '.$this->objLanguage->languageText('mod_essayadmin_in', 'essayadmin').' '.$topic;
$titledownload=$this->objLanguage->languageText('mod_essayadmin_download').' '.$this->objLanguage->languageText('mod_essayadmin_essay', 'essayadmin');
$titleupload=$this->objLanguage->languageText('mod_essayadmin_upload').' '.$this->objLanguage->languageText('mod_essayadmin_marks', 'essayadmin').' '.$this->objLanguage->languageText('mod_essayadmin_and').' '.$this->objLanguage->languageText('mod_essayadmin_marked').' '.$this->objLanguage->languageText('mod_essayadmin_essay');
$topiclist=$this->objLanguage->languageText('word_back').' '.strtolower($this->objLanguage->languageText('word_to')).' '.$topichead;
$topichome=$this->objLanguage->languageText('mod_essayadmin_name', 'essayadmin').' '.$this->objLanguage->languageText('word_home');
$noessays=$this->objLanguage->languageText('mod_essayadmin_nosubmittedessays', 'essayadmin');
$rubricLabel = $this->objLanguage->languageText('mod_rubric_name');

/**
* new language items added 20/mar/06
* @author: otim samuel, sotim@dicts.mak.ac.ug
*/
$unmarked=0;
$unmarked=$this->objLanguage->languageText('mod_essayadmin_unmarked');
$markrow=0;
$markrow=$this->objLanguage->languageText('mod_essayadmin_mark');
$closingdate=0;
$closingdate=$this->objLanguage->languageText('mod_essayadmin_closedate');
$downloadEssays=0;
$downloadEssays=$this->objLanguage->languageText('mod_essayadmin_downloadessays', 'essayadmin');
//create the zipped file
$zippedTopic = 0;
$zippedTopic = ereg_replace("'","",$topic);
$zippedTopic = ereg_replace(" ","",$zippedTopic);
//add the dir
//$this->objZip->add_dir($essayadminpath.$zippedTopic."/");

/****************** set up table headers ************************/

$this->setVarByRef('heading',$head);


$tableHd=array();
$tableHd[]=$studenthead;
$tableHd[]=$essayhead;
$tableHd[]=$submithead;
$tableHd[]=$markhead;
$tableHd[]='';

$objTable->cellspacing=2;
$objTable->cellpadding=3;
$objTable->addHeader($tableHd,'heading');

$objTable->row_attributes=' height="5"';
$objTable->startRow();
$objTable->addCell('','20%','bottom');
$objTable->addCell('','40%','bottom');
$objTable->addCell('','15%','bottom');
$objTable->addCell('','15%','bottom');
$objTable->addCell('','10%');
$objTable->endRow();

/******************** set up table data ***********************/

if(!empty($data)){
    $i=0;
    foreach($data as $item){
        $class = ($i++%2) ? 'even':'odd';

        // if essay submitted: allow download
        if($item['fileid']){
            $this->objIcon->setIcon('download');
        $this->objIcon->extra='';
            $this->objIcon->title=$titledownload;
        $this->objLink = new link($this->uri(array('action'=>'download','fileid'=>$item['fileid'])));
        $this->objLink->link=$this->objIcon->show();
            $loadicons=$this->objLink->show();
        $this->objIcon->setIcon('submit2');
        $this->objIcon->title=$titleupload;
        $uriUp = $this->uri(array('action'=>'upload','book'=>$item['id'],'id'=>$item['topicid']));
        $this->objLink = new link($uriUp);
        $this->objLink->link=$this->objIcon->show();
        $loadicons.='&nbsp;&nbsp;&nbsp;&nbsp;'.$this->objLink->show();
        }else $loadicons='';

        if($item['mark']){
            $mark=$item['mark'].' %';
        }else{
			$uriMark = 0;
			$uriMark = $this->uri(array('action'=>'upload','book'=>$item['id'],'id'=>$item['topicid']));
			$this->objLink = new link($uriMark);
			$this->objLink->link=$markrow;
            $mark=$unmarked.'<br>'.$this->objLink->show();
        }

        $uriUp = $this->uri(array('action'=>'upload','book'=>$item['id'],'id'=>$item['topicid']));
        $this->objLink = new link($uriUp);
        $this->objLink->link = $item['student'];
        $this->objLink->title = $titleupload;
        $studentLink = $this->objLink->show();

        $objTable->startRow();
        $objTable->addCell($studentLink,'','','',$class);
        $objTable->addCell($item['essay'],'','','',$class);
        $objTable->addCell($this->objDateformat->formatDate($item['submitdate']),'','','',$class);
        $objTable->addCell($mark,'','','center',$class);
        $objTable->addCell($loadicons,'','','center',$class,' colspan=2');
        $objTable->endRow();
		//add the binary data for the zipped file
		$zippedStudent = 0;
		$zippedStudent = eregi_replace("'","",$item['student']);
		$zippedStudent = eregi_replace(" ","",$zippedStudent);
		/**
		* using the same algorithms as are found within download_page_tpl.php
		* populate the variable $filedata which contains the binary data for the essay
		*/
		$fId=0;
		$fId=$item['fileid'];
		$fdata=0;
		$fdata=$this->objFile->getArray("select * from tbl_essay_filestore where fileId='$fId'");
		if (count($fdata)==0){ // if the file has been deleted
			$filedata = "No data found!";
		} else {
			$fname=0;
			$fname=$fdata[0]['filename'];
			//get the extension
			$fext=0;
			$farray=array();
			$farray=explode(".",$fname);
			$fext=$farray[count($farray)-1];
			$fsize=0;
			$fsize=$fdata[0]['size'];
			$ftype=0;
			$ftype=$fdata[0]['filetype']; 
			$fId2=0;
			$fId2=$fdata[0]['fileId']; 
			$flist=$this->objFile->getArray("select id from tbl_essay_blob where fileId='$fId2' order by segment");
		
			$line=array();
			foreach ($flist as $line)
			{
				$id=0;
				$id=$line['id'];
				$ffiledata=array();
				$ffiledata=$this->objFile->getArray("select * from tbl_essay_blob where id='$id'");
				$filedata = $ffiledata[0]['filedata'];
			}
		}

		$this->objZip->add_file($filedata, "$zippedStudent.$fext");
    }
}else{
    $objTable->startRow();
    $objTable->addCell($noessays,'','','','odd',' colspan="6"');
    $objTable->endRow();
}
$objTable->row_attributes='height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

// back to topic
$this->objLink->title='';
$this->objLink->link($this->uri(array('action'=>'view','id'=>$topicdata[0]['id'])));
$this->objLink->link=$topiclist;
$link1=$this->objLink->show();

/******************* Display table *******************/

//show the due date for this essay
//echo '<br><strong>'.$closingdate.':</strong> '.$this->formatDate($duedate);

if($this->rubric){
    $objPop->resizable = 'yes';
    $objPop->scrollbars = 'yes';
    $objPop->set('location',$this->uri('','rubric'));
    $objPop->set('linktext',$rubricLabel);
    echo '<p>'.$objPop->show();
}

echo $objTable->show();

// essay home
$this->objLink->link($this->uri(array('')));
$this->objLink->link=$topichome;
$link2=$this->objLink->show();

//download submitted essays
$filename = 0;
$filename = $essayadminpath.$zippedTopic.date("Y-m-d-Hms").".zip";


$fileUploader = $this->getObject('fileuploader', 'files');
// Set the Upload Restriction
$fileUploader->allowedCategories = array('documents', 'images');

// Set folder/path in usrfiles to save file
// If the path does not exist, the class will create it for you
$fileUploader->savePath = '/etd/essayadmin/'; // This will then be saved in usrfiles/etd/december


// Set whether to overwrite file
$fileUploader->overwriteExistingFile = TRUE;

// Upload. Returns result as an array
$results = $fileUploader->uploadFile('fileupload1'); // This corresponds with the name of the input - 
//<input type="file"  name="fileupload1" />;

//$fd = fopen($filename, "wb");
//$out = fwrite ($fd, $this->objZip->file());
//fclose ($fd);



//make a record of this file
$this->objDbZip->insertData($zippedTopic.date("Y-m-d-Hms").".zip",$essayadminpath.$zippedTopic."/".$zippedTopic.date("Y-m-d-Hms").".zip",$essayadminDownloadLink.$zippedTopic."/".$zippedTopic.date("Y-m-d-Hms").".zip");

$this->objLink->link("$essayadminDownloadLink$zippedTopic".date("Y-m-d-Hms").".zip");
$this->objLink->link=$downloadEssays;
$link3=0;
$link3=$this->objLink->show();

$objLayer->align='center';
$objLayer->str=$link3.'&nbsp;&nbsp;&nbsp;&nbsp;'.$link2.'&nbsp;&nbsp;&nbsp;&nbsp;'.$link1;
echo $objLayer->show();
?>