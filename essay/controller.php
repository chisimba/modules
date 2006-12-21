<?php
/**
* Controller class for the Essay Management Module.
* @copyright (c) 2004 UWC
* @package essay
* @version 0.9
* @filestore
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot  this page directly');
}
// end security check

/**
* Controller class for the Essay Management Module.
* Students are provided with functionality for booking an essay and uploading it for marking.
* They are also able to download the marked essay.
*
* @author Megan Watson
* @copyright (c) 2004 UWC
* @package essay
* @version 0.9
*/

class essay extends controller
{
    /**
    * Initialise objects used in the module
    */
    public function init()
    {
        // Check if the module is registered and redirect if not.
        // Check if the assignment module is registered and can be linked to.
        $this->objModules =& $this->newObject('modules','modulecatalogue');
        if(!$this->objModules->checkIfRegistered('essayadmin')){
            return $this->nextAction('notregistered',array(),'redirect');
        }
        $this->assignment = FALSE;
        if($this->objModules->checkIfRegistered('Assignment Management', 'assignment')){
            $this->assignment = TRUE;
        } 

        // Get instances of the module classes
        $this->dbessays=& $this->getObject('dbessays');
        $this->dbtopic=& $this->getObject('dbessay_topics');
        $this->dbbook=& $this->getObject('dbessay_book');
        // Get instances of the html elements:
        // form, table, link, textinput, button, icon, layer, checkbox, textarea, iframe
        $this->objForm=& $this->newObject('form','htmlelements');
        $this->objTable=& $this->newObject('htmltable','htmlelements');
        $this->objLayer=& $this->newObject('layer','htmlelements');
        $this->objLink=& $this->newObject('link','htmlelements');
        $this->objInput=& $this->newObject('textinput','htmlelements');
        $this->objButton=& $this->newObject('button','htmlelements');
        $this->objCheck=& $this->newObject('checkbox','htmlelements');
        $this->objText=& $this->newObject('textarea','htmlelements');
        $this->objIcon=& $this->newObject('geticon','htmlelements');
        $this->objIframe=& $this->newObject('iframe','htmlelements');
        // Get an instance of the confirmation object
        $this->objConfirm=& $this->newObject('confirm','utilities');
        // Get an instance of the language object
        $this->objLanguage=& $this->getObject('language','language');
        // Get an instance of the user object
        $this->objUser=& $this->getObject('user','security');
        // Get an instance of the context object
        $this->objContext=& $this->getObject('dbcontext','context');
        // Get an instance of the filestore object and change the tables to essay specific tables
        $this->objFile=& $this->getObject('dbfile','filemanager');
//        $this->objFile->changeTables('tbl_essay_filestore','tbl_essay_blob');
        $this->objHelp = $this->newObject('helplink','help');
     //   $this->objDate = $this->newObject('simplecal','datetime');
        $this->objDate = $this->newObject('datepicker','htmlelements');
        
        $this->objDateformat = $this->newObject('datetime','utilities');

        // Log this call if registered
        if(!$this->objModules->checkIfRegistered('logger', 'logger')){
            //Get the activity logger class
            $this->objLog=$this->newObject('logactivity', 'logger');
            //Log this module call
            $this->objLog->log();
        }
    }

    /**
    * The standard dispatch method for the module.
    * The dispatch() method must return the name of a page body template which will render
    * the module output (for more details see Modules and templating)
    * @return string The template to display
    */
    public function dispatch($action)
    {
        $this->setVar('pageSuppressXML',true);
        // default context = lobby
        $this->contextcode='root';
        $this->context=$this->objLanguage->languageText('word_inlobby','Lobby');

        // get user details
        $this->userId=$this->objUser->userId();
        $this->user=$this->objUser->fullname();

        // check if in context, and get code & title
        if($this->objContext->isInContext()){
            $this->contextcode=$this->objContext->getContextCode();
            $this->context=$this->objContext->getTitle();
            $incontext=TRUE;
        }else{
            $incontext=FALSE;
        }

        // set variable references in templates
        $this->setVarByRef('context',$this->context);
        $this->setVarByRef('contextcode',$this->contextcode);

        // add left display panel
        $topicid=$this->getParam('id');

        switch($action){
            // view essays within a topic
            case 'view':
                // get id of topic being viewed
                $id=$this->getParam('id');
                // get topic name
                $topic=$this->dbtopic->getTopic($id);
                // get essays in topic
                $essays=$this->dbessays->getEssays($id);
                // check if force 1 student per essay and fetch all bookings in topic
                $filter = "where topicid='$id'";
                if($topic[0]['forceone']==1){
                    $booked=$this->dbbook->getBooking($filter);
                }else{
                    $booked=NULL;
                }
                // fetch current users booking
                $filter .= " and studentid='".$this->userId."'";
                $studentbook=$this->dbbook->getBooking($filter);
                // get table display[0]['essayid']
                $list=$this->getEssays($essays,$topic,$studentbook,$booked);
                $this->setVarByRef('list',$list);
                $template='essay_tpl.php';
                
					             
            break;

            // display notes in a pop up window
             case 'showcomment':
                // get essay title
                $essay=$this->getParam('essay');
                // get booking id
                $id=$this->getParam('book');
                // get comment form booking details
                $comment=$this->dbbook->getBooking("where id='$id'",'comment');
                $data[0]['topic']=$essay;
                $data[0]['notes']=$comment[0]['comment'];
                // add head
                $head=$this->objLanguage->languageText('mod_essay_comment','Comment');
                // remove left navigation
                $left='';
                $this->setVarByRef('head',$head);
                $this->setVarByRef('data',$data);
                $this->setVarByRef('leftNav',$left);
                $template='notes_tpl.php';
                //$this->setPageTemplate('essay_page_tpl.php');
            break;

            // display notes in a pop up window
            case 'shownotes':
                // get id of essay
                $id=$this->getParam('essay');
                // get essay details
                $data=$this->dbessays->getEssay($id,'topic, notes');
                // add head
                $head=$this->objLanguage->languageText('mod_essay_notes','Notes');
                // remove left navigation
                $left='';
                $this->setVarByRef('head',$head);
                $this->setVarByRef('data',$data);
                $this->setVarByRef('leftNav',$left);

                $template='notes_tpl.php';
                //$this->setPageTemplate('essay_page_tpl.php');
            break;

            case 'bookessay':
               // get id of essay
                $id=$this->getParam('essay');
                // get topic id
                $topic=$this->getParam('id');
                // get student id (use pk value)
                $student=$this->userId;
                $fields=array('studentid'=>$student,'essayid'=>$id,'topicid'=>$topic,'context'=>$this->contextcode);
                $this->dbbook->bookEssay($fields);
                $this->nextAction('view',array('id'=>$topic));
            break;

            case 'unbook':
                // get topic id
                $topic=$this->getParam('id');
                // get student id (use pk value)
                $student=$this->userId;
                // delete booking
                $this->dbbook->deleteBooking(NULL," where topicid='$topic' and studentid='$student'");
                $this->nextAction('view',array('id'=>$topic));
            break;

            // display students essays details
            case 'viewessays':
                $data=$this->getStudentEssays();
                $this->setVarByRef('data',$data);
                $template='view_essays_tpl.php';
            break;

            // upload an essay for marking or a marked essay & marks & comment
            case 'uploadsubmit':
                // get topic id
                $topic=$this->getParam('id');
                // get booking id
                $book=$this->getParam('book');
                $msg = '';
                $postSubmit = $this->getParam('submit');

                // exit upload form
                if($postSubmit==$this->objLanguage->languageText('word_exit')){
                    return $this->nextAction('viewessays');
                }

                // upload essay and return to form
                if($postSubmit==$this->objLanguage->languageText('mod_essay_upload', 'essay')){

                    // change the file name to fullname_studentId
                    $studentid = $this->userId;
                    $name = $this->user;
                    $ext = explode('.', $_FILES['uploadessay']['name']);
                    $_FILES['uploadessay']['name'] = $name.'_'.$studentid.'.'.$ext[1];

                    // upload file to database
                    $fileid=$this->objFile->getFileName($_FILES['uploadessay']);

                    // save file id and submit date to database
                    $fields=array('fileid'=>$fileid,'submitdate'=>date('Y-m-d'));
                    $this->dbbook->bookEssay($fields,$book);

                    // display success message
                    $msg = $this->objLanguage->languageText('mod_essay_uploadsuccess','essay');
                    $this->setVarByRef('msg',$msg);
                }
                return $this->nextAction('upload',array('book'=>$book,'msg'=>$msg,'id'=>$topic));
            break;

            // display page to upload essay
            case 'upload':
                // get message (if already submitted)
                $msg=$this->getParam('msg');
                $this->setVarByRef('msg',$msg);
                // get booking number
                $id=$this->getParam('book');
                $this->setVarByRef('book',$id);
                $template='upload_tpl.php';
            break;

            case 'download':
                $template='download_tpl.php';
                $this->setPageTemplate('download_page_tpl.php');
            break;

            default:
                $topics = $this->getTopicData();
                $list=$this->getTopics($topics);
                $this->setVarByRef('list',$list);

                $template='essay_tpl.php';
            }
        return $template;
    }

    /**
    * Method to get a list of topics from the database for display.
    * @param array $topics Details of the essay topics
    * @return string $objTable Table containing topic details for display in the template
    */
    public function getTopics($topics)
    {
        // set up html elements
        $objTable=$this->objTable;
        $objLayer=$this->objLayer;
        $objLink=$this->objLink;
        $objIcon=$this->objIcon;
        $objHead=$this->newObject('htmlheading','htmlelements');

        // set up language items
        $subhead=$this->objLanguage->languageText('mod_essay_selecttopic','Select Topic');
        $topicslabel=$this->objLanguage->languageText('mod_essay_topics','essay');
        $duedate=$this->objLanguage->languageText('mod_essay_closedate','essay');
        $viewLabel=$this->objLanguage->languageText('word_view');
        $essaysLabel=$this->objLanguage->languageText('mod_essay_essays','essay');
        $title=$viewLabel.' '.$essaysLabel;
        $heading=$this->objLanguage->languageText('mod_essay_name', 'essay');
        $submittedLabel=$this->objLanguage->languageText('mod_essay_submitted','Submitted');
        $viewSubmitted=$this->objLanguage->languageText('mod_essay_viewbookedsubmitted','essay');
        $assignmentLabel = $this->objLanguage->languageText('mod_assignment_name');
        $percentLabel = $this->objLanguage->languageText('mod_essayadmin_percentyrmark','essayadmin');
        $noTopics = $this->objLanguage->code2Txt('mod_essay_notopics','essay');

        $this->setVarByRef('heading',$heading);

        // table header
        $objTable->row_attributes=' height="25"';
        $objTable->cellpadding=4;
        $objTable->cellspacing=2;
        $tableHd=array();
        $tableHd[]=$topicslabel;
        $tableHd[]=$percentLabel;
        $tableHd[]=$duedate;

        $objTable->addHeader($tableHd,'heading');

        $objTable->row_attributes=' height="2"';
        $objTable->startRow();
        $objTable->addCell('','60%');
        $objTable->addCell('','20%');
        $objTable->addCell('','20%');
        $objTable->endRow();

        /**************** Display topic list in table *******************/

        $i=0;
        if(!empty($topics)){
            foreach($topics as $val){
                $class = ($i++%2) ? 'even':'odd';

                if($val['bypass']==0){
                    $date = $this->objDateformat->formatDate($val['date']);
                }else{
                    $date='';
                }

                $percent = $val['percentage'];

                // set up view essays in topic link
                $this->objLink->link($this->uri(array('action'=>'view','id'=>$val['id'])));
                $this->objLink->link=$val['name'];
                $this->objLink->title=$title;
                $view=$this->objLink->show();

                
                
                  
                // display in table
                $objTable->row_attributes=' height="25"';
                $objTable->startRow();
                $objTable->addCell($view,'','center','',$class);
                $objTable->addCell($percent,'','center','',$class);
                $objTable->addCell($date,'','center','',$class);
                $objTable->endRow();
            }
        }else{
            $objTable->startRow();
            $objTable->addCell($noTopics,'','','','noRecordsMessage','colspan="5"');
            $objTable->endRow();
        }
        $objTable->row_attributes=' height="15"';
        $objTable->startRow();
        $objTable->addCell('','','','','',' colspan="4"');
        $objTable->endRow();

        $objLink->link($this->uri(array('action'=>'viewessays')));
        $objLink->link = $viewSubmitted;
        $back = $objLink->show();

        if($this->assignment){
            $objLink->link($this->uri(array(''), 'assignment'));
            $objLink->link = $assignmentLabel;
            $back .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$objLink->show();
        }
        $objLayer->align = 'center';
        $objLayer->border = 0;
        $objLayer->str = $back;

        $str = $objTable->show();
        $str .= $objLayer->show();

        // return table
        return $str;
    }

    /**
    * Method to get a list of essays associated with the given topic for display.
    * @param array $essays Details of the essays in the topic
    * @param array $topic The topic details
    * @param bool $studentbook Flag whether the student has booked an essay, if TRUE prevent further booking
    * @param bool $booked Flag whether only one student can book an essay, if TRUE prevent another student booking it
    * @return string $objTable Table containing essay info for display.
    */
    public function getEssays($essays,$topic,$studentbook=NULL,$booked=NULL)
    {
        // set up html elements
        $objTable=$this->objTable;
        $objTable2=$this->objTable;
        $objLayer=$this->objLayer;
        $objLink=$this->objLink;
        $objHead=& $this->newObject('htmlheading','htmlelements');

        // set up language elements
        $head=$this->objLanguage->languageText('mod_essay_essay', 'essay').' ';
        $head.=$this->objLanguage->languageText('mod_essay_topic', 'essay').':&nbsp;&nbsp;'.$topic[0]['name'];
        $subhead=$this->objLanguage->languageText('mod_essay_essays', 'essay');
        $descriptionLabel=$this->objLanguage->languageText('mod_essay_description', 'essay');
        $instructionsLabel=ucwords($this->objLanguage->code2Txt('mod_essay_instructions',array('readonlys'=>'students')));
        $duedate=$this->objLanguage->languageText('mod_essay_closedate', 'essay');
        $view=$this->objLanguage->languageText('word_view');
        $title=$view.' '.$this->objLanguage->languageText('mod_essay_notes');
        $title4=$this->objLanguage->languageText('mod_essay_book', 'essay').' '.$this->objLanguage->languageText('mod_essay_essay','essay');
        $title5=$this->objLanguage->languageText('mod_essay_unbookessay');
        $topiclist=$this->objLanguage->languageText('mod_essay_name', 'essay').' '.$this->objLanguage->languageText('word_home');
        $viewSubmitted=$this->objLanguage->languageText('mod_essay_viewbookedsubmitted', 'essay');
        $assignLabel=$this->objLanguage->languageText('mod_assignment_name');
        $percentLabel = $this->objLanguage->languageText('mod_essayadmin_percentyrmark', 'essayadmin');
        $explainBook = $this->objLanguage->languageText('mod_essay_explainbook', 'essay');
        $marklabel = $this->objLanguage->languageText('mod_essay_mark');
        $noEssays = $this->objLanguage->languageText('mod_essay_noessaysintopic');

        $formAction='viewessays';

        $this->setVarByRef('heading',$head);

        /************************ Display Topic data *****************************/

        $objTable2->cellpadding=2;
        $objTable2->startRow();
        $objTable2->addCell('<b>'.$descriptionLabel.'</b>','20%','','','even');
        $objTable2->addCell($topic[0]['description'],'80%','','','even');
        $objTable2->endRow();

        $objTable2->startRow();
        $objTable2->addCell('<b>'.$instructionsLabel.'</b>','20%','','','odd');
        $objTable2->addCell($topic[0]['instructions'],'80%','','','odd');
        $objTable2->endRow();

        $objTable2->startRow();
        $objTable2->addCell('<b>'.$percentLabel.'</b>','20%','','','even');
        $objTable2->addCell($topic[0]['percentage'].' %','80%','','','even');
        $objTable2->endRow();

        $date = $this->objDateformat->formatDate($topic[0]['closing_date']);
        $objTable2->startRow();
        $objTable2->addCell('<b>'.$duedate.'</b>','20%','','','odd');
        $objTable2->addCell($date,'80%','','','odd');
        $objTable2->endRow();

        $objHead->type=4;
        $objHead->str=$subhead;

        $objLayer->border='';
        $objLayer->str=$objTable2->show();
        $str=$objLayer->show().$objHead->show();
        $str .= '<p>'.$explainBook.'</p>';
		  //$str .= $explainBook;
		  

    /************************* Display essay list in table ***********************/

        // check if essay has been submitted and marked, display flag
        if(!empty($studentbook[0]['essayid'])){
            $studentessay = $studentbook[0]['essayid'];
        }else{
            $studentessay = FALSE;
        }

        if(!empty($studentbook[0]['submitdate'])){
            $essaysubmit=$studentbook[0]['submitdate'];
        }else{
            $essaysubmit=NULL;
        }
        if(!empty($studentbook[0]['mark'])){
            $essaymark=$studentbook[0]['mark'];
        }else{
            $essaymark=NULL;
        }
        $i=0;$book=FALSE;$book1=FALSE;
        // check if student has booked an essay
        if($studentessay){
            $book1=TRUE;
        }

        // set up table
        $objTable->cellpadding=5;
        $objTable->cellspacing=2;

        if(!empty($essays)){
            foreach($essays as $val){
                $class = ($i++%2) ? 'even':'odd';
                $id = $val['id'];

                // check if student booked essay
                if($studentessay){
                    if($studentessay==$id){
                        $book1=FALSE;
                        $book=TRUE;
                    }else{
                        $book1=TRUE;
                        $book=FALSE;
                    }
                }else{
                    // if force one: check if essay is booked
                    if($booked){
                        foreach($booked as $item){
                            if($item['essayid']==$id){
                                $book1=TRUE;
                                break;
                            }else{
                                $book1=FALSE;
                            }
                        }
                    }
                }

                // display notes for essay in a pop-up window
                $this->objIcon->setIcon('notes');
                $this->objIcon->title=$title;
                $this->objIcon->alt=$title;
           //     $this->objIcon->extra="height="18" width="18" onclick=\"javascript:window.open('" .$this->uri(array('action'=>'shownotes','essay'=>$id))."', 'essaynotes', 'width="400", height="200", scrollbars="1"')\" ";
                $this->objLink->link('#');
                $this->objLink->link=$this->objIcon->show();
                $show=$this->objLink->show();
                $msg='';$mark='';

                // if student has booked an essay, unlink others, change action to unbook
                if(!$book1){
                    if($book){
                        $this->objLink->link($this->uri(array('action'=>'unbook','essay'=>$id,'id'=>$topic[0]['id'])));
                        $this->objLink->title=$title5;
                        $this->objIcon->title=$title5;
                        $msg = ' ';
                        $msg.=$this->objLanguage->languageText('mod_essay_bookby','essay').' '.$this->user;
                    }else{
                        $this->objLink->link($this->uri(array('action'=>'bookessay','essay'=>$id,'id'=>$topic[0]['id'])));
                        $this->objLink->title=$title4;
                        $this->objIcon->title=$title4;
                    }

                    // if student has submitted, remove link to unbook
                    if($essaysubmit==NULL){
                        $this->objLink->link=$val['topic'];
                        $view=$this->objLink->show();

                        $this->objIcon->setIcon('bullet');
                        $this->objIcon->extra='';
                        $this->objLink->link=$this->objIcon->show();
                        $bookicon=$this->objLink->show();

                    }else{
                        $view = '<b>'.$val['topic'].'</b>';
                        $bookicon='';
                        if($essaymark!=NULL){
                            $mark = '<b>'.$marklabel.':</b> '.$essaymark.'&nbsp;%';
                        }
                    }
                }else{
                    $view = '<b>'.$val['topic'].'</b>';
                    $this->objIcon->setIcon('bullet');
                    $extra='';
                    $bookicon='';
                }

                $icons = $bookicon;

                if(strlen($val['notes']) > 150){
                    $pos = strpos($val['notes'], ' ', 150);
                    $notes = substr($val['notes'], 0, $pos).' ... '.$show;
                }else{
                    $notes = $val['notes'];
                }

                $objTable->row_attributes=' height="25"';
                $objTable->startRow();
                $objTable->addCell($i, '2%', 'center','',$class);
             $objTable->addCell($view .$msg,'30%','center','',$class);
                $objTable->addCell($mark,'','left','',$class);
                $objTable->addCell($notes,'58%','left','',$class);
                $objTable->addCell($icons,'','center','left',$class);
                $objTable->endRow();
            }
        }else{
            $objTable->startRow();
            $objTable->addCell($noEssays,'','','','noRecordsMessage','colspan="5"');
            $objTable->endRow();
        }
        $objTable->row_attributes=' height="10"';
        $objTable->startRow();
        $objTable->addCell('','','','','','colspan="5"');
        $objTable->endRow();


    /******************** Form to return to topic list ************************/

        $objLink->link($this->uri(array('')));
        $objLink->title='';
        $objLink->link=$topiclist;
        $back=$objLink->show().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $objLink->link($this->uri(array('action'=>$formAction,'id'=>$topic[0]['id'])));
        $objLink->link=$viewSubmitted;
        $back.=$objLink->show();

        $objLayer->align='center';
        $objLayer->border=0;
        $objLayer->str=$back;

        $str .= $objTable->show();
        $str .= $objLayer->show();

        // return table
        return $str;
    }

    /**
    * Method to get booked and submitted essays for a student.
    * @return array $data The students essays
    **/
    public function getStudentEssays()
    {
    /**************** import data ********************/

        // get student booked essays
        $data=$this->dbbook->getBooking("where context='".$this->contextcode
        ."' and studentid='".$this->userId."'");

        if($data){
            foreach($data as $key=>$item){
                // get essay info: topic, num
                $essay=$this->dbessays->getEssay($item['essayid'],'id, topic');

                $data[$key]['essay']=$essay[0]['topic'];

                // get topic info: closing date
                $topic=$this->dbtopic->getTopic($item['topicid'],'name, closing_date, bypass');

                $data[$key]['name']=$topic[0]['name'];
                if($topic[0]['bypass']){
                    $data[$key]['date']='';
                }else{
                    $data[$key]['date']=$topic[0]['closing_date'];
                }

                // get booking info: check if submitted or marked
                if(!empty($item['fileid'])){
                    $data[$key]['mark']=$item['mark'];
                }else{
                    $data[$key]['mark']='submit';
                }
            }
        }

    /**************** return data *******************/
        return $data;
    }

    /**
    * Method to get a list of topics, closing date and number of essays submitted in the topic and the number marked.
    * @return array Topic data
    **/
    public function getTopicData()
    {
        // get topic info
        $topics=$this->dbtopic->getTopic(NULL,NULL,"context='".$this->contextcode."'");
        $data=array();

        // count number of marked essays & number of submitted essays in each topic
        if(!empty($topics)){
            foreach($topics as $key=>$item){
                $filter="where topicid='".$item['id']."'";
                $fields="COUNT(fileid) as submitted, COUNT(mark) as marked";
                $bookings=$this->dbbook->getBooking($filter,$fields);
                $data[$key]['id']=$item['id'];
                $data[$key]['name']=$item['name'];
                $data[$key]['date']=$item['closing_date'];
                $data[$key]['bypass']=$item['bypass'];
                $data[$key]['percentage']=$item['percentage'];
                $data[$key]['marked']=$bookings[0]['marked'];
                $data[$key]['submitted']=$bookings[0]['submitted'];
            }
        }
        // return data
        return $data;
    }

    /**
    * Method to take a datetime string and reformat it as text.
    * @param string $date The date in datetime format.
    * @return string $ret The formatted date.
    */
 /*   public function formatDate($date)
    {
        $ret = $this->objDate->formatDate($date);
        return $ret;
    }*/
}
?>