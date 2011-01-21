<?php

$id = "";
/**
 *
 *  PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   wicid (document management system)

 *
  =
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end security check

class apo extends controller {

    function init() {
        $this->loadclass('link', 'htmlelements');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objLog = $this->getObject('logactivity', 'logger');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objLog->log();
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        //get the util object
        // user object
        $this->objUser = $this->getObject('user', 'security');
        //file type info object
        $this->objPermitted = $this->getObject('dbpermittedtypes');
        $this->objUploads = $this->getObject('dbfileuploads');
        $this->objFileFolder = $this->getObject('filefolder', 'filemanager');
        $this->folderPermissions = $this->getObject('dbfolderpermissions');
        $this->documents = $this->getObject('dbdocuments');
        $this->objUtils = $this->getObject('userutils');
        $this->objUploadTable = $this->getObject('dbfileuploads');
        $this->objformdata = $this->getObject('dbformdata');
        $this->forwardto = $this->getObject('dbforwardto');
        $this->mode = $this->objSysConfig->getValue('MODE', 'apo');
        $this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'apo');

        /**
         * Form DB objects
         */
        $this->dboverview = $this->getObject('dboverview');
    }

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
        $this->setLayoutTemplate("wicid_layout_tpl.php");
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
        $selected = "unapproved";
        $documents = $this->documents->getdocuments(0, 10, $this->mode);
        //print_r($documents);
//die();
        $this->setVarByRef("documents", $documents);
        $this->setVarByRef("selected", $selected);
        return "unapproveddocs_tpl.php";
    }

    /*
     * Method to show the upload file page
     *
     */

    public function __uploadFile() {
        $topic = $this->getParam('topic');
        $docname = $this->getParam('docname');
        $docid = $this->getParam('docid');
        $action = "upload";
        $this->setVarByRef('action', $action);
        $this->setVar('pageSuppressXML', TRUE);
        $this->setVarByRef('topic', $topic);
        $this->setVarByRef('docname', $docname);
        $this->setVarByRef('docid', $docid);

        return "upload_tpl.php";
    }

    /*
     * Method to submit file of any type
     *
     */

    public function __doupload() {

        $path = $this->getParam('path');
        $docname = $this->getParam('docname');
        $docid = $this->getParam('docid');

        $result = $this->objUtils->saveFile($path, $docname, $docid);

        if (strstr($result, "success")) {
            $this->nextAction('home');
        } else {
            return $this->nextAction('home', array('message' => $result));
        }
    }

    public function __getJSONdata() {
        $userid = $this->objUser->userId();
        return $this->objUtils->createJSONFileData($userid);
    }

    /**
     * displays the search GUI
     * @return <type>
     */
    public function __searchforfile() {
        $this->setVarByRef('action', 'search');
        return "searchForFile_tpl.php";
    }

    public function __viewfolder() {
        //  $documents = $this->documents->getdocuments($this->mode);
        $rejecteddocuments = $this->documents->getdocuments(0, 10, $this->mode, "Y");

        $dir = $this->getParam("folder", "");

        $objPreviewFolder = $this->getObject('previewfolder');

        $selected = "";
        $selected = $dir;
        $basedir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
        if ($dir == $basedir) {
            $selected = "";
        }
        $files = $this->objUtils->getFiles($dir);
        $this->setVarByRef("files", $files);
        $this->setVarByRef("documents", $documents);
        $this->setVarByRef("rejecteddocuments", $rejecteddocuments);
        $selected = $this->baseDir . $selected;
        $this->setVarByRef("selected", $selected);
        return "viewfolder_tpl.php";
    }

    function __getdefaultfolder($dir) {
        $handle = opendir($dir);
        $files = array();
        while (($file = readdir($handle)) !== false) {

            if ($file == '.' || $file == '..') {
                continue;
            }
            $filepath = $dir == '.' ? $file : $dir . '/' . $file;
            if (is_link($filepath))
                continue;
            if (is_dir($filepath)) {
                $cfile = substr($filepath, strlen($dir));
                if ($this->folderPermissions->isValidFolder($cfile)) {
                    $files[] = $filepath;
                }
            }
        }
        closedir($handle);
        sort($files, SORT_LOCALE_STRING);

        return $files;
    }

    /**
     * Used to display details of a specific file
     * @return <type>
     */
    public function __viewfiledetails() {
        $id = $this->getParam('id');
        $this->setVarByRef("id", $id);
        $this->setVarByRef('action', 'Details');

        return "viewfiledetails_tpl.php";
    }

    /**
     * for admin puproses
     * @return <type>
     */
    public function __admin() {
        $this->setVarByRef('action', 'admin');
        return "admin_tpl.php";
    }

    /**
     * Used to add a new ext type to the database
     * @return <type>
     */
    public function __savefiletype() {
        // go save stuff
        $this->objPermitted->saveFileTypes($this->getParam('filetypedesc'), $this->getParam('filetypeext'));
        return $this->nextAction('admin');
    }

    /**
     * for deleting an extension
     * @return <type>
     */
    public function __deleteext() {
        $id = $this->getParam('id');
        $this->objPermitted->deleteFileType($id);
        return $this->nextAction('admin');
    }

    /**
     * Used for downloading a selected file
     * @return <type>
     */
    public function __downloadfile() {
        $filename = $this->getParam('filename');
        $filepath = $this->getParam('filepath');
        return $this->objUtils->downloadFile($filepath, $filename);
    }

    /**
     * gets a list of folders for a give dir. List given in json format
     * @return <type>
     */
    public function __getFolders() {
        $mode = $this->getParam("mode");
        return $this->objUtils->getFolders($mode);
    }

    /**
     * gets a list of files in a selected dir. Thel list is given in json format
     * @return <type>
     */
    public function __getFiles() {
        $node = $this->getParam('node');
        return $this->objUtils->getFiles($node);
    }

    /**
     * used to create a new folder in a selected dir. If none is provided, the folder is
     * created in the root dir
     * @return <type>
     */
    public function __createfolder() {
        $path = $this->getParam('folderpath');
        $name = $this->getParam('foldername');
        if (!$path) {
            $path = "";
        }
        $this->objUtils->createFolder($path, $name);
        return $this->nextAction('getFolders', array());
    }

    /**
     * renames the supplied folder
     * @return <type>
     */
    public function __renamefolder() {
        $res = $this->objUtils->renameFolder($this->getParam('folderpath'), $this->getParam('foldername'));
        return $this->nextAction('home', array("result" => $res));
    }

    /**
     * deletes the selected file
     * @return <type>
     */
    public function __deletefile() {
        $userid = $this->objUser->userId();
        $id = $this->getParam('id');
        $fileRes = $this->objUtils->deleteFile($userid, $id);
        $result = "";

        if ($fileRes == 1) {
            $this->objUploadsTable->deleteFileRecord($id);
        } else {
            $result = $this->objLanguage->languageText("error_DELETE", 'wicid');
        }

        return $this->nextAction('home', array("result" => "$result"));
    }

    /**
     * deletes the selected folder
     */
    public function __deletefolder() {
        $this->objUtils->deleteFolder($this->getParam('folderpath'));
    }

    /**
     * returns a list of users for have access to the supplied folder
     * @return <type>
     */
    public function __getusers() {
        $foldername = $this->getParam('foldername');
        return $this->folderPermissions->getusers($foldername);
    }

    /**
     * gets all users in the database based on the search filter
     * @return <type>
     */
    public function __getallusers() {
        $searchfield = $this->getParam('searchfield');
        return $this->folderPermissions->getallusers($searchfield);
    }

    /**
     * adds a user access rights to the selected folder
     * @return <type>
     */
    public function __adduser() {
        $userid = $this->getParam('userid');
        $folderpath = $this->getParam('folderpath');
        $viewfiles = $this->getParam('viewfiles');
        $uploadfiles = $this->getParam('uploadfiles');
        $createfolder = $this->getParam('createfolder');

        return $this->folderPermissions->addPermission($userid, $folderpath, $viewfiles,
                $uploadfiles, $createfolder);
    }

    /**
     * deletes permisions of the selected user on the selected folder
     * @return <type>
     */
    public function __removeuser() {
        $userid = $this->getParam('userid');
        $folderpath = $this->getParam('folderpath');
        return $this->folderPermissions->removePermission($userid, $folderpath);
    }

    /**
     * returns a list of file extensions as json list
     * @return <type>
     */
    public function __getFileExtensions() {
        return $this->objPermitted->getFileExtensions();
    }

    /**
     * saves a new file extension into the database
     * @return <type>
     */
    public function __addfileextension() {
        $ext = $this->getParam('ext');
        $desc = $this->getParam('desc');
        return $this->objPermitted->saveFileType($desc, $ext);
    }

    /**
     *  returns true / false, if admin
     */
    public function __getMode() {
        $mode = $this->objSysConfig->getValue('MODE', 'wicid');
        return $mode;
    }

    public function __monitorupload() {
        $filename = $this->getParam('filename');
        $folderpath = $this->getParam('folderpath');
        $basedir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');

        $path = $basedir . '/' . $folderpath . '/' . $filename;
        $path = str_replace("//", "/", $path);

        echo file_exists($path) ? "success" : "false";
    }

    function __registerdocument() {

        $errormessages = array();
        $date = $this->getParam('entrydate');

        $number = $this->getParam('number');


        $dept = $this->getParam('department');

        if ($dept == '') {
            $errormessages[] = "Fill in department";
        }
        $title = $this->getParam('title');



        if ($title == 'title') {
            $errormessages[] = "Fill in course title";
        }
        $selectedfolder = $this->getParam('parentfolder');

        /* if ($selectedfolder == '0') {
          $errormessages[] = "Select topic";
          } */
        //check wat is the largest count for this year.
        $ref_version = $this->documents->checkRefNo($number);
        $refno = $number . date("Y"); //."-".($res;
        $contact = $this->getParam('contact', '');
        if ($contact == null || $contact == '') {
            $contact = $this->objUser->fullname();
        }
        $telephone = $this->getParam('telephone');


        if ($telephone == '') {
            $errormessages[] = "Fill in telephone";
        }


        if (count($errormessages) > 0) {

            $this->setVarByRef("errormessages", $errormessages);
            $this->setVarByRef("department", $dept);
            $this->setVarByRef("contact", $contact);
            $this->setVarByRef("telephone", $telephone);
            $this->setVarByRef("title", $title);
            $this->setVarByRef("number", $number);

            $mode = "fixup";
            // $action = "registerdocument";
            $this->setVarByRef("mode", $mode);
            $this->setVarByRef("action", $action);
            return "addeditdocument_tpl.php";
        }
        $status = $this->getParam('status');
        if ($status == '' || $status == NULL) {
            $status = "0";
        }
        $currentuserid = $this->objUser->userid();
        $groupid = "0";
        $selectedfolder = "/";
        $version = $this->getParam('version', "1");

        $refNo = $this->documents->addDocument(
                        $date,
                        $refno,
                        $dept,
                        $contact,
                        $telephone,
                        $title,
                        $groupid,
                        $selectedfolder,
                        $currentuserid,
                        $version,
                        $ref_version,
                        $mode = "apo",
                        $approved = "N",
                        $status = "0",
                        $currentuserid,
                        $version,
                        $ref_version
        );


        $documents = $this->documents->getdocuments(0, 10, $this->mode);
        $this->setVarByRef("documents", $documents);
        $selected = "unapproved";
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("refno", $refNo);
        return "unapproveddocs_tpl.php";
        // }
    }

    function __updatedocument() {
        $number = $this->getParam('number');
        $dept = $this->getParam('department');
        $date = $this->getParam('entrydate');
        $title = $this->getParam('title');
        $group = $this->getParam('group');
        $selectedfolder = $this->getParam('parentfolder');

        $telephone = $this->getParam('telephone');
        $id = $this->getParam('docid');
        $contact = $this->getParam('contact');
        $status = $this->getParam('status', "0");
        $currentuserid = $this->getParam('currentuserid');
        $version = $this->getParam('version', "0");
        $data = array(
            "department" => $dept,
            "telephone" => $telephone,
            "docname" => $title,
            "date_created" => $date,
            "contact_person" => $contact,
            "groupid" => $group,
            "topic" => $selectedfolder,
            "status" => $status,
            "currentuserid" => $currentuserid,
            "version" => $version
        );

        $this->documents->updateInfo($id, $data);
        $this->nextAction('unapproveddocuments');
    }

    /*
     * use for editing course proposals main information, in apo mode
     * 
     */

    /* function __updatedocument() {
      $dept = $this->getParam('dept');
      $title = $this->getParam('title');
      $group = $this->getParam('group');
      $selectedfolder = $this->getParam('topic');
      $tel = $this->getParam('tel');
      $id = $this->getParam('docid');

      $data = array("department" => $dept, "docname" => $title, "telephone" => $tel, "groupid" => $group, "topic" => $selectedfolder);
      $this->documents->updateInfo($id, $data);
      }
     */

    function __editdocument() {

        $id = $this->getParam("id");
        $document = $this->documents->getDocument($id);
        $mode = "edit";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("document", $document);
        $this->setVarByRef("id", $id);
        return "addeditdocument_tpl.php";
    }

    /**
     * used for creating proposals , in apo mode
     */
    function __createproposal() {
        $date = $this->getParam('date');
        $number = "A";
        $dept = $this->getParam('department');
        $title = $this->getParam('title');
        $ext = "doc";
        $selectedfolder = $this->getParam('topic');
        $refno = $number . $date;
        $telephone = $this->getParam('telephone');
        $mode = $this->getParam("mode");
        $docid = $this->documents->addDocument(
                        $date,
                        $refno,
                        $dept,
                        $telephone,
                        $title,
                        $selectedfolder, $mode, "Y");
        /* $basedir=$this->objSysConfig->getValue('FILES_DIR', 'wicid');
          $template=$this->objSysConfig->getValue('GENERAL_TEMPLATE', 'wicid');
          $source=$basedir.'/resources/'.$template;
          $dest=$basedir.'/'.$selectedfolder.'/'.$title.'.'.$ext; */

        //copy($source, $dest);
        // save the file information into the database
        $data = array(
            'filename' => $title . '.' . $ext,
            'filetype' => $ext,
            'date_uploaded' => strftime('%Y-%m-%d %H:%M:%S', mktime()),
            'userid' => $this->userutils->getUserId(),
            'parent' => "/",
            'refno' => $refno,
            'docid' => $docid,
            'filepath' => $selectedfolder . '/' . $title . '.' . $ext);
        $this->objUploadTable->saveFileInfo($data);
    }

    function __getdepartment() {
        /* $ch=curl_init($url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $r=curl_exec($ch);
          curl_close($ch);
          $jsonArray=json_decode($r);
          echo $jsonArray->objects[0]->organizationName;
         */
        echo "Test";
    }

    function __searchfiles() {
        $filter = $this->getParam("filter");
        $this->objUploadTable->searchfiles($filter);
    }

    function __getdocuments() {
        $mode = $this->getParam("mode");
        $userid = $this->getParam("userid");
        $this->documents->getdocuments(0, 10, $mode, $userid);
    }

    function __getrejecteddocuments() {
        $mode = $this->getParam("mode");
        $userid = $this->getParam("userid");
        $rejected = "Y";
        $this->documents->getdocuments(0, 10, $mode, $userid, $rejected);
    }

    /**
     * we get document details and format then in special json that allows
     * us to use GWT objects
     */
    function __getdocument() {
        $docid = $this->getParam('docid');
        $doc = $this->documents->getdocument($docid);
        $userid = $this->userutils->getUserId();
        $ownername = $this->objUser->fullname($userid);
        $owner = $doc['userid'] == $userid ? "true" : "false";
        $str = "[";
        $str.='{';
        $str.='"docname":' . '"' . $doc['docname'] . '",';
        $str.='"refno":' . '"' . $doc['refno'] . '",';
        $str.='"topic":' . '"' . $doc['topic'] . '",';
        $str.='"owner":' . '"' . $owner . '",';
        $str.='"ownername":' . '"' . $ownername . '",';
        $str.='"department":' . '"' . $doc['department'] . '",';
        $str.='"attachmentstatus":' . '"' . $doc['upload'] . '",';
        $str.='"telephone":' . '"' . $doc['telephone'] . '"';
        $str.='}';
        $str.=']';

        echo $str;
    }

    function __approvedocument() {
        $id = $this->getParam('id');
        $this->documents->approveDocs($id);
        $this->nextAction("unapproveddocs");
    }

    function __rejectdocument() {
        $id = $this->getParam('id');
        $this->documents->rejectDocs($id);
        $this->nextAction("unapproveddocs");
    }

    function __deleteDocs() {
        $docids = $this->getParam('docids');
        $this->documents->deleteDocs($docids);
    }

    function requiresLogin() {
        return true;
    }

    function __registeracademicpresenters() {
        print_r($_POST);
    }

    /**
     * Used to do the actual upload
     *
     */
    function __doajaxupload() {
        $dir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
        $generatedid = $this->getParam('id');
        $filename = $this->getParam('filename');

        $objMkDir = $this->getObject('mkdir', 'files');
        $topic = $this->getParam('topic');
        $docname = $this->getParam('docname');
        $docid = $this->getParam('docid');
        $destinationDir = $dir . '/' . $topic;

        //$objMkDir->mkdirs($destinationDir);
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
            $mimetype = $result['mimetype'];
            $path_parts = $result['storedname'];
            //$ext = $path_parts['extension'];
            $filename = strtolower($filename);
            $exts = split("[/\\.]", $filename);
            $n = count($exts) - 1;
            $ext = $exts[$n];
            $doc = $this->documents->getDocument($docid);
            $placeholder = $file = $dir . '/' . $topic . '/' . $docname . '.na';
            $file = "";
            if ($doc['active'] == 'Y') {
                unlink($placeholder);
                $xxpath = '/' . $topic . '/' . $docname . '.na';
                $xxpath = str_replace("//", "/", $xxpath);
                $this->objUploadTable->deleteNAFile($xxpath, $docname . '.na');
            } else {
                $oldname = $dir . '/' . $topic . '/' . $docname . '.' . $ext;
                $newname = $dir . '/' . $topic . '/' . $docname . '.na';
                $oldname = str_replace("//", "/", $oldname);
                $newname = str_replace("//", "/", $newname);

                rename($oldname, $newname);
            }

            $uploadedFiles = $this->getSession('uploadedfiles', array());
            $uploadedFiles[] = $id;
            $this->setSession('uploadedfiles', $uploadedFiles);
            $path = $topic . '/' . $docname . '.' . $ext;

            // save the file information into the database
            $data = array(
                'filename' => $docname . '.' . $ext,
                'filetype' => $ext,
                'date_uploaded' => strftime('%Y-%m-%d %H:%M:%S', mktime()),
                'userid' => $this->objUtils->getUserId(),
                'parent' => "/",
                'refno' => $this->objUtils->getRefNo($docid),
                'docid' => $docid,
                'filepath' => $path);

            $result = $this->objUploadTable->saveFileInfo($data);
            $this->documents->updateInfo($docid, array("ext" => $ext, "upload" => "Y"));
            return $this->nextAction('ajaxuploadresults', array('id' => $generatedid, 'fileid' => $id, 'filename' => $filename));
        }
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

    public function __saveFormData() {
        $formname = $this->getParam('formname');
        $formdata = $this->getParam('formdata');
        $docid = $this->getParam('docid');
        //print_r($formname . "/n" . $formdata . "/n" . $docid);
        die();
        $this->objformdata->saveData($formname, $formdata, $docid);
    }

    public function __forwardto() {
        $link = $this->getParam('link');
        $email = $this->getParam('email');
        $docid = $this->getParam('docid');

        $this->forwardto->forwardTo($link, $email, $docid);
    }

    public function __advancedsearch() {
        $startDate = $this->getParam('date');
        $endDate = $this->getParam('date2');
        $fname = $this->getParam('fname');
        $lname = $this->getParam('lname');
        $topic = $this->getParam('topic');
        $docname = $this->getParam('docname');
        $doctype = $this->getParam('doctype');
        $refno = $this->getParam('refno');
        $topic = $this->getParam('topic');
        $dept = $this->getParam('dept');
        //$active = $this->getParam('');

        $data = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
            'fname' => $fname,
            'lname' => $lname,
            'docname' => $docname,
            'topic' => $topic,
            'refno' => $refno,
            'topic' => $topic,
            'dept' => $dept,
            'groupid' => $groupid,
            'ext' => $ext,
            'mode' => $mode,
            'doctype' => $doctype);

        return $this->objUploadTable->advancedSearch($data); //$this->documents->advancedSearch($data);
    }

    /**
     * retrives form
     */
    function __getFormData() {
        $formname = $this->getParam("formname");
        $docid = $this->getParam("docid");

        echo $this->objformdata->getFormData($formname, $docid);
    }

    public function __checkdocattach() {
        echo $this->objUploadTable->checkAttachment($this->getParam('docids'));
    }

    public function __searchusers() {
        /* $firstname=$this->getParam("firstname");
          $surname=$this->getParam("surname");
          $this->forwardto->getEmails($firstname,$surname); */
        $filter = $this->getParam('filter');
        $this->forwardto->getUsers($filter);
    }

    public function __retrievedocument() {
        $userid = $this->getParam('userid');
        $docid = $this->getParam('docid');
        $this->documents->retrieveDocument($userid, $docid);
    }

    public function __changecurrentuser() {
        $userid = $this->getParam('userid');
        $docid = $this->getParam('docid');
        $version = $this->getParam('version');
        $this->documents->changeCurrentUser($userid, $docid, $version);
    }

    public function __checkusers() {
        $docid = $this->getParam('docid');
        $this->documents->checkUsers($docid);
    }

    public function __getstatus() {
        $docid = $this->getParam('docid');
        $this->documents->getStatus($docid);
    }

    public function __setstatus() {
        $docid = $this->getParam('docid');
        $status = $this->getParam('status');
        $version = $this->getParam('version');
        $this->documents->setStatus($docid, $status, $version);
    }

    public function __addcommentdata() {
        $docid = $this->getParam('docid');
        $formname = $this->getParam('formname');
        $commentdata = $this->getParam('commentdata');
        $this->objformdata->addCommentData($docid, $formname, $commentdata);
    }

    public function __getcommentdata() {
        $docid = $this->getParam('docid');
        $formname = $this->getparam('formname');
        $this->objformdata->getCommentData($docid, $formname);
    }

    public function __increaseversion() {
        $docid = $this->getParam('docid');
        $this->documents->increaseVersion($docid);
    }

    public function __getversion() {
        $docid = $this->getParam('docid');
        $this->documents->getVersion($docid);
    }

    public function __reclaimdocument() {
        $userid = $this->getParam('userid');
        $docid = $this->getParam('docid');
        $version = $this->getParam('version');
        $this->documents->reclaimDocument($userid, $docid, $version);
    }

    public function __unapproveddocs() {
        $selected = "unapproved";
        $documents = $this->documents->getdocuments(0, 10, $this->mode);
        $this->setVarByRef("documents", $documents);
        $this->setVarByRef("selected", $selected);
        return "unapproveddocs_tpl.php";
    }

    public function __rejecteddocuments() {
        $selected = "rejecteddocuments";
        $this->setVarByRef("selected", $selected);
        return "rejecteddocuments_tpl.php";
    }

    public function __newdocument() {
        $selected = $this->getParam('selected');
        $mode = "new";
        $action = "registerdocument";
        $this->setVarByRef("action", $action);
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);

        return "addeditdocument_tpl.php";
    }

    public function gettagtext($tag, $formdata) {
        $xml = simplexml_load_string($formdata);
        $text = $xml->$tag;
        return $text;
    }

    public function __createXML($tag, $text) {
        $xml = "<" . $tag . ">" . $text . "</" . $tag . ">";
        return $xml;
    }

    public function __addoverview() {
        // $id = $this->getParam('id');
        //$formname = $this->getParam('formname');
        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        // $this->setVarByRef("id", $id);
        return "overview_tpl.php";
    }

    public function __addrulesandsyllabusone() {
        /* $id = $this->getParam("id");
          $formname = $this->getParam('formname');

          $a1 = $this->getParam("a1");
          $a2 = $this->getParam("a2");
          $a3 = $this->getParam("a3");
          $a4 = $this->getParam("a4");
          $a5 = $this->getParam("a5");

          if ($a1 == null) {
          $errormessages[] = "Please provide an answer for A.1";
          }
          if ($a3 == null) {
          $errormessages[] = "Please provide an answer for A.3";
          }
          if ($a4 == null) {
          $errormessages[] = "Please provide an answer for A.4";
          }

          if (count($errormessages) > 0) {

          $this->setVarByRef("errormessages", $errormessages);
          $this->setVarByRef("a1", $a1);
          $this->setVarByRef("a2", $a2);
          $this->setVarByRef("a3", $a3);
          $this->setVarByRef("a4", $a4);
          $this->setVarByRef("a5", $a5);
          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "overview_tpl.php";
          }
          $formname = "overview";

          $formdata = new SimpleXMLElement();
          $formdata->addChild('a1', $a1);
          $formdata->addChild('a2', $a2);
          $formdata->addChild('a3', $a3);
          $formdata->addChild('a4', $a4);
          $formdata->addChild('a5', $a5);
          $this->objformdata->saveData($formname, $formdata, $docid);

          $formdata = "<a1>" . $a1 . "</a1>";
          $formdata .= "<a2>" . $a2 . "</a2>";
          $formdata .= "<a3>" . $a3 . "</a3>";
          $formdata .= "<a4>" . $a4 . "</a4>";
          $formdata .= "<a5>" . $a5 . "</a5>";
          $this->objformdata->saveData($formname, $formdata, $id); */

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "rulesandsyllabusone_tpl.php";
    }

    public function __addrulesandsyllabustwo() {
        /* $id = $this->getParam("id");
          $formname = $this->getParam('formname');

          $errormessages = array();

          $b1 = $this->getParam("b1");
          $b2 = $this->getParam("b2");
          $b3a = $this->getParam("b3a");
          $b3b = $this->getParam("b3b");
          $b4a = $this->getParam("b4a");
          $b4b = $this->getParam("b4b");
          $b4c = $this->getParam("b4c");

          if ($b1 == null) {
          $errormessages[] = "Please provide an answer for B.1";
          }
          if ($b2 == null) {
          $errormessages[] = "Please provide an answer for B.2";
          }
          if ($b3a == null) {
          $errormessages[] = "Please provide an answer for B.3.a";
          }
          if ($b3b == null) {
          $errormessages[] = "Please provide an answer for B.3.b";
          }
          if ($b4b == null) {
          $errormessages[] = "Please provide an answer for B.4.b";
          }
          if ($b4c == null) {
          $errormessages[] = "Please provide an answer for B.4.c";
          }

          if (count($errormessages) > 0) {

          $this->setVarByRef("errormessages", $errormessages);
          $this->setVarByRef("b1", $b1);
          $this->setVarByRef("b2", $b2);
          $this->setVarByRef("b3a", $b3a);
          $this->setVarByRef("b3b", $b3b);
          $this->setVarByRef("b4a", $b4a);
          $this->setVarByRef("b4b", $b4b);
          $this->setVarByRef("b4c", $b4c);
          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "rulesandsyllabusone_tpl.php";
          }

          $formdata = "<b1>" . $b1 . "</b1>";
          $formdata .= "<b2>" . $b2 . "</b2>";
          $formdata .= "<b3a>" . $b3a . "</b3a>";
          $formdata .= "<b3b>" . $b3b . "</b3b>";
          $formdata .= "<b4a>" . $b4a . "</b4a>";
          $formdata .= "<b4b>" . $b4b . "</b4b>";
          $formdata .= "<b4c>" . $b4c . "</b4c>";
          $this->objformdata->saveData($formname, $formdata, $id); */

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        // $this->setVarByRef("id", $id);
        return "rulesandsyllabustwo_tpl.php";
    }

    public function __addsubsidyrequirements() {
        /* $id = $this->getParam("id");
          $formname = $this->getParam('formname');

          $errormessages = array();

          $b5a = $this->getParam("b5a");
          $b5b = $this->getParam("b5b");
          $b6a = $this->getParam("b6a");
          $b6b = $this->getParam("b6b");
          $b6c = $this->getParam("b6c");

          if ($b5a == null) {
          $errormessages[] = "Please provide an answer for B.5.a";
          }
          if ($b5b == null) {
          $errormessages[] = "Please provide an answer for B.5.b";
          }
          if ($b6a == null) {
          $errormessages[] = "Please provide an answer for B.6.a";
          }
          if ($b6b == null) {
          $errormessages[] = "Please provide an answer for B.6.b";
          }
          if ($b6c == null) {
          $errormessages[] = "Please provide an answer for B.6.c";
          }

          if (count($errormessages) > 0) {

          $this->setVarByRef("errormessages", $errormessages);
          $this->setVarByRef("b5a", $b5a);
          $this->setVarByRef("b5b", $b5b);
          $this->setVarByRef("b6a", $b6a);
          $this->setVarByRef("b6b", $b6b);
          $this->setVarByRef("b6c", $b6c);
          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "rulesandsyllabustwo_tpl.php";
          }

          $formdata = "<b5a>" . $b5a . "</b5a>";
          $formdata .= "<b5b>" . $b5b . "</b5b>";
          $formdata .= "<b6a>" . $b6a . "</b6a>";
          $formdata .= "<b6b>" . $b6b . "</b6b>";
          $formdata .= "<b6c>" . $b6c . "</b6c>";
          $this->objformdata->saveData($formname, $formdata, $id); */

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        //$this->setVarByRef("id", $id);
        return "subsidyrequirements_tpl.php";
    }

    public function __addoutcomesandassessmentone() {
        /* $id = $this->getParam("id");
          $formname = $this->getParam('formname');

          $errormessages = array();

          /*$errormessages = array();

          $c1 = $this->getParam("c1");
          $c2a = $this->getParam("c2a");
          $c2b = $this->getParam("c2b");
          $c3 = $this->getParam("c3");
          $c4a = $this->getParam("c4a");
          $c4b = $this->getParam("c4b");
          $c4c = $this->getParam("c4c");

          if ($c1 == null) {
          $errormessages[] = "Please provide an answer for C.1";
          }
          if ($c2a == null) {
          $errormessages[] = "Please provide an answer for C.2.a";
          }
          if ($c2b == null) {
          $errormessages[] = "Please provide an answer for C.2.b";
          }
          if ($c3 == null) {
          $errormessages[] = "Please provide an answer for C.3";
          }
          if ($c4a == null) {
          $errormessages[] = "Please provide an answer for C.4.1";
          }
          if ($c4b == null) {
          $errormessages[] = "Please provide an answer for C.4.b";
          }

          if (count($errormessages) > 0) {

          $this->setVarByRef("errormessages", $errormessages);
          $this->setVarByRef("c1", $c1);
          $this->setVarByRef("c2a", $c2a);
          $this->setVarByRef("c2b", $c2b);
          $this->setVarByRef("c3", $c3);
          $this->setVarByRef("c4a", $c4a);
          $this->setVarByRef("c4b", $c4b);
          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "subsidyrequirements_tpl.php";
          }

          $formdata = "<c1>" . $c1 . "</c1>";
          $formdata .= "<c2a>" . $c2a . "</c2a>";
          $formdata .= "<c2b>" . $c2b . "</c2b>";
          $formdata .= "<c3>" . $c3 . "</c3>";
          $formdata .= "<c4a>" . $c4a . "</c4a>";
          $formdata .= "<c4b>" . $c4b . "</c4b>";
          $this->objformdata->saveData($formname, $formdata, $id); */
//$formname = $this->getParam('form');
//$c3 = $this->getParam("c3");
//$c3->label='CEMS (must be 6 characters)';
//$surname->label='Surname (must be less than 15 characters)';
//$formname->addRule(array('name'=>'c3','length'=>6), 'Check CESM manual','maxlength');
//$objForm->addRule(array('name'=>'surname','length'=>6), 'Your surname is too long',
        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        //$this->setVarByRef("id", $id);
        return "outcomesandassessmentone_tpl.php";
    }

    public function __addoutcomesandassessmenttwo() {
        /* $id = $this->getParam("id");
          $formname = $this->getParam('formname');

          $errormessages = array();

          $d1a = $this->getParam("d1a");
          $d1b = $this->getParam("d1b");
          $d2a = $this->getParam("d2a");
          $d2b = $this->getParam("d2b");
          $d2c = $this->getParam("d2c");
          $d3 = $this->getParam("d3");

          if ($d1a == null) {
          $errormessages[] = "Please provide an answer for D.1.a";
          }
          if ($d1b == null) {
          $errormessages[] = "Please provide an answer for D.1.b";
          }
          if ($d2a == null) {
          $errormessages[] = "Please provide an answer for D.2.a";
          }
          if ($d2b == null) {
          $errormessages[] = "Please provide an answer for D.2.b";
          }
          if ($d2c == null) {
          $errormessages[] = "Please provide an answer for D.2.c";
          }
          if ($d3 == null) {
          $errormessages[] = "Please provide an answer for D.3";
          }

          if (count($errormessages) > 0) {

          $this->setVarByRef("errormessages", $errormessages);
          $this->setVarByRef("d1a", $d1a);
          $this->setVarByRef("d1b", $d1b);
          $this->setVarByRef("d2a", $d2a);
          $this->setVarByRef("d2b", $d2b);
          $this->setVarByRef("d2c", $d2c);
          $this->setVarByRef("d3", $d3);
          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "rulesandsyllabustwo_tpl.php";
          }

          $formdata = "<d1a>" . $d1a . "</d1a>";
          $formdata .= "<d1b>" . $d1b . "</d1b>";
          $formdata .= "<d2a>" . $d2a . "</d2a>";
          $formdata .= "<d2b>" . $d2b . "</d2b>";
          $formdata .= "<d2c>" . $d2c . "</d2c>";
          $formdata .= "<d3>" . $d3 . "</d3>";
          $this->objformdata->saveData($formname, $formdata, $id); */

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        // $this->setVarByRef("id", $id);
        return "outcomesandassessmenttwo_tpl.php";
    }

    public function __addoutcomesandassessmentthree() {
        /*  $id = $this->getParam("id");
          $formname = $this->getParam('formname');

          $errormessages = array();

          $a = $this->getParam("a");
          $b = $this->getParam("b");
          $c = $this->getParam("c");
          $d = $this->getParam("d");
          $e = $this->getParam("e");
          $f = $this->getParam("f");
          $g = $this->getParam("g");
          $h = $this->getParam("h");

          if ($a == null) {
          $errormessages[] = "Please provide an answer for a";
          }

          $d1b = $this->getParam("d1b");
          if ($d1b == '') {
          $errormessages[] = "Select NEW NQF level";
          }

          $d2a = $this->getParam("d2a");
          if ($d2a == '' ) {
          $errormessages[] = "Specify Learning Outcomes of the Course/Unit";
          }

          $d2b = $this->getParam("d2b");
          if ($d2b == ''){
          $errormessages[]= "Assessment Criteria for the Learning Outcomes";

          }

          $d2c = $this->getParam("d2c");
          if ($d2c == ''){
          $errormessages[]= "Specify Assessment Methods to be Used";

          }

          $d3 = $this->getParam("d3");
          if ($d3 == ''){
          $errormessages[]= "Please Provide answer for D.3.";

          }

          if (count($errormessages) > 0) {

          $this->setVarByRef("errormessages", $errormessages);

          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "outcomesandassessmenttwo_tpl.php";
          }

          $formdata = "<a>" . $a . "</a>";
          $formdata .= "<b>" . $b . "</b>";
          $formdata .= "<c>" . $c . "</c>";
          $formdata .= "<d>" . $d . "</d>";
          $formdata .= "<e>" . $e . "</e>";
          $formdata .= "<f>" . $f . "</f>";
          $formdata .= "<g>" . $g . "</g>";
          $formdata .= "<h>" . $h . "</h>";
          $this->objformdata->saveData($formname, $formdata, $id); */

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        //  $this->setVarByRef("id", $id);
        return "outcomesandassessmentthree_tpl.php";
    }

    public function __addresources() {
        /*   $id = $this->getParam("id");
          $formname = $this->getParam('formname');

          $errormessages = array();

          $a = $this->getParam("a");
          $b = $this->getParam("b");
          $c = $this->getParam("c");
          $d = $this->getParam("d");
          $e = $this->getParam("e");
          $f = $this->getParam("f");
          $g = $this->getParam("g");
          $h = $this->getParam("h");
          $i = $this->getParam("i");

          if ($a == null) {
          $errormessages[] = "Please provide an answer for a";
          }
          if ($b == null) {
          $errormessages[] = "Please provide an answer for b";
          }
          if ($c == null) {
          $errormessages[] = "Please provide an answer for c";
          }
          if ($d == null) {
          $errormessages[] = "Please provide an answer for d";
          }
          if ($e == null) {
          $errormessages[] = "Please provide an answer for e";
          }
          if ($f == null) {
          $errormessages[] = "Please provide an answer for f";
          }
          if ($g == null) {
          $errormessages[] = "Please provide an answer for g";
          }
          if ($h == null) {
          $errormessages[] = "Please provide an answer for h";
          }
          if ($i == null) {
          $errormessages[] = "Please provide an answer for i";
          }

          if (count($errormessages) > 0) {
          $this->setVarByRef("errormessages", $errormessages);
          $this->setVarByRef("a", $a);
          $this->setVarByRef("b", $b);
          $this->setVarByRef("c", $c);
          $this->setVarByRef("d", $d);
          $this->setVarByRef("e", $e);
          $this->setVarByRef("f", $f);
          $this->setVarByRef("g", $g);
          $this->setVarByRef("h", $h);
          $this->setVarByRef("i", $i);
          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "outcomesandassessmentthree_tpl.php";
          }

          $formdata = "<a>" . $a . "</a>";
          $formdata .= "<b>" . $b . "</b>";
          $formdata .= "<c>" . $c . "</c>";
          $formdata .= "<d>" . $d . "</d>";
          $formdata .= "<e>" . $e . "</e>";
          $formdata .= "<f>" . $f . "</f>";
          $formdata .= "<g>" . $g . "</g>";
          $formdata .= "<h>" . $h . "</h>";
          $formdata .= "<i>" . $i . "</i>";
          $this->objformdata->saveData($formname, $formdata, $id); */

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        //$this->setVarByRef("id", $id);
        return "resources_tpl.php";
    }

    public function __addcollaborationandcontracts() {
        /*   $id = $this->getParam("id");
          $formname = $this->getParam('formname');

          $errormessages = array();

          $e1a = $this->getParam("e1a");
          $e1b = $this->getParam("e1b");
          $e2a = $this->getParam("e2a");
          $e2b = $this->getParam("e2b");
          $e2c = $this->getParam("e2c");
          $e3a = $this->getParam("e3a");
          $e3b = $this->getParam("e3b");
          $e3c = $this->getParam("e3c");
          $e4 = $this->getParam("e4");
          $e5a = $this->getParam("e5a");
          $e5b = $this->getParam("e5b");

          if ($e1a == null) {
          $errormessages[] = "Please provide an answer for E.1.a";
          }
          if ($e1b == null) {
          $errormessages[] = "Please provide an answer for E.1.b";
          }
          if ($e2a == null) {
          $errormessages[] = "Please provide an answer for E.2.a";
          }
          if ($e2b == null) {
          $errormessages[] = "Please provide an answer for E.2.b";
          }
          if ($e2c == null) {
          $errormessages[] = "Please provide an answer for E.2.c";
          }
          if ($e3a == null) {
          $errormessages[] = "Please provide an answer for E.3.a";
          }
          if ($e3b == null) {
          $errormessages[] = "Please provide an answer for E.3.b";
          }
          if ($e3c == null) {
          $errormessages[] = "Please provide an answer for E.3.c";
          }
          if ($e4 == null) {
          $errormessages[] = "Please provide an answer for E.4";
          }
          if ($e5a == null) {
          $errormessages[] = "Please provide an answer for E.5.a";
          }
          if ($e5b == null) {
          $errormessages[] = "Please provide an answer for E.5.b";
          }

          if (count($errormessages) > 0) {

          $this->setVarByRef("errormessages", $errormessages);
          $this->setVarByRef("e1a", $e1a);
          $this->setVarByRef("e1b", $e1b);
          $this->setVarByRef("e2a", $e2a);
          $this->setVarByRef("e2b", $e2b);
          $this->setVarByRef("e2c", $e2c);
          $this->setVarByRef("e3a", $e3a);
          $this->setVarByRef("e3b", $e3b);
          $this->setVarByRef("e3c", $e3c);
          $this->setVarByRef("e4", $e4);
          $this->setVarByRef("e5a", $e5a);
          $this->setVarByRef("e5b", $e5b);
          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "resources_tpl.php";
          }

          $formdata = "<e1a>" . $e1a . "</e1a>";
          $formdata .= "<e1b>" . $e . "</e1b>";
          $formdata .= "<e2a>" . $e . "</e2a>";
          $formdata .= "<e2b>" . $e . "</e2b>";
          $formdata .= "<e2c>" . $e . "</e2c>";
          $formdata .= "<e3a>" . $e . "</e3a>";
          $formdata .= "<e3b>" . $e . "</e3b>";
          $formdata .= "<e3c>" . $e . "</e3c>";
          $formdata .= "<e4>" . $e . "</e4>";
          $formdata .= "<e5a>" . $e . "</e5a>";
          $formdata .= "<e5b>" . $e . "</e5b>";
          $this->objformdata->saveData($formname, $formdata, $id); */

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        //$this->setVarByRef("id", $id);
        return "collaborationandcontracts_tpl.php";
    }

    public function __addreview() {
        /*  $id = $this->getParam("id");
          $formname = $this->getParam('formname');

          $errormessages = array();

          $f1a = $this->getParam("f1a");
          $f1b = $this->getParam("f1b");
          $f2a = $this->getParam("f2a");
          $f2b = $this->getParam("f2b");
          $f3a = $this->getParam("f3a");
          $f3b = $this->getParam("f3b");
          $f4 = $this->getParam("f4");

          if ($f1a == null) {
          $errormessages[] = "Please provide an answer for F.1.a";
          }

          /*$f1b = $this->getParam("F1b");
          if ($f1b == '' ) {
          $errormessages[] = "Provide answer for F.1.b.";
          }

          $f2a = $this->getParam("F2a");
          if (empty($_POST['f2a'])) {
          $errormessages[] = "Provide answer for F.2.a.";
          }

          /* $f2b = $this->getParam("F2b");
          if ($f2b == '') {
          $errormessages[] = "Provide answer for F.2.b.";
          }

          $f3a = $this->getParam("F3a");
          if (empty($_POST['f3a']) ) {
          $errormessages[] = "Provide answer for F.3.a.";
          }

          /* $f3b = $this->getParam("F3b");
          if ($f3b == '') {
          $errormessages[] = "Provide answer for F.3.b.";
          }

          $f4 = $this->getParam("F4");
          if (empty($_POST['f4'])) {
          $errormessages[] = "Provide answer for F.4.";
          }

          if (count($errormessages) > 0) {

          $this->setVarByRef("errormessages", $errormessages);

          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "collaborationandcontracts_tpl.php";
          }

          $formdata = "<f1a>" . $f1a . "</f1a>";
          $formdata .= "<f1b>" . $f1b . "</f1b>";
          $formdata .= "<f2a>" . $f2a . "</f2a>";
          $formdata .= "<f2b>" . $f2b . "</f2b>";
          $formdata .= "<f3a>" . $f3a . "</f3a>";
          $formdata .= "<f3b>" . $f3b . "</f3b>";
          $formdata .= "<f4>" . $f4 . "</f4>";
          $this->objformdata->saveData($formname, $formdata, $id); */

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        //  $this->setVarByRef("id", $id);
        return "review_tpl.php";
    }

    public function __addcontactdetails() {
        /* $id = $this->getParam("id");
          $formname = $this->getParam('formname');

          $errormessages = array();

          $g1a = $this->getParam("g1a");
          $g1b = $this->getParam("g1b");
          $g2a = $this->getParam("g2a");
          $g2b = $this->getParam("g2b");
          $g3a = $this->getParam("g3a");
          $g3b = $this->getParam("g3b");
          $g4a = $this->getParam("g4a");
          $g4b = $this->getParam("g4b");

          if ($g1a == null) {
          $errormessages[] = "Please provide an answer for G.1.a";
          }

          $g1b = $this->getParam("g1b");
          if ($g1b == '') {
          $errormessages[] = "Provide answer for G.1.b.";
          }

          $g2a = $this->getParam("g2a");
          if ($g2a == '' ) {
          $errormessages[] = "Provide answer for G.2.a.";
          }

          $g2b = $this->getParam("g2b");
          if ($g2b == ''){
          $errormessages[]= "Provide answer for G.2.b.";
          }

          $g3a = $this->getParam("g3a");
          if ($g3a == '' ) {
          $errormessages[] = "Provide answer for G.3.a.";
          }

          $g3b = $this->getParam("g3b");
          if ($g3b == ''){
          $errormessages[]= "Provide answer for G.3.b.";
          }

          $g4a = $this->getParam("g4a");
          if ($g4a == '' ) {
          $errormessages[] = "Provide answer for G.4.a. ";
          }

          $g4b = $this->getParam("g4b");
          if ($g4b == ''){
          $errormessages[]= "Provide answer for G.4.b.";
          }

          if (count($errormessages) > 0) {

          $this->setVarByRef("errormessages", $errormessages);

          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "review_tpl.php";
          }

          $formdata = "<g1a>" . $g1a . "</g1a>";
          $formdata .= "<g1b>" . $g1b . "</g1b>";
          $formdata .= "<g2a>" . $g2a . "</g2a>";
          $formdata .= "<g2b>" . $g2b . "</g2b>";
          $formdata .= "<g3a>" . $g3a . "</g3a>";
          $formdata .= "<g3b>" . $g3b . "</g3b>";
          $formdata .= "<g4a>" . $g4a . "</g4a>";
          $formdata .= "<g4b>" . $g4b . "</g4b>";
          $this->objformdata->saveData($formname, $formdata, $id); */

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        // $this->setVarByRef("id", $id);
        return "contactdetails_tpl.php";
    }

    public function __finishdocument() {
        /* $id = $this->getParam("id");
          $formname = $this->getParam('formname');

          $errormessages = array();

          $h1 = $this->getParam("h1");
          $h2a = $this->getParam("h2a");
          $h2b = $this->getParam("h2b");
          $h3a = $this->getParam("h3a");
          $h3b = $this->getParam("h3b");

          if ($h1 == null) {
          $errormessages[] = "Please provide an answer for H.1";
          }
          if ($h2a == null) {
          $errormessages[] = "Please provide an answer for H.2.a";
          }
          if ($h2b == null) {
          $errormessages[] = "Please provide an answer for H.2.b";
          }
          if ($h3a == null) {
          $errormessages[] = "Please provide an answer for H.3.a";
          }
          if ($h3b == null) {
          $errormessages[] = "Please provide an answer for H.3.b";
          }

          if (count($errormessages) > 0) {

          $this->setVarByRef("errormessages", $errormessages);
          $this->setVarByRef("h1", $h1);
          $this->setVarByRef("h2a", $h2a);
          $this->setVarByRef("h2b", $h2b);
          $this->setVarByRef("h3a", $h3a);
          $this->setVarByRef("h3b", $h3b);
          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "contactdetails_tpl.php";
          }

          $formdata = "<h1>" . $h1 . "</h1>";
          $formdata .= "<h2a>" . $h2a . "</h2a>";
          $formdata .= "<h2b>" . $h2b . "</h2b>";
          $formdata .= "<h3a>" . $h3a . "</h3a>";
          $formdata .= "<h3b>" . $h3b . "</h3b>";
          $this->objformdata->saveData($formname, $formdata, $id); */

        return "finishdocument_tpl.php";
    }

    public function __calculatespreedsheet() {
        /* $id = $this->getParam("id");
          $formname = $this->getParam('formname');
          print_r($id);
          die();

          $errormessages = array();

          $a = $this->getParam("a");
          $b = $this->getParam("b");
          $c = $this->getParam("c");
          $d = $this->getParam("d");
          $e = $this->getParam("e");
          $f = $this->getParam("f");
          $g = $this->getParam("g");
          $h = $this->getParam("h");
          $i = $this->getParam("i");

          if ($a == null) {
          $errormessages[] = "Please provide an answer for a";
          }
          if ($b == null) {
          $errormessages[] = "Please provide an answer for b";
          }
          if ($c == null) {
          $errormessages[] = "Please provide an answer for c";
          }
          if ($d == null) {
          $errormessages[] = "Please provide an answer for d";
          }
          if ($e == null) {
          $errormessages[] = "Please provide an answer for e";
          }
          if ($f == null) {
          $errormessages[] = "Please provide an answer for f";
          }
          if ($g == null) {
          $errormessages[] = "Please provide an answer for g";
          }
          if ($h == null) {
          $errormessages[] = "Please provide an answer for h";
          }
          if ($i == null) {
          $errormessages[] = "Please provide an answer for i";
          }

          if (count($errormessages) > 0) {
          $this->setVarByRef("errormessages", $errormessages);
          $this->setVarByRef("a", $a);
          $this->setVarByRef("b", $b);
          $this->setVarByRef("c", $c);
          $this->setVarByRef("d", $d);
          $this->setVarByRef("e", $e);
          $this->setVarByRef("f", $f);
          $this->setVarByRef("g", $g);
          $this->setVarByRef("h", $h);
          $this->setVarByRef("i", $i);
          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "outcomesandassessmentthree_tpl.php";
          } */
        $id = $this->getParam("id");
        $mode = "fixup";

        $totalContactTime = ($b + $c + $d + $e) * $a;
        $totalstudyhoursNoexam = $totalContactTime * $f;
        $totalExamTime = $g * $h;
        $totalstudyhoursExam = $totalstudyhoursNoexam + $totalExamTime + $i;
        $totalSAQAcredits = $totalstudyhoursExam / 10;

        $formdata = "<a>" . $a . "</a>";
        $formdata .= "<b>" . $b . "</b>";
        $formdata .= "<c>" . $c . "</c>";
        $formdata .= "<d>" . $d . "</d>";
        $formdata .= "<e>" . $e . "</e>";
        $formdata .= "<f>" . $f . "</f>";
        $formdata .= "<g>" . $g . "</g>";
        $formdata .= "<h>" . $h . "</h>";
        $formdata .= "<i>" . $i . "</i>";
        $formdata .= "<totalcontacttime>" . $totalContactTime . "</totalcontacttime>";
        $formdata .= "<studyhoursnoexam>" . $totalstudyhoursNoexam . "</studyhoursnoexam>";
        $formdata .= "<totalexamtime>" . $totalExamTime . "</totalexamtime>";
        $formdata .= "<totalstudyhours>" . $totalstudyhoursExam . "</totalstudyhours>";
        $formdata .= "<saqa>" . $totalSAQAcredits . "</saqa>";
        $this->objformdata->saveData($formname, $formdata, $id);

        $this->setVarByRef("a", $a);
        $this->setVarByRef("b", $b);
        $this->setVarByRef("c", $c);
        $this->setVarByRef("d", $d);
        $this->setVarByRef("e", $e);
        $this->setVarByRef("f", $f);
        $this->setVarByRef("g", $g);
        $this->setVarByRef("h", $h);
        $this->setVarByRef("i", $i);

        $this->setVarByRef("totalcontacttime", $totalContactTime);
        $this->setVarByRef("studyhoursnoexam", $totalstudyhoursNoexam);
        $this->setVarByRef("totalexamtime", $totalExamTime);
        $this->setVarByRef("totalstudyhours", $totalstudyhoursExam);
        $this->setVarByRef("saqa", $totalSAQAcredits);

        $this->setVarByRef("id", $id);
        return "outcomesandassessmentthree_tpl.php";
    }

    public function __addeditCourseProposal() {

        $telephone = $this->getParam("telephone");
        $title = $this->getParam("title");
        $owner = $this->getParam("contact");
        $department = $this->getParam("department");
        $id = $this->getParam('docid');

        $this->setVarByRef("telephone", $telephone);
        $this->setVarByRef("title", $title);
        $this->setVarByRef("contact", $contact);
        $this->setVarByRef("department", $department);


        /* $selected = $this->getParam('selected');
          $mode = "new";
          $this->setVarByRef("mode", $mode);
          $this->setVarByRef("selected", $selected); */
        return "editCourseProposal_tpl.php";
    }

    public function __saveeditcourseproposal() {
        
    }

    public function __saveoverview() {

        $a1 = $this->getParam("a1");
        $a2 = $this->getParam("a2");
        $a3 = $this->getParam("a3");
        $a4 = $this->getParam("a4");
        $a5 = $this->getParam("a5");
        $docid = $this->getParam("id");

        $this->dboverview->saveOverview();
    }

}