<?php

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
 * @author    Nguni Phakela, david wafula
 *
  =
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end security check

class wicid extends controller {

    //Declare global variables
    public $TRUE;
    public $FALSE;

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
        $this->mode = $this->objSysConfig->getValue('MODE', 'wicid');
        $this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
        //Set global variables
        $this->TRUE = 1;
        $this->FALSE = 0;
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

        $tobeeditedfoldername = $this->getParam("tobeeditedfoldername", Null);
        $documents = $this->documents->getdocuments($this->mode);
        $this->setVarByRef("tobeeditedfoldername", $tobeeditedfoldername);

        $this->setVarByRef("documents", $documents);
        $this->setVarByRef("mode", $this->mode);
        $this->setVarByRef("selected", $selected);
        return "unapproveddocs_tpl.php";
    }

    /**
     * Function that receives search parameters for document retrieval
     * @return object
     */
    public function __filtersearch() {

        $filter = $this->getParam("filter", Null);
        $this->setVarByRef("selected", $this->getParam("selected", Null));
        $this->setVarByRef("printresults", $this->getParam("printresults", Null));
        $this->setVarByRef("filter", $filter);
        $this->setVarByRef("files", $this->getParam("files", Null));
        $this->setVarByRef("status", $this->getParam("status", Null));
        if ($filter == Null) {
            return $this->nextAction('home');
        } else {
            return "entersearchparam_tpl.php";
        }
    }

    /**
     * Function that searches and returns data per predefined params
     * @return object
     */
    public function __filterbyparam() {
        //Get the filter action
        $filter = $this->getParam("filter", Null);
        if ($filter=="Date") {
            //Get the values to search for
            $filtervalue = array();
            $filtervalue['start'] = $this->getParam("startdate", Null);
            $filtervalue['end'] = $this->getParam("enddate", Null);
        } else {
            //Get the value to search for
            $filtervalue = $this->getParam("filtervalue", Null);
        }
        //  $documents = $this->documents->getdocuments($this->mode);
        $rejecteddocuments = $this->documents->getdocuments($this->mode, "Y");

        $dir = $this->getParam("folder", "");
        $mode = $this->getParam("mode", "");

        $objPreviewFolder = $this->getObject('previewfolder');

        $selected = "";
        $selected = $dir;
        $basedir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
        if ($dir == $basedir) {
            $selected = "";
        }
        $files = $this->objUtils->searchFileInAllNodes($filter, $filtervalue);

        $this->setVarByRef("files", $files);
        $this->setVarByRef("documents", $documents);
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("rejecteddocuments", $rejecteddocuments);
        $selected = $this->baseDir . $selected;
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("filter", $filter);
        $status = 1;
        $this->setVarByRef("status", $status);
        $this->setVarByRef("printresults", $results);
        return "entersearchparam_tpl.php";
    }

    /**
     * Fetch the records based on the search parameters
     * @param string $filter the type of parameter to use
     * @param string $filtervalue the value supplied by the user
     * @return array
     */
    public function __getFilteredRecords($filter, $filtervalue) {
        $documents = $this->documents->filterdocuments($filter, $filtervalue);

        $mode = $this->getParam("mode", "");

        $selected = "";
        $selected = $dir;
        $basedir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
        if ($dir == $basedir) {
            $selected = "";
        }
        $files = $this->objUtils->getFiles($dir);
        $this->setVarByRef("files", $files);
        $this->setVarByRef("documents", $documents);
        $this->setVarByRef("mode", $mode);
        $selected = $this->baseDir . $selected;
        $this->setVarByRef("selected", $selected);
        return "viewfolder_tpl.php";
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

    /*
     * Method to delete folder/topic
     *
     */

    public function __deletetopic() {

        //Get the folder
        $folder = $this->getParam("parentfolder", "");
        $userId = $this->objUser->userId();
        if($folder=='0')
            $folder = '/';
        //Check if user is authorised to delete
        $isowner = $this->folderPermissions->permissionExists($userId, $folder);
        $folderpermserror = $this->objLanguage->languageText('mod_wicid_deletetopicpermserror', 'wicid', "You do not have permissions to delete this folder");
        $deletesuccess = $this->objLanguage->languageText('mod_wicid_deletesuccess', 'wicid', "was deleted successfully");
        if(!$isowner){
            return $this->nextAction('viewfolder', array('message' => $folderpermserror, 'folder'=>$folder));
        }

        //Check if folder has documents
        $checkfolderdocs = $this->objUploads->getNodeFiles($folder);
        $foldernotempty = $this->objLanguage->languageText('mod_wicid_deletetopicerrormessage', 'wicid', "Kindly delete the documents in this topic before deleting it. These documents are listed in the Topics section on the lower part of this form");
        //Ask user to delete the contents of the folder first, else delete the topic if empty
        if(count($checkfolderdocs)>=1){
            return $this->nextAction('viewfolder', array('message' => $foldernotempty, 'folder'=>$folder));
        } else {
            //Delete the topic
            $this->folderPermissions->removePermission($userId, $folder);

            return $this->nextAction('viewfolder', array('message' => $folder." ".$deletesuccess, 'folder'=>'/'));
        }            

        if (strstr($result, "success")) {
            $this->nextAction('home');
        } else {
            return $this->nextAction('home', array('message' => $result));
        }
    }

    /**
     * function that renders a folder and its associated documents
     * 
     * @return form
     */

    public function __viewfolder() {
        //Set show rows
        $rows = 10;
        $start = $this->getParam("start", 0);
        //Select records Limit array
        $limit = array();
        $limit['start'] = $start;
        $limit['rows'] = $rows;
        //Get the rowcount
        $rowcount = $this->getParam("rowcount", Null);

        $rejecteddocuments = $this->documents->getdocuments($this->mode, 'N', "Y",$limit,$rowcount);

        $dir = $this->getParam("folder", "");
        $mode = $this->getParam("mode", "");
        $message = $this->getParam("message", "");
        

        $objPreviewFolder = $this->getObject('previewfolder');

        $selected = "";
        $selected = $dir;
        
        $basedir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
        if ($dir == $basedir) {
            $selected = "";
        }
        $rowcount = $this->getParam("rowcount", Null);
        $this->setVarByRef("start", $start);
        $this->setVarByRef("rows", $rows);
        $files = $this->objUtils->getFiles($dir,$limit, $rowcount);
        $this->setVarByRef("files", $files);
        $this->setVarByRef("dir", $dir);
        $this->setVarByRef("documents", $documents);
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("message", $message);
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
        $path = $this->getParam('parentfolder');
        $name = $this->getParam('foldername');

        if (!$path) {
            $path = "";
        }

        //Confirm that folder does not exist
        $exists = $this->objUtils->folderExistsCheck($path, $name);
        //Create only if new
        if (!$exists) {
            $this->objUtils->createFolder($path, $name);
        }
        $this->nextAction('unapproveddocs');
    }

    /**
     * used to check if a folder exists in the selected dir.
     * 
     * @return boolean
     */
    public function __folderExistsCheck() {
        $path = $this->getParam('parentfolder');
        $name = $this->getParam('foldername');

        if (!$path) {
            $path = "";
        }

        $exists = $this->objUtils->folderExistsCheck($path, $name);
        if ($exists) {
            echo 'exists';
        } else {
            echo 'create';
        }
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
        if ($number == 'Select ...') {
            $errormessages[] = "Select Document Number";
        }

        $dept = $this->getParam('department');

        if ($dept == '') {
            $errormessages[] = "Fill in department";
        }
        $title = $this->getParam('title');



        if ($title == 'title') {
            $errormessages[] = "Fill in title";
        }
        $selectedfolder = $this->getParam('parentfolder');


        if ($selectedfolder == '0') {
            $errormessages[] = "Select topic";
        }
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
        $group = $this->getParam('group');

        if ($group == 'Select ...') {
            $errormessages[] = "Select group";
        }



        if (count($errormessages) > 0) {

            $this->setVarByRef("errormessages", $errormessages);
            $this->setVarByRef("department", $dept);
            $this->setVarByRef("contact", $contact);
            $this->setVarByRef("telephone", $telephone);
            $this->setVarByRef("title", $title);
            $this->setVarByRef("number", $number);
            $this->setVarByRef("groupid", $group);
            $this->setVarByRef("selected", $selectedfolder);
            $mode = "fixup";
            $this->setVarByRef("mode", $mode);
            return "addeditdocument_tpl.php";
        }
        $status = $this->getParam('status');
        if ($status == '' || $status == NULL) {
            $status = "0";
        }
        $currentuserid = $this->objUser->userid();
        $version = $this->getParam('version', "1");
        //  if (!$this->documents->documentExists($dept, $refno, $title, $selectedfolder, $version)) {
        $refNo = $this->documents->addDocument(
                        $date,
                        $refno,
                        $dept,
                        $contact,
                        $telephone,
                        $title,
                        $group,
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


        $documents = $this->documents->getdocuments($this->mode);
        $this->setVarByRef("documents", $documents);
        $selected = "unapproved";
        $tobeeditedfoldername = $this->getParam('tobeeditedfoldername', Null);
        $this->setVarByRef("tobeeditedfoldername", $tobeeditedfoldername);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("mode", $this->mode);
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
        $this->documents->getdocuments($mode, $userid);
    }

    function __getrejecteddocuments() {
        $mode = $this->getParam("mode");
        $userid = $this->getParam("userid");
        $rejected = "Y";
        $this->documents->getdocuments($mode, $userid, $rejected);
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

    /*
     * Function that approves documents in batch
     */

    function __batchexecute() {
        //Get parameters
        $submit = strtolower($this->getParam('submit'));
        $id = $this->getParam('id');
        $mode = $this->getParam('mode');
        $active = $this->getParam('active', 'Y');
        $rejected = $this->getParam('rejected', 'N');

        $documents = $this->documents->getdocuments($this->mode, $rejected, $active);

        //Check and execute action
        if ($submit == "approve selected") {
            //Step through the documents and approve those selected
            if (isset($documents)) {
                foreach ($documents as $document) {
                    //if ($document['attachmentstatus'] != "No")
                    if ($this->getParam($document['id'] . '_app') == 'execute') {
                        $this->documents->approveDocs($document['id']);
                    }
                }
            }
        } elseif ($submit == "delete selected") {

            //Step through the documents and approve those selected
            if (isset($documents)) {

                foreach ($documents as $document) {
                    if ($this->getParam($document['id'] . '_app') == 'execute') {
                        $this->documents->deleteDocuments($document['id']);
                    }
                }
            }
        }
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

            //return $this->nextAction('erroriframe', array('message' => 'Unsupported file extension.Only use txt, doc, odt, ppt, pptx, docx,pdf', 'file' => $filename, 'id' => $generatedid));
            $message = 'Unsupported file extension.Only use txt, doc, odt, ppt, pptx, docx,pdf';
            return $this->nextAction('ajaxuploadresults', array('id' => $generatedid, 'fileid' => $id, 'filename' => '', 'message' => $message));
        } else {

            $filename = $result['filename'];
            $mimetype = $result['mimetype'];
            $path_parts = $result['storedname'];
            //$ext = $path_parts['extension'];
            $filename = strtolower($filename);
            $exts = split("[/\\.]", $filename);
            $n = count($exts) - 1;
            $ext = $exts[$n];
            $ext = strtolower($ext);
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
            return $this->nextAction('ajaxuploadresults', array('id' => $generatedid, 'fileid' => $id, 'filename' => $filename, 'message' => 'file uploaded'));
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

        $message = $this->getParam('message');
        $this->setVarByRef('message', $message);

        return 'ajaxuploadresults_tpl.php';
    }

    public function __saveFormData() {
        $formname = $this->getParam('formname');
        $formdata = $this->getParam('formdata');
        $docid = $this->getParam('docid');
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
     * retrives for
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

    public function __changecurrentuser() {
        $userid = $this->getParam('userid');
        $docid = $this->getParam('docid');
        $version = $this->getParam('version');
        $this->documents->changeCurrentUser($userid, $docid, $version);
    }

    public function __retrievedocument() {
        $userid = $this->getParam('userid');
        $docid = $this->getParam('docid');
        $this->documents->retrieveDocument($userid, $docid);
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
        $tobeeditedfoldername = $this->getParam("tobeeditedfoldername", Null);
        $attachmentStatus = $this->getParam("attachmentStatus", Null);
        $documents = $this->documents->getdocuments($this->mode);
        $this->setVarByRef("tobeeditedfoldername", $tobeeditedfoldername);
        $this->setVarByRef("documents", $documents);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("mode", $this->mode);
        $this->setVarByRef("attachmentStatus", $attachmentStatus);
        return "unapproveddocs_tpl.php";
    }

    public function __rejecteddocuments() {
        $selected = "rejecteddocuments";
        $attachmentStatus = $this->getParam("attachmentStatus", Null);
        $documents = $this->documents->getRejectedDocuments($this->mode, 'Y', 'Y');
        $this->setVarByRef("attachmentStatus", $attachmentStatus);
        $this->setVarByRef("documents", $documents);
        $this->setVarByRef("selected", $selected);
        return "rejecteddocuments_tpl.php";
    }

    public function __newdocument() {
        $selected = $this->getParam('selected', Null);
        $mode = $this->getParam('mode', Null);
        $errormessages = $this->getParam('errormessages', Null);
        $telephone = $this->getParam('telephone', Null);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("errormessages", $errormessages);
        $this->setVarByRef("telephone", $telephone);
        return "addeditdocument_tpl.php";
    }

}