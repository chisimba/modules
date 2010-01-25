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
 * @package   dms (document management system)
 * @author    Nguni Phakela, david wafula
 *
 =
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

class dms extends controller {
    function init() {
        $this->loadclass('link','htmlelements');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objLog = $this->getObject('logactivity', 'logger');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objLog->log();
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        //get the util object
        $this->objUtils = $this->getObject('userutils');
        // user object
        $this->objUser = $this->getObject('user', 'security');
        //file type info object
        $this->objPermitted = $this->getObject('dbpermittedtypes');
        $this->objUploads = $this->getObject('dbfileuploads');
        $this->objFileFolder = $this->getObject('filefolder','filemanager');
        $this->folderPermissions=$this->getObject('dbfolderpermissions');
        $this->documents=$this->getObject('dbdocuments');
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
            return '__'.$action;
        }
        else {
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
        if (method_exists($this, '__'.$action)) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * Method to show the Home Page of the Module
     */
    public function __home() {
        $error = "";
        $error = $this->getParam('result');
        $userid = $this->objUser->userId();
        $this->setVarByRef("userid", $userid);
        $this->setVarByRef("error", $error);
        return "home_tpl.php";
    }

    /*
     * Method to show the upload file page
     *
    */
    public function __uploadFile() {
        $this->setVarByRef('action','upload');
        return "uploadFile_tpl.php";
    }

    /*
     * Method to submit file of any type
     *
    */
    public function __doupload() {
        $path = $this->getParam('path');
        $docname=$this->getParam('docname');
        $docid=$this->getParam('docid');

        $result = $this->objUtils->saveFile($path,$docname,$docid);

        if(strstr($result, "success")) {
            $this->nextAction('home');
        }
        else {
            return $this->nextAction('home', array('message'=>$result));
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
        $this->setVarByRef('action','search');
        return "searchForFile_tpl.php";
    }

    /**
     * Used to display details of a specific file
     * @return <type>
     */
    public function __viewfiledetails() {
        $id = $this->getParam('id');
        $this->setVarByRef("id", $id);
        $this->setVarByRef('action','Details');

        return "viewfiledetails_tpl.php";
    }

    /**
     * for admin puproses
     * @return <type>
     */
    public function __admin() {
        $this->setVarByRef('action','admin');
        return "admin_tpl.php";
    }

    /**
     * Used to add a new ext type to the database
     * @return <type>
     */
    public function __savefiletype() {
        // go save stuff
        $this->objPermitted->saveFileTypes($this->getParam('filetypedesc'),$this->getParam('filetypeext'));
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
        $filename=$this->getParam('filename');
        return $this->objUtils->downloadFile($filename);
    }
    /**
     *gets a list of folders for a give dir. List given in json format
     * @return <type>
     */
    public function __getFolders() {
        return $this->objUtils->getFolders();
    }
    /**
     * gets a list of files in a selected dir. Thel list is given in json format
     * @return <type>
     */
    public function __getFiles() {
        return $this->objUtils->getFiles();
    }

    /**
     * used to create a new folder in a selected dir. If none is provided, the folder is
     * created in the root dir
     * @return <type>
     */
    public function __createfolder() {
        $path=$this->getParam('folderpath');
        $name=$this->getParam('foldername');
        if(!$path) {
            $path="";
        }
        $this->objUtils->createFolder($path,$name);
        return $this->nextAction('getFolders', array());
    }

    /**
     * renames the supplied folder
     * @return <type>
     */
    public function __renamefolder() {
        $res = $this->objUtils->renameFolder($this->getParam('folderpath'),$this->getParam('foldername'));
        return $this->nextAction('home', array("result"=>$res));
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

        if($fileRes == 1) {
            $this->objUploads->deleteFileRecord($id);
        }
        else {
            $result = $this->objLanguage->languageText("error_DELETE", 'dms');
        }

        return $this->nextAction('home', array("result"=>"$result"));
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
        $foldername=$this->getParam('foldername');
        return $this->folderPermissions->getusers($foldername);
    }
    /**
     * gets all users in the database based on the search filter
     * @return <type>
     */
    public function __getallusers() {
        $searchfield=$this->getParam('searchfield');
        return $this->folderPermissions->getallusers($searchfield);
    }

    /**
     * adds a user access rights to the selected folder
     * @return <type>
     */
    public function __adduser() {
        $userid=$this->getParam('userid');
        $folderpath=$this->getParam('folderpath');
        $viewfiles=$this->getParam('viewfiles');
        $uploadfiles=$this->getParam('uploadfiles');
        $createfolder=$this->getParam('createfolder');

        return $this->folderPermissions->addPermission($userid,$folderpath,$viewfiles,
                $uploadfiles,$createfolder);
    }
    /**
     * deletes permisions of the selected user on the selected folder
     * @return <type>
     */
    public function __removeuser() {
        $userid=$this->getParam('userid');
        $folderpath=$this->getParam('folderpath');
        return $this->folderPermissions->removePermission($userid,$folderpath);
    }
    /**
     * returns a list of file extensions as json list
     * @return <type>
     */
    public function  __getFileExtensions() {
        return $this->objPermitted->getFileExtensions();
    }
    /**
     * saves a new file extension into the database
     * @return <type>
     */
    public  function __addfileextension() {
        $ext=$this->getParam('ext');
        $desc=$this->getParam('desc');
        return $this->objPermitted->saveFileType($desc,$ext);
    }
    /**
     *  returns true / false, if admin
     */
    public function __isadmin() {
        echo "true";
        // echo $this->objUser->isAdmin()?"true":"false";
    }


    public function __monitorupload() {
        $filename=$this->getParam('filename');
        $folderpath=$this->getParam('folderpath');
        $basedir=$this->objSysConfig->getValue('FILES_DIR', 'dms');

        $path=$basedir.'/'.$folderpath.'/'. $filename;
        $path=str_replace("//", "/", $path);

        echo file_exists($path)?"success":"false";
    }

    function __registerdocument() {
        $date=$this->getParam('date');
        $number=$this->getParam('number');
        $dept=$this->getParam('department');
        $title=$this->getParam('title');
        $selectedfolder=$this->getParam('topic');
        $refno=$number.$date;
        $telephone=$this->getParam('telephone');

        $this->documents->addDocument(
                $date,
                $refno,
                $dept,
                $telephone,
                $title,
                $selectedfolder);

    }

    function __getdepartment() {
        /*   $username='A0017615';//$this->getParam('username');
        $url="http://paxdev.wits.ac.za:8080/wits-wims-services-0.1-SNAPSHOT/wims/staff/position/$username";
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $r=curl_exec($ch);
        curl_close($ch);
        $jsonArray=json_decode($r);
        echo $jsonArray->objects[0]->organizationName;
        */
        echo "Test";
    }
    function __getdocuments() {
        $this->documents->getdocuments();
    }

    /**
     * we get document details and format then in special json that allows
     * us to use GWT objects
     */
    function __getdocument() {
        $docid=$this->getParam('docid');
        $doc= $this->documents->getdocument($docid);
        $userid=$this->objUser->userid();
        //$userid="1";
        $owner=$doc['userid'] == $userid? "true":"false";
        $str="[";
        $str.='{';
        $str.='"docname":'.'"'.$doc['docname'].'",';
        $str.='"refno":'.'"'.$doc['refno'].'",';
        $str.='"topic":'.'"'.$doc['topic'].'",';
        $str.='"owner":'.'"'.$owner.'",';
        $str.='"department":'.'"'.$doc['department'].'",';
        $str.='"upload":'.'"'.$doc['upload'].'",';
        $str.='"telephone":'.'"'.$doc['telephone'].'"';
        $str.='}';
        $str.=']';

        echo $str;
    }
    function __approveDocs() {
        $docids=$this->getParam('docids');
        $this->documents->approveDocs($docids);
    }
    function requiresLogin() {
        return true;
    }
}