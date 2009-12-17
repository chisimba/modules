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
 * @author    Nguni Phakela
 *
 * @copyright 2008 Free Software Innnovation Unit
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
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


        //get the util object
        $this->objUtils = $this->getObject('userutils');
        // user object
        $this->objUser = $this->getObject('user', 'security');
        //file type info object
        $this->objPermitted = $this->getObject('dbpermittedtypes');
        $this->objUploads = $this->getObject('dbfileuploads');
        $this->objFileFolder = $this->getObject('filefolder','filemanager');
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
        return "home2_tpl.php";
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
        $permissions = $this->getParam('permissions');
        $path = $this->getParam('path');
        $result = $this->objUtils->saveFile($permissions, $path);

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

    public function __searchforfile() {
        $this->setVarByRef('action','search');
        return "searchForFile_tpl.php";
    }

    public function __viewfiledetails() {
        $id = $this->getParam('id');
        $this->setVarByRef("id", $id);
        $this->setVarByRef('action','Details');

        return "viewfiledetails_tpl.php";
    }

    public function __admin() {
        $this->setVarByRef('action','admin');
        return "admin_tpl.php";
    }

    public function __savefiletype() {
    // go save stuff
        $this->objPermitted->saveFileTypes($this->getParam('filetypedesc'),$this->getParam('filetypeext'));
        return $this->nextAction('admin');
    }

    public function __deletefiletype() {
        $id = $this->getParam('id');
        $this->objPermitted->deleteFileType($id);
        return $this->nextAction('admin');
    }

    public function __downloadfile() {
        $filename=$this->getParam('filename');
        return $this->objUtils->downloadFile($filename);
    }
    public function __getFiles() {
        return $this->objUtils->getFiles();
    }

    public function __createfolder() {
        $this->objUtils->createFolder($this->getParam('folderpath'),$this->getParam('foldername'));
        return $this->nextAction('home', array());
    }

    public function __renamefolder() {
        $res = $this->objUtils->renameFolder($this->getParam('folderpath'),$this->getParam('foldername'));
        return $this->nextAction('home', array("result"=>$res));
    }

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

    public function __deletefolder() {
        $res = $this->objUtils->deleteFolder($this->getParam('folderpath'));
        return $this->nextAction('home', array("result"=>$res));
    }
}