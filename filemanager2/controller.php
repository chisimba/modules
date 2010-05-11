<?php
/**
 *
 * File Manager2
 *
 * PHP version 5
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
 * @package   helloforms
 * @author    Qhamani Fenama qfenama@uwc.ac.za/qfenama@gmail.com
 * @copyright 2010 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: controller.php 15834 2009-12-08 11:40:36Z paulscott $
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 *
 * Controller class for Chisimba for the module filemanager2
 *
 * @author Qhamani Fenama
 * @package filemanager2
 *
 */
class filemanager2 extends controller {

    /**
     *
     * @var string $objConfig String object property for holding the
     * configuration object
     * @access public;
     *
     */
    public $objConfig;

    /**
     *
     * @var string $objLanguage String object property for holding the
     * language object
     * @access public
     *
     */

    public $fileSize;

    /**
     *
     * @var string $objLanguage String object property for holding the
     * language object
     * @access public
     *
     */
    public $objLanguage;
    /**
     *
     * @var string $objLog String object property for holding the
     * logger object for logging user activity
     * @access public
     *
     */
    public $objLog;

    /**
     * Object of the groupadminmodel class in the groupadmin module.
     *
     * @access protected
     * @var object
     */
    protected $objGroup;

    /**
     * Object of the dbsysconfig class in the sysconfig module.
     *
     * @access protected
     * @var object
     */
    protected $objSysConfig;

    public $debug = FALSE;

    /**
     *
     * Intialiser for the filemanager2 controller
     * @access public
     *
     */
    public function init() {
        // File Manager Classes
        $this->objFiles = $this->getObject('dbfile', 'filemanager');
        $this->objFolders = $this->getObject('dbfolder', 'filemanager');
        $this->objFileTags = $this->getObject('dbfiletags', 'filemanager');
        $this->objCleanUrl = $this->getObject('cleanurl', 'filemanager');
        $this->objUpload = $this->getObject('upload', 'filemanager');
        $this->objFilePreview = $this->getObject('filepreview', 'filemanager');
        $this->objQuotas = $this->getObject('dbquotas', 'filemanager');
        $this->objSymlinks = $this->getObject('dbsymlinks', 'filemanager');

        $this->objUploadMessages = $this->getObject('uploadmessages', 'filemanager');

        // Other Classes
        $this->loadClass('formatfilesize', 'files');
        $this->objFileIcons = $this->getObject('fileicons', 'files');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objUser = $this->getObject('user', 'security');
        $this->objGroup = $this->getObject('groupadminmodel', 'groupadmin');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');

        $this->objLanguage = $this->getObject('language', 'language');
        $this->objMenuTools = $this->getObject('tools', 'toolbar');
        $this->loadClass('link', 'htmlelements');


        $this->userId = $this->objUser->userId();

        if ($this->userId != '') {
            // Setup User Folder
            $folderpath = 'users/'.$this->userId;

            $folderId = $this->objFolders->getFolderId($folderpath);



            if ($folderId == FALSE) {
                $objIndexFileProcessor = $this->getObject('indexfileprocessor', 'filemanager');
                $list = $objIndexFileProcessor->indexUserFiles($this->objUser->userId());
            }
        }

        $this->objContext = $this->getObject('dbcontext', 'context');
        $this->contextCode = $this->objContext->getContextCode();
        if ($this->contextCode != '') {
            $folderpath = 'context/'.$this->contextCode;

            $folderId = $this->objFolders->getFolderId($folderpath);
            if ($folderId == FALSE) {
                $objIndexFileProcessor = $this->getObject('indexfileprocessor','filemanager');
                $list = $objIndexFileProcessor->indexFiles('context', $this->contextCode);
            }
        }
    }

    /**
     * Override the login object in the parent class
     *
     * @param void
     * @return bool
     * @access public
     */
    public function requiresLogin($action) {
        if ($action == 'file') {
            return FALSE;
        } else {
            return TRUE;
        }
    }


    public function dispatch($action='home') {


        // Check to ensure the user has access to the file manager.
        if (!$this->userHasAccess()) {
            return 'access_denied_tpl.php';
        }


        /*
* Convert the action into a method (alternative to 
* using case selections)
        */
        $method = $this->__getMethod($action);
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
     * @return stromg the name of the method
     *
     */
    function __getMethod(& $action) {
        if ($this->__validAction($action)) {
            return "__" . $action;
        } else {
            return "__home";
        }
    }

    /**
     * Default Action for File manager module
     * It shows the list of folders of a user
     * @access private
     */
    private function __home() {
        $contextFolderId='';
        if ($this->contextCode != '') {
            $contextFolderpath = 'context/'.$this->contextCode;
            $contextFolderId = $this->objFolders->getFolderId($contextFolderpath);
            $folderpath = 'users/'.$this->objUser->userId();
            $folderId = $this->objFolders->getFolderId($folderpath);
        }else {
            // Get Folder Details
            $folderpath = 'users/'.$this->objUser->userId();
            $folderId = $this->objFolders->getFolderId($folderpath);

        }
        $this->setVar('contextfolderid', $contextFolderId);
        $this->setVar('folderId', $folderId);

        return 'admin_tpl.php';
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function __json_viewfile() {
     $id=$this->getParam("id");
     $fileInfo = $this->objLanguage->languageText('mod_filemanager_fileinfo', 'filemanager', 'File Information');
     $fileInfoContent = '<h2>'.$fileInfo.'</h2>'.$this->objFiles->getFileInfoTable($id);
     echo $fileInfoContent;
    }
    public function __json_uploadFile() {

        $folder = $this->objFolders->getFolder($this->getParam('selectedfolder'));

        if ($folder != FALSE) {
            $this->objUpload->setUploadFolder($folder['folderpath']);
        }

        // Upload Files
        $results = $this->objUpload->uploadFiles();

        // Check if User entered page by typing in URL
        if ($results == FALSE) {
            return $this->nextAction(NULL);
        }

        // Check if no files were provided
        if (count($results) == 1 && array_key_exists('nofileprovided', $results)) {
            $extjs['success'] = true;
            $extjs['error'] = 'No files were provided';
            echo json_encode($extjs);
            exit(0);
        }

        $extjs['success'] = true;
        echo json_encode($extjs);
        exit(0);
    }


    public function __getDirectory() {
        echo $this->getJsonTree('users', $this->objUser->userId(), $this->getParam('node'));
        exit(0);
    }

    public function __getContextDirectory() {
        echo $this->getJsonTree('context', $this->contextCode, $this->getParam('node'));
        exit(0);
    }


    public function __getFolderContent() {
        $id = $this->getParam('id');

        // Get Folder Details
        $folder = $this->objFolders->getFolder($id);

        if ($folder == FALSE) {
            return $this->nextAction(NULL);
        }


        $folderParts = explode('/', $folder['folderpath']);

        $quota = $this->objQuotas->getQuota($folder['folderpath']);

        if ($folderParts[0] == 'context' && $folderParts[1] != $this->contextCode) {
            return $this->nextAction(NULL);
        }

        $files = $this->objFiles->getFolderFiles($folder['folderpath']);

        $allarr = array();
        $fileSize = new formatfilesize();
        if (count($files) > 0) {
            $totalCount = count($files);
            foreach ($files as $file) {

                $arr = array();
                $arr['id'] = $file['id'];
                $arr['filename'] = $file['filename'];
                $arr['filesize'] = $fileSize->formatsize($file['filesize']);
                $arr['fileicon'] = $this->objFileIcons->getFileIcon($file['filename']);
                $arr['filepath'] = $file['filefolder'].'/'.$file['filename'];

                $allarr[] = $arr;
                $arr = null;
            }
            $arr['totalCount'] = strval($totalCount);
            $arr['files'] = $allarr;
            echo json_encode($arr);
        }else {
            $arr['totalCount'] = "0";
            $arr['files'] = array();
            echo json_encode($arr);
        }

        exit(0);
    }

    public function __json_removefiles() {

        $ids = $this->getParam('ids');

        if ($ids) {
            $fileIds = substr_replace($ids, "",strlen($ids) - 1);

            $files = explode(',', $fileIds);
            foreach ($files as $id) {
                if($id) {
                    $res = $this->objFiles->deleteFile($id, False);
                }
            }

            $extjs['success'] = true;
        }
        else {
            $extjs['success'] = false;
            $extjs['errors']['message'] = 'Unable to connect to DB';
        }

        return json_encode($extjs);
    }

    function getJsonTree($folderType='users', $id, $node) {
        $folders = $this->objFolders->getSubFolders($node);

        $allarr = array();

        if (count($folders) > -1) {
            foreach ($folders as $folder) {
                $arr = array();
                $folderText = basename($folder['folderpath']);
                $folderShortText = substr(basename($folder['folderpath']), 0, 60) . '...';
                $arr['text'] = $folderText;
                $arr['id'] = $folder['id'];
                $arr['cls'] = 'folder';

                $allarr[] = $arr;
            }
        }

        return json_encode($allarr);
    }

    public function __jsoncreatefolder() {
        $parentId = $this->getParam('parentfolder', 'ROOT');
        $foldername = $this->getParam('foldername');

        if (preg_match('/\\\|\/|\\||:|\\*|\\?|"|<|>/', $foldername)) {
            $extjs['success'] = true;
            $extjs['error'] = "Illigal charectors in a folder name";
            echo json_encode($extjs);
            exit(0);
        }

        // Replace spaces with underscores
        $foldername = str_replace(' ', '_', $foldername);

        if ($parentId == 'ROOT') {
            $folderpath = 'users/'.$this->objUser->userId();
        } else {
            $folder = $this->objFolders->getFolder($parentId);

            if ($folder == FALSE) {
                return $this->nextAction(NULL, array('error'=>'couldnotfindparentfolder'));
            }
            $folderpath = $folder['folderpath'];
        }

        $this->objMkdir = $this->getObject('mkdir', 'files');

        $path = $this->objConfig->getcontentBasePath().'/'.$folderpath.'/'.$foldername;

        $result = $this->objMkdir->mkdirs($path);

        if ($result) {
            $folderId = $this->objFolders->indexFolder($path);
        }

        $extjs['success'] = true;
        $extjs['data'] = $folderId;
        echo json_encode($extjs);
        exit(0);

    }

    function __renameFolder() {
        $id = $this->getParam('id');
        $newname = $this->getParam('newname');
        $res = $this->objFolders->renameFolder($id,$newname);
        $extjs['success'] = true;
        $extjs['data'] = $res;
        echo json_encode($extjs);
        exit(0);
    }
    function __deleteFolder() {

        $id = $this->getParam('id');
        $res = $this->objFolders->deleteFolder($id);
        $extjs['success'] = true;
        $extjs['data'] = $res;
        echo json_encode($extjs);
        exit(0);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


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
    function __validAction(& $action) {
        if (method_exists($this, "__".$action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function getFreeSpace($path) {
        $fileSize = new formatfilesize();
        $quota = $this->objQuotas->getQuota($path);

        if ($quota['quotausage'] > $quota['quota']) {
            $freeSpace = 0;
        } else {
            $freeSpace = $quota['quota'] - $quota['quotausage'];
        }
        return $fileSize->formatsize($freeSpace);
    }

    /**
     * Checks if the user should have access to the file manager.
     *
     * @return boolean True if the user has access, false otherwise.
     */
    protected function userHasAccess() {
        $limitedUsers = $this->objSysConfig->getValue('LIMITEDUSERS', 'filemanager');
        if ($limitedUsers) {
            $userId = $this->objUser->userId();
            $groups = array('Site Admin', 'Lecturers');
            $isMember = FALSE;
            foreach ($groups as $group) {
                $groupId = $this->objGroup->getId($group);
                if ($this->objGroup->isGroupMember($userId, $groupId)) {
                    $isMember = TRUE;
                    break;
                }
            }
            return $isMember;
        } else {
            return TRUE;
        }
    }

}
?>
