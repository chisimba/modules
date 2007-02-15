<?php
/* -------------------- internalmail extends controller ----------------*/
// security check-must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Module class to create and reply to email
 * @copyright (c) 2004 KEWL.NextGen
 * @version 1.0
 * @package email
 * @author Kevin Cyster
 *
 * $Id: controller.php
 */
class email extends controller
{
    /**
     * @var string $userId The userId of the current user
     * @access private
     */
    protected $userId;

    /**
     * @var string $name The full name of the current user
     * @access private
     */
    private $name;

    /**
     * @var string $email The email address of the current user
     * @access private
     */
    private $email;

    /**
     * @var string $maxSize The maximum size for email attachments e.g. 2M (2 Megabytes)
     * @access private
     */
    protected $maxSize;

    /**
     * Method to construct the class.
     *
     * @access public
     * @return
     */
    public function init()
    {
        // user objects
        $this->sqlUsers = &$this->newObject('sqlusers', 'security');
        $this->objUser = &$this->newObject('user', 'security');
        $this->userId = $this->objUser->userId();
        $this->name = $this->objUser->fullname($this->userId);
        $this->email = $this->objUser->email($this->userId);

        // system objects
        $this->objLanguage = &$this->newObject('language', 'language');
        $this->objGroups = & $this->newObject('managegroups', 'contextgroups');
        $this->objGroupAdmin = &$this->newObject('groupadminmodel', 'groupadmin');
        $this->objContext = &$this->newObject('dbcontext', 'context');
        $this->objDate = &$this->newObject('datetime', 'utilities');
        $this->objConfig = &$this->newObject('altconfig', 'config');
        $this->noSubject = $this->objLanguage->languageText('phrase_nosubject');

        // set upload size
        $this->objSysconfig = &$this->newObject('dbsysconfig', 'sysconfig');
        $this->uploadSize = $this->objSysconfig->getValue('ATTACHMENT_MAX_SIZE', 'email');
        $attachmentSize = substr($this->uploadSize, 0, -1);
        $maxPost = substr(ini_get('post_max_size') , 0, -1);
        $maxUpload = substr(ini_get('upload_max_filesize') , 0, -1);
        $this->maxSize = min($attachmentSize, $maxPost, $maxUpload);
        $this->maxFileSize = $this->maxSize*1048576;

        //db objects
        $this->dbFolders = &$this->newObject('dbfolders');
        $this->dbBooks = &$this->newObject('dbaddressbooks');
        $this->dbBookEntries = &$this->newObject('dbbookentries');
        $this->dbEmailUsers = &$this->newObject('dbemailusers');
        $this->dbEmail = &$this->newObject('dbemail');
        $this->dbRouting = &$this->newObject('dbrouting');
        $this->dbAttachments = &$this->newObject('dbattachments');
        $this->dbConfiguration = &$this->newObject('dbconfiguration');
        $this->dbRules = &$this->newObject('dbrules');
        $this->emailFiles = &$this->newObject('emailfiles');
        /*
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity','logger');

        //Log this module call
        $this->objLog->log();
        */
    }

    /**
     * This is the main method of the class
     * It calls other functions depending on the value of $action
     *
     * @access public
     * @param string $action The action the module is to perform
     * @return various Returns other actions or templates
     */
    public function dispatch($action)
    {
        // Now the main switch statement to pass values for $action
        switch ($action) {
            case 'managefolders':
                $currentFolderId = $this->getParam('currentFolderId');
                $mode = $this->getParam('mode');
                $folderId = $this->getParam('folderId');
                $addbutton = $this->getParam('addbutton', NULL);
                $editbutton = $this->getParam('editbutton', NULL);
                $cancelbutton = $this->getParam('cancelbutton', NULL);
                if ($addbutton == 'Submit') {
                    $folderName = $this->getParam('folderName');
                    $this->dbFolders->addFolder($folderName);
                    return $this->nextAction('managefolders', array(
                        'currentFolderId' => $currentFolderId
                    ));
                } elseif ($editbutton == 'Submit') {
                    $folderName = $this->getParam('folderName');
                    $this->dbFolders->editFolder($folderId, $folderName);
                    return $this->nextAction('managefolders', array(
                        'currentFolderId' => $currentFolderId
                    ));
                } elseif ($mode == 'delete') {
                    $this->dbFolders->deleteFolder($folderId);
                    return $this->nextAction('managefolders', array(
                        'currentFolderId' => $currentFolderId
                    ));
                } elseif ($cancelbutton == 'Cancel') {
                    return $this->nextAction('managefolders', array(
                        'currentFolderId' => $currentFolderId
                    ));
                }
                $arrFolderList = $this->dbFolders->listFolders();
                $this->setVarByRef('mode', $mode);
                $this->setVarByRef('folderId', $folderId);
                $this->setVarByRef('arrFolderList', $arrFolderList);
                $this->setVarByRef('currentFolderId', $currentFolderId);
                return 'folders_tpl.php';
                break;

            case 'compose':
                $arrUserId = $this->getParam('userId');
                if (!empty($arrUserId)) {
                    if (!is_array($arrUserId)) {
                        $arrUserId = explode('|', $arrUserId);
                    }
                    $toList = '';
                    foreach($arrUserId as $key => $userId) {
                        $icon = $this->deleteIcon($userId);
                        $name = $this->dbRouting->getName($userId);
                        $toList.= '<span id="'.$userId.'">'.$name.$icon.'&nbsp;&nbsp;&nbsp;</span>';
                    }
                    $recipientList = implode('|', $arrUserId);
                } else {
                    $toList = '';
                    $recipientList = '';
                }
                $subject = $this->getParam('subject');
                $message = $this->getParam('message', NULL);
                $error = $this->getParam('error');
                $emailId = $this->getParam('emailId', NULL);
                $this->setVarByRef('toList', $toList);
                $this->setVarByRef('recipientList', $recipientList);
                $this->setVarByRef('subject', $subject);
                $this->setVarByRef('message', $message);
                $this->setVarByRef('error', $error);
                $this->setVarByRef('emailId', $emailId);
                return 'compose_tpl.php';
                break;

            case 'composelist':
                $field = $this->getParam('field');
                if($field == 'firstname'){
                    $search = $this->getParam('firstname');
                }else{
                    $search = $this->getParam('surname');
                }
                return $this->composeList($search, $field);

            case 'makelist':
                $recipientList = $this->getParam('recipientList');
                return $this->makeList($recipientList);

            case 'manageaddressbooks':
                $mode = $this->getParam('mode');
                $bookId = $this->getParam('bookId');
                $addbutton = $this->getParam('addbutton', NULL);
                $editbutton = $this->getParam('editbutton', NULL);
                $cancelbutton = $this->getParam('cancelbutton', NULL);
                if ($addbutton == 'Submit') {
                    $bookName = $this->getParam('bookName');
                    $this->dbBooks->addBook($bookName);
                    return $this->nextAction('manageaddressbooks');
                } elseif ($editbutton == 'Submit') {
                    $bookName = $this->getParam('bookName');
                    $this->dbBooks->editBook($bookId, $bookName);
                    return $this->nextAction('manageaddressbooks');
                } elseif ($mode == 'delete') {
                    $this->dbBookEntries->deleteBook($bookId);
                    $this->dbBooks->deleteBook($bookId);
                    return $this->nextAction('manageaddressbooks');
                } elseif ($cancelbutton == 'Cancel') {
                    return $this->nextAction('manageaddressbooks');
                }
                $arrContextList = $this->objGroups->usercontextcodes($this->userId);
                $this->setVarByRef('arrContextList', $arrContextList);
                $arrBookList = $this->dbBooks->listBooks();
                $this->setVarByRef('arrBookList', $arrBookList);
                $this->setVarByRef('mode', $mode);
                $this->setVarByRef('bookId', $bookId);
                return 'addressbook_tpl.php';
                break;

            case 'addressbook':
                $contextCode = $this->getParam('contextcode', NULL);
                $groupId = $this->objGroupAdmin->getLeafId(array(
                    $contextCode
                ));
                $arrContextUserList = $this->objGroupAdmin->getSubGroupUsers($groupId, array(
                    'userId',
                    'firstName',
                    'surname',
                    'username'
                ));
                $this->setVarByRef('contextCode', $contextCode);
                $this->setVarByRef('arrContextUserList', $arrContextUserList);
                $bookId = $this->getParam('bookId', NULL);
                $arrBookEntryList = $this->dbBookEntries->listBookEntries($bookId);
                $this->setVarByRef('bookId', $bookId);
                $this->setVarByRef('arrBookEntryList', $arrBookEntryList);
                $this->setVar('mode', NULL);
                return 'entries_tpl.php';
                break;

            case 'addentry':
                $bookId = $this->getParam('bookId');
                $arrBookEntryList = $this->dbBookEntries->listBookEntries($bookId);
                $this->setVarByRef('bookId', $bookId);
                $this->setVarByRef('arrBookEntryList', $arrBookEntryList);
                $this->setVar('mode', 'add');
                return 'entries_tpl.php';
                break;
                
            case 'searchlist':
                $field = $this->getParam('field');
                if($field == 'username'){
                    $search = $this->getParam('username');
                }elseif($field == 'firstname'){
                    $search = $this->getParam('firstname');
                }else{
                    $search = $this->getParam('surname');
                }
                return $this->searchList($search, $field);

            case 'submitentry':
                $bookId = $this->getParam('bookId');
                $userId = $this->getParam('userid', NULL);
                if ($userId != NULL) {
                    $this->dbBookEntries->addBookEntry($bookId, $userId);
                }
                return $this->nextAction('addressbook', array(
                    'bookId' => $bookId
                ));
                break;

            case 'deleteentry':
                $bookId = $this->getParam('bookId');
                $entryId = $this->getParam('entryId');
                $this->dbBookEntries->deleteBookEntry($entryId);
                return $this->nextAction('addressbook', array(
                    'bookId' => $bookId
                ));
                break;

            case 'sendemail':
                $recipientList = $this->getParam('recipient');
                $subject = $this->getParam('subject');
                if ($subject == '') {
                    $subject = $this->noSubject;
                }
                $message = $this->getParam('message');
                $cancelbutton = $this->getParam('cancelbutton');
                $sendbutton = $this->getParam('sendbutton');
                if ($cancelbutton == 'Cancel') {
                    $this->emailFiles->clearAttachments();
                    return $this->nextAction('compose');
                } elseif ($sendbutton == 'Send') {
                    $emailId = $this->dbEmail->sendMail($recipientList, $subject, $message, 0);
                }
                return $this->nextAction('');
                break;

            case 'gotofolder':
                $folderId = $this->getParam('folderId');
                $filter = $this->getParam('filter');
                $mode = $this->getParam('mode');
                if ($mode == 'restore') {
                    $routingId = $this->getParam('routingId');
                    $this->dbRouting->restoreEmail($routingId);
                } elseif ($mode == 'resend') {
                    $emailId = $this->getParam('emailId');
                    $newEmailId = $this->dbEmail->resendEmail($emailId);
                }
                $arrFolderList = $this->dbFolders->listFolders();
                $this->setVarByRef('arrFolderList', $arrFolderList);
                $this->setVarByRef('folderId', $folderId);
                $this->setVarByRef('filter', $filter);
                $this->setVar('routingId', NULL);
                $this->setVar('sortOrder', array(
                    0 => 'messageListTable',
                    1 => 3,
                    2 => 'DESC'
                ));
                return 'default_tpl.php';
                break;

            case 'gotomessage':
                $this->emailFiles->clearAttachments();
                $folderId = $this->getParam('folderId');
                $routingId = $this->getParam('routingId');
                $filter = $this->getParam('filter', NULL);
                $mode = $this->getParam('mode');
                $messageListTable = $this->getParam('messageListTable', 'messageListTable|3|DESC');
                $sortOrder = explode("|", $messageListTable);
                if ($mode == 'prev' || $mode == 'next') {
                    $arrMessageList = $this->dbRouting->getAllMail($folderId, $sortOrder, $filter);
                    $arrCnt = count($arrMessageList);
                    foreach($arrMessageList as $key => $message) {
                        if ($mode == 'prev') {
                            if ($message['routing_id'] == $routingId) {
                                if ($key > 0) {
                                    $routingId = $arrMessageList[$key-1]['routing_id'];
                                }
                            }
                        }
                        if ($mode == 'next') {
                            if ($message['routing_id'] == $routingId) {
                                if ($key < ($arrCnt-1)) {
                                    $routingId = $arrMessageList[$key+1]['routing_id'];
                                    break;
                                }
                            }
                        }
                    }
                }
                $this->dbRouting->markAsRead($routingId);
                $arrFolderList = $this->dbFolders->listFolders();
                $this->setVarByRef('arrFolderList', $arrFolderList);
                $this->setVarByRef('folderId', $folderId);
                $this->setVarByRef('routingId', $routingId);
                $this->setVarByRef('filter', $filter);
                $this->setVarByRef('sortOrder', $sortOrder);
                return 'default_tpl.php';
                break;

            case 'upload':
                $recipientList = $this->getParam('recipient');
                $subject = $this->getParam('subject');
                $message = $this->getParam('message');
                $results = $this->emailFiles->uploadFile();
                return $this->nextAction('compose', array(
                    'userId' => $recipientList,
                    'subject' => $subject,
                    'message' => $message,
                    'error' => $results['message'],
                ));
                break;

            case 'deleteattachment':
                $recipientList = $this->getParam('recipient');
                $subject = $this->getParam('subject');
                $message = $this->getParam('message');
                $file = $this->getParam('file');
                $this->emailFiles->deleteAttachment($file);
                return $this->nextAction('compose', array(
                    'userId' => $recipientList,
                    'subject' => $subject,
                    'message' => $message
                ));
                break;

            case 'deleterecipient':
                $recipientId = $this->getParam('recipientId');
                $this->loadClass('xajax', 'ajaxwrapper');
                //Register another function in this controller
                $xajaxUsername = new xajax($this->uri(array(
                    'action' => 'deleterecipient',
                    'recipientId' => $recipientId
                )));
                $xajaxUsername->registerFunction(array(
                    $this,
                    "deleteRecipient"
                ));
                //XAJAX method to be called
                $xajaxUsername->processRequests();
                //Send JS to header
                $this->appendArrayVar('headerParams', $xajaxUsername->getJavascript());
                break;

            case 'downloadfile':
                $this->setPageTemplate('filedownload_page_tpl.php');
                return 'filedownload_tpl.php';
                break;

            case 'messages':
                $arrMsgId = $this->getParam('msgId', NULL);
                $folderId = $this->getParam('folderId');
                $newFolderId = $this->getParam('newFolderId', NULL);
                $moveAction = $this->getParam('movemessage', NULL);
                $deleteAction = $this->getParam('deletemessage', NULL);
                $markAction = $this->getParam('markmessage', NULL);
                $filter = $this->getParam('filter', NULL);
                if ($moveAction == 'Move') {
                    if (!empty($arrMsgId) && is_array($arrMsgId) && !empty($newFolderId)) {
                        $this->dbRouting->moveEmail($arrMsgId, $newFolderId);
                    }
                }
                if ($deleteAction == 'Delete') {
                    if (!empty($arrMsgId) && is_array($arrMsgId)) {
                        foreach($arrMsgId as $routingId) {
                            $this->dbRouting->deleteEmail($routingId);
                        }
                    }
                }
                if ($markAction == 'Mark') {
                    if (!empty($arrMsgId) && is_array($arrMsgId)) {
                        foreach($arrMsgId as $routingId) {
                            $this->dbRouting->markAsRead($routingId);
                        }
                    }
                }
                if ($filter != NULL) {
                    return $this->nextAction('gotofolder', array(
                        'folderId' => $folderId,
                        'filter' => $filter
                    ));
                } else {
                    return $this->nextAction('gotofolder', array(
                        'folderId' => $folderId
                    ));
                }
                break;

            case 'managesettings':
                $section = $this->getParam('section', NULL);
                $arrFolderList = $this->dbFolders->listFolders();
                $arrRulesList = $this->dbRules->getRules();
                $this->setVarByRef('arrFolderList', $arrFolderList);
                $this->setVarByRef('section', $section);
                $this->setVarByRef('arrRulesList', $arrRulesList);
                return 'settings_tpl.php';
                break;
                
            case 'namedisplay':
                $field = $this->getParam('field');
                $value = $this->getParam('value');
                $other = $this->getParam('other');
                return $this->userDisplay($field, $value, $other);                

            case 'buttondisplay':
                $section = $this->getParam('section');
                return $this->buttonDisplay($section);                

            case 'updateconfig':
                $updateUser = $this->getParam('update_user', NULL);
                $updateFolder = $this->getParam('update_folder', NULL);
                $updateDelete = $this->getParam('update_delete', NULL);
                $updateSignature = $this->getParam('update_signature', NULL);
                if (!empty($updateUser)) {
                    $surnameFirst = $this->getParam('name');
                    $hideUsername = $this->getParam('username');
                    $this->dbConfiguration->setConfig('surname_first', $surnameFirst);
                    $this->dbConfiguration->setConfig('hide_username', $hideUsername);
                    $section = 'user';
                }
                if (!empty($updateFolder)) {
                    $defaultFolderId = $this->getParam('defaultfolder');
                    $this->dbConfiguration->setConfig('default_folder_id', $defaultFolderId);
                    $section = 'folder';
                }
                if (!empty($updateDelete)) {
                    $autoDelete = $this->getParam('autodelete');
                    $this->dbConfiguration->setConfig('auto_delete', $autoDelete);
                    $section = 'delete';
                }
                if (!empty($updateSignature)) {
                    $signature = $this->getParam('signature');
                    $this->dbConfiguration->setConfig('signature', $signature);
                    $section = 'signature';
                }
                $configs = $this->dbConfiguration->getConfigs();
                $this->setSession('configs', $configs);
                return $this->nextAction('managesettings', array(
                    'section' => $section
                ));
                break;

            case 'managerules':
                $this->loadClass('xajax', 'ajaxwrapper');
                //Register another function in this controller
                $xajaxSettings = new xajax($this->uri(array(
                    'action' => 'managerules'
                )));
                $xajaxSettings->registerFunction(array(
                    $this,
                    "filterDisplay"
                ));
                $xajaxSettings->registerFunction(array(
                    $this,
                    "criteriaDisplay"
                ));
                $xajaxSettings->registerFunction(array(
                    $this,
                    "actionDisplay"
                ));
                $xajaxSettings->registerFunction(array(
                    $this,
                    "destDisplay"
                ));
                //XAJAX method to be called
                $xajaxSettings->processRequests();
                //Send JS to header
                $this->appendArrayVar('headerParams', $xajaxSettings->getJavascript());
                $mode = $this->getParam('mode');
                $ruleId = $this->getParam('ruleId');
                $this->setVarByRef('mode', $mode);
                $this->setVarByRef('ruleId', $ruleId);
                return 'rules_tpl.php';
                break;

            case 'saverule':
                $mode = $this->getParam('mode');
                $mailAction = $this->getParam('mailAction');
                $messageField = $this->getParam('messageField');
                $mailField = $this->getParam('mailField');
                $criteria = $this->getParam('criteria');
                $ruleAction = $this->getParam('ruleAction');
                $destFolderId = $this->getParam('destFolderId');
                $saveRule = FALSE;
                if ($mailAction != NULL) {
                    if ($messageField != NULL) {
                        if ($messageField == 1) {
                            if ($ruleAction != NULL) {
                                if ($ruleAction == 1) {
                                    if ($destFolderId != NULL) {
                                        $saveRule = TRUE;
                                    }
                                } else {
                                    $saveRule = TRUE;
                                }
                            }
                        } else {
                            if ($mailField != NULL) {
                                if ($mailField != 5) {
                                    if ($criteria != '') {
                                        if ($ruleAction != NULL) {
                                            if ($ruleAction == 1) {
                                                if ($destFolderId != NULL) {
                                                    $saveRule = TRUE;
                                                }
                                            } else {
                                                $saveRule = TRUE;
                                            }
                                        }
                                    }
                                } else {
                                    if ($ruleAction != NULL) {
                                        if ($ruleAction == 1) {
                                            if ($destFolderId != NULL) {
                                                $saveRule = TRUE;
                                            }
                                        } else {
                                            $saveRule = TRUE;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if ($saveRule) {
                    if ($mode == 'addrule') {
                        $this->dbRules->addRule($mailAction, $mailField, $criteria, $ruleAction, $destFolderId);
                    } else {
                        $ruleId = $this->getParam('ruleId');
                        $this->dbRules->editRule($ruleId, $mailAction, $mailField, $criteria, $ruleAction, $destFolderId);
                    }
                }
                return $this->nextAction('managesettings');
                break;

            case 'deleterule':
                $ruleId = $this->getParam('ruleId');
                $this->dbRules->deleteRule($ruleId);
                $rules = $this->dbRules->getRules();
                return $this->nextAction('managesettings');
                break;

            default:
                // calls the default template
                $configs = $this->getSession('configs');
                if ($configs == NULL) {
                    $configs = $this->dbConfiguration->getConfigs();
                    $configs['surname_first'] = isset($configs['surname_first']) ? $configs['surname_first'] : 0;
                    $configs['hide_username'] = isset($configs['hide_username']) ? $configs['hide_username'] : 0;
                    $configs['default_folder_id'] = isset($configs['default_folder_id']) ? $configs['default_folder_id'] : 'init_1';
                    $autoDelete = isset($configs['auto_delete']) ? $configs['auto_delete'] : 0;
                    if ($autoDelete == 1) {
                        $arrEmailList = $this->dbRouting->getAllMail('init_4', array(
                            0 => 'messageListTable',
                            1 => 1,
                            2 => 'DESC'
                        ) , NULL);
                        if ($arrEmailList != FALSE) {
                            foreach($arrEmailList as $key => $email) {
                                $this->dbRouting->deleteEmail($email['routing_id']);
                            }
                        }
                    }
                } else {
                    $configs['surname_first'] = isset($configs['surname_first']) ? $configs['surname_first'] : 0;
                    $configs['hide_username'] = isset($configs['hide_username']) ? $configs['hide_username'] : 0;
                    $configs['default_folder_id'] = isset($configs['default_folder_id']) ? $configs['default_folder_id'] : 'init_1';
                    $autoDelete = isset($configs['auto_delete']) ? $configs['auto_delete'] : 0;
                }
                $this->setSession('configs', $configs);
                $this->emailFiles->clearAttachments();
                $arrFolderList = $this->dbFolders->listFolders();
                $this->setVarByRef('arrFolderList', $arrFolderList);
                $this->setVar('folderId', $configs['default_folder_id']);
                $this->setVar('routingId', NULL);
                $this->setVar('filter', NULL);
                $this->setVar('sortOrder', array(
                    0 => 'messageListTable',
                    1 => 3,
                    2 => 'DESC'
                ));
                return 'default_tpl.php';
                break;
        }
    }

    /**
     * This method is called by Ajax for the User list
     * Puts a response with the user list.
     *
     * @access public
     * @param string $search The text to search for
     * @param string $field The field to search in
     * @return
     */
    public function searchList($search, $field)
    {
        $arrUserList = $this->dbEmailUsers->getUsers($field, $search);
        if ($arrUserList != FALSE) {
            $response = '<ul>';
            foreach ($arrUserList as $user) {
                $response .= '<li onclick="javascript:document.getElementById(\'input_userid\').value=\''.$user['userid'].'\'"><strong>';
                $response .= $this->dbRouting->getName($user['userid']);
                $response .= '</strong></li>';
            }
            $response .= '</ul>';            
        } else {
            $response = '';
        }
        echo $response;
    }

    /**
     * This method is called by Ajax for the User list
     * Puts a response with the user list.
     *
     * @access public
     * @param string $field The field to search
     * @param string $value The value to search for
     * @param string $recipientList The list of current recipients
     * @return xml $xml The xajax response xml
     */
    public function composeList($search, $field)
    {
        $arrUserList = $this->dbEmailUsers->getUsers($field, $search);
        if ($arrUserList != FALSE) {
            $response = '<ul>';
            foreach ($arrUserList as $user) {
                $response .= '<li onclick="addRecipient(\''.$user['userid'].'\')"><strong>';
                $response .= $this->dbRouting->getName($user['userid']);
                $response .= '</strong></li>';
            }
            $response .= '</ul>';            
        } else {
            $response = '';
        }
        echo $response;
    }

    /**
     * Method to add a recipient using AJAX
     *
     * @access public
     * @param string $userId The id of the recipient to add
     * @param string $recipientList The list of recipientId's
     * @return xml $xml The xajax response xml
     */
    public function makeList($recipientList)
    {
        $configs = $this->getSession('configs');
        $response = '';
        if($recipientList != ''){
            $arrUserId = explode('|', $recipientList);
            foreach($arrUserId as $userId){
                $name = $this->dbRouting->getName($userId);
                $icon = $this->deleteIcon($userId);
                $response .= '<span id="'.$userId.'">'.$name.$icon.'&#160;&#160;&#160;</span>';
           }
        }
        echo $response;
    }

    /**
     * Method to return the HTML string for the delete icon to be used by AJAX
     *
     * @access private
     * @param string $userId The userId of the user associated with the icon
     * @return string $delIcon The HTML string for the delete icon
     */
    private function deleteIcon($userId)
    {
        $confirm = $this->objLanguage->languageText('mod_email_delrecipient', 'email');
        $title = $this->objLanguage->languageText('word_delete');
        
        $objIcon = &$this->newObject('geticon', 'htmlelements');
        $objIcon->title = $title;
        $objIcon->setIcon('cancel');
        $objIcon->extra = ' height="17" width="17" onclick="javascript:if(confirm(\''.$confirm.'\')){deleteRecipient(\''.$userId.'\');}"';
        $delIcon = '<a href="#">'.$objIcon->show().'</a>';

        return $delIcon;
    }

    /**
     * Method to delete a recipient using AJAX
     *
     * @access public
     * @param string $userId The id of the recipient to delete
     * @param string $recipientList The list of recipientId's
     * @return xml $xml The xajax response xml
     */
    public function deleteRecipient($userId, $recipientList)
    {
        $this->loadClass('xajaxresponse', 'ajaxwrapper');
        $arrUserId = explode('|', $recipientList);
        $key = array_search($userId, $arrUserId);
        unset($arrUserId[$key]);
        $recipientList = implode('|', $arrUserId);
        $objResponse = new xajaxResponse();
        $objResponse->addRemove($userId);
        $objResponse->addAssign('input_recipient', 'value', $recipientList);
        $xml = $objResponse->getXML();
        return $xml;
    }

    /**
     * Method to output an exapmle of the user display using AJAX
     *
     * @access public
     * @param string $field The field being modified
     * @param string $value The value of the field
     * @param string $other The value of the other fields
     * @return xml $xml The xajax response xml
     */
    public function userDisplay($field, $value, $other)
    {
        $firstname = strtoupper($this->objUser->getFirstname($this->userId));
        $surname = strtoupper($this->objUser->getSurname($this->userId));
        $username = $this->objUser->userName($this->userId);
        if ($field == 'name') {
            if ($value == 1) {
                $response = $surname.", ".$firstname;
            } else {
                $response = $firstname." ".$surname;
            }
            if ($other != 1) {
                $response.= " [".$username."] ";
            }
        } else {
            if ($other == 1) {
                $response = $surname.", ".$firstname;
            } else {
                $response = $firstname." ".$surname;
            }
            if ($value != 1) {
                $response.= " [".$username."] ";
            }
        }
        echo $response;
    }

    /**
     * Method to output a button to the settings template depending on the section using AJAX
     *
     * @access public
     * @param string $section The section being modified
     * @return 
     */
    public function buttonDisplay($section)
    {
        $updateLabel = $this->objLanguage->languageText('word_update');
        $this->loadClass('button', 'htmlelements');
        if ($section == 'user_button') {
            $objButton = new button('update_user', $updateLabel);
            $objButton->setToSubmit();
            $response = $objButton->show();
        } elseif ($section == 'folder_button') {
            $objButton = new button('update_folder', $updateLabel);
            $objButton->setToSubmit();
            $response = $objButton->show();
        } elseif ($section == 'delete_button') {
            $objButton = new button('update_delete', $updateLabel);
            $objButton->setToSubmit();
            $response = $objButton->show();
        } else {
            $objButton = new button('update_signature', $updateLabel);
            $objButton->setToSubmit();
            $response = $objButton->show();
        }
        echo $response;
    }

    /**
     * Method to display the filter fields depending on the filter action
     *
     * @access public
     * @param string $messageField The value of the message field
     * @return 
     */
    public function filterDisplay($messageField)
    {
        $toLabel = $this->objLanguage->languageText('word_to');
        $fromLabel = $this->objLanguage->languageText('word_from');
        $subjectLabel = $this->objLanguage->languageText('word_subject');
        $messageLabel = $this->objLanguage->languageText('word_message');
        $selectLabel = $this->objLanguage->languageText('word_select');
        $notApplicableLabel = $this->objLanguage->languageText('phrase_notapplicable');
        $attachmentsLabel = $this->objLanguage->languageText('word_attachments');
        $this->loadClass('xajaxresponse', 'ajaxwrapper');
        $this->loadClass('dropdown', 'htmlelements');
        if ($messageField == '') {
            $fieldDrop = NULL;
            $criteriaInput = NULL;
        } elseif ($messageField == 1) {
            $fieldDrop = '<b>'.$notApplicableLabel.'</b>';
            $criteriaInput = '<b>'.$notApplicableLabel.'</b>';
        } elseif ($messageField == 2) {
            $objDrop = new dropdown('mailField');
            $objDrop->addOption(NULL, '- '.$selectLabel.' -');
            $objDrop->addOption(1, $toLabel);
            $objDrop->addOption(2, $fromLabel);
            $objDrop->addOption(3, $subjectLabel);
            $objDrop->addOption(4, $messageLabel);
            $objDrop->addOption(5, $attachmentsLabel);
            $objDrop->extra = ' onchange="javascript:xajax_criteriaDisplay(this.value);"';
            $fieldDrop = $objDrop->show();
            $criteriaInput = NULL;
        }
        $objResponse = new xajaxResponse();
        $objResponse->addAssign('fieldLayer', 'innerHTML', $fieldDrop);
        $objResponse->addAssign('criteriaLayer', 'innerHTML', $criteriaInput);
        $xml = $objResponse->getXML();
        return $xml;
    }

    /**
     * Method to display the criteria fields depending on the filter
     *
     * @access public
     * @param string $mailField The value of the mail filter field
     * @return xml $xml The xajax response xml
     */
    public function criteriaDisplay($mailField)
    {
        $notApplicableLabel = $this->objLanguage->languageText('phrase_notapplicable');
        $this->loadClass('xajaxresponse', 'ajaxwrapper');
        $this->loadClass('textinput', 'htmlelements');
        if ($mailField == '') {
            $criteriaInput = NULL;
        } elseif ($mailField == 5) {
            $criteriaInput = '<b>'.$notApplicableLabel.'</b>';
        } else {
            $objInput = new textinput('criteria');
            $criteriaInput = $objInput->show();
        }
        $objResponse = new xajaxResponse();
        $objResponse->addAssign('criteriaLayer', 'innerHTML', $criteriaInput);
        $xml = $objResponse->getXML();
        return $xml;
    }

    /**
     * Method to display the action list depending on the mail action
     *
     * @access public
     * @param string $mailAction The value of the mailAction field
     * @return xml $xml The xajax response xml
     */
    public function actionDisplay($mailAction)
    {
        $moveLabel = $this->objLanguage->languageText('word_move');
        $readLabel = $this->objLanguage->languageText('phrase_markasread');
        $selectLabel = $this->objLanguage->languageText('word_select');
        $this->loadClass('xajaxresponse', 'ajaxwrapper');
        $this->loadClass('dropdown', 'htmlelements');
        if ($mailAction == '') {
            $actionDrop = NULL;
            $folderDrop = NULL;
        } elseif ($mailAction == '1') {
            $objDrop = new dropdown('ruleAction');
            $objDrop->addOption(NULL, '- '.$selectLabel.' -');
            $objDrop->addOption(1, $moveLabel);
            $objDrop->addOption(2, $readLabel);
            $objDrop->extra = ' onchange="javascript:xajax_destDisplay(this.value,document.getElementById(\'input_mailAction\').value);"';
            $actionDrop = $objDrop->show();
            $arrFolderList = $this->dbFolders->listFolders();
            $objDrop = new dropdown('destFolderId');
            $objDrop->addOption(NULL, '- '.$selectLabel.' -');
            foreach($arrFolderList as $folder) {
                if ($folder['id'] != 'init_1') {
                    $objDrop->addOption($folder['id'], $folder['folder_name']);
                }
            }
            $folderDrop = $objDrop->show();
        } elseif ($mailAction == '2') {
            $objDrop = new dropdown('ruleAction');
            $objDrop->addOption(NULL, '- '.$selectLabel.' -');
            $objDrop->addOption(1, $moveLabel);
            $objDrop->addOption(2, $readLabel);
            $objDrop->extra = ' onchange="javascript:xajax_destDisplay(this.value,document.getElementById(\'input_mailAction\').value);"';
            $actionDrop = $objDrop->show();
            $arrFolderList = $this->dbFolders->listFolders();
            $objDrop = new dropdown('destFolderId');
            $objDrop->addOption(NULL, '- '.$selectLabel.' -');
            foreach($arrFolderList as $folder) {
                if ($folder['id'] != 'init_3') {
                    $objDrop->addOption($folder['id'], $folder['folder_name']);
                }
            }
            $folderDrop = $objDrop->show();
        }
        $objResponse = new xajaxResponse();
        $objResponse->addAssign('actionLayer', 'innerHTML', $actionDrop);
        $objResponse->addAssign('destLayer', 'innerHTML', $folderDrop);
        $xml = $objResponse->getXML();
        return $xml;
    }

    /**
     * Method to display the action list depending on the mail action
     *
     * @access public
     * @param string $ruleAction The value of the ruleAction field
     * @param string $mailAction The value of the mailAction field
     * @return xml $xml The xajax response xml
     */
    public function destDisplay($ruleAction, $mailAction)
    {
        $selectLabel = $this->objLanguage->languageText('word_select');
        $notApplicableLabel = $this->objLanguage->languageText('phrase_notapplicable');
        $this->loadClass('xajaxresponse', 'ajaxwrapper');
        $this->loadClass('dropdown', 'htmlelements');
        if ($ruleAction == '2' || $ruleAction == NUll) {
            $folderDrop = '<b>'.$notApplicableLabel.'</b>';
        } else {
            $arrFolderList = $this->dbFolders->listFolders();
            $objDrop = new dropdown('destFolderId');
            $objDrop->addOption(NULL, '- '.$selectLabel.' -');
            foreach($arrFolderList as $folder) {
                if ($folder['id'] != 'init_1' && $mailAction == '1') {
                    $objDrop->addOption($folder['id'], $folder['folder_name']);
                }
                if ($folder['id'] != 'init_3' && $mailAction == '2') {
                    $objDrop->addOption($folder['id'], $folder['folder_name']);
                }
            }
            $folderDrop = $objDrop->show();
        }
        $objResponse = new xajaxResponse();
        $objResponse->addAssign('destLayer', 'innerHTML', $folderDrop);
        $xml = $objResponse->getXML();
        return $xml;
    }
}
?>