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
        $this->faculties = $this->getObject('dbfaculties');
    }

    /**
     *
     * The standard dispatch method for the apo module.
     * The dispatch method uses methods determined from the action
     * parameter of the querystring and executes the appropriate method,
     * returning its appropriate template. This template contains the code
     * which renders the module output.
     *
     * @access public
     * @param $action
     * @return A call to the appropriate method
     *
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

    function __showeditdocument() {
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

    public function __saveFormData() {
        $docid = $this->getParam("id");
        $formname = $this->getParam('formname');
        $toform = $this->getParam('toform');

        if ($formname == "overview") {
            $formdata["a1"] = $this->getParam("a1");
            $formdata["a2"] = $this->getParam("a2");
            $formdata["a3"] = $this->getParam("a3");
            $formdata["a4"] = $this->getParam("a4");
            $formdata["a5"] = $this->getParam("a5");
        } else if ($formname == "rulesandsyllabusone") {
            $formdata["b1"] = $this->getParam("b1");
            $formdata["b2"] = $this->getParam("a2");
            $formdata["b3a"] = $this->getParam("b3a");
            $formdata["b4a"] = $this->getParam("b4a");
            $formdata["b4b"] = $this->getParam("b4b");
            $formdata["b4c"] = $this->getParam("b4c");
        } else if ($formname == "rulesandsyllabustwo") {
            $formdata["b5a"] = $this->getParam("b5a");
            $formdata["b5b"] = $this->getParam("b5b");
            $formdata["b6a"] = $this->getParam("b6a");
            $formdata["b6b"] = $this->getParam("b6b");
            $formdata["b6c"] = $this->getParam("b6c");
        } else if ($formname == "subsidyrequirements") {
            $formdata["c1"] = $this->getParam("c1");
            $formdata["c2a"] = $this->getParam("c2a");
            $formdata["c2b"] = $this->getParam("c2b");
            $formdata["c3"] = $this->getParam("c3");
            $formdata["c4a"] = $this->getParam("c4a");
            $formdata["c4b1"] = $this->getParam("c4b1");
            $formdata["c4b2"] = $this->getParam("c4b1");
        } else if ($formname == "outcomesandassessmentone") {
            $formdata["d1a"] = $this->getParam("d1a");
            $formdata["d1b"] = $this->getParam("d1b");
            $formdata["d2a"] = $this->getParam("d2a");
            $formdata["d2b"] = $this->getParam("d2b");
            $formdata["d2c"] = $this->getParam("d2c");
            $formdata["d3"] = $this->getParam("d3");
        } else if ($formname == "outcomesandassessmenttwo") {
            $formdata["d4"] = $this->getParam("d4");
        } else if ($formname == "outcomesandassessmentthree") {
            $formdata["a"] = $this->getParam("a");
            $formdata["b"] = $this->getParam("b");
            $formdata["c"] = $this->getParam("c");
            $formdata["d"] = $this->getParam("d");
            $formdata["e"] = $this->getParam("e");
            $formdata["f"] = $this->getParam("f");
            $formdata["g"] = $this->getParam("g");
            $formdata["h"] = $this->getParam("h");
            $formdata["i"] = $this->getParam("i");
        } else if ($formname == "resources") {
            $formdata["e1a"] = $this->getParam("e1a");
            $formdata["e1b"] = $this->getParam("e1b");
            $formdata["e2a"] = $this->getParam("e2a");
            $formdata["e2b"] = $this->getParam("e2b");
            $formdata["e2c"] = $this->getParam("e2c");
            $formdata["e3a"] = $this->getParam("e3a");
            $formdata["e3b"] = $this->getParam("e3b");
            $formdata["e3c"] = $this->getParam("e3c");
            $formdata["e4"] = $this->getParam("e4");
            $formdata["e5a"] = $this->getParam("e5a");
            $formdata["e5b"] = $this->getParam("e5b");
        } else if ($formname == "collaborationandcontracts") {
            $formdata["f1a"] = $this->getParam("f1a");
            $formdata["f1b"] = $this->getParam("f1b");
            $formdata["f2a"] = $this->getParam("f2a");
            $formdata["f2b"] = $this->getParam("f2b");
            $formdata["f3a"] = $this->getParam("f3a");
            $formdata["f3b"] = $this->getParam("f3b");
            $formdata["f4"] = $this->getParam("f4");
        } else if ($formname == "review") {
            $formdata["g1a"] = $this->getParam("g1a");
            $formdata["g1b"] = $this->getParam("g1b");
            $formdata["g2a"] = $this->getParam("g2a");
            $formdata["g2b"] = $this->getParam("g2b");
            $formdata["g3a"] = $this->getParam("g3a");
            $formdata["g3b"] = $this->getParam("g3b");
            $formdata["g4a"] = $this->getParam("g4a");
            $formdata["g4b"] = $this->getParam("g4b");
        } else if ($formname == "contact details") {
            $formdata["h1"] = $this->getParam("h1");
            $formdata["h2a"] = $this->getParam("h2a");
            $formdata["h2b"] = $this->getParam("h2b");
            $formdata["h3a"] = $this->getParam("h3a");
            $formdata["h3b"] = $this->getParam("h3b");
        }

        $errormessages = array();
        for ($i = 0; $i < $formdata->count(); $i++) {
            if ($formdata[$i][1] == null) {
                $errormessages[] = "Please provide an answer for " . $formdata[$i][0];
            }
        }

        if (count($errormessages) > 0) {
            $this->setVarByRef("errormessages", $errormessages);
            for ($i = 0; $i < $formdata->count(); $i++) {
                $this->setVarByRef($formdata[$i][0], $formdata[$i][1]);
            }
            $mode = "fixup";
            $this->setVarByRef("mode", $mode);
            $this->setVarByRef("id", $id);
            return $formname . "_tpl.php";
        }

        $formdata = serialize($formdata);
        $this->objformdata->saveData($docid, $formname, $formdata);

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return $toform . "_tpl.php";
    }

    public function __showoverview() {
        $id = $this->getParam('id');
        print_r($id);
        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "overview_tpl.php";
    }

    public function __showrulesandsyllabusone() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);

        $a1 = $this->getParam("a1");
        $a2 = $this->getParam("a2");
        $a3 = $this->getParam("a3");
        $a4 = $this->getParam("a4");
        $a5 = $this->getParam("a5");

        $errormessages = array();
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

        $formdata = array();
        $formdata["a1"] = $a1;
        $formdata["a2"] = $a2;
        $formdata["a3"] = $a3;
        $formdata["a4"] = $a4;
        $formdata["a5"] = $a5;

        $formdata = serialize($formdata);

        $this->objformdata->saveData($id, $formname, $formdata);

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "rulesandsyllabusone_tpl.php";
    }

    public function __showrulesandsyllabustwo() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);

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

        $formdata = array();
        $formdata["b1"] = $b1;
        $formdata["b2"] = $b2;
        $formdata["b3a"] = $b3a;
        $formdata["b3b"] = $b3b;
        $formdata["b4a"] = $b4a;
        $formdata["b4b"] = $b4b;
        $formdata["b4c"] = $b4c;

        $formdata = serialize($formdata);

        $this->objformdata->saveData($id, $formname, $formdata);

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "rulesandsyllabustwo_tpl.php";
    }

    public function __showsubsidyrequirements() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);

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

        $formdata = array();
        $formdata["b5a"] = $b5a;
        $formdata["b5b"] = $b5b;
        $formdata["b6a"] = $b6a;
        $formdata["b6b"] = $b6b;
        $formdata["b6c"] = $b6c;

        $formdata = serialize($formdata);

        $this->objformdata->saveData($id, $formname, $formdata);


        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "subsidyrequirements_tpl.php";
    }

    public function __showoutcomesandassessmentone() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);

        $errormessages = array();

        $c1 = $this->getParam("c1");
        $c2a = $this->getParam("c2a");
        $c2b = $this->getParam("c2b");
        $c3 = $this->getParam("c3");
        $c4a = $this->getParam("c4a");
        $c4b1 = $this->getParam("c4b1");
        $c4b2 = $this->getParam("c4b2");

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
            $errormessages[] = "Please provide an answer for C.4.a";
        }
        if ($c4b1 == null || $c4b2 == null) {
            $errormessages[] = "Please provide an answer for C.4.b";
        }
        if ($c4b2 == null) {
            $errormessages[] = "Please provide an answer for C.4.b";
        }

        if (count($errormessages) > 0) {

            $this->setVarByRef("errormessages", $errormessages);
            $this->setVarByRef("c1", $c1);
            $this->setVarByRef("c2a", $c2a);
            $this->setVarByRef("c2b", $c2b);
            $this->setVarByRef("c3", $c3);
            $this->setVarByRef("c4a", $c4a);
            $this->setVarByRef("c4b1", $c4b1);
            $this->setVarByRef("c4b2", $c4b2);
            $mode = "fixup";
            $this->setVarByRef("mode", $mode);
            $this->setVarByRef("id", $id);
            return "subsidyrequirements_tpl.php";
        }

        $formdata = array();
        $formdata["c1"] = $c1;
        $formdata["c2a"] = $c2a;
        $formdata["c2b"] = $c2b;
        $formdata["c3"] = $c3;
        $formdata["c4a"] = $c4a;
        $formdata["c4b1"] = $c4b1;
        $formdata["c4b2"] = $c4b2;

        $formdata = serialize($formdata);

        $this->objformdata->saveData($id, $formname, $formdata);
//$formname = $this->getParam('form');
//$c3 = $this->getParam("c3");
//$c3->label='CEMS (must be 6 characters)';
//$surname->label='Surname (must be less than 15 characters)';
//$formname->addRule(array('name'=>'c3','length'=>6), 'Check CESM manual','maxlength');
//$objForm->addRule(array('name'=>'surname','length'=>6), 'Your surname is too long',*/
        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "outcomesandassessmentone_tpl.php";
    }

    public function __showoutcomesandassessmentoneScience() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);

        $errormessages = array();

        $c1 = $this->getParam("c1");
        $c2a = $this->getParam("c2a");
        $c2b = $this->getParam("c2b");
        $c3 = $this->getParam("c3");
        $c4a = $this->getParam("c4a");
        $c4b1 = $this->getParam("c4b1");
        $c4b2 = $this->getParam("c4b2");

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
            $errormessages[] = "Please provide an answer for C.4.a";
        }
        if ($c4b1 == null || $c4b2 == null) {
            $errormessages[] = "Please provide an answer for C.4.b";
        }
        if ($c4b2 == null) {
            $errormessages[] = "Please provide an answer for C.4.b";
        }

        if (count($errormessages) > 0) {

            $this->setVarByRef("errormessages", $errormessages);
            $this->setVarByRef("c1", $c1);
            $this->setVarByRef("c2a", $c2a);
            $this->setVarByRef("c2b", $c2b);
            $this->setVarByRef("c3", $c3);
            $this->setVarByRef("c4a", $c4a);
            $this->setVarByRef("c4b1", $c4b1);
            $this->setVarByRef("c4b2", $c4b2);
            $mode = "fixup";
            $this->setVarByRef("mode", $mode);
            $this->setVarByRef("id", $id);
            return "subsidyrequirements_tpl.php";
        }

        $formdata = array();
        $formdata["c1"] = $c1;
        $formdata["c2a"] = $c2a;
        $formdata["c2b"] = $c2b;
        $formdata["c3"] = $c3;
        $formdata["c4a"] = $c4a;
        $formdata["c4b1"] = $c4b1;
        $formdata["c4b2"] = $c4b2;

        $formdata = serialize($formdata);

        $this->objformdata->saveData($id, $formname, $formdata);
//$formname = $this->getParam('form');
//$c3 = $this->getParam("c3");
//$c3->label='CEMS (must be 6 characters)';
//$surname->label='Surname (must be less than 15 characters)';
//$formname->addRule(array('name'=>'c3','length'=>6), 'Check CESM manual','maxlength');
//$objForm->addRule(array('name'=>'surname','length'=>6), 'Your surname is too long',*/
        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "outcomesandassessmentoneScience_tpl.php";
    }

    public function __showoutcomesandassessmenttwo() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);



        $errormessages = array();

        if ($formname == "outcomesandassessmentone") {
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
                $this->setVarByRef("id", $id);
                return "outcomesandassessmentone_tpl.php";
            }

            $formdata = array();
            $formdata["d1a"] = $d1a;
            $formdata["d1b"] = $d1b;
            $formdata["d2a"] = $d2a;
            $formdata["d2b"] = $d2b;
            $formdata["d2c"] = $d2c;
            $formdata["d3"] = $d3;
            $formdata = serialize($formdata);
            $this->objformdata->saveData($id, $formname, $formdata);
        } else if ($formname == "outcomesandassessmentoneScience") {
            $d1 = $this->getParam("d1");
            $d21 = $this->getParam("d21");
            $d22 = $this->getParam("d22");
            $d23 = $this->getParam("d23");
            $d24 = $this->getParam("d24");
            $d25 = $this->getParam("d25");
            $d3 = $this->getParam("d3");

            if ($d1 == null) {
                $errormessages[] = "Please provide an answer for D.1";
            }
            if ($d21 == null) {
                $errormessages[] = "Please provide an answer for D.2.1";
            }
            if ($d22 == null) {
                $errormessages[] = "Please provide an answer for D.2.2";
            }
            if ($d23 == null) {
                $errormessages[] = "Please provide an answer for D.2.3";
            }
            if ($d24 == null) {
                $errormessages[] = "Please provide an answer for D.2.4";
            }
            if ($d25 == null) {
                $errormessages[] = "Please provide an answer for D.2.5";
            }
            if ($d3 == null) {
                $errormessages[] = "Please provide an answer for D.3";
            }

            if (count($errormessages) > 0) {

                $this->setVarByRef("errormessages", $errormessages);
                $this->setVarByRef("d1", $d1);
                $this->setVarByRef("d21", $d21);
                $this->setVarByRef("d22", $d22);
                $this->setVarByRef("d23", $d23);
                $this->setVarByRef("d24", $d24);
                $this->setVarByRef("d25", $d25);
                $this->setVarByRef("d3", $d3);
                $mode = "fixup";
                $this->setVarByRef("mode", $mode);
                $this->setVarByRef("id", $id);
                return "outcomesandassessmentoneScience_tpl.php";
            }

            $formdata = array();
            $formdata["d1"] = $d1;
            $formdata["d21"] = $d21;
            $formdata["d22"] = $d22;
            $formdata["d23"] = $d23;
            $formdata["d24"] = $d24;
            $formdata["d25"] = $d25;
            $formdata["d3"] = $d3;
            $formdata = serialize($formdata);
            $this->objformdata->saveData($id, $formname, $formdata);
        }

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "outcomesandassessmenttwo_tpl.php";
    }

    public function __showoutcomesandassessmentthree() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);

        $errormessages = array();

        $d4_1 = $this->getParam("1");
        $d4_2 = $this->getParam("2");
        $d4_3 = $this->getParam("3");
        $d4_4 = $this->getParam("4");
        $d4_5 = $this->getParam("5");
        $d4_6 = $this->getParam("6");
        $d4_7 = $this->getParam("7");
        $d4_8 = $this->getParam("8");

        /*      if ($d4_1 == null && $d4_2 == null && $d4_3 == null && $d4_4 == null && $d4_5 == null && $d4_6 == null && $d4_7 == null && $d4_8 == null) {
          $errormessages[] = "Please provide an answer for D.4";
          }

          if (count($errormessages) > 0) {
          $this->setVarByRef("errormessages", $errormessages);
          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "outcomesandassessmenttwo_tpl.php";
          } */

        $d4 = $d4_1 . " " . $d4_2 . " " . $d4_3 . " " . $d4_4 . " " . $d4_5 . " " . $d4_6 . " " . $d4_7 . " " . $d4_8;

        $formdata = array();
        $formdata["d4"] = $d4;
        $formdata = serialize($formdata);
        //$this->objformdata->saveData($id, $formname, $formdata);

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "outcomesandassessmentthree_tpl.php";
    }

    public function __showoutcomesandassessmentthreeScience() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);

        $errormessages = array();

        $d4_1 = $this->getParam("1");
        $d4_2 = $this->getParam("2");
        $d4_3 = $this->getParam("3");
        $d4_4 = $this->getParam("4");
        $d4_5 = $this->getParam("5");
        $d4_6 = $this->getParam("6");
        $d4_7 = $this->getParam("7");
        $d4_8 = $this->getParam("8");

        /*      if ($d4_1 == null && $d4_2 == null && $d4_3 == null && $d4_4 == null && $d4_5 == null && $d4_6 == null && $d4_7 == null && $d4_8 == null) {
          $errormessages[] = "Please provide an answer for D.4";
          }

          if (count($errormessages) > 0) {
          $this->setVarByRef("errormessages", $errormessages);
          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "outcomesandassessmenttwo_tpl.php";
          } */

        $d4 = $d4_1 . " " . $d4_2 . " " . $d4_3 . " " . $d4_4 . " " . $d4_5 . " " . $d4_6 . " " . $d4_7 . " " . $d4_8;

        $formdata = array();
        $formdata["d4"] = $d4;
        $formdata = serialize($formdata);
        //$this->objformdata->saveData($id, $formname, $formdata);

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "outcomesandassessmentthreeScience_tpl.php";
    }

    public function __showresources() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);

        /* $errormessages = array();

          if ($formname == "outcomesandassessmentthree") {
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

          $formdata = array();
          $formdata["a"] = $a;
          $formdata["b"] = $b;
          $formdata["c"] = $c;
          $formdata["d"] = $d;
          $formdata["e"] = $e;
          $formdata["f"] = $f;
          $formdata["g"] = $g;
          $formdata["h"] = $h;
          $formdata["i"] = $i;
          $formdata = serialize($formdata);
          $this->objformdata->saveData($id, $formname, $formdata);
          } else if ($formname == "outcomesandassessmentthreeScience") {
          $a1 = $this->getParam("a1");
          $a2 = $this->getParam("a2");
          $a3 = $this->getParam("a3");
          $a4 = $this->getParam("a4");
          $a5 = $this->getParam("a5");
          $a6 = $this->getParam("a6");
          $a7 = $this->getParam("a7");
          $a8 = $this->getParam("a8");
          $b1 = $this->getParam("b1");
          $b2 = $this->getParam("b2");
          $b3 = $this->getParam("b3");
          $b4 = $this->getParam("b4");
          $b5 = $this->getParam("b5");
          $b6 = $this->getParam("b6");
          $b7 = $this->getParam("b7");
          $b8 = $this->getParam("b8");
          $c1 = $this->getParam("c1");
          $c2 = $this->getParam("c2");
          $c3 = $this->getParam("c3");
          $c4 = $this->getParam("c4");
          $c5 = $this->getParam("c5");
          $c6 = $this->getParam("c6");
          $c7 = $this->getParam("c7");
          $c8 = $this->getParam("c8");
          $d1 = $this->getParam("d1");
          $d3 = $this->getParam("d3");
          $d4 = $this->getParam("d4");
          $d8 = $this->getParam("d8");
          $e1 = $this->getParam("e1");
          $e8 = $this->getParam("e8");
          $other = $this->getParam("other");
          $f1 = $this->getParam("f1");
          $f2 = $this->getParam("f2");
          $f3 = $this->getParam("f3");
          $f4 = $this->getParam("f4");
          $f5 = $this->getParam("f5");
          $f6 = $this->getParam("f6");
          $f7 = $this->getParam("f7");
          $f8 = $this->getParam("f8");
          $g9 = $this->getParam("g9");
          $g10 = $this->getParam("g10");
          $h11 = $this->getParam("h11");
          $h12 = $this->getParam("h12");

          /* if ($a == null) {
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
          $this->setVarByRef("a1", $a1);
          $this->setVarByRef("a2", $a2);
          $this->setVarByRef("a3", $a3);
          $this->setVarByRef("a4", $a4);
          $this->setVarByRef("a5", $a5);
          $this->setVarByRef("a6", $a6);
          $this->setVarByRef("a7", $a7);
          $this->setVarByRef("a8", $a8);
          $this->setVarByRef("b1", $b1);
          $this->setVarByRef("b2", $b2);
          $this->setVarByRef("b3", $b3);
          $this->setVarByRef("b4", $b4);
          $this->setVarByRef("b5", $b5);
          $this->setVarByRef("b6", $b6);
          $this->setVarByRef("b7", $b7);
          $this->setVarByRef("b8", $b8);
          $this->setVarByRef("c1", $c1);
          $this->setVarByRef("c2", $c2);
          $this->setVarByRef("c3", $c3);
          $this->setVarByRef("c4", $c4);
          $this->setVarByRef("c5", $c5);
          $this->setVarByRef("c6", $c6);
          $this->setVarByRef("c7", $c7);
          $this->setVarByRef("c8", $c8);
          $this->setVarByRef("d1", $d1);
          $this->setVarByRef("d3", $d3);
          $this->setVarByRef("d4", $d4);
          $this->setVarByRef("d8", $d8);
          $this->setVarByRef("e1", $e1);
          $this->setVarByRef("e8", $e8);
          $this->setVarByRef("other", $other);
          $this->setVarByRef("f1", $f1);
          $this->setVarByRef("f2", $f2);
          $this->setVarByRef("f3", $f3);
          $this->setVarByRef("f4", $f4);
          $this->setVarByRef("f5", $f5);
          $this->setVarByRef("f6", $f6);
          $this->setVarByRef("f7", $f7);
          $this->setVarByRef("f8", $f8);
          $this->setVarByRef("g9", $g9);
          $this->setVarByRef("g10", $g10);
          $this->setVarByRef("h11", $h11);
          $this->setVarByRef("h12", $h12);
          $mode = "fixup";
          $this->setVarByRef("mode", $mode);
          return "outcomesandassessmentthreeScience_tpl.php";
          }

          $formdata = array();
          $formdata["a1"] = $a1;
          $formdata["a2"] = $a2;
          $formdata["a3"] = $a3;
          $formdata["a4"] = $a4;
          $formdata["a5"] = $a5;
          $formdata["a6"] = $a6;
          $formdata["a7"] = $a7;
          $formdata["a8"] = $a8;
          $formdata["b1"] = $b1;
          $formdata["b2"] = $b2;
          $formdata["b3"] = $b3;
          $formdata["b4"] = $b4;
          $formdata["b5"] = $b5;
          $formdata["b6"] = $b6;
          $formdata["b7"] = $b7;
          $formdata["b8"] = $b8;
          $formdata["c1"] = $c1;
          $formdata["c2"] = $c2;
          $formdata["c3"] = $c3;
          $formdata["c4"] = $c4;
          $formdata["c5"] = $c5;
          $formdata["c6"] = $c6;
          $formdata["c7"] = $c7;
          $formdata["c8"] = $c8;
          $formdata["d1"] = $d1;
          $formdata["d3"] = $d3;
          $formdata["d4"] = $d4;
          $formdata["d8"] = $d8;
          $formdata["e1"] = $e1;
          $formdata["e8"] = $e8;
          $formdata["other"] = $other;
          $formdata["f1"] = $f1;
          $formdata["f2"] = $f2;
          $formdata["f3"] = $f3;
          $formdata["f4"] = $f4;
          $formdata["f5"] = $f5;
          $formdata["f6"] = $f6;
          $formdata["f7"] = $f7;
          $formdata["f8"] = $f8;
          $formdata["g9"] = $g9;
          $formdata["g10"] = $g10;
          $formdata["h11"] = $h11;
          $formdata["h12"] = $h12;
          $formdata = serialize($formdata);
          $this->objformdata->saveData($id, $formname, $formdata);
          } */

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "resources_tpl.php";
    }

    public function __showcollaborationandcontracts() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);

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

        $formdata = array();
        $formdata["e1a"] = $e1a;
        $formdata["e1b"] = $e1b;
        $formdata["e2a"] = $e2a;
        $formdata["e2b"] = $e2b;
        $formdata["e2c"] = $e2c;
        $formdata["e3a"] = $e3a;
        $formdata["e3b"] = $e3b;
        $formdata["e3c"] = $e3c;
        $formdata["e4"] = $e4;
        $formdata["e5a"] = $e5a;
        $formdata["e5b"] = $e5b;
        $formdata = serialize($formdata);
        $this->objformdata->saveData($id, $formname, $formdata);

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "collaborationandcontracts_tpl.php";
    }

    public function __showreview() {
        $id = $this->getParam("id");

        $formname = $this->getParam('formname');
        print_r($id);

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

        if ($f1b == '') {
            $errormessages[] = "Provide answer for F.1.b.";
        }

        if (empty($_POST['f2a'])) {
            $errormessages[] = "Provide answer for F.2.a.";
        }

        if ($f2b == '') {
            $errormessages[] = "Provide answer for F.2.b.";
        }

        if (empty($_POST['f3a'])) {
            $errormessages[] = "Provide answer for F.3.a.";
        }

        if ($f3b == '') {
            $errormessages[] = "Provide answer for F.3.b.";
        }

        if (empty($_POST['f4'])) {
            $errormessages[] = "Provide answer for F.4.";
        }

        if (count($errormessages) > 0) {

            $this->setVarByRef("errormessages", $errormessages);
            $this->setVarByRef("f1a", $f1a);
            $this->setVarByRef("f1b", $f1b);
            $this->setVarByRef("f2a", $f2a);
            $this->setVarByRef("f2b", $f2b);
            $this->setVarByRef("f3a", $f3a);
            $this->setVarByRef("f3b", $f3b);
            $this->setVarByRef("f4", $f4);
            $mode = "fixup";
            $this->setVarByRef("mode", $mode);
            return "collaborationandcontracts_tpl.php";
        }

        $formdata = array();
        $formdata["f1a"] = $f1a;
        $formdata["f1b"] = $f1b;
        $formdata["f2a"] = $f2a;
        $formdata["f2b"] = $f2b;
        $formdata["f3a"] = $f3a;
        $formdata["f3b"] = $f3b;
        $formdata["f4"] = $f4;

        $formdata = serialize($formdata);
        $this->objformdata->saveData($id, $formname, $formdata);

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "review_tpl.php";
    }

    public function __showcontactdetails() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);

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
        if ($g2a == '') {
            $errormessages[] = "Provide answer for G.2.a.";
        }

        $g2b = $this->getParam("g2b");
        if ($g2b == '') {
            $errormessages[] = "Provide answer for G.2.b.";
        }

        $g3a = $this->getParam("g3a");
        if ($g3a == '') {
            $errormessages[] = "Provide answer for G.3.a.";
        }

        $g3b = $this->getParam("g3b");
        if ($g3b == '') {
            $errormessages[] = "Provide answer for G.3.b.";
        }

        $g4a = $this->getParam("g4a");
        if ($g4a == '') {
            $errormessages[] = "Provide answer for G.4.a. ";
        }

        $g4b = $this->getParam("g4b");
        if ($g4b == '') {
            $errormessages[] = "Provide answer for G.4.b.";
        }

        if (count($errormessages) > 0) {

            $this->setVarByRef("errormessages", $errormessages);
            $this->setVarByRef("g1a", $g1a);
            $this->setVarByRef("g1b", $g1b);
            $this->setVarByRef("g2a", $g2a);
            $this->setVarByRef("g2b", $g2b);
            $this->setVarByRef("g3a", $g3a);
            $this->setVarByRef("g3b", $g3b);
            $this->setVarByRef("g4a", $g4a);
            $this->setVarByRef("g4b", $g4b);
            $mode = "fixup";
            $this->setVarByRef("mode", $mode);
            return "review_tpl.php";
        }

        $formdata = array();
        $formdata["g1a"] = $g1a;
        $formdata["g1b"] = $g1b;
        $formdata["g2a"] = $g2a;
        $formdata["g2b"] = $g2b;
        $formdata["g3a"] = $g3a;
        $formdata["g3b"] = $g3b;
        $formdata["g4a"] = $g4a;
        $formdata["g4b"] = $g4b;
        $formdata = serialize($formdata);
        $this->objformdata->saveData($id, $formname, $formdata);

        $selected = $this->getParam('selected');
        $mode = "new";
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("id", $id);
        return "contactdetails_tpl.php";
    }

    public function __showcomments() {
        /*   $id = $this->getParam("id");
          $formname = $this->getParam('formname');
          print_r($id);

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

          $formdata = array();
          $formdata["h1"] = $h1;
          $formdata["h2a"] = $h2a;
          $formdata["h2b"] = $h2b;
          $formdata["h3a"] = $h3a;
          $formdata["h3b"] = $h3b;
          $formdata = serialize($formdata);
          $this->objformdata->saveData($id, $formname, $formdata); */

        return "comments_tpl.php";
    }

    public function __showfeedback() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);

        return "feedback_tpl.php";
    }

    public function __finishdocument() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);

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

        $formdata = array();
        $formdata["h1"] = $h1;
        $formdata["h2a"] = $h2a;
        $formdata["h2b"] = $h2b;
        $formdata["h3a"] = $h3a;
        $formdata["h3b"] = $h3b;
        $formdata = serialize($formdata);
        $this->objformdata->saveData($id, $formname, $formdata);

        return "finishdocument_tpl.php";
    }

    public function __calculatespreedsheet() {
        $id = $this->getParam("id");
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
        }

        $id = $this->getParam("id");
        $mode = "fixup";

        $totalContactTime = ($b + $c + $d + $e) * $a;
        $totalstudyhoursNoexam = $totalContactTime * $f;
        $totalExamTime = $g * $h;
        $totalstudyhoursExam = $totalstudyhoursNoexam + $totalExamTime + $i;
        $totalSAQAcredits = $totalstudyhoursExam / 10;

        $formdata = array();
        $formdata["a"] = $a;
        $formdata["b"] = $b;
        $formdata["c"] = $c;
        $formdata["d"] = $d;
        $formdata["e"] = $e;
        $formdata["f"] = $f;
        $formdata["g"] = $g;
        $formdata["h"] = $h;
        $formdata["i"] = $i;
        $formdata = serialize($formdata);
        $this->objformdata->saveData($id, $formname, $formdata);

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

    public function __calculatespreedsheetScience() {
        $id = $this->getParam("id");
        $formname = $this->getParam('formname');
        print_r($id);

        $errormessages = array();

        $a3 = 0.75;
        $a5 = 0.8;
        $a6 = 1.2;
        $a7 = 1.6;
        $b3 = 0.75;
        $b5 = 1.5;
        $b6 = 1.4;
        $b7 = 1.6;
        $c3 = 3;
        $c5 = 0.6;
        $c6 = 1.2;
        $c7 = 1.6;
        $d3 = 8;
        $f5 = 0.6;
        $f6 = 1.2;
        $f7 = 1.6;

        $a1 = $this->getParam("a1");
        $a2 = $this->getParam("a2");
        $b1 = $this->getParam("b1");
        $b2 = $this->getParam("b2");
        $c1 = $this->getParam("c1");
        $c2 = $this->getParam("c2");
        $d1 = $this->getParam("d1");
        $d8 = $this->getParam("d8");
        $e1 = $this->getParam("e1");
        $e8 = $this->getParam("e8");
        $other = $this->getParam("other");
        $f1 = $this->getParam("f1");
        $f2 = $this->getParam("f2");
        $f3 = $this->getParam("f3");
        $g9 = $this->getParam("g9");
        $g10 = $this->getParam("g10");
        $h11 = $this->getParam("h11");
        $h12 = $this->getParam("h12");

        $d6 = $this->getParam("d6");
        $d7 = $this->getParam("d7");

        if ($a1 == null) {
            $errormessages[] = "Please provide an answer for a1";
        }
        if ($a2 == null) {
            $errormessages[] = "Please provide an answer for a2";
        }
        if ($b1 == null) {
            $errormessages[] = "Please provide an answer for b1";
        }
        if ($b2 == null) {
            $errormessages[] = "Please provide an answer for b2";
        }
        if ($c1 == null) {
            $errormessages[] = "Please provide an answer for c1";
        }
        if ($c2 == null) {
            $errormessages[] = "Please provide an answer for c2";
        }
        if ($d1 == null) {
            $errormessages[] = "Please provide an answer for d1";
        }
        if ($d8 == null) {
            $errormessages[] = "Please provide an answer for d8";
        }
        if ($e1 == null) {
            $errormessages[] = "Please provide an answer for e1";
        }
        if ($e8 == null) {
            $errormessages[] = "Please provide an answer for e8";
        }
        if ($other == null && ($f1 != null || $f2 != null || $f3 != null)) {
            $errormessages[] = "Please specify 'other'";
            if ($f1 == null) {
                $errormessages[] = "Please provide an answer for f1";
            }
            if ($f2 == null) {
                $errormessages[] = "Please provide an answer for f2";
            }
            if ($f3 == null) {
                $errormessages[] = "Please provide an answer for f3";
            }
        }
        if ($other != null) {
            if ($f1 == null) {
                $errormessages[] = "Please provide an answer for f1";
            }
            if ($f2 == null) {
                $errormessages[] = "Please provide an answer for f2";
            }
            if ($f3 == null) {
                $errormessages[] = "Please provide an answer for f3";
            }
        }
        if ($g9 == null) {
            $errormessages[] = "Please provide an answer for g9";
        }
        if ($g10 == null) {
            $errormessages[] = "Please provide an answer for g10";
        }
        if ($h11 == null) {
            $errormessages[] = "Please provide an answer for h11";
        }
        if ($h12 == null) {
            $errormessages[] = "Please provide an answer for h12";
        }
        if ($d6 == null) {
            $errormessages[] = "Please provide an answer for d6";
        }
        if ($d7 == null) {
            $errormessages[] = "Please provide an answer for d7";
        }

        if (count($errormessages) > 0) {
            $this->setVarByRef("errormessages", $errormessages);
            $this->setVarByRef("a1", $a1);
            $this->setVarByRef("a2", $a2);
            $this->setVarByRef("b1", $b1);
            $this->setVarByRef("b2", $b2);
            $this->setVarByRef("c1", $c1);
            $this->setVarByRef("c2", $c2);
            $this->setVarByRef("d1", $d1);
            $this->setVarByRef("d8", $d8);
            $this->setVarByRef("e1", $e1);
            $this->setVarByRef("e8", $e8);
            $this->setVarByRef("other", $other);
            $this->setVarByRef("f1", $f1);
            $this->setVarByRef("f2", $f2);
            $this->setVarByRef("f3", $f3);
            $this->setVarByRef("g9", $g9);
            $this->setVarByRef("g10", $g10);
            $this->setVarByRef("h11", $h11);
            $this->setVarByRef("h12", $h12);
            $this->setVarByRef("d6", $d6);
            $this->setVarByRef("d7", $d7);
            $mode = "fixup";
            $this->setVarByRef("mode", $mode);
            return "outcomesandassessmentthreeScience_tpl.php";
        }

        $id = $this->getParam("id");
        $mode = "fixup";

        $a4 = $a1 * $a3;
        switch ($a2) {
            case 1:
                $a8 = $a4 * $a5;
                break;
            case 2:
                $a8 = $a4 * $a6;
                break;
            case 3:
                $a8 = $a4 * $a7;
                break;
            default:
                $errormessages[] = "Level of course must be 1, 2 or 3";
        }
        $a13 = $a4 + $a8;
        $a13 = round($a13);

        $b4 = $b1 * $b3;
        switch ($b2) {
            case 1:
                $b8 = $b4 * $b5;
                break;
            case 2:
                $b8 = $b4 * $b6;
                break;
            case 3:
                $b8 = $b4 * $b7;
                break;
            default:
                $errormessages[] = "Level of course must be 1, 2 or 3";
        }
        $b13 = $b4 + $b8;
        $b13 = round($b13);

        $c4 = $c1 * $c3;
        switch ($c2) {
            case 1:
                $c8 = $c4 * $c5;
                break;
            case 2:
                $c8 = $c4 * $c6;
                break;
            case 3:
                $c8 = $c4 * $c7;
                break;
            default:
                $errormessages[] = "Level of course must be 1, 2 or 3";
        }
        $c13 = $c4 + $c8;
        $c13 = round($c13);

        $d4 = $d1 * $d3;
        $d13 = $d4 + $d8;
        $d13 = round($d13);

        $e13 = $e8;

        $f4 = $f1 * $f3;
        switch ($f2) {
            case 1:
                $f8 = $f4 * $f5;
                break;
            case 2:
                $f8 = $f4 * $f6;
                break;
            case 3:
                $f8 = $f4 * $f7;
                break;
            default:
                $errormessages[] = "Level of course must be 1, 2 or 3";
        }
        $f13 = $f4 + $f8;
        $f13 = round($f13);

        $g13 = $g9 + $g10;
        $g13 = round($g13);

        $h13 = $h11 + $h12;
        $h13 = round($h13);

        $i13 = $a13 + $b13 + $c13 + $d13 + $e13 + $f13 + $g13 + $h13;

        $formdata = array();
        $formdata["a1"] = $a1;
        $formdata["a2"] = $a2;
        $formdata["b1"] = $b1;
        $formdata["b2"] = $b2;
        $formdata["c1"] = $c1;
        $formdata["c2"] = $c2;
        $formdata["d1"] = $d1;
        $formdata["d8"] = $d8;
        $formdata["e1"] = $e1;
        $formdata["e8"] = $e8;
        $formdata["other"] = $other;
        $formdata["f1"] = $f1;
        $formdata["f2"] = $f2;
        $formdata["f3"] = $f3;
        $formdata["g9"] = $g9;
        $formdata["g10"] = $g10;
        $formdata["h11"] = $h11;
        $formdata["h12"] = $h12;
        $formdata["d6"] = $d6;
        $formdata["d7"] = $d7;
        $formdata = serialize($formdata);
        $this->objformdata->saveData($id, $formname, $formdata);

        $this->setVarByRef("a1", $a1);
        $this->setVarByRef("a2", $a2);
        $this->setVarByRef("b1", $b1);
        $this->setVarByRef("b2", $b2);
        $this->setVarByRef("c1", $c1);
        $this->setVarByRef("c2", $c2);
        $this->setVarByRef("d1", $d1);
        $this->setVarByRef("d8", $d8);
        $this->setVarByRef("e1", $e1);
        $this->setVarByRef("e8", $e8);
        $this->setVarByRef("other", $other);
        $this->setVarByRef("f1", $f1);
        $this->setVarByRef("f2", $f2);
        $this->setVarByRef("f3", $f3);
        $this->setVarByRef("g9", $g9);
        $this->setVarByRef("g10", $g10);
        $this->setVarByRef("h11", $h11);
        $this->setVarByRef("h12", $h12);
        $this->setVarByRef("d6", $d6);
        $this->setVarByRef("d7", $d7);

        $this->setVarByRef("a4", $a4);
        $this->setVarByRef("a8", $a8);
        $this->setVarByRef("a13", $a13);
        $this->setVarByRef("b4", $b4);
        $this->setVarByRef("b8", $b8);
        $this->setVarByRef("b13", $b13);
        $this->setVarByRef("c4", $c4);
        $this->setVarByRef("c8", $c8);
        $this->setVarByRef("c13", $c13);
        $this->setVarByRef("d4", $d4);
        $this->setVarByRef("d13", $d13);
        $this->setVarByRef("e13", $e13);
        $this->setVarByRef("f4", $f4);
        $this->setVarByRef("f8", $f8);
        $this->setVarByRef("f13", $f13);
        $this->setVarByRef("g13", $g13);
        $this->setVarByRef("h13", $h13);
        $this->setVarByRef("i13", $i13);

        $this->setVarByRef("id", $id);
        return "outcomesandassessmentthreeScience_tpl.php";
    }

    public function __showeditCourseProposal() {

        $telephone = $this->getParam("telephone");
        $title = $this->getParam("title");
        $owner = $this->getParam("contact");
        $department = $this->getParam("department");
        $id = $this->getParam('docid');

        $this->setVarByRef("telephone", $telephone);
        $this->setVarByRef("title", $title);
        $this->setVarByRef("contact", $contact);
        $this->setVarByRef("department", $department);
        $this->setVarByRef("id", $id);

        /* $selected = $this->getParam('selected');
          $mode = "new";
          $this->setVarByRef("mode", $mode);
          $this->setVarByRef("selected", $selected); */
        return "editCourseProposal_tpl.php";
    }

    public function __showforwardform() {
        return "forwardto_tpl.php";
    }

    public function __forward() {
        $id = $this->getParam("id");

        return "unapproveddocs_tpl.php";
    }

    public function __showsubmitform() {
        return "submit_tpl.php";
    }

    public function __submit() {
        $id = $this->getParam("id");
        $submit = $this->getParam("submit");

        $this->documents->setStatus($id, $submit);

        return "unapproveddocs_tpl.php";
    }

    /**
     *
     * The standard dispatch method for the apo module.
     * The dispatch method uses methods determined from the action
     * parameter of the querystring and executes the appropriate method,
     * returning its appropriate template. This template contains the code
     * which renders the module output.
     *
     * @access public
     * @param $action
     * @return A call to the appropriate method
     *
     */
    public function __facultymanagement() {
        $selected = "facultymanagement";
        $faculties = $this->faculties->getfaculties(0, 10, $this->mode);
        $this->setVarByRef("faculties", $faculties);
        $this->setVarByRef("selected", $selected);

        return "facultymanagement_tpl.php";
    }

    /*
     * This method is used to add a new faculty
     * @param none
     * @access public
     * @return the form that will be used to capture the information for the new
     * faculty
     */
    public function __newfaculty() {
        $selected = $this->getParam('selected');
        $mode = "new";
        $action = "registerfaculty";
        $this->setVarByRef("action", $action);
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);

        return "addeditfaculty_tpl.php";
    }

    /*
     * This method is used to add a new faculty
     * @param none
     * @access public
     * @return the form that will be used to edit the information for the faculty
     */
    public function __editfaculty() {
        $selected = $this->getParam('selected');
        $mode = "edit";
        $action = "editfaculty";
        $this->setVarByRef("action", $action);
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("selected", $selected);

        return "addeditfaculty_tpl.php";
    }

    public function __registerfaculty() {
        $faculty = $this->getParam('faculty');
        $contact = $this->getParam('contact_person');
        $tel = $this->getParam('telephone');
        
        $this->faculties->addFaculty($faculty, $contact, $tel);

        return $this->nextAction('facultymanagement', array('folder' => '0'));
    }
}