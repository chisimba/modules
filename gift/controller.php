<?php

class gift extends controller {

    public $id;
    public $msg;
    public $giftPolicyAccepted = "false";
    public $clickedAdd = "false";

    function init() {
        // Importing classes for use in controller
        $this->objLanguage = $this->getObject("language", "language");
        $this->objDbGift = $this->getObject("dbgift");
        $this->objGift = $this->getObject("giftops");
        $this->objDepartments = $this->getObject("dbdepartments");
        $this->objAttachments = $this->getObject("dbattachments");
        $this->objHome = $this->getObject("home");
        $this->objUser = $this->getObject("user", "security");
        $this->objEdit = $this->getObject("edit");
        $this->objGiftUser = $this->getObject("dbuserstbl");
        $this->objConfig = $this->getObject('altconfig', 'config');
        if ($this->objGiftUser->policyAccepted() == 'Y') {
            $this->giftPolicyAccepted = "true";
        }
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->divisionLabel = $this->objSysConfig->getValue('DIVISION_LABEL', 'gift');
        $test = "test";
        // Initialising $data (holds the data from database when edit link is called)
        $this->data = array();
     
    }

    /*
      function dispatch($action) {
      $this->setLayoutTemplate("gift_layout_tpl.php");
      switch ($action) {

      case 'add': return $this->add();
      case 'submitadd': return $this->submitAdd();
      case 'result': return $this->result();
      case 'edit': return $this->edit();
      case 'archive': return $this->archive();
      case 'submitedit': return $this->submitEdit();
      case 'search': return $this->searchGift();
      case 'viewpolicy': return $this->viewPolicy();
      case 'acceptpolicy': return $this->acceptPolicy();
      case 'userexists': return $this->userExists();
      case 'saveuser': return $this->saveUser();
      default:
      $this->clickedAdd = $this->getParam('clickedadd');

      return "home_tpl.php";

      }
      }
     */

    /**
     * Standard Dispatch Function for Controller
     * @param <type> $action
     * @return <type>
     */
    public function dispatch($action) {
        /*
         * Convert the action into a method (alternative to
         * using case selections)
         */
        $this->setLayoutTemplate("gift_layout_tpl.php");
        $method = $this->getMethod($action);
        /*
         * Return the template determined by the method resulting
         * from action
         */
        return $this->$method();
    }

    /**
     *
     * Method to convert the action parameter into the name of
     * a method of this class.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return string the name of the method
     *
     */
    function getMethod(& $action) {
        if ($this->validAction($action)) {
            return '__' . $action;
        } else {
            return '__home';
        }
    }

    /**
     *
     * Method to check if a given action is a valid method
     * of this class preceded by double underscore (__). If it __action
     * is not a valid method it returns FALSE, if it is a valid method
     * of this class it returns TRUE.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return boolean TRUE|FALSE
     *
     */
    function validAction(& $action) {
        if (method_exists($this, '__' . $action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Method to show the Home Page of the Module
     */
    public function __home() {
        $departmentid = $this->getParam("departmentid");
        $departmentname = $this->getParam("departmentname");
        if ($departmentid == '') {
            $depts = $this->objDepartments->getDepartments();

            if (count($depts) > 0) {
                $defaultDept = $depts[0];

                $departmentid = $defaultDept['id'];
                $departmentname = $defaultDept['name'];
                $this->setVarByRef("departmentname", $departmentname);
                $this->setVarByRef("departmentid", $departmentid);
            }
        } else {
            $this->setVarByRef("departmentname", $departmentname);
            $this->setVarByRef("departmentid", $departmentid);
        }
        $gifts = $this->objDbGift->getGifts($departmentid);
        $this->setVarByRef("gifts", $gifts);
        return "home_tpl.php";
    }

    public function __createdepartment() {
        $name = $this->getParam('departmentname');
        $this->objDepartments->addDepartment($name);
        return $this->nextAction("home");
    }

    function __search() {
        $query = $this->getParam("query");
        if ($query == '') {
            return $this->nextAction("home");
        }
        $gifts = $this->objDbGift->searchGifts($query);
        $this->setVarByRef("gifts", $gifts);

        return "home_tpl.php";
    }

    /*
     * Search for a gift
     */

    public function __searchGift() {
        //echo $action;
        $searchkey = $this->getParam('giftname');

        $this->setVarByRef('searchStr', $searchkey);
        return "home_tpl.php";
    }

    /**
     * Add link clicked from home page, calls this method
     * @return string
     */
    function __add() {
        $departmentname = $this->getParam("departmentname");
        $mode = "add";
        $this->setVarByRef("mode", $mode);
        if ($this->giftPolicyAccepted == "true") {
            $this->setVarByRef("departmentname", $departmentname);
            return "addeditgift_tpl.php";
        } else {
            return "giftpolicy_tpl.php";
        }
    }

    function __showuseractivity() {
        $action = "retrieveuseractivity";
        $this->setVarByRef("action", $action);
        return "selectdates_tpl.php";
    }

    function __retrieveuseractivity() {
        $startDate = $this->getParam('startdate');
        $endDate = $this->getParam('enddate');
        $module = "gift";

        $data = $this->objDbGift->getUserActivity($startDate, $endDate, $module);
        $this->setVarByRef("data", $data);
        $this->setVarByRef("startdate", $startDate);
        $this->setVarByRef("enddate", $endDate);
        $this->setVarbyRef("modulename", $module);
        return "useractivity_tpl.php";
    }

    /**
     * Submits the addition of a new gift to the database
     * and returns to the home page
     * @return string
     */
    function __save() {
        $errormessages = array();
        $name = $this->getParam('giftname');                // Gift's name

        if ($name == '') {
            $errormessages[] = "Gift Name required";
        }

        $donor = $this->getParam('donor');             // Donor name
        if ($donor == '') {
            $errormessages[] = "Donor required";
        }
        $recipient = $this->objUser->userid();         // Recipient name

        $description = $this->getParam('giftdescription');  // Description

        $value = $this->getParam('giftvalue');

        if (!is_numeric($value)) {
            $errormessages[] = "Value must be integer";
        }

        $division = $this->getParam('selecteddepartment');

        if ($division == "-1") {
            $errormessages[] = "Select departments";
        }
        $type = $this->getParam('type');
        if ($type == "Select ...") {
            $errormessages[] = "Select gift type ";
        }

        if (count($errormessages) > 0) {
            $this->setVarByRef("errormessages", $errormessages);
            $mode = "fixup";
            $this->setVarByRef("mode", $mode);
            $this->setVarByRef("name", $name);
            $this->setVarByRef("donor", $donor);
            $this->setVarByRef("type", $type);
            $this->setVarByRef("value", $value);
            $this->setVarByRef("description", $description);
            $this->setVarByRef("department", $this->objDepartments->getDepartment($division));
            return "addeditgift_tpl.php";
        }
        $result = $this->objDbGift->addInfo(
                        $donor,
                        $recipient,
                        $name,
                        $description,
                        $value,
                        $listed,
                        $division,
                        $type);

        if ($result) {
            $this->msg = $this->objLanguage->languageText('mod_addInfoSuccess', 'gift');
        } else {
            $this->msg = $this->objLanguage->languageText('mod_infoFailure', 'gift');
        }

        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $subject = $objSysConfig->getValue('EMAIL_SUBJECT', 'gift');
        $subject = str_replace("{department}", $division, $subject);
        $subject = str_replace("{names}", $this->objUser->fullname(), $subject);
        $body = $objSysConfig->getValue('EMAIL_BODY', 'gift');

        $body = str_replace("{department}", $division, $body);
        $body = str_replace("{names}", $this->objUser->fullname(), $body);


        $groupName = $objSysConfig->getValue('EMAIL_GROUP', 'gift');
        $groupOps = $this->getObject('groupops', 'groupadmin');
        $objGroups = $this->getObject('groupadminmodel', 'groupadmin');
        $groupId = $objGroups->getId($groupName);
        $users = $groupOps->getUsersInGroup($groupId);
        $objMailer = $this->getObject('email', 'mail');
        $recipients = array();
        foreach ($users as $user) {
            $recipients[] = $this->objUser->email($user['perm_user_id']);
        }
        $objMailer->setValue('to', $recipients);
        $objMailer->setValue('from', "no-reply@wits.ac.za");
        $objMailer->setValue('fromName', $this->objUser->fullnames);

        $objMailer->setValue('subject', $subject);

        $objMailer->setValue('body', strip_tags($body));
        $objMailer->setValue('AltBody', strip_tags($body));

        $objMailer->send();

        return $this->nextAction('home');
    }

    /**
     * Used to do the actual upload
     *
     */
    function __doajaxupload() {
        $dir = $this->objSysConfig->getValue('UPLOADS_DIR', 'gift');


        $generatedid = $this->getParam('id');
        $filename = $this->getParam('filename');

        $objMkDir = $this->getObject('mkdir', 'files');

        $giftid = $this->getParam('giftid');
        $destinationDir = $dir . '/' . $giftid;



        $objMkDir->mkdirs($destinationDir);
        //@chmod($destinationDir, 0777);

        $objUpload = $this->newObject('upload', 'files');
        $objUpload->permittedTypes = array(
            'txt',
            'doc',
            'odt',
            'pdf',
            'docx',
            'ppt',
            'pptx',
            'xml',
            'xls',
            'xlsx',
            'launch'
        );
        $objUpload->overWrite = TRUE;
        $objUpload->uploadFolder = $destinationDir . '/';

        $result = $objUpload->doUpload(TRUE, $docname);


        if ($result['success'] == FALSE) {

            $filename = isset($_FILES['fileupload']['name']) ? $_FILES['fileupload']['name'] : '';

            return $this->nextAction('erroriframe', array('message' => 'Unsupported file extension.Only use txt, doc, odt, ppt, pptx, docx,pdf', 'file' => $filename, 'id' => $generatedid));
        } else {

            $filename = $result['filename'];
            $this->objAttachments->addAttachment($giftid, $filename);

            /*
              $myFile = "/dwaf/giftattachments/testFile.txt";
              $fh = fopen($myFile, 'w') or die("can't open file");
              $stringData = "dest == $destinationDir";

              fwrite($fh, $stringData);
              fclose($fh); */

            //  $result = $this->gift->update($id, $data);

            return $this->nextAction('ajaxuploadresults', array('id' => $generatedid, 'fileid' => $id, 'filename' => $filename));
        }
    }

    function __attach() {
        $id = $this->getParam("id");
        $this->setVarByRef('id', $id);
        return "upload_tpl.php";
    }

    function __downloadattachment() {
        $filename = $this->getParam("filename");

        $giftid = $this->getParam("giftid");
        $filepath = $giftid . '/' . $filename;
        return $this->objGift->downloadFile($filepath, $filename);
    }

    /**
     * Used to push through upload results for AJAX
     */
    function __ajaxuploadresults() {
        $this->setVar('pageSuppressToolbar', TRUE);
        $this->setVar('pageSuppressBanner', TRUE);
        $this->setVar('suppressFooter', TRUE);

        $id = $this->getParam('id');
        $this->setVarByRef('id', $id);

        $fileid = $this->getParam('fileid');
        $this->setVarByRef('fileid', $fileid);

        $filename = $this->getParam('filename');
        $this->setVarByRef('filename', $filename);

        return 'ajaxuploadresults_tpl.php';
    }

    /**
     * Depending on the archived parameter, finds all gifts donated
     * to the specific owner based on whether the gift is archived
     * or not archived
     * @return string
     */
    function __result() {
        $recipient = $this->objUser->fullName();     // Recipient name

        $qry = "SELECT * FROM tbl_gift"; // WHERE recipient = '$recipient'";
        $this->data = $this->objDbGift->getInfo($qry);

        return "edit_tpl.php";
    }

    function __view() {
        $id = $this->getParam("id");
        $gift = $this->objDbGift->getGift($id);
        $this->setVarByRef("gift", $gift);
        
        return "viewgift_tpl.php";
    }

    /**
     * Edit link from edit template, calls this method
     * @return string
     */
    function __edit() {
        $id = $this->getParam("id");
        $gift = $this->objDbGift->getGift($id);
        $this->setVarByRef("gift", $gift);
        $mode = "edit";
        $this->setVarByRef("mode", $mode);
        return "addeditgift_tpl.php";
    }

    /**
     * Updates a record in the database dependent on the gift that
     * was edited and returns to the home page.
     * @return string
     */
    function __update() {
        $errormessages = array();
        $name = $this->getParam('giftname');                // Gift's name

        if ($name == '') {
            $errormessages[] = "Gift Name required";
        }

        $donor = $this->getParam('donor');             // Donor name
        if ($donor == '') {
            $errormessages[] = "Donor required";
        }
        $recipient = $this->objUser->userid();         // Recipient name

        $description = $this->getParam('giftdescription');  // Description

        $value = $this->getParam('giftvalue');

        if (!is_numeric($value)) {
            $errormessages[] = "Value must be integer";
        }

        $division = $this->getParam('selecteddepartment');

        if ($division == "-1") {
            $errormessages[] = "Select departments";
        }
        $type = $this->getParam('type');
        if ($type == "Select ...") {
            $errormessages[] = "Select gift type ";
        }

        $comments = $this->getParam("comments");

        if (count($errormessages) > 0) {
            $this->setVarByRef("errormessages", $errormessages);
            $mode = "fixup";
            $this->setVarByRef("mode", $mode);
            $this->setVarByRef("name", $name);
            $this->setVarByRef("donor", $donor);
            $this->setVarByRef("type", $type);
            $this->setVarByRef("value", $value);
            $this->setVarByRef("comments", $comments);
            $this->setVarByRef("description", $description);
            $this->setVarByRef("department", $this->objDepartments->getDepartment($division));
            return "addeditgift_tpl.php";
        }
        $id = $this->getParam('id');

        $result = $this->objDbGift->updateInfo(
                        $donor, $recipient, $name, $description, $value, $listed, $id, $comments);

        return $this->nextAction('home');
    }

    //shows the gift policy template
    function __viewPolicy() {
        return 'giftPolicy_tpl.php';
    }

    function __userExists() {
        $userid = $this->objUser->userId();
        return $this->objGiftUser->userExists($userid);
    }

    function __saveUser() {
        $this->clickedAdd = "true";
        //save the user info in the database
        $data = array('userid' => $this->objUser->userId(), 'time' => strftime('%Y-%m-%d %H:%M:%S', mktime()));
        $this->objGiftUser->addUser($data);
        if ($this->objGiftUser->policyAccepted() == 'Y') {
            $this->nextAction('home');
            $this->giftPolicyAccepted = "true";
        } else {
            $this->nextAction('viewPolicy');
        }
    }

    function __acceptPolicy() {
        $this->objGiftUser->acceptPolicy();
        $this->nextAction('add');
    }

}

?>
