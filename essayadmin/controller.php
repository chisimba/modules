<?php
/**
* Controller class for the Essay Management Module.
* @copyright (c) 2004 UWC
* @package essayadmin
* @version 0.9
* @filestore
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot view this page directly');
}
// end security check

/**
* Controller class for the Essay Management Module.
* Class essay provides functionality for lecturers to create, edit and delete topics. They can
* create, edit and delete essays within the topics and download students submitted essays for
* marking.
*
* Students are provided with functionality for booking an essay and uploading it for marking.
* They are also able to download the marked essay.
*
* @author Megan Watson
* @copyright (c) 2004 UWC
* @package essayadmin
* @version 0.9
*/

class essayadmin extends controller
{
    /**
    * Initialise objects used in the module
    */
    public function init()
    {
        // Check if the module is registered and redirect if not.
        // Check if the assignment module is registered and can be linked to.
        $this->objModules = $this->newObject('modules','modulecatalogue');
	    //   if(!$this->objModules->checkIfRegistered('Essay Management','essayadmin')){
        //      return $this->nextAction('notregistered', array(), 'redirect');
        //  }
        $this->assignment = FALSE;
        if($this->objModules->checkIfRegistered('Assignment Management', 'assignmentadmin')){
            $this->assignment = TRUE;
        }
        $this->rubric = FALSE;
        if($this->objModules->checkIfRegistered(NULL, 'rubric')){
            $this->objRubric = $this->getObject('dbrubricassessments', 'rubric');
            $this->rubric = TRUE;
        }

	    // Get instances of the module classes
        $this->dbessays=  $this->getObject('dbessays', 'essay');
        $this->dbtopic=  $this->getObject('dbessay_topics', 'essay');
        $this->dbbook=  $this->getObject('dbessay_book', 'essay');
        // Get instances of the html elements:
        // form, table, link, textinput, button, icon, layer, checkbox, textarea, iframe
        $this->loadclass('htmltable','htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadclass('form','htmlelements');
        $this->loadClass('htmltable','htmlelements');
        $this->loadClass('layer','htmlelements');
        $this->loadClass('link','htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('button','htmlelements');
        //$checkBox = new checkbox('checkbox','htmlelements');
        $this->loadClass('textarea','htmlelements');
        $this->objIcon= $this->getObject('geticon','htmlelements');
        $this->loadClass('iframe','htmlelements');
        $this->loadClass('htmlHeading','htmlelements');
		$this->objFile =  $this->newObject('upload','filemanager');
        // Get an instance of the confirmation object
        $this->objConfirm= $this->getObject('confirm','utilities');
        // Get an instance of the language object
        $this->objLanguage=  $this->getObject('language','language');
        // Get an instance of the user object
        $this->objUser=  $this->getObject('user','security');
        // Get an instance of the context object
        $this->objContext= $this->getObject('dbcontext','context');
        $this->objHelp = $this->newObject('helplink','help');
       	$this->objDateformat = $this->newObject('dateandtime','utilities');
        $this->objDate = $this->newObject('datepicker','htmlelements');
        // Log this call if registered
        if(!$this->objModules->checkIfRegistered('logger', 'logger')){
            //Get the activity logger class
            $this->objLog=$this->newObject('logactivity', 'logger');
            //Log this module call
            $this->objLog->log();
        }

								$this->objContextGroups = $this->getObject('managegroups', 'contextgroups');
								//Load the activity Streamer
								if($this->objModules->checkIfRegistered('activitystreamer'))
								{
									$this->objActivityStreamer = $this->getObject('activityops', 'activitystreamer');
									$this->eventDispatcher->addObserver ( array ($this->objActivityStreamer, 'postmade' ) );
									$this->eventsEnabled = TRUE;
								} else {
									$this->eventsEnabled = FALSE;
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
		/**
		* management of zip files, added 27/mar/06
		* check if the essayadmin dir has been created
		* @author: otim samuel, sotim@dicts.mak.ac.ug
		*/
      $this->setVar('pageSuppressXML',true);

		$essayadmindir=0;
		$essayadminpath=0;
		$essayadmindir="usrfiles/essayadmin/";
	//	$essayadminpath=KEWL_SITEROOT_PATH."usrfiles/essayadmin/";
		//array may be comprised of the following: array('action' => 'editHeader')
		$parametersArray=array('action' => $this->getParam("action", NULL), 'id' => $this->getParam("id", NULL));
		//the download link, e.g. http://nextgen.mak.ac.ug/
		$essayadminDownloadLink=0;
		$essayadminDownloadLink=$this->uri($parametersArray);
		/*
		* $essayadminDownloadLink is currently made up of
		* http://nextgen.mak.ac.ug/index.php?module=essayadmin
		* or the equivalent. required is to remove
		* index.php?module=essayadmin or its equivalent
		* $_SERVER['QUERY_STRING'] contains everthing after the ?
		* hence by appending this variable to index.php? and running
		* ereg_replace("index.php\?".$_SERVER['QUERY_STRING'],"",$essayadminDownloadLink)
		* add a \? cause the ? is taken as a regular expression
		* should give us http://nextgen.mak.ac.ug/ which can then be appended to
		* $essayadmindir and the required download file for an accurate download link
		*/
		$essayadminReplacement=0;
		$essayadminReplacement="index.php\?".$_SERVER['QUERY_STRING'];
		$essayadminReplacement=ereg_replace("&","&amp;",$essayadminReplacement);
		$essayadminDownloadLink=ereg_replace($essayadminReplacement,"",$essayadminDownloadLink);
		$essayadminDownloadLink.=$essayadmindir;

		if(!is_dir($essayadmindir)) {
			mkdir($essayadmindir, 0777);
		}
		$this->setVar('essayadmindir',$essayadmindir);
		$this->setVar('essayadminpath',$essayadminpath);
		$this->setVar('essayadminDownloadLink',$essayadminDownloadLink);

		//remove all zip files older than 24hrs, or 86,400 seconds
		//$this->objDbZip->deleteOldFiles();

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
            // add a new topic: pass empty variable $data
            case 'addtopic':
                $data=array();
                $head=$this->objLanguage->languageText('mod_essayadmin_newtopic','essayadmin');
                $this->setVarByRef('head',$head);
                $this->setVarByRef('data',$data);
                $template='topic_tpl.php';
            break;

        // save topic
        case 'savetopic':
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

            if($this->getParam('save')==$this->objLanguage->languageText('word_save')){
	            $id=isset($_POST['id'])?$_POST['id']:NULL;
                //$id=$this->getParam('id',NULL);
                //echo gettype($id)."[$id]";

                // get time in correct format
                $date=array_fill(0,3,0);
                $delims='- ';
                $postTimeStamp = $this->getParam('timestamp', 0);
                $word=strtok($postTimeStamp, $delims);
                $i=0;
                while(is_string($word)){
                    $date[$i]=$word;
                    $i++;
                    $word=strtok($delims);
                }

                $time=mktime(0,0,0,$date[1],$date[2],$date[0]);
                $fields=array();
                $fields['name']=$this->getParam('topicarea', '');
                $fields['context']=$this->contextcode;
                $fields['description']=$this->getParam('description', '');
                $fields['instructions']=$this->getParam('instructions', '');
                $fields['percentage']=$this->getParam('percentage', '');
                $fields['closing_date']= $this->getParam('closing_date');

                $bypass = $this->getParam('bypass',NULL);
                $force = $this->getParam('force',NULL);
                //echo "[$bypass]";
                //echo "[$force]";
                //die;

                $fields['bypass'] = ($bypass == 'on')?'1':'0';
	            $fields['forceone'] = ($force == 'on')?'1':'0';

                if(is_null($id)){
	                $this->dbtopic->addTopic($fields);
                    $id=$this->dbtopic->getLastInsertId();
                }
                else {
	                $this->dbtopic->addTopic($fields,$id);
																}

                // set confirmation message
                $message = $this->objLanguage->languageText('mod_essayadmin_confirmtopic','essayadmin');
                $this->setSession('confirm', $message);
						         	//add to activity log
						         	if($this->eventsEnabled)
						         	{
						         		$message = $this->objUser->getsurname()." ".$this->objLanguage->languageText('mod_essayadmin_hasaddedessay', 'essayadmin').": ".$fields['name']." ".$this->objLanguage->languageText('mod_essayadmin_in', 'essayadmin')." ".$this->objContext->getContextCode();
						         	 	$this->eventDispatcher->post($this->objActivityStreamer, "context", array('title'=> $message,
						 																				'link'=> $this->uri(array()),
						 																				'contextcode' => $this->objContext->getContextCode(),
						 																				'author' => $this->objUser->fullname(),
						 																				'description'=>$message));
						 																				
													 				$this->eventDispatcher->post($this->objActivityStreamer, "context", array('title'=> $message,
						 																				'link'=> $this->uri(array()),
						 																				'contextcode' => null,
						 																				'author' => $this->objUser->fullname(),
						 																				'description'=>$message));
						         	}

                return $this->nextAction('view', array('id' => $id, 'confirm' => 'yes'));
            }
            $this->nextAction('');
        break;

        // view essays within a topic
        case 'view':
            // get id of topic being viewed
            $id=$this->getParam('id');
            // get topic name
            $topic=$this->dbtopic->getTopic($id);
            // get essays in topic
            $essays=$this->dbessays->getEssays($id);
            // get table display[0]['essayid']
            $list=$this->getEssays($essays,$topic);
            $this->setVarByRef('list',$list);
            $template='essay_tpl.php';
        break;

        // edit a topic
        case 'edit':
        case 'edittopic':
            // get topic id
            $id=$this->getParam('id');
            // get topic details
            $data=$this->dbtopic->getTopic($id);
            $head=$this->objLanguage->languageText('mod_essayadmin_edittopic','essayadmin').': '.$data[0]['name'];
            $this->setVarByRef('head',$head);
            $this->setVarByRef('data',$data);
            $template='topic_tpl.php';
        break;

        // delete a topic
        case 'delete':
        case 'deletetopic':
            // get topic id
            $id=$this->getParam('id');
            $this->dbtopic->deleteTopic($id);
            $this->deleteEssay($id);
            $back=$this->getParam('back');
            if($back){
                Header("Location: ".$this->uri(array('action'=>'viewbyletter'),'assignmentadmin'));
            }else{
                $this->nextAction('');
            }
        break;

        // list student essay submissions
        case 'mark':


        case 'viewmarktopic':
            // get topic id
            $id=$this->getParam('id');
            $topicdata=$this->dbtopic->getTopic($id,'id, name, closing_date');
            // get booked essays in topic
            $data=$this->dbbook->getBooking("where topicid='$id'");

            // get essay titles and student names for each booked essay
            foreach($data as $key=>$item){
                $essay=$this->dbessays->getEssay($item['essayid'],'topic');
                $data[$key]['essay']=$essay[0]['topic'];
                //$student=$this->objUser->fullname($item['studentid']);
                //$data[$key]['student']=$student;//[0]['fullname'];
                    $data[$key]['studentNo']=$this->objUser->getStaffNumber($item['studentid']); //[0]['fullname'];
                    $data[$key]['student']=$this->objUser->fullname($item['studentid']); //$student;//[0]['fullname'];
                }
            $this->setVarByRef('topicdata',$topicdata);
            $this->setVarByRef('data',$data);
            $template='mark_essays_tpl.php';
        break;


        case 'marktopic':
            // get topic id
            $id=$this->getParam('id');
            $topicdata=$this->dbtopic->getTopic($id,'id, name, closing_date');
            // get booked essays in topic
            $data=$this->dbbook->getBooking("where topicid='$id'");
            // get essay titles and student names for each booked essay
          // echo "<pre>"; print_r($data);  print_r($topicdata); echo "</pre>";


		    foreach($data as $key=>$item){
                $essay=$this->dbessays->getEssay($item['essayid'],'topic');
                $data[$key]['essay']=$essay[0]['topic'];
                //$student=
		        //$studentNo=$this->objUser->fullname($item['studentid']);
                    $data[$key]['studentNo']=$this->objUser->getStaffNumber($item['studentid']); //[0]['fullname'];
                    $data[$key]['student']=$this->objUser->fullname($item['studentid']); //$student;//[0]['fullname'];
            }
            $this->setVarByRef('topicdata',$topicdata);
            $this->setVarByRef('data',$data);
            $template='mark_essays_tpl.php';
        break;

        // add essay
        case 'addessay':
            $data=array();
            // get id of topic
            $id=$this->getParam('id');
            // get topic name
            $topic=$this->dbtopic->getTopic($id,'name, id');
            $head=$this->objLanguage->languageText('mod_essayadmin_newessay','essayadmin');
            $this->setVarByRef('topicname',$topic[0]['name']);
            $this->setVarByRef('topicid',$topic[0]['id']);
            $this->setVarByRef('head',$head);
            $this->setVarByRef('data',$data);
            $template='addessay_tpl.php';
        break;

        // save essay
        case 'saveessay':
            $confirm = NULL;
            if($this->getParam('save')==$this->objLanguage->languageText('word_save')){
                $id=$this->getParam('essay');

                $fields=array();
                $fields['topicid']=$this->getParam('id', '');
                $fields['topic']=$this->getParam('essaytopic', '');
                $fields['notes']=$this->getParam('notes', '');
                $this->dbessays->addEssay($fields,$id);
                //if(empty($id)){
                  //  $id=$this->dbessays->getLastInsertId();
                //}
                // set confirmation message
                $message = $this->objLanguage->languageText('mod_essayadmin_confirmessay', 'essayadmin');
                $this->setSession('confirm', $message);
                $confirm = 'yes';
            }
            $this->nextAction('view',array('id'=>$this->getParam('id'), 'confirm' => $confirm));
        break;

        // edit essay
        case 'editessay':
            // get id of topic
            $id=$this->getParam('id');
            // get topic name
            $topic=$this->dbtopic->getTopic($id,'id, name');
            // get id of essay
            $essay=$this->getParam('essay');
            // get essay details
            $data=$this->dbessays->getEssay($essay);
            $head=$this->objLanguage->languageText('mod_essayadmin_editessay','essayadmin');
            $this->setVarByRef('topicname',$topic[0]['name']);
            $this->setVarByRef('topicid',$topic[0]['id']);
            $this->setVarByRef('data',$data);
            $this->setVarByRef('head',$head);
            $template='addessay_tpl.php';
        break;

        // delete an essay
        case 'deleteessay':
            // get id of essay
            $id=$this->getParam('essay');
            // get topic id
            $topic=$this->getParam('id');
            $this->deleteEssay($topic, $id);
            $this->nextAction('view',array('id'=>$topic));
        break;

        // display students essays details
        case 'viewessays':
            $data=$this->getStudentEssays();
            $this->setVarByRef('data',$data);
            $template='view_essays_tpl.php';
        break;

        case 'exitupload':
            $id = $this->getParam('id', '');
            return $this->nextAction('marktopic',array('id'=>$id));

        // upload an essay for marking or a marked essay & marks & comment
        case 'uploadsubmit':
        	$book = $this->getParam('book');
            // get topic id
            $topic=$this->getParam('id');
            // exit upload form
            $postSubmit = $this->getParam('save');

            if($postSubmit ==$this->objLanguage->languageText('word_exit')){
                return $this->nextAction('marktopic',array('id'=>$topic));
            }

            // if form action is save (only performed by lecturers or admin)
            if($postSubmit==$this->objLanguage->languageText('word_save')){
                // get booking id
                //$book=$this->getParam('book');
                // get mark data
                $mark=$this->getParam('mark', '');
                $comment=$this->getParam('comment', '');
                // insert / update database
                $fields=array('mark'=>$mark,'comment'=>$comment);
                $this->dbbook->bookEssay($fields,$book);
                //$msg='';
                return $this->nextAction('marktopic',array('id'=>$topic));
            }

            // upload essay and return to form
            if($postSubmit==$this->objLanguage->languageText('mod_essayadmin_upload','essayadmin')){
                // get booking id
                //$book=$this->getParam('book');

                // get data
                $mark=$this->getParam('mark', '');
                $comment=$this->getParam('comment', '');

                // get fileid
                $booking=$this->dbbook->getBooking("where id='$book'",'fileid');

                // upload file to database, overwrite original file
                $arrayfiledetails = $this->objFile->uploadFile('file');

				if ($arrayfiledetails === FALSE){
	            	$msg = $this->objLanguage->languageText('mod_essayadmin_uploadfailureunknown', 'essayadmin');
				}
				else if (!$arrayfiledetails['success']) {
					switch ($arrayfiledetails['reason']) {
					case 'bannedfile':
						$reason = $this->objLanguage->languageText('mod_essayadmin_fileupload_bannedfile', 'essayadmin');
						break;
					case 'partialuploaded':
						$reason = $this->objLanguage->languageText('mod_essayadmin_fileupload_partialuploaded', 'essayadmin');
						break;
					case 'nouploadedfileprovided':
						$reason = $this->objLanguage->languageText('mod_essayadmin_fileupload_nouploadedfileprovided', 'essayadmin');
						break;
					case 'doesnotmeetextension':
						$reason = $this->objLanguage->languageText('mod_essayadmin_fileupload_doesnotmeetextension', 'essayadmin');
						break;
					case 'needsoverwrite':
						$reason = $this->objLanguage->languageText('mod_essayadmin_fileupload_needsoverwrite', 'essayadmin');
						break;
					case 'filecouldnotbesaved':
						$reason = $this->objLanguage->languageText('mod_essayadmin_fileupload_filecouldnotbesaved', 'essayadmin');
						break;
					default:
						$reason = $this->objLanguage->languageText('mod_essayadmin_fileupload_unknownreason', 'essayadmin');
					}
	            	$msg = $this->objLanguage->languageText('mod_essayadmin_uploadfailure', 'essayadmin')."<br />"
					.$reason;
					/*
						." REASON: ".$arrayfiledetails['reason']."<br />"
						." FILENAME: ".$arrayfiledetails['name']."<br />"
						." SIZE: ".$arrayfiledetails['size']."<br />"
						." MIMETYPE: ".$arrayfiledetails['mimetype']."<br />"
						." ERRORCODE: ".$arrayfiledetails['errorcode']
					;
					*/
				}
				else {
					$fields=array('lecturerfileid'=>$arrayfiledetails['fileid'],'mark'=>$mark,'comment'=>$comment);
                    $this->dbbook->bookEssay($fields,$book);

                	// display success message
                	$msg = $this->objLanguage->languageText('mod_essayadmin_uploadsuccess','essayadmin');
                	//$this->setVarByRef('msg',$msg);
            	}
			}
			$this->setSession('MSG',$msg);
            return $this->nextAction('upload', array('book'=>$book,/*'msg'=>$msg,*/'id'=>$topic));
        break;

        // display page to upload essay
        case 'upload':
            // get message (if already submitted)
//            $msg=$this->getParam('msg');
			$msg = $this->getSession('MSG','');
            $this->unsetSession('MSG');
            $this->setVar('msg',$msg);
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
    * Method to delete an essay or all essays in a topic.
    * @param string $topic The id of the current topic.
    * @param string $id The id of the essay to delete. Default=NULL if deleting all essays in the topic.
    * @return
    */
    function deleteEssay($topic, $id=NULL)
    {
        // if delete essay $id
        if($id){
            $this->dbessays->deleteEssay($id);
            // delete bookings on essay
            $this->dbbook->deleteBooking(NULL,"where topicid='$topic' and essayid='$id'");
        }else{
            // if deleting topic $topic: delete all essays within topic
            // get essays in topic and delete 1 by 1
            $rows=$this->dbessays->getEssays($topic);
            if($rows){
                foreach($rows as $item){
                    $this->dbessays->deleteEssay($item['id']);
                    // delete bookings on essay
                    $this->dbbook->deleteBooking(NULL,"where topicid='$topic' and essayid='".$item['id']."'");
                }
            }
        }
    }

    /**
    * Method to get a list of topics from the database for display.
    * @param array $topics Details of the essay topics
    * @return string $objTable Table containing topic details for display in the template
    */
    function getTopics($topics)
    {
        // set up html elements
        $objTable= $this->newObject('htmltable','htmlelements');
        $objLayer= new layer;

        $objIcon=$this->objIcon;
        $objHead=$this->newObject('htmlheading','htmlelements');

        // set up language items
        $subhead=$this->objLanguage->languageText('mod_essayadmin_selecttopic','essayadmin');
        $topicslabel=$this->objLanguage->languageText('mod_essayadmin_topics','essayadmin');
        $duedate=$this->objLanguage->languageText('mod_essayadmin_closedate','essayadmin');
        $iconlabel=$this->objLanguage->languageText('mod_essayadmin_editdelete','essayadmin');
        $viewLabel=$this->objLanguage->languageText('word_view');
        $essaysLabel=$this->objLanguage->languageText('mod_essayadmin_essays','essayadmin');
        $title=$viewLabel.' '.$essaysLabel;
        $title1=$this->objLanguage->languageText('word_edit');
        $title2=$this->objLanguage->languageText('word_delete');
        $title3=$this->objLanguage->languageText('mod_essayadmin_newtopic','essayadmin');
        $title4=$this->objLanguage->languageText('mod_essayadmin_markessays','essayadmin');
        $heading=$this->objLanguage->languageText('mod_essayadmin_name','essayadmin');
        $submittedLabel=$this->objLanguage->languageText('mod_essayadmin_submitted','essayadmin');
        $markedlabel=$this->objLanguage->languageText('mod_essayadmin_marked','essayadmin').' / '.$submittedLabel;
        $viewSubmitted=$viewLabel.' '.$submittedLabel.' '.$essaysLabel;
        $assignmentLabel = $this->objLanguage->languageText('mod_assignmentadmin_name','essayadmin');
        $percentLbl=$this->objLanguage->languageText('mod_essayadmin_percentyrmark', 'essayadmin');
        $noTopics = $this->objLanguage->code2Txt('mod_essayadmin_notopicsavailable', 'essayadmin');

        // icon for add new topic
        $this->objIcon->title=$title3;
        $addicon=$this->objIcon->getAddIcon($this->uri(array('action'=>'addtopic')));
        $heading.= '&nbsp;&nbsp;'.$addicon;
        $objLink = new link($this->uri(array('action'=>'addtopic')));
        $objLink->link=$title3;
        $linkbtn=$objLink->show();

        $this->setVarByRef('heading',$heading);

        // table header
        $objTable->cellpadding=4;
        $objTable->cellspacing=2;

        $tableHd=array();
        $tableHd[]=$topicslabel;
        $tableHd[] = $percentLbl;
        $tableHd[]=$duedate;
        $tableHd[]=$markedlabel;
        $tableHd[]=$iconlabel;

        $objTable->addHeader($tableHd,'heading');

        $objTable->row_attributes=' height="0" ';
        $objTable->startRow();
        $objTable->addCell('','40%');
        $objTable->addCell('','15%');
        $objTable->addCell('','15%');
        $objTable->addCell('','15%');
        $objTable->addCell('','15%');
        $objTable->endRow();
        $objTable->row_attributes=' height="25"';

        /**************** Display topic list in table *******************/

        $i=0;
        if(!empty($topics)){
            foreach($topics as $val){
                $class = ($i++%2) ? 'even':'odd';

                if($val['bypass']==0){
                    $date = $this->objDateformat->formatDate($val['closing_date']);
                }else{
                    $date='';
                }

                // set up view essays in topic link
                $objLink->link($this->uri(array('action'=>'view','id'=>$val['id'])));
                $objLink->link=$val['name'];
                $objLink->title=$title;
                $view = $objLink->show();

                $this->objIcon->setIcon('paper');
                $this->objIcon->title=$title;
                $this->objIcon->alt=$title;
                $objLink->link=$this->objIcon->show();
                $show = $objLink->show();

                if($val['submitted']==0){
                    $mark='';
                }else{
                    $this->objIcon->setIcon('comment');
                    $this->objIcon->title=$title4;
                    $this->objIcon->alt=$title4;
                    $objLink->link($this->uri(array('action'=>'marktopic','id'=>$val['id'])));
                $objLink->link = $this->objIcon->show();
                $mark = $objLink->show();
                }

                // set up edit & delete icons
                $this->objIcon->title=$title1;
                $edit=$this->objIcon->getEditIcon($this->uri(array('action'=>'edittopic','id'=>$val['id'])));
                $this->objIcon->title=$title2;
                $this->objIcon->setIcon('delete');
                $this->objConfirm->setConfirm($this->objIcon->show(),$this->uri(array('action'=>'deletetopic','id'=>$val['id'])),$this->objLanguage->languageText('mod_essayadmin_deleterequest','essayadmin').' '.$val['name'].'?');
                $delete=$this->objConfirm->show();
                $icons=$edit.$delete.$show.$mark;

                $markSubmit = $val['marked'].' / '.$val['submitted'];

                $percent = $val['percentage'];

                // display in table
                $date = $this->objDateformat->formatDate($val['closing_date']);
                $objTable->row_attributes=' height="25"';
                $objTable->startRow();
                $objTable->addCell($view,'','center','',$class);
                $objTable->addCell($percent,'','center','',$class);
                $objTable->addCell($date,'','center','',$class);
                $objTable->addCell($markSubmit,'','center','',$class);
                $objTable->addCell($icons,'','center','left',$class);
                $objTable->endRow();
            }
        }else{
            $objTable->row_attributes=' height="15"';
            $objTable->startRow();
            $objTable->addCell($noTopics,'','','','noRecordsMessage',' colspan="5"');
            $objTable->endRow();
        }

        $objTable->row_attributes=' height="15"';
        $objTable->startRow();
        $objTable->addCell('','','','','',' colspan="4"');
        $objTable->endRow();

        $back = $linkbtn;

        if($this->assignment){
            $objLink->link($this->uri(array(''), 'assignmentadmin'));
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
    * @return string $str Table containing essay info for display.
    */
    function getEssays($essays,$topic)
    {
        // set up html elements
        $objTable = new htmltable();
        $objTable2 = new htmltable();
        //$objTable=$this->objTable;
        //$objTable2=$this->objTable;
        $objLayer = new layer;
        //$objLink = $this->objLink;
        $objHead =  new htmlHeading;
        $objMsg  = $this->newObject('timeoutmessage', 'htmlelements');

        // set up language elements
        $head=$this->objLanguage->languageText('mod_essayadmin_essay','essayadmin').' ';
        $head.=$this->objLanguage->languageText('mod_essayadmin_topic','essayadmin').':&nbsp;&nbsp;'.$topic[0]['name'];
        $subhead=$this->objLanguage->languageText('mod_essayadmin_essays','essayadmin');
        $descriptionLabel=$this->objLanguage->languageText('mod_essayadmin_description','essayadmin');
        $instructionsLabel=ucwords($this->objLanguage->code2Txt('mod_essayadmin_instructions','essayadmin'));
        $duedate=$this->objLanguage->languageText('mod_essayadmin_closedate','essayadmin');
        $view=$this->objLanguage->languageText('word_view');
        $title1=$this->objLanguage->languageText('word_edit');
        $title2=$this->objLanguage->languageText('word_delete');
        $title3=$this->objLanguage->languageText('mod_essayadmin_newessay','essayadmin');
        $topiclist=$this->objLanguage->languageText('mod_essayadmin_name','essayadmin').' '.$this->objLanguage->languageText('word_home');
        $viewSubmitted=$this->objLanguage->languageText('mod_essayadmin_viewbookedsubmitted','essayadmin');
        $assignLabel=$this->objLanguage->languageText('mod_assignment_name','essayadmin');
        $percentLbl=$this->objLanguage->languageText('mod_essayadmin_percentyrmark','essayadmin');
        $noEssays = $this->objLanguage->code2Txt('mod_essayadmin_noessaysintopic','essayadmin');

        // add new essay icon
        $this->objIcon->title=$title3;
        $addicon=$this->objIcon->getAddIcon($this->uri(array('action'=>'addessay','id'=>$topic[0]['id'])));
        $subhead.= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$addicon;
        // edit & delete topic
        $this->objIcon->title=$title1;
        $topicEdit=$this->objIcon->getEditIcon($this->uri(array('action'=>'edittopic','id'=>$topic[0]['id'])));
        $this->objIcon->title=$title2;
        $this->objIcon->setIcon('delete');
        $this->objConfirm->setConfirm($this->objIcon->show(),$this->uri(array('action'=>'deletetopic','id'=>$topic[0]['id'])),$this->objLanguage->languageText('mod_essayadmin_deleterequest','essayadmin').' '.$topic[0]['name'].'?');
        $topicDelete=$this->objConfirm->show();
        $topicIcons=$topicEdit.'&nbsp;&nbsp;'.$topicDelete;
        $head.='&nbsp;&nbsp;&nbsp;&nbsp;'.$topicIcons;
        $formAction='marktopic';

        $this->setVarByRef('heading',$head);

        $str = '';
        // set confirm message if exists
        $confirm = $this->getParam('confirm');
        if($confirm == 'yes'){
            $msg = $this->getSession('confirm');
            $this->unsetSession('confirm');
            $objMsg->setMessage($msg.'&nbsp;'.date('d/m/Y H:i'));
            $objMsg->setTimeOut(10000);
            $str .= '<p>'.$objMsg->show().'</p>';
        }

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
        $objTable2->addCell('<b>'.$percentLbl.'</b>','20%','','','even');
        $objTable2->addCell($topic[0]['percentage'].' %','80%','','','even');
        $objTable2->endRow();


        $date = $this->objDateformat->formatDate($topic[0]['closing_date']);
        $objTable2->startRow();
        $objTable2->addCell('<b>'.$duedate.'</b>','20%','','','odd');
      //  $objTable2->addCell($topic[0]['closing_date'].' %','80%','','','even');
        $objTable2->addCell($date,'80%','','','odd');
        $objTable2->endRow();

        $objHead->type=4;
        $objHead->str=$subhead;

        $objLayer = new layer;
        $objLayer->border='';
        $objLayer->str=$objTable2->show();
        $str .= $objLayer->show().$objHead->show();

        /************************* Display essay list in table ***********************/

        $i=0;
        // set up table
        $objTable->width='99%';
        $objTable->cellpadding=5;
        $objTable->cellspacing=2;

        if(!empty($essays)){
            foreach($essays as $val){
                $class = ($i++%2)? 'even':'odd';

                // edit essay
                $objLink = new link;
                $objLink->link($this->uri(array('action'=>'editessay','essay'=>$val['id'],'id'=>$topic[0]['id'])));
                $objLink->link = $val['topic'];
                $objLink->title = $title1;
                $view = $objLink->show();

                $this->objIcon->title=$title1;
                $this->objIcon->extra='';
                $edit=$this->objIcon->getEditIcon($this->uri(array('action'=>'editessay','essay'=>$val['id'],'id'=>$topic[0]['id'])));
                // delete essay display confirmation
                $this->objIcon->title=$title2;
                $this->objIcon->setIcon('delete');
                $this->objConfirm->setConfirm($this->objIcon->show(),$this->uri(array('action'=>'deleteessay','essay'=>$val['id'],'id'=>$topic[0]['id'])),$this->objLanguage->languageText('mod_essayadmin_deleterequest','essayadmin').' '.$val['topic'].'?');
                $delete=$this->objConfirm->show();
                $icons=$edit.$delete;

                if(strlen($val['notes']) > 100){
                    $pos = strpos($val['notes'], ' ', 100);
                    $notes = substr($val['notes'], 0, $pos).'...';
                }else{
                    $notes = $val['notes'];
                }

                $objTable->row_attributes=' height="25"';
                $objTable->startRow();
                $objTable->addCell($i, '2%','center','',$class);
                $objTable->addCell($view,'30%','center','',$class);
                $objTable->addCell($notes,'60%','center','',$class);
                $objTable->addCell($icons,'8%','center','left',$class);
                $objTable->endRow();
            }
        }else{
            $objTable->row_attributes=' height="15"';
            $objTable->startRow();
            $objTable->addCell($noEssays,'','','','noRecordsMessage',' colspan="5"');
            $objTable->endRow();
        }
        $objTable->row_attributes=' height="10"';
        $objTable->startRow();
        $objTable->addCell('','','','','','colspan="4"');
        $objTable->endRow();

        $str .= $objTable->show();

    /******************** Form to return to topic list ************************/

        $objLink = new link($this->uri(array('action'=>'addessay','id'=>$topic[0]['id'])));
        $objLink->title = $title3;
        $objLink->link = $title3;
        $back = $objLink->show();
	// removed this from the uri $formAction

        $objLink = new link($this->uri(array('action'=>'viewmarktopic','id'=>$topic[0]['id'])));
        $objLink->link=$viewSubmitted;
        $back .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$objLink->show();

        $objLink = new link($this->uri(array('')));
        $objLink->title='';
        $objLink->link=$topiclist;
        $back .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$objLink->show();

        $objLayer->align='center';
        $objLayer->border=0;
        $objLayer->str=$back;

        $str .= $objLayer->show();

        // return table
        return $str;
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
                $fields="COUNT(studentfileid) as submitted, COUNT(mark) as marked";
                $bookings=$this->dbbook->getBooking($filter,$fields);
                $data[$key]['id']=$item['id'];
                $data[$key]['name']=$item['name'];
                $data[$key]['closing_date']=$item['closing_date'];
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
/*    public function formatDate($date)
    {
        $ret = $this->objDate->formatDate($date);
        return $ret;
    }
*/
}
?>
