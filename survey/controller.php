<?
/* -------------------- survey extends controller ----------------*/

// security check-must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end security check

/**
* Module class to create and reply to online surveys
* @copyright (c) 2004 KEWL.NextGen
* @version 1.0
* @package survey
* @author Kevin Cyster
*
* $Id: controller.php
*/

class survey extends controller
{
    /**
     * @var string $userId The userId of the current user
     * @access public
     */
    public $userId;

    /**
     * @var string $name The full name of the current user
     * @access public
     */
    public $name;

    /**
     * @var string $email The email address of the current user
     * @access public
     */
    public $email;

    /**
     * @var boolean $isAdmin TRUE if the user is in the site admin group, FALSE if not
     * @access public
     */
    public $isAdmin;

    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        // system objects
        $this->objUser=&$this->newObject('user','security');
        $this->userId=$this->objUser->userId();
        $this->name=$this->objUser->fullname($this->userId);
        $this->email=$this->objUser->email($this->userId);
        $this->isAdmin=$this->objUser->inAdminGroup($this->userId,$group='Site Admin');

        $this->objLanguage=&$this->newObject('language','language');
        $this->objGroupAdmin=&$this->newObject('groupadminmodel','groupadmin');
        $this->objDate=&$this->newObject('datetime','utilities');
        $this->objComment=&$this->newObject('commentinterface','comment');

        $this->objMailer=$this->newObject('email', 'mail');
        $this->objMailer->setValue('from', $this->email);
        $this->objMailer->setValue('fromName', $this->name);

        //db objects
        $this->dbSurvey=&$this->newObject('dbsurvey');
        $this->dbType=&$this->newObject('dbtype');
        $this->dbQuestion=&$this->newObject('dbquestion');
        $this->dbRows=&$this->newObject('dbquestionrow');
        $this->dbColumns=&$this->newObject('dbquestioncol');
        $this->dbResponse=&$this->newObject('dbresponse');
        $this->dbAnswer=&$this->newObject('dbanswer');
        $this->dbItems=&$this->newObject('dbitem');
        $this->dbComment=&$this->newObject('dbcomments');
        $this->dbPages=&$this->newObject('dbpages');
        $this->dbPageQuestions=&$this->newObject('dbpagequestions');

        //survey objects
        $this->groups=&$this->newObject('groups');
        $this->session=&$this->newObject('surveysession');
        $this->validate=&$this->newObject('validate');
        $this->questions=&$this->newObject('questions');

        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity','logger');

        //Log this module call
        $this->objLog->log();
    }

    /**
    * This is the main method of the class
    * It calls other functions depending on the value of $action
    *
    * @access public
    * @param string $action
    * @return
    */
    public function dispatch($action)
    {
        // Now the main switch statement to pass values for $action
        switch($action){
            case 'addsurvey':
                // calls the add_edit template to add a survey record
                $this->setVar('mode','add');
                $this->setVar('error',FALSE);
                return 'add_edit_tpl.php';
                break;

            case 'editsurvey':
                // calls the add_edit template to add a survey record
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator'){
                    $this->setVarByRef('surveyId',$surveyId);
                    $this->setVar('mode','edit');
                    $this->setVar('error',FALSE);
                    return 'add_edit_tpl.php';
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'viewsurvey':
                // calls the view_survey template to view a survey record
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='None' || $userGroup=='Respondents'){
                    return $this->nextAction('');
                }else{
                    $this->setVarByRef('surveyId',$surveyId);
                    return 'view_survey_tpl.php';
                }
                break;

            case 'deletesurvey':
                // deletes a survey
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator' || $this->isAdmin){
                    $this->dbSurvey->deleteSurvey($surveyId);
                    $this->groups->deleteGroups($surveyId);
                    return $this->nextAction('listsurveys');
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'validatesurvey':
                // validates the survey data
                // calls the edit survey template if errors are found or
                // calls the savesurvey action
                $arrSurveyData=$this->moveSurveyData();
                $valid=$this->validate->checkSurveyData();
                if($valid){
                    return $this->nextAction('savesurvey');
                }else{
                    $this->setVar('mode',$arrSurveyData['mode']);
                    $this->setVar('error',TRUE);
                    return 'add_edit_tpl.php';
                }
                break;

            case 'savesurvey':
                // saves the survey data
                // calls the add groups template or
                // calls the add question template if no questions exist or
                // calls the question list template if questions exist
                $arrSurveyData=$this->getSession('survey');
                $mode=$arrSurveyData['mode'];
                $surveyId=$arrSurveyData['survey_id'];
                $this->setVarByRef('surveyId',$surveyId);
                if($mode=='add'){
                    $surveyId=$this->dbSurvey->addSurvey();
                    $this->groups->addGroups($surveyId);
                    $array=array('survey','error');
                    $this->session->deleteSessionData($array);
                    return $this->nextAction('surveygroups',array('survey_id'=>$surveyId));
                }else{
                    $this->dbSurvey->editSurvey();
                    $array=array('survey','error');
                    $this->session->deleteSessionData($array);
                    $arrQuestionList=$this->dbQuestion->listQuestions($surveyId);
                    if(!empty($arrQuestionList)){
                        return $this->nextAction('listquestions',array('survey_id'=>$surveyId));
                    }else{
                        return $this->nextAction('addquestion',array('survey_id'=>$surveyId));
                    }
                }
                break;

            case 'surveygroups':
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator'){
                    $this->setVarByRef('surveyId',$surveyId);
                    return 'group_tpl.php';
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'search':
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator'){
                    $field=$this->getParam('field','surname');
                    $search=$this->getParam('search','');
                    $order=$this->getParam('order','surname');
                    $number=$this->getParam('number',25);
                    $page=$this->getParam('page',0);
                    $this->setVarByRef('surveyId',$surveyId);
                    $this->setVarByRef('field',$field);
                    $this->setVarByRef('search',$search);
                    $this->setVarByRef('order',$order);
                    $this->setVarByRef('number',$number);
                    $this->setVarByRef('page',$page);
                    if($field=='groups'){
                        $arrGroupList=$this->groups->searchGroups($search,$number,$page);
                        $this->setVarByRef('arrGroupList',$arrGroupList);
                        return 'search_groups_tpl.php';
                    }else{
                        $arrUserList=$this->groups->searchUsers($search,$field,$order,$number,$page);
                        $this->setVarByRef('arrUserList',$arrUserList);
                        return 'search_users_tpl.php';
                    }
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'updategroups':
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator'){
                    $arrAssignList=$this->getParam('assign');
                    $mode=$this->getParam('mode');
                    if($mode=='users'){
                        $this->groups->assignUsers($surveyId,$arrAssignList);
                    }else{
                        $this->groups->assignGroups($surveyId,$arrAssignList);
                    }
                    return $this->nextAction('surveygroups',array('survey_id'=>$surveyId));
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'deletegroupusers':
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator'){
                    $arrUserIdList=$this->getParam('userId');
                    $group=$this->getParam('group');
                    $this->groups->deleteGroupUsers($surveyId,$arrUserIdList,$group);
                    return $this->nextAction('surveygroups',array('survey_id'=>$surveyId));
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'listsurveys':
                // calls the default template to list all surveys
                $array=array('survey','question','row','column','error','deletedRows','deletedColumns','answer','page','deletedPages');
                $this->session->deleteSessionData($array);
                $arrSurveyList=$this->dbSurvey->listSurveys();
                $this->setVarByRef('arrSurveyList',$arrSurveyList);
                return 'default_tpl.php';

            case 'mailpopup':
                // send email to all members of the groups and calls the mail confirm template
                // set up user list for all groups taking the survey
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator'){
                    $mode=$this->getParam('mode');
                    $this->setVarByRef('surveyId',$surveyId);
                    $this->setVarByRef('mode',$mode);
                    return 'email_tpl.php';
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'sendemail':
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator'){
                    $mode=$this->getParam('mode');
                    $subject=$this->getParam('subject');
                    $body=$this->getParam('body');
                    if($mode=='Respondents'){
                        $linkText=$this->getParam('link');
                        $objLink=new link($this->uri(array('action'=>'takesurvey','survey_id'=>$surveyId)),'survey');
                        $objLink->link=$linkText;
                        $takeLink=$objLink->show();
                        $body.='<br />'.$takeLink;
                        $groupId=$this->objGroupAdmin->getLeafId(array('Surveys',$surveyId,'Respondents'));
                        $arrRespondentList=$this->objGroupAdmin->getGroupUsers($groupId,array('emailaddress'));
                        $addressList=array();
                        foreach($arrRespondentList as $respondent){
                            $addressList[]=$respondent['emailaddress'];
                        }

                        $this->objMailer->setValue('to',$addressList);
                        $this->objMailer->setValue('subject',$subject);
                        $this->objMailer->setValue('body', $body);
                        $this->objMailer->send();

                        $this->dbSurvey->editSurveyField($surveyId,' email_sent',1);
                        return $this->nextAction('');
                    }elseif($mode=='Observers'){
                        $body.='\n'.$this->getParam('link');
                        $body.='\n'.$this->uri(array(''));
                        $groupId=$this->objGroupAdmin->getLeafId(array('Surveys',$surveyId,'Observers'));
                        $arrObserverList=$this->objGroupAdmin->getGroupUsers($groupId,array('emailaddress'));
                        $addressList=array();
                        foreach($arrObserverList as $observer){
                            $addressList[]=$observer['emailaddress'];
                        }

                        $this->objMailer->setValue('to',$addressList);
                        $this->objMailer->setValue('subject',$subject);
                        $this->objMailer->setValue('body', $body);
                        $this->objMailer->send();

                        return $this->nextAction('surveygroups',array('survey_id'=>$surveyId));
                    }else{
                        $linkText=$this->getParam('link');
                        $objLink=new link($this->uri(''),'survey');
                        $objLink->link=$linkText;
                        $takeLink=$objLink->show();
                        $body.='<br />'.$takeLink;
                        $groupId=$this->objGroupAdmin->getLeafId(array('Surveys',$surveyId,'Collaborators'));
                        $arrCollaboratorList=$this->objGroupAdmin->getGroupUsers($groupId,array('emailaddress'));
                        $addressList=array();
                        foreach($arrCollaboratorList as $collaborator){
                            $addressList[]=$collaborator['emailaddress'];
                        }

                        $this->objMailer->setValue('to',$addressList);
                        $this->objMailer->setValue('subject',$subject);
                        $this->objMailer->setValue('body', $body);
                        $this->objMailer->send();

                        return $this->nextAction('surveygroups',array('survey_id'=>$surveyId));
                    }
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'changestatus':
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator'){
                    $status=$this->getParam('status');
                    if($status=='activate'){
                        $this->dbSurvey->editSurveyField($surveyId,'survey_active','1');
                    }else{
                        $this->dbSurvey->editSurveyField($surveyId,'survey_active','');
                    }
                    return $this->nextAction('listsurveys');
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'addquestion':
                // calls the add question template
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator' || $userGroup=='Collaborators'){
                    $this->setVarByRef('surveyId',$surveyId);
                    $update=$this->getParam('update');
                    $this->setVarByRef('update',$update);
                    $this->setVar('mode','add');
                    $this->setVar('error',FALSE);
                    return 'question_tpl.php';
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'editquestion':
                // calls the add question template
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator' || $userGroup=='Collaborators'){
                    $questionId=$this->getParam('question_id');
                    $this->setVarByRef('questionId',$questionId);
                    $update=$this->getParam('update');
                    $this->setVarByRef('update',$update);
                    $this->setVar('mode','edit');
                    $this->setVar('error',FALSE);
                    return 'question_tpl.php';
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'deletequestion':
                // deletes the question
                // calls the question list template or
                // calls the add question template if there are no questions
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator' || $userGroup=='Collaborators'){
                    $questionId=$this->getParam('question_id');
                    $arrQuestionData=$this->dbQuestion->getQuestion($questionId);
                    $arrPageQuestionData=$this->dbPageQuestions->getQuestionRecord($questionId);
                    $surveyId=$arrQuestionData['0']['survey_id'];
                    $this->dbQuestion->deleteQuestion($questionId);
                    $this->dbPageQuestions->deleteRecordByQuestionId($questionId);
                    if(!empty($arrPageQuestionData)){
                        $pageId=$arrPageQuestionData['0']['page_id'];
                        $arrPageQuestionList=$this->dbPageQuestions->listRows($pageId);
                        if(!empty($arrPageQuestionList)){
                            foreach($arrPageQuestionList as $key=>$pageQuestion){
                                $this->dbPageQuestions->editPageQuestionField($pageQuestion['id'],'question_order',($key+1));
                            }
                        }
                    }
                    $arrQuestionList=$this->dbQuestion->listQuestions($surveyId);
                    if(!empty($arrQuestionList)){
                        $i=1;
                        foreach($arrQuestionList as $question){
                            $questionId=$question['id'];
                            $this->dbQuestion->editQuestionField($questionId,'question_order',$i);
                            $i++;
                        }
                        return $this->nextAction('listquestions',array('survey_id'=>$surveyId));
                    }else{
                        return $this->nextAction('addquestion',array('survey_id'=>$surveyId));
                    }
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'validatequestion':
                // moves the question data in to the session variable
                // calls the add question template if errors are found or
                // calls the  savequestion action
                $surveyId=$this->getParam('survey_id');
                $update=$this->getParam('update');
                $mode=$this->getParam('mode');
                $this->moveQuestionData();
                $this->moveRowData($update);
                $this->moveColumnData($update);
                if($update!='save'){
                    if($mode=='add'){
                        return $this->nextAction('addquestion',array('update'=>$update,'survey_id'=>$surveyId));
                    }else{
                        return $this->nextAction('editquestion',array('update'=>$update,'survey_id'=>$surveyId));
                    }
                }else{
                    $valid=$this->validate->checkQuestionData();
                    if($valid){
                        return $this->nextAction('savequestion',array('mode'=>$mode));
                    }else{
                        $this->setVar('mode',$mode);
                        $this->setVar('error',TRUE);
                        return 'question_tpl.php';
                    }
                }
                break;

            case 'savequestion':
                $mode=$this->getParam('mode');
                $arrQuestionData=$this->getSession('question');
                $typeId=$arrQuestionData['type_id'];
                $presetOptions=$arrQuestionData['preset_options'];
                $surveyId=$arrQuestionData['survey_id'];
                $questionId=$arrQuestionData['question_id'];
                if($mode=='add'){
                    $arrQuestionList=$this->dbQuestion->listQuestions($surveyId);
                    $questionOrder=!empty($arrQuestionList)?(count($arrQuestionList)+1):'1';
                    $questionId=$this->dbQuestion->addQuestion($questionOrder);
                    if($typeId!='init_7' && $typeId!='init_9' && $typeId!='init_10' && $presetOptions!='1'){
                        $this->dbRows->addQuestionRows($surveyId,$questionId);
                    }
                    if($typeId=='init_3' || $typeId=='init_4' || $typeId=='init_5'){
                        $this->dbColumns->addQuestionColumns($surveyId,$questionId);
                    }
                }else{
                    $this->dbQuestion->editQuestion();
                    if($typeId!='init_7' && $typeId!='init_9' && $typeId!='init_10' && $presetOptions!='1'){
                        $this->dbRows->addQuestionRows($surveyId,$questionId);
                        $this->dbRows->editQuestionRows();
                        $this->dbRows->deleteQuestionRows();
                    }
                    if($typeId=='init_3' || $typeId=='init_4' || $typeId=='init_5'){
                        $this->dbColumns->addQuestionColumns($surveyId,$questionId);
                        $this->dbColumns->editQuestionColumns();
                        $this->dbColumns->deleteQuestionColumns();
                    }
                }
                $array=array('question','row','column','deletedRows','deletedColumns','error');
                $this->session->deleteSessionData($array);
                return $this->nextAction('listquestions',array('survey_id'=>$surveyId));
                break;

            case 'listquestions':
                // calls the question list template to list questions for this survey
                $array=array('survey','question','row','column','deletedRows','deletedColumns','error','answer','page','deletedPages');
                $this->session->deleteSessionData($array);
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator' || $userGroup=='Collaborators'){
                    $this->setVarByRef('surveyId',$surveyId);
                    return 'questions_list_tpl.php';
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'copyquestion':
                // copies a question on the database
                // call the edit question template
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator' || $userGroup=='Collaborators'){
                    $questionId=$this->getParam('question_id');
                    $arrQuestionData=$this->dbQuestion->getQuestion($questionId);
                    $arrQuestionData=$arrQuestionData['0'];
                    $typeId=$arrQuestionData['type_id'];
                    $presetOptions=$arrQuestionData['preset_options'];
                    $this->session->addQuestionData($arrQuestionData);

                    $surveyId=$arrQuestionData['survey_id'];
                    $arrQuestionList=$this->dbQuestion->listQuestions($surveyId);
                    $questionOrder=!empty($arrQuestionList)?(count($arrQuestionList)+1):'1';
                    $newQuestionId=$this->dbQuestion->addQuestion($questionOrder);

                    if($typeId!='init_7' && $typeId!='init_9' && $typeId!='init_10' && $presetOptions!='1'){
                        $arrRowData=$this->dbRows->listQuestionRows($questionId);
                        foreach($arrRowData as $key=>$row){
                            $arrRowData[$key]['id']='';
                        }
                        $this->session->addRowData($arrRowData);
                        $this->dbRows->addQuestionRows($surveyId,$newQuestionId);
                    }
                    if($typeId=='init_3' || $typeId=='init_4' || $typeId=='init_5'){
                        $arrColumnData=$this->dbColumns->listQuestionColumns($questionId);
                        foreach($arrColumnData as $key=>$column){
                            $arrColumnData[$key]['id']='';
                        }
                        $this->session->addColumnData($arrColumnData);
                        $this->dbColumns->addQuestionColumns($surveyId,$newQuestionId);
                    }
                    $array=array('question','row','column');
                    $this->session->deleteSessionData($array);
                    return $this->nextAction('editquestion',array('question_id'=>$newQuestionId,'survey_id'=>$surveyId));
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'movequestion':
                // moves the question up or down in the question order
                // calls the question list template
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator' || $userGroup=='Collaborators'){
                    $firstQuestionId=$this->getParam('question_id');
                    $direction=$this->getParam('direction');
                    $arrFirstQuestionData=$this->dbQuestion->getQuestion($firstQuestionId);
                    $arrFirstQuestionData=$arrFirstQuestionData['0'];
                    $surveyId=$arrFirstQuestionData['survey_id'];
                    $firstQuestionOrder=$arrFirstQuestionData['question_order'];
                    if($direction=='down'){
                        $questionOrder=$firstQuestionOrder+1;
                    }else{
                        $questionOrder=$firstQuestionOrder-1;
                    }
                    $arrSecondQuestionData=$this->dbQuestion->getQuestionByQuestionOrder($surveyId,$questionOrder);
                    $arrSecondQuestionData=$arrSecondQuestionData['0'];
                    $secondQuestionId=$arrSecondQuestionData['id'];
                    $secondQuestionOrder=$arrSecondQuestionData['question_order'];
                    $this->dbQuestion->editQuestionField($firstQuestionId,'question_order',$secondQuestionOrder);
                    $this->dbQuestion->editQuestionField($secondQuestionId,'question_order',$firstQuestionOrder);
                    return $this->nextAction('listquestions',array('survey_id'=>$surveyId));
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'previewsurvey':
                // calls the survey survey template top preview the survey
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup!='None' && $userGroup!='Respondents'){
                    $this->setVarByRef('surveyId',$surveyId);
                    $method=$this->getParam('method','return');
                    $this->setVarByRef('method',$method);
                    $this->setVar('mode','view');
                    $this->setVar('error',FALSE);
                    return 'survey_tpl.php';
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'takesurvey':
                // checks id the survey has pages
                // calls the survey template to take the survey if no pages or
                // calls the page survey template to take the survey
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='None' || $userGroup=='Respondents'){
                    $arrSurveyData=$this->dbSurvey->getSurvey($surveyId);
                    $singleResponses=$arrSurveyData['0']['single_responses'];
                    if($singleResponses=='1'){
                        $arrResponseList=$this->dbResponse->listResponses($surveyId);
                        if(!empty($arrResponseList)){
                            foreach($arrResponseList as $response){
                                if($response['userId']==$this->userId){
                                    return $this->nextAction('');
                                }
                            }
                        }
                    }
                    $arrPageList=$this->dbPages->listPages($surveyId);
                    $this->setVarByRef('surveyId',$surveyId);
                    $this->setVar('mode','take');
                    $this->setVar('error',FALSE);
                    if(!empty($arrPageList)){
                        $pageNo=$this->getParam('pageNo');
                        $this->setVarByRef('pageNo',$pageNo);
                        return 'page_survey_tpl.php';
                    }else{
                        return 'survey_tpl.php';
                    }
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'validateresponse':
                // moves the response data in to the session variable
                // calls the survey template if errors are found or
                // calls the  saveresponse action
                $surveyId=$this->getParam('survey_id');
                $pageNo=$this->getParam('pageNo');
                $newPageNo=$this->getParam('newPageNo');
                $arrAnswerData=$this->getParam('questionNo');
                $arrQuestionList=$this->dbQuestion->listQuestions($surveyId);
                $arrPageList=$this->dbPages->listPages($surveyId);
                foreach($arrQuestionList as $key=>$question){
                    if($question['type_id']=='init_10' && isset($arrAnswerData[$key])){
                        $dateAnswer=$this->getParam('questionNo_'.$key);
                        if(!empty($dateAnswer)){
                            $arrAnswerData[$key]['date']=$dateAnswer;
                        }else{
                            $arrAnswerData[$key]['date']='';
                        }
                    }
                }
                if(empty($arrPageList)){
                    $this->session->addAnswerData($arrAnswerData);
                    $valid=$this->validate->checkAnswerData($surveyId);
                    if($valid){
                        return $this->nextAction('saveresponse',array('survey_id'=>$surveyId));
                    }else{
                        $this->setVarByRef('surveyId',$surveyId);
                        $this->setVar('mode','take');
                        $this->setVar('error',TRUE);
                        return 'survey_tpl.php';
                    }
                }else{
                    $temp=$this->getSession('answer');
                    if(!empty($arrAnswerData)){
                        foreach($arrAnswerData as $key=>$answer){
                            $temp[$key]=$answer;
                        }
                    }
                    $this->session->addAnswerData($temp);
                    if(($pageNo==0 && $newPageNo==1) || ($newPageNo<$pageNo)){
                        return $this->nextAction('takesurvey',array('survey_id'=>$surveyId,'pageNo'=>$newPageNo));
                    }else{
                        $valid=$this->validate->checkAnswerData($surveyId,$pageNo);
                        if($valid){
                            if($newPageNo=='submit'){
                                return $this->nextAction('saveresponse',array('survey_id'=>$surveyId));
                            }else{
                                return $this->nextAction('takesurvey',array('survey_id'=>$surveyId,'pageNo'=>$newPageNo));
                            }
                        }else{
                            $this->setVarByRef('surveyId',$surveyId);
                            $this->setVar('mode','take');
                            $this->setVar('error',TRUE);
                            $this->setVarByRef('pageNo',$pageNo);
                            return 'page_survey_tpl.php';
                        }
                    }
                }
                break;

            case 'saveresponse':
                $surveyId=$this->getParam('survey_id');
                $arrSurveyData=$this->dbSurvey->getSurvey($surveyId);
                $responseCounter=$arrSurveyData['0']['response_counter'];
                $respondentNumber=$responseCounter+1;
                $this->dbSurvey->editSurveyField($surveyId,'response_counter',$respondentNumber);
                $responseId=$this->dbResponse->addResponse($surveyId,$respondentNumber);
                $this->validate->checkIfAnswered($surveyId);
                $this->dbAnswer->addAnswer($responseId,$surveyId);
                $this->dbComment->addComments($responseId,$surveyId);
                return $this->nextAction('confirm',array('mode'=>'respond','survey_id'=>$surveyId));
                break;

            case 'confirm':
                // calls the confirm template
                $mode=$this->getParam('mode');
                $surveyId=$this->getParam('survey_id');
                if($mode=='respond'){
                    $arrSurveyData=$this->dbSurvey->getSurvey($surveyId);
                    $this->setVarByRef('arrSurveyData',$arrSurveyData);
                }else{
                    $arrGroupList=$this->groups->getSurveyGroups($surveyId);
                    $this->setVarByRef('arrGroupList',$arrGroupList);
                }
                $this->setVar('mode',$mode);
                return 'confirm_tpl.php';

            case 'viewresults':
                // calls the results template to view results
                $surveyId=$this->getParam('survey_id');
                $canViewResults=$this->canViewResults($surveyId);
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if(($userGroup!='None' && $userGroup!='Respondents') || $canViewResults){
                    $this->setVarByRef('surveyId',$surveyId);
                    return 'results_tpl.php';
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'viewresponses':
                // calls the response template to view responses
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup!='None' && $userGroup!='Respondents'){
                    $this->setVarByRef('surveyId',$surveyId);
                    $respondentNumber=$this->getParam('respondent_number',1);
                    $this->setVar('respondentNumber',$respondentNumber);
                    return 'response_tpl.php';
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'viewcomments':
                // calls the comments template to view comments on questions
                $surveyId=$this->getParam('survey_id');
                $canViewResults=$this->canViewResults($surveyId);
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if(($userGroup!='None' && $userGroup!='Respondents') || $canViewResults){
                    $questionId=$this->getParam('question_id');
                    $this->setVarByRef('questionId',$questionId);
                    return 'comments_tpl.php';
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'viewopen':
                // calls the open template to view open ended results
                $surveyId=$this->getParam('survey_id');
                $canViewResults=$this->canViewResults($surveyId);
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if(($userGroup!='None' && $userGroup!='Respondents') || $canViewResults){
                    $questionId=$this->getParam('question_id');
                    $this->setVarByRef('questionId',$questionId);
                    return 'open_tpl.php';
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'managepages':
                // calls the pages template to manage survey pages
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator' || $userGroup=='Collaborators'){
                    $this->setVarByRef('surveyId',$surveyId);
                    $update=$this->getParam('update');
                    $this->setVarByRef('update',$update);
                    $this->setVar('error',FALSE);
                    return 'pages_tpl.php';
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'validatepages':
                // moves the page data in to the session variable
                // calls the pages template if errors are found or
                // calls the  savepages action
                $surveyId=$this->getParam('survey_id');
                $update=$this->getParam('update');
                $this->movePageData($update);
                if($update=='deleteall'){
                    $this->dbPages->deleteAllPages($surveyId);
                    return $this->nextAction('listquestions',array('survey_id'=>$surveyId));
                }elseif($update!='save'){
                    return $this->nextAction('managepages',array('survey_id'=>$surveyId,'update'=>$update));
                }else{
                    $valid=$this->validate->checkPageData($surveyId);
                    if($valid){
                        return $this->nextAction('savepages',array('survey_id'=>$surveyId));
                    }else{
                        $this->setVar('error',TRUE);
                        $this->setVarByRef('update',$update);
                        $this->setVarByRef('surveyId',$surveyId);
                        return 'pages_tpl.php';
                    }
                }
                break;

            case 'savepages':
                $surveyId=$this->getParam('survey_id');
                $this->dbPages->addPages($surveyId);
                $this->dbPages->editPages();
                $this->dbPages->deletePages();
                return $this->nextAction('listquestions',array('survey_id'=>$surveyId));
                break;

            case 'assignquestions':
                // assigns questions to pages
                // calls the questions list template
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator' || $userGroup=='Collaborators'){
                    $mode=$this->getParam('mode');
                    $newPageId=$this->getParam('newPageId');
                    $arrQuestionId=$this->getParam('arrQuestionId');
                    if($mode!='reassign'){
                        $this->dbPageQuestions->addPageQuestions($newPageId,$surveyId,$arrQuestionId);
                    }else{
                        if(!empty($arrQuestionId)){
                            $arrPageQuestionList=$this->dbPageQuestions->listRows($newPageId);
                            $i=!empty($arrPageQuestionList)?count($arrPageQuestionList)+1:'1';
                            foreach($arrQuestionId as $questionId){
                                $arrPageQuestionData=$this->dbPageQuestions->getQuestionRecord($questionId);
                                $id=$arrPageQuestionData['0']['id'];
                                $pageId=$arrPageQuestionData['0']['page_id'];
                                $this->dbPageQuestions->editRecord($id,$newPageId,$i);
                                $i++;
                            }
                            $arrPageQuestionList=$this->dbPageQuestions->listRows($pageId);
                            if(!empty($arrPageQuestionList)){
                                foreach($arrPageQuestionList as $key=>$pageQuestion){
                                    $id=$pageQuestion['id'];
                                    $this->dbPageQuestions->editRecord($id,$pageId,($key+1));
                                }
                            }
                        }
                    }
                    return $this->nextAction('listquestions',array('survey_id'=>$surveyId));
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'movepagequestion':
                // moves the question up or down in the question order
                // calls the question list template
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup=='Creator' || $userGroup=='Collaborators'){
                    $questionId=$this->getParam('question_id');
                    $direction=$this->getParam('direction');
                    $arrFirstQuestionData=$this->dbPageQuestions->getQuestionRecord($questionId);
                    $arrFirstQuestionData=$arrFirstQuestionData['0'];
                    $firstQuestionId=$arrFirstQuestionData['id'];
                    $pageId=$arrFirstQuestionData['page_id'];
                    $firstQuestionOrder=$arrFirstQuestionData['question_order'];
                    if($direction=='down'){
                        $questionOrder=$firstQuestionOrder+1;
                    }else{
                        $questionOrder=$firstQuestionOrder-1;
                    }
                    $arrSecondQuestionData=$this->dbPageQuestions->getRecordByOrderNo($pageId,$questionOrder);
                    $arrSecondQuestionData=$arrSecondQuestionData['0'];
                    $secondQuestionId=$arrSecondQuestionData['id'];
                    $secondQuestionOrder=$arrSecondQuestionData['question_order'];
                    $this->dbPageQuestions->editPageQuestionField($firstQuestionId,'question_order',$secondQuestionOrder);
                    $this->dbPageQuestions->editPageQuestionField($secondQuestionId,'question_order',$firstQuestionOrder);
                    return $this->nextAction('listquestions',array('survey_id'=>$surveyId));
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'copysurvey':
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup!='None' && $userGroup!='Respondents'){
                    $arrPageList=$this->dbPages->listPages($surveyId);
                    $newSurveyId=$this->dbSurvey->copySurvey($surveyId);
                    $this->groups->copyGroups($surveyId,$newSurveyId);
                    if(!empty($arrPageList)){
                        $this->dbPages->copyPages($surveyId,$newSurveyId);
                    }else{
                        $this->dbQuestion->copyQuestions($surveyId,$newSurveyId);
                    }
                    return $this->nextAction('editsurvey',array('survey_id'=>$newSurveyId));
                }else{
                    return $this->nextAction('');
                }
                break;

            case 'viewobservercomments':
                $surveyId=$this->getParam('survey_id');
                $userGroup=$this->groups->getUserGroup($this->userId,$surveyId);
                if($userGroup!='Creator' || $userGroup=='Collaborators' || $userGroup='Observers'){
                    $this->setVarByRef('surveyId',$surveyId);
                    return 'view_comments_tpl.php';
                }else{
                    return $this->nextAction('');
                }
                break;

            default:
                // calls the default template to list all surveys
                return $this->nextAction('listsurveys');
                break;
        }
    }

    /**
    * Method to move the add survey form data to the session variable
    *
    * @return array $arrSurveyData An array with the survey data
    */
    function moveSurveyData()
    {
        $arrSurveyData=array();
        $arrSurveyData['survey_id']=$this->getParam('survey_id');
        $arrSurveyData['survey_name']=$this->getParam('survey_name');
        $arrSurveyData['start_date']=$this->getParam('start_date');
        $arrSurveyData['end_date']=$this->getParam('end_date');
        $arrSurveyData['max_responses']=$this->getParam('max_responses');
        $arrSurveyData['recorded_responses']=$this->getParam('recorded_responses');
        $arrSurveyData['single_responses']=$this->getParam('single_responses');
        $arrSurveyData['view_results']=$this->getParam('view_results');
        $arrSurveyData['intro_label']=$this->getParam('intro_label');
        $arrSurveyData['intro_text']=$this->getParam('intro_text');
        $arrSurveyData['thanks_label']=$this->getParam('thanks_label');
        $arrSurveyData['thanks_text']=$this->getParam('thanks_text');
        $arrSurveyData['mode']=$this->getParam('mode');
        $this->session->addSurveyData($arrSurveyData);

        return $arrSurveyData;
    }

    /**
    * Method to move the question data to the session variable
    *
    * @return NULL
    */
    function moveQuestionData()
    {
        $arrQuestionData=array();
        $arrQuestionData['question_id']=$this->getParam('question_id');
        $arrQuestionData['survey_id']=$this->getParam('survey_id');
        $arrQuestionData['type_id']=$this->getParam('type_id');
        $arrQuestionData['question_text']=$this->getParam('question_text');
        $arrQuestionData['question_subtext']=$this->getParam('question_subtext');
        $arrQuestionData['compulsory_question']=$this->getParam('compulsory_question');
        $arrQuestionData['vertical_alignment']=$this->getParam('vertical_alignment');
        $arrQuestionData['comment_requested']=$this->getParam('comment_requested');
        $arrQuestionData['comment_request_text']=$this->getParam('comment_request_text');
        $arrQuestionData['radio_element']=$this->getParam('radio_element');
        $arrQuestionData['preset_options']=$this->getParam('preset_options');
        $arrQuestionData['true_or_false']=$this->getParam('true_or_false');
        $arrQuestionData['rating_scale']=$this->getParam('rating_scale');
        $arrQuestionData['constant_sum']=$this->getParam('constant_sum');
        $arrQuestionData['minimum_number']=$this->getParam('minimum_number');
        $arrQuestionData['maximum_number']=$this->getParam('maximum_number');
        $this->session->addQuestionData($arrQuestionData);
    }

    /**
    * Method to move the question row data to the session variable
    *
    * @param string $update A variable indicating what action is to be performed
    * @return NULL
    */
    function moveRowData($update)
    {
        $arrRowIdData=$this->getParam('arrRowId',array('','',''));
        $arrRowNoData=$this->getParam('arrRowNo');
        $arrRowTextData=$this->getParam('arrRowText');
        $arrRowData=array();
        foreach($arrRowIdData as $key=>$id){
            $arrRowData[]=array('id'=>$id,'row_order'=>$arrRowNoData[$key],'row_text'=>$arrRowTextData[$key]);
        }
        if($update=='addrow'){
            $arrRowData[]=array('id'=>'','row_order'=>'','row_text'=>'');
        }
        $temp=explode("_",$update);
        if($temp['0']=='deleterow'){
            if(isset($temp['1'])){
                $rowId=$arrRowData[$temp['1']]['id'];
                if($rowId!=''){
                    $this->session->addDeletedRowId($rowId);
                }
                unset($arrRowData[$temp['1']]);
            }
        }
        $arrRowData=array_merge($arrRowData);
        $this->session->addRowData($arrRowData);
    }

    /**
    * Method to move the question column data to the session variable
    *
    * @param string $update A variable indicating what action is to be performed
    * @return NULL
    */
    function moveColumnData($update)
    {
        $arrColumnIdData=$this->getParam('arrColumnId',array('','',''));
        $arrColumnNoData=$this->getParam('arrColumnNo');
        $arrColumnTextData=$this->getParam('arrColumnText');
        $arrColumnData=array();
        foreach($arrColumnIdData as $key=>$id){
            $arrColumnData[]=array('id'=>$id,'column_order'=>$arrColumnNoData[$key],'column_text'=>$arrColumnTextData[$key]);
        }
        if($update=='addcolumn'){
            $arrColumnData[]=array('id'=>'','column_order'=>'','column_text'=>'');
        }
        $temp=explode("_",$update);
        if($temp['0']=='deletecolumn'){
            if(isset($temp['1'])){
                $columnId=$arrColumnData[$temp['1']]['id'];
                if($columnId!=''){
                    $this->session->addDeletedColumnId($columnId);
                }
                unset($arrColumnData[$temp['1']]);
            }
        }
        $arrColumnData=array_merge($arrColumnData);
        $this->session->addColumnData($arrColumnData);
    }

    /**
    * Method to move the survey page data to the session variable
    *
    * @param string $update A variable indicating what action is to be performed
    * @return NULL
    */
    function movePageData($update)
    {
        $arrPageIdData=$this->getParam('arrPageId',array('','',''));
        $arrPageOrderData=$this->getParam('arrPageOrder');
        $arrPageLabelData=$this->getParam('arrPageLabel');
        $arrPageTextData=$this->getParam('arrPageText');
        $arrPageData=array();
        foreach($arrPageIdData as $key=>$id){
            $arrPageData[]=array('id'=>$id,'page_order'=>$arrPageOrderData[$key],'page_label'=>$arrPageLabelData[$key],'page_text'=>$arrPageTextData[$key]);
        }
        if($update=='addpage'){
            $arrPageData[]=array('id'=>'','page_order'=>'','page_label'=>'','page_text'=>'');
        }
        $temp=explode("_",$update);
        if($temp['0']=='deletepage'){
            if(isset($temp['1'])){
                $pageId=$arrPageData[$temp['1']]['id'];
                if($pageId!=''){
                    $this->session->addDeletedPageId($pageId);
                }
                unset($arrPageData[$temp['1']]);
            }
        }
        if($temp['0']=='movepage'){
            if($temp['2']=='down'){
                $firstPage=$arrPageData[$temp['1']];
                $secondPage=$arrPageData[($temp['1']+1)];
                $arrPageData[$temp['1']]=$secondPage;
                $arrPageData[($temp['1']+1)]=$firstPage;
            }else{
                $firstPage=$arrPageData[$temp['1']];
                $secondPage=$arrPageData[($temp['1']-1)];
                $arrPageData[$temp['1']]=$secondPage;
                $arrPageData[($temp['1']-1)]=$firstPage;
            }
        }
        $arrPageData=array_merge($arrPageData);
        $this->session->addPageData($arrPageData);
        if($temp['0']=='movepage'){
            $this->dbPages->editPages();
        }
    }

    /**
    * Method to format a date
    *
    * @access public
    * @param string/long $date The date to format in datetime string format or timestamp format
    * @param bool $isTimestamp TRUE if $date is a timestamp FALSE if $date is datetime string
    * @param bool $fullMonth TRUE if full month text is to be displayed FALSE if 3 letters
    * @param bool $showTime TRUE if the time is to be displayed FALSE if not
    * @param bool $showSeconds TRUE if the seconds are be displayed FALSE if not
    * @return string $ret The formatted date
    */
    function formatDate($date,$isTimestamp=FALSE,$fullMonth=TRUE,$showTime=FALSE,$showSeconds=FALSE)
    {
        // check if time stamp
        if(!$isTimestamp){
            // convert date to timestamp
            $timestamp=strtotime($date);
        }else{
            $timestamp=$date;
        }

        // explode date
        $explodedDate=getdate($timestamp);

        // check month display
        if($fullMonth){
            $arrayOfMonths=$this->objDate->getMonthsAsArray();
        }else{
            $arrayOfMonths=$this->objDate->getMonthsAsArray('3letter');
        }
        $month=$arrayOfMonths[$explodedDate['mon']-1];

        // build date string
        $ret="<nobr>";
        $ret.=$explodedDate['mday'];
        $ret.="&nbsp;".$month;
        $ret.="&nbsp;".$explodedDate['year'];

        // check time display
        if($showTime){
            $ret.="&nbsp;";
            if($explodedDate['hours']<10){
                $ret.="0".$explodedDate['hours'];
            }else{
                $ret.=$explodedDate['hours'];
            }
            $ret.=":";
            if($explodedDate['minutes']<10){
                $ret.="0".$explodedDate['minutes'];
            }else{
                $ret.=$explodedDate['minutes'];
            }
            // check seconds display
            if($showSeconds){
                $ret.=":";
                if($explodedDate['seconds']<10){
                    $ret.="0".$explodedDate['seconds'];
                }else{
                    $ret.=$explodedDate['seconds'];
                }
            }
        }

        $ret.="</nobr>";

        return $ret;
    }

    /**
    * Method to determine if a respondent can view the results
    *
    * @access public
    * @param string $surveyId The id of the current survey
    * @return boolean $canViewResults TRUE if the respondent can view the results FALSE if not
    */
    function canViewResults($surveyId)
    {
        $arrSurveyData=$this->dbSurvey->getSurvey($surveyId);
        $arrResponseList=$this->dbResponse->listResponses($surveyId);
        $canViewResults=FALSE;
        if(!empty($arrResponseList)){
            foreach($arrResponseList as $response){
                if($response['user_id']==$this->userId){
                    if($arrSurveyData[0]['view_results']==1){
                        $canViewResults=TRUE;
                    }
                    break;
                }
            }
        }
        return $canViewResults;
    }
}
?>