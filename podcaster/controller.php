<?php

/**
 * podcaster is used for sharing podcasts online.
 * Registered users can upload and manage thier podcasts
 * Supported file formats: mp3.
 * JODConverter is used primarly as the document converter engine, although
 * in same cases we are using swftools.
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
 * @package   podcaster
 * @author    Paul Mungai <paulwando@gmail.com>
 *
 * @copyright 2011 Free Software Innnovation Unit
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link      http://avoir.uwc.ac.za
 * 
 */
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

// end of security

class podcaster extends controller {

    public $objConfig;
    public $realtimeManager;
    public $presentManager;
    public $objAnalyzeMediaFile;
    public $objMediaFileData;

    /**
     * Constructor
     */
    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->objAnalyzeMediaFile = $this->getObject('analyzemediafile', 'filemanager');
        $this->objMediaFileData = $this->getObject('dbmediafiledata');
        $this->objFiles = $this->getObject('dbpodcasterfiles');
        $this->objTags = $this->getObject('dbpodcastertags');
        $this->objUtils = $this->getObject('userutils');
        $this->objUploads = $this->getObject('dbfileuploads');
        $this->documents = $this->getObject('dbdocuments');
        $this->objViewerUtils = $this->getObject('viewerutils');
        $this->objSchedules = $this->getObject('dbpodcasterschedules');
        $this->realtimeManager = $this->getObject('realtimemanager');
        $this->folderPermissions = $this->getObject('dbfolderpermissions');

        $this->objSearch = $this->getObject('indexdata', 'search');
        // user object
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
        //Get podcaster base directory
        $this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'podcaster');
    }

    /**
     * Method to override login for certain actions
     * @param <type> $action
     * @return <type>
     */
    public function requiresLogin($action) {
        $required = array('describepodcast', 'login', 'steponeupload', 'upload', 'edit', 'updatedetails', 'tempiframe', 'erroriframe', 'uploadiframe', 'doajaxupload', 'ajaxuploadresults', 'delete', 'admindelete', 'deleteslide', 'deleteconfirm', 'regenerate', 'schedule', 'addfolder', 'removefolder', 'createfolder', 'folderexistscheck', 'renamefolder', 'deletetopic', 'deletefile', 'viewfolder', 'unpublishedpods');


        if (in_array($action, $required)) {
            return TRUE;
        } else {
            return FALSE;
        }
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
    function __home() {
        $tagCloud = $this->objTags->getTagCloud();
        $this->setVarByRef('tagCloud', $tagCloud);

        $latestFiles = $this->objFiles->getLatestPodcasts();
        $this->setVarByRef('latestFiles', $latestFiles);

        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $hometpl = $objSysConfig->getValue('HOMETPL', 'podcaster');

        $permittedTypes = array('newhome', 'home');

        // Check that period is valid, if not, the default home install
        if (!in_array($hometpl, $permittedTypes)) {
            $period = 'home';
        }
        return $hometpl . '_tpl.php';
    }

    /**
     * function that loads create folder form
     *
     * @return form
     */
    public function __steponeupload() {
        $defaultexists = $this->objUtils->defaultFolderExistsCheck();
        //Create only if new
        if (!$defaultexists) {
            $this->objUtils->createDefaultFolder();
        }

        $createcheck = $this->getParam('createcheck', 'new');
        $dir = $this->getParam("folder", "");
        if (empty($dir)) {
            $successmsg = Null;
            $dir = $this->objUser->userId();
            $this->setVarByRef('successmsg', $successmsg);
        } else {
            if ($createcheck == "add") {
                $successmsg = $dir . " " . $this->objLanguage->languageText('mod_podcaster_createsuccess', 'podcaster', "was created successfully");
                $this->setVarByRef('successmsg', $successmsg);
            } else if ($createcheck == "fail") {
                if ($dir == "/") {
                    $successmsg = $this->objLanguage->languageText('mod_podcaster_enterfoldername', 'podcaster', "You need to type in a meaningful folder name before submitting");
                    $this->setVarByRef('successmsg', $successmsg);
                } else {
                    $successmsg = $dir . " " . $this->objLanguage->languageText('mod_podcaster_createfail', 'podcaster', "was not created successfully. A corresponding folder already exists");
                    $this->setVarByRef('successmsg', $successmsg);
                }
            }
        }

        $this->setVarByRef("dir", $dir);
        $this->setVarByRef("mode", $this->mode);
        $selected = $this->baseDir . $dir;
        $selected = str_replace("//", "/", $selected);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("successmsg", $successmsg);
        return "steponeupload_tpl.php";
    }

    /**
     * function that loads create folder form
     *
     * @return form
     */
    public function __addfolder() {
        $this->setLayoutTemplate("podcaster_layout_tpl.php");

        $defaultexists = $this->objUtils->defaultFolderExistsCheck();
        //Create only if new
        if (!$defaultexists) {
            $this->objUtils->createDefaultFolder();
        }

        $createcheck = $this->getParam('createcheck', 'new');
        $dir = $this->getParam("folder", "");
        if (empty($dir)) {
            $successmsg = Null;
            $dir = $this->objUser->userId();
            $this->setVarByRef('successmsg', $successmsg);
        } else {
            if ($createcheck == "add") {
                $successmsg = $dir . " " . $this->objLanguage->languageText('mod_podcaster_createsuccess', 'podcaster', "was created successfully");
                $this->setVarByRef('successmsg', $successmsg);
            } else if ($createcheck == "fail") {
                if ($dir == "/") {
                    $successmsg = $this->objLanguage->languageText('mod_podcaster_enterfoldername', 'podcaster', "You need to type in a meaningful folder name before submitting");
                    $this->setVarByRef('successmsg', $successmsg);
                } else {
                    $successmsg = $dir . " " . $this->objLanguage->languageText('mod_podcaster_createfail', 'podcaster', "was not created successfully. A corresponding folder already exists");
                    $this->setVarByRef('successmsg', $successmsg);
                }
            }
        }

        $this->setVarByRef("dir", $dir);
        $this->setVarByRef("mode", $this->mode);
        $selected = $this->baseDir . $dir;
        $selected = str_replace("//", "/", $selected);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("successmsg", $successmsg);
        return "createfolder_tpl.php";
    }

    /**
     * Function that gets the default folder for the user
     * @param <type> $dir
     * @return <type>
     */
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
     * function that loads delete folder form
     *
     * @return form
     */
    public function __removefolder() {
        $this->setLayoutTemplate("podcaster_layout_tpl.php");
        $dir = $this->getParam("folder", "");
        $this->setVarByRef("mode", $this->mode);
        $selected = $this->baseDir . $dir;
        $message = $this->getParam('message', '');
        $this->setVarByRef("mode", $this->mode);
        $this->setVarByRef("message", $message);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("successmsg", $message);
        return "deletefolder_tpl.php";
    }

    /**
     * used to create a new folder in a selected dir. If none is provided, the folder is
     * created in the root dir
     * @return array
     */
    public function __createfolder2() {
        $this->setLayoutTemplate("podcaster_layout_tpl.php");
        $path = $this->getParam('parentfolder');
        if ($path == '0')
            $path = '/';
        $name = $this->getParam('foldername');
        $userId = $this->objUser->userId();
        $pathf = "/";
        //We need to remove the userId from the path
        if ($path != '/') {
            $remUId = split("/", $path);
            $count = count($remUId);
            $start = 0;
            do {
                if ($userId != $remUId[$start] && $remUId[$start] != "") {
                    $pathf .= $remUId[$start];
                    if (($start + 1) != $count)
                        $pathf .= "/";
                }
                $start++;
            }while ($start < $count);
            $path = $pathf;
        }
        if (empty($name) || $name == "/") {
            $flag = "selected";
            $folderdata = $this->folderPermissions->getPermmissions($pathf);
            $folderid = $folderdata[0]['id'];
            $this->nextAction('upload', array('createcheck' => $flag, 'folderid' => $folderid));
        }
        if (!$path) {
            $path = "";
        }

        $flag = "";

        $defaultexists = $this->objUtils->defaultFolderExistsCheck();
        //Create only if new
        if (!$defaultexists) {
            $this->objUtils->createDefaultFolder();
        }
        //Confirm that folder does not exist
        $exists = $this->objUtils->folderExistsCheck($path, $name);
        //Create only if new
        if (!$exists) {
            $path = $pathf;
            $this->objUtils->createFolder($path, $name);
            $path = $path . "/" . $name;
            $flag = 'add';
        } else {
            $flag = 'fail';
        }
        $path = str_replace("//", "/", $path);
        $folderdata = $this->folderPermissions->getPermmissions($path);
        $folderid = $folderdata[0]['id'];
        $this->setVarByRef('folder', $name);
        $this->nextAction('upload', array('createcheck' => $flag, 'folderid' => $folderid));
    }

    /**
     * used to create a new folder in a selected dir. If none is provided, the folder is
     * created in the root dir
     * @return <type>
     */
    public function __createfolder() {
        $this->setLayoutTemplate("podcaster_layout_tpl.php");
        $path = $this->getParam('parentfolder');
        $name = $this->getParam('foldername');

        if (!$path) {
            $path = "";
        }
        $flag = "";
        $defaultexists = $this->objUtils->defaultFolderExistsCheck();
        //Create only if new
        if (!$defaultexists) {
            $this->objUtils->createDefaultFolder();
            $flag = 'add';
        }
        //Confirm that folder does not exist
        $exists = $this->objUtils->folderExistsCheck($path, $name);
        //Create only if new
        if (!$exists) {
            $userId = $this->objUser->userId();
            //We need to remove the userId from the path
            $remUId = split("/", $path);
            $pathf = "/";
            $count = count($remUId);
            $start = 0;
            do {
                if ($userId != $remUId[$start] && $remUId[$start] != "") {
                    $pathf .= $remUId[$start];
                    if (($start + 1) != $count)
                        $pathf .= "/";
                }
                $start++;
            }while ($start < $count);
            $path = $pathf;
            $this->objUtils->createFolder($path, $name);
            $flag = 'add';
        } else {
            $flag = 'fail';
        }
        $this->setVarByRef('folder', $name);
        $this->nextAction('addfolder', array('createcheck' => $flag, 'folder' => $name));
    }

    /**
     * used to check if a folder exists in the selected dir.
     *
     * @return boolean
     */
    public function __folderExistsCheck() {
        $this->setLayoutTemplate("podcaster_layout_tpl.php");
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
        $this->setLayoutTemplate("podcaster_layout_tpl.php");
        $res = $this->objUtils->renameFolder($this->getParam('folderpath'), $this->getParam('foldername'));
        return $this->nextAction('home', array("result" => $res));
    }

    /*
     * Method to delete folder/topic
     *
     */

    public function __deletetopic() {
        $this->setLayoutTemplate("podcaster_layout_tpl.php");
        //Get the folder
        $folder = $this->getParam("parentfolder", "");
        $userId = $this->objUser->userId();
        if ($folder == '0')
            $folder = '/';
        $path = $folder;
        $userId = $this->objUser->userId();
        //We need to remove the userId from the path
        $remUId = split("/", $path);
        $pathf = "/";
        $count = count($remUId);
        $start = 0;
        do {
            if ($userId != $remUId[$start] && $remUId[$start] != "") {
                $pathf .= $remUId[$start];
                if (($start + 1) != $count)
                    $pathf .= "/";
            }
            $start++;
        }while ($start < $count);
        $path = $pathf;
        $folder = $path;
        $folderpermserror = '<strong class="confirm">' . $this->objLanguage->languageText('mod_podcaster_deletefolderpermserror', 'podcaster', "You do not have permissions to delete this folder") . '</strong>';
        if (!empty($folder) || $folder == "/") {
            //Check if user is authorised to delete
            $isowner = $this->folderPermissions->permissionExists($userId, $folder);
        } else {
            return $this->nextAction('removefolder', array('message' => $folderpermserror, 'folder' => $folder));
        }
        $deletesuccess = '<strong class="confirm">' . $this->objLanguage->languageText('mod_podcaster_deletesuccess', 'podcaster', "was deleted successfully") . '</strong>';
        if (!$isowner) {
            return $this->nextAction('removefolder', array('message' => $folderpermserror, 'folder' => $folder));
        }

        //Check if folder has podcasts
        $checkfolderdocs = $this->objUploads->getAllNodeFiles($folder);

        $foldernotempty = '<strong class="confirm">' . $this->objLanguage->languageText('mod_podcaster_shortdeleteallinfoldermessage', 'podcaster', "Kindly delete both approved and un-approved podcasts in this folder before deleting it") . '</strong>';
        //Ask user to delete the contents of the folder first, else delete the topic if empty
        if (count($checkfolderdocs) >= 1) {
            return $this->nextAction('removefolder', array('message' => $foldernotempty, 'folder' => $folder));
        } else {
            //Delete the topic
            $this->folderPermissions->removePermission($userId, $folder);

            return $this->nextAction('removefolder', array('message' => '<strong id="confirm">' . $folder . "</strong> " . $deletesuccess, 'folder' => '/'));
        }

        if (strstr($result, "success")) {
            $this->nextAction('removefolder');
        } else {
            return $this->nextAction('removefolder', array('message' => $result));
        }
    }

    /**
     * deletes the selected file
     * @return array
     */
    public function __deletefile() {
        $this->setLayoutTemplate("podcaster_layout_tpl.php");
        $userid = $this->objUser->userId();
        $id = $this->getParam('id');
        $fileRes = $this->objUtils->deleteFile($userid, $id);
        $result = "";

        if ($fileRes == 1) {
            $this->objUploads->deleteFileRecord($id);
        } else {
            $result = $this->objLanguage->languageText("mod_podcaster_deleteerror", 'podcaster', 'Folder could not be deleted. Note: You need to be the owner of this folder and also, the folder needs to be empty to delete');
        }
        return $this->nextAction('home', array("result" => "$result"));
    }

    /**
     * function that renders a folder and its associated documents
     *
     * @return form
     */
    public function __viewfolder() {
        $this->setLayoutTemplate("podcaster_layout_tpl.php");
        //Set show rows
        $rows = $this->pageSize;
        $start = $this->getParam("start", 0);
        //Select records Limit array
        $limit = array();
        $limit['start'] = $start;
        $limit['rows'] = $rows;
        //Get the rowcount
        $rowcount = $this->getParam("rowcount", Null);

        $rejecteddocuments = $this->documents->getdocuments($this->mode, 'N', "Y", $limit, $rowcount);

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
        $files = $this->objUtils->getFiles($dir, $limit, $rowcount);
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

    /*
     * Function that returns unpublished podcasts
     */

    public function __unpublishedpods() {
        $this->setLayoutTemplate("podcaster_layout_tpl.php");
        $selected = "unapproved";

        //Set show rows
        $rows = $this->pageSize;
        $start = $this->getParam("start", 0);

        //Select records Limit array
        $limit = array();
        $limit['start'] = $start;
        $limit['rows'] = $rows;

        //Get the rowcount
        $rowcount = $this->getParam("rowcount", Null);

        $tobeeditedfoldername = $this->getParam("tobeeditedfoldername", Null);
        $attachmentStatus = $this->getParam("attachmentStatus", Null);
        $documents = $this->documents->getdocuments($this->mode, 'N', "N", $limit, $rowcount);
        $this->setVarByRef("start", $start);
        $this->setVarByRef("rows", $rows);
        $this->setVarByRef("tobeeditedfoldername", $tobeeditedfoldername);
        $this->setVarByRef("documents", $documents);
        $this->setVarByRef("selected", $selected);
        $this->setVarByRef("mode", $this->mode);
        $this->setVarByRef("attachmentStatus", $attachmentStatus);
        return "unpublishedpodcasts_tpl.php";
    }

    /**
     * function to test whether target machine has java well installed
     */
    public function __willappletrun() {

        $actiontype = $this->getParam('actiontype');
        $id = $this->getParam('id');
        $agenda = $this->getParam('agenda');

        $this->setVarByRef('appletaction', $actiontype);
        $this->setVarByRef('id', $id);

        $this->setVarByRef('agenda', $agenda);

        return "willappletrun_tpl.php";
    }

    /**
     * This calls function that displays actual applet after veryifying that java exists
     * The applet is invoked in presenter mode
     *  @return <type>
     */
    public function __showpresenterapplet() {
        return $this->showapplet('true');
    }

    /**
     * Calls function to display applet, but in participant mode
     * @return <type>
     */
    public function __showaudienceapplet() {
        return $this->showapplet('false');
    }

    /**
     * Displays actual applet by returning the template responsible for this
     * @param <type> $isPresenter
     * @return <type>
     */
    private function showapplet($isPresenter) {

        $objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $supernodeHost = $objSysConfig->getValue('SUPERNODE_HOST', 'realtime');
        $supernodePort = $objSysConfig->getValue('SUPERNODE_PORT', 'realtime');

        $this->setVarByRef('supernodeHost', $supernodeHost);
        $this->setVarByRef('supernodePort', $supernodePort);

        $slideServerId = $this->realtimeManager->randomString(32); //'gen19Srv8Nme50';
        $this->realtimeManager->startSlidesServer($slideServerId);

        $id = $this->getParam('id');
        $title = $this->getParam('agenda');

        $filePath = $this->objConfig->getContentBasePath() . '/podcaster/' . $id;
        $this->setVarByRef('filePath', $filePath);
        $this->setVarByRef('sessionTitle', $title);

        $this->setVarByRef('sessionid', $id);
        $this->setVarByRef('slideServerId', $slideServerId);
        $this->setVarByRef('isPresenter', $isPresenter);

        // $this->setVar('pageSuppressBanner', TRUE);
        // $this->setVar('suppressFooter', TRUE);

        return "showapplet_tpl.php";
    }

    /**
     * displayes error
     */
    public function __showerror() {
        $title = $this->getParam('title');
        $content = $this->getParam('content');
        $content.='<br><a href="http://java.com">Download here</a>';

        $desc = $this->getParam('desc');

        $this->setVarByRef('title', $title);
        $this->setVarByRef('content', $content);
        $this->setVarByRef('desc', $desc);

        return "dump_tpl.php";
    }

    /**
     * Method to display the search results
     */
    public function __search() {
        $query = $this->getParam('q');

        $this->setVarByRef('query', $query);

        return 'search_tpl.php';
    }

    /**
     * Method to edit the details of a presentation
     *
     */
    function __edit() {
        $id = $this->getParam('id');

        $file = $this->objFiles->getFile($id);

        if ($file == FALSE) {
            return $this->nextAction('home', array('error' => 'norecord'));
        }

        $tags = $this->objTags->getTags($id);

        $this->setVarByRef('file', $file);
        $this->setVarByRef('tags', $tags);

        $mode = $this->getParam('mode', 'window');
        $this->setVarByRef('mode', $mode);

        if ($mode == 'submodal') {
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('suppressFooter', TRUE);
        }

        return 'process_tpl.php';
    }

    /**
     * Method to update the details of a presentation
     *
     */
    function __updatedetails() {
        $id = $this->getParam('id');
        $title = $this->getParam('title');
        $description = $this->getParam('description');
        $tags = explode(',', $this->getParam('tags'));
        $newTags = array();

        // Create an Array to store problems
        $problems = array();
        // Check that username is available
        if ($title == '') {
            $problems[] = 'emptytitle';
            $title = $id;
        }
        //
        // Clean up Spaces
        foreach ($tags as $tag) {
            $newTags[] = trim($tag);
        }

        $tags = array_unique($newTags);
        $license = $this->getParam('creativecommons');

        $this->objFiles->updateFileDetails($id, $title, $description, $license);
        $this->objTags->addTags($id, $tags);

        $file = $this->objFiles->getFile($id);
        $tags = $this->objTags->getTagsAsArray($id);
        $slides = $this->objSlides->getSlides($id);

        $file['tags'] = $tags;
        $file['slides'] = $slides;

        $this->_prepareDataForSearch($file);

        if (count($problems) > 0) {
            $this->setVar('mode', 'addfixup');
            $this->setVarByRef('problems', $problems);
            return 'process_tpl.php';
        } else {
            return $this->nextAction('view', array('id' => $id, 'message' => 'infoupdated'));
        }
    }

    /**
     * Method to view the details of a presentation
     *
     */
    function __view() {
        $id = $this->getParam('id');

        $filedata = $this->objMediaFileData->getFile($id);

        if (empty($filedata)) {
            return $this->nextAction('home', array('error' => 'norecord'));
        }

        $tags = $this->objTags->getTags($filedata['fileid']);

        $getPodcast = $this->objViewerUtils->getPodcastView($id);

        $this->setVarByRef('file', $getPodcast);
        $this->setVarByRef('tags', $tags);

        $this->setVar('pageTitle', $this->objConfig->getSiteName() . ' - ' . $filedata['title']);

        $objViewCounter = $this->getObject('dbpodcasterviewcounter');
        $objViewCounter->addView($id);

        return 'view_tpl.php';
    }

    /**
     * Method to get the flash file
     */
    function __getflash() {
        $id = $this->getParam('id');

        $fileExists = $this->objFiles->onlyCheckpodcasterVersion2($id);

        if ($fileExists) {
            // Return New version
            $redirect = $this->objConfig->getcontentPath() . 'podcaster/' . $id . '/' . $id . '_v2.swf';
        } else {
            // Return Old version
            $redirect = $this->objConfig->getcontentPath() . 'podcaster/' . $id . '/' . $id . '.swf';
        }

        header('Location:' . $redirect);
    }

    /**
     * Method to download a presentation
     */
    function __download() {
        $id = $this->getParam('id');
        $type = $this->getParam('type');

        $fullPath = $this->objConfig->getcontentBasePath() . 'podcaster/' . $id . '/' . $id . '.' . $type;

        if (file_exists($fullPath)) {
            $relLink = $this->objConfig->getcontentPath() . 'podcaster/' . $id . '/' . $id . '.' . $type;

            $objDownloadCounter = $this->getObject('dbpodcasterdownloadcounter');
            $objDownloadCounter->addDownload($id, $type);

            header('Location:' . $relLink);
        } else {
            return $this->nextAction(NULL, array('error' => 'cannotfindfile'));
        }
    }

    /**
     * Method to view a list of presentations that match a particular tag
     *
     */
    function __tag() {
        $tag = $this->getParam('tag');
        $sort = $this->getParam('sort', 'dateuploaded_desc');

        // Check that sort options provided is valid
        if (!preg_match('/(dateuploaded|title|creatorname)_(asc|desc)/', strtolower($sort))) {
            $sort = 'dateuploaded_desc';
        }

        if (trim($tag) != '') {
            $tagCounter = $this->getObject('dbpodcastertagviewcounter');
            $tagCounter->addView($tag);
        }

        $files = $this->objTags->getFilesWithTag($tag, str_replace('_', ' ', $sort));

        $this->setVarByRef('tag', $tag);
        $this->setVarByRef('files', $files);
        $this->setVarByRef('sort', $sort);

        return 'tag_tpl.php';
    }

    /**
     * Used to view a list of presentations uploaded by a particular user
     *
     */
    function __byuser() {
        $userid = $this->getParam('userid');
        $sort = $this->getParam('sort', 'dateuploaded_desc');

        // Check that sort options provided is valid
        if (!preg_match('/(dateuploaded|title)_(asc|desc)/', strtolower($sort))) {
            $sort = 'dateuploaded_desc';
        }

        $files = $this->objFiles->getByUser($userid, str_replace('_', ' ', $sort));

        $this->setVarByRef('userid', $userid);
        $this->setVarByRef('files', $files);
        $this->setVarByRef('sort', $sort);

        return 'byuser_tpl.php';
    }

    /**
     * Used to show a tag cloud for all tags
     */
    function __tagcloud() {
        $tagCloud = $this->objTags->getCompleteTagCloud();
        $this->setVarByRef('tagCloud', $tagCloud);

        return 'tagcloud_tpl.php';
    }

    /**
     * Ajax method to return statistics from another period/source
     */
    function __ajaxgetstats() {
        $period = $this->getParam('period');
        $type = $this->getParam('type');

        switch ($type) {
            case 'downloads':
                $objSource = $this->getObject('dbpodcasterdownloadcounter');
                break;
            case 'tags':
                $objSource = $this->getObject('dbpodcastertagviewcounter');
                break;
            case 'uploads':
                $objSource = $this->getObject('dbpodcasteruploadscounter');
                break;
            default:
                $objSource = $this->getObject('dbpodcasterviewcounter');
                break;
        }

        echo $objSource->getAjaxData($period);
    }

    /**
     * Used to show interface to upload a presentation
     *
     */
    function __upload() {
        $createcheck = $this->getParam('createcheck');
        $folder = $this->getParam('folder');
        $folderid = $this->getParam('folderid');
        $folderdata = $this->folderPermissions->getById($folderid);
        $folderdata = $folderdata[0];
        $this->setVarByRef('createcheck', $createcheck);
        $this->setVarByRef('folderdata', $folderdata);
        return 'testupload_tpl.php';
    }

    /**
     * Used to show a temporary iframe
     * (it is hidden, and thus does nothing)
     *
     */
    function __tempiframe() {
        echo '<pre>';
        print_r($_GET);
    }

    /**
     * Used to show upload errors
     *
     */
    function __erroriframe() {
        $this->setVar('pageSuppressToolbar', TRUE);
        $this->setVar('pageSuppressBanner', TRUE);
        $this->setVar('suppressFooter', TRUE);

        $id = $this->getParam('id');
        $this->setVarByRef('id', $id);

        $message = $this->getParam('message');
        $this->setVarByRef('message', $message);

        return 'erroriframe_tpl.php';
    }

    /**
     * Used to show upload results if the upload was successful
     *
     */
    function __uploadiframe() {
        $this->setVar('pageSuppressToolbar', TRUE);
        $this->setVar('pageSuppressBanner', TRUE);
        $this->setVar('suppressFooter', TRUE);

        $id = $this->getParam('id');
        $this->setVarByRef('id', $id);

        return 'uploadiframe.php';
    }

    /**
     * Ajax Process to display form for user to add presentation info
     *
     */
    function __ajaxprocess() {
        $this->setPageTemplate(NULL);

        $id = $this->getParam('id');

        $file = $this->objFiles->getFile($id);

        if ($file == FALSE) {
            return $this->nextAction('home', array('error' => 'norecord'));
        }

        // Set Filename as title in this process
        // Based on the filename, it might make it easier for users to complete the name
        $file['title'] = $file['filename'];

        $tags = $this->objTags->getTags($id);

        $this->setVarByRef('file', $file);
        $this->setVarByRef('tags', $tags);

        $this->setVar('mode', 'add');

        return 'process_tpl.php';
    }

    /**
     * Method to display the error messages/problems in the user registration
     * @param string $problem Problem Code
     * @return string Explanation of Problem
     */
    protected function explainProblemsInfo($problem) {
        switch ($problem) {
            case 'emptytitle':
                return 'Title of Presentation Required';
        }
    }

    /**
     * function that loads the edit podcast details form
     *
     * @return form
     */
    public function __describepodcast() {
        $fileid = $this->getParam("fileid", "");
        $filedata = $this->objMediaFileData->getFileByFileId($fileid);
        $this->setVarByRef("filedata", $filedata);
        return "tpl_addeditpodcast.php";
    }
    /**
     * function that saves the podcast details form
     *
     * @return form
     */
    public function __savedescribepodcast() {
        $id = $this->getParam("id", "");
        $fileid = $this->getParam("fileid", "");
        $podtitle = $this->getParam("podtitle", "");
        $cclicense = $this->getParam("creativecommons", "");
        $artist = $this->getParam("artist", "");
        $description = $this->getParam("description", "");
        $filedata = $this->objMediaFileData->updateFileDetails($id, $podtitle, $description, $cclicense, $artist);
        $this->setVarByRef("filedata", $filedata);
        return $this->nextAction('describepodcast', array('fileid' => $fileid));
    }
    /**
     * Used to do the actual upload
     *
     */
    function __doajaxupload() {

        $generatedid = $this->getParam('id');
        $filename = $this->getParam('filename');
        $pathid = $this->getParam('pathid');
        $folderdata = $this->folderPermissions->getById($pathid);
        $folderdata = $folderdata[0];
        $id = $this->objFiles->autoCreateTitle();
        $path = $folderdata['folderpath'];
        $destinationDir = $this->baseDir . "/" . $this->userId . "/" . $path . "/";
        $destinationDir = str_replace("//", "/", $destinationDir);

        $fullPath = "/" . $this->userId . "/" . $path . "/";
        $fullPath = str_replace("//", "/", $fullPath);

        @chmod($destinationDir, 0777);

        $objUpload = $this->newObject('upload', 'files');
        $objUpload->permittedTypes = array('mp3');
        $objUpload->overWrite = TRUE;
        $objUpload->setUploadFolder($destinationDir . '/');

        $result = $objUpload->doUpload(TRUE);

        if ($result['success'] == FALSE) {

            $filename = isset($_FILES['fileupload']['name']) ? $_FILES['fileupload']['name'] : '';

            return $this->nextAction('erroriframe', array('message' => $result['message'], 'file' => $filename, 'pathid' => $pathid));
        } else {

            //var_dump($result);

            $filename = $result['filename'];
            $mimetype = $result['mimetype'];

            $path_parts = $result['storedname'];

            $ext = $path_parts['extension'];


            $file = $destinationDir . $filename;

            if ($ext == '1') {
                $rename = $destinationDir . $filename;

                rename($file, $rename);

                //$filename = $path_parts['filename'] . '.mp3';
            }

            if (is_file($file)) {
                @chmod($file, 0777);
            }
            // Check if File Exists
            if (file_exists($file)) {
                // Get Media Info
                $fileInfo = $this->objAnalyzeMediaFile->analyzeFile($file);

                // Add Information to Databse
                $this->objMediaFileData->addMediaFileInfo($id, $fileInfo[0], $filename, $folderdata['id']);
            }

            //$this->objFiles->updateReadyForConversion($id, $filename, $mimetype);

            $uploadedFiles = $this->getSession('uploadedfiles', array());
            $uploadedFiles[] = $id;
            $this->setSession('uploadedfiles', $uploadedFiles);

            return $this->nextAction('ajaxuploadresults', array('id' => $generatedid, 'fileid' => $id, 'filename' => $filename, 'pathid' => $pathid));
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

        $pathid = $this->getParam('pathid');
        $this->setVarByRef('pathid', $pathid);

        return 'ajaxuploadresults_tpl.php';
    }

    /**
     * Used to Start the Conversions of Files
     *
     * This method is called using an Ajax process and is then
     * run as a background process, so that it continues, even
     * if the user closes the browser, or moves away.
     */
    function __ajaxprocessconversions() {
        $objBackground = $this->newObject('background', 'utilities');

        //check the users connection status,
        //only needs to be done once, then it becomes internal
        $status = $objBackground->isUserConn();

        //keep the user connection alive, even if browser is closed!
        $callback = $objBackground->keepAlive();

        $result = $this->objFiles->convertFiles();

        $call2 = $objBackground->setCallback("john.doe@tohir.co.za", "Your Script", "The really long running process that you requested is complete!");

        echo $result;
    }

    /**
     * Used to delete a presentation
     * Check: Users can only upload their own presentations
     */
    function __delete() {
        $id = $this->getParam('id');

        $file = $this->objFiles->getFile($id);

        if ($file == FALSE) {
            return $this->nextAction('home', array('error' => 'norecord'));
        }

        if ($file['creatorid'] != $this->objUser->userId()) {
            return $this->nextAction('view', array('id' => $id, 'error' => 'cannotdeleteslidesofothers'));
        }

        return $this->_deleteslide($file);
    }

    /**
     * Used when an administrator deletes the file of another person
     */
    function __admindelete() {
        $id = $this->getParam('id');

        $file = $this->objFiles->getFile($id);

        if ($file == FALSE) {
            return $this->nextAction('home', array('error' => 'norecord'));
        }

        return $this->_deleteslide($file);
    }

    /**
     * Used to display the delete form interface
     * This method is called once it is verified the user can delete the presentation
     *
     * @access private
     */
    private function _deleteslide($file) {
        $this->setVarByRef('file', $file);

        $randNum = rand(0, 500000);
        $this->setSession('delete_' . $file['id'], $randNum);

        $this->setVar('randNum', $randNum);

        $mode = $this->getParam('mode', 'window');
        $this->setVarByRef('mode', $mode);

        if ($mode == 'submodal') {
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('suppressFooter', TRUE);
        }


        return 'delete_tpl.php';
    }

    /**
     * Used to delete a presentation if user confirms delete
     *
     */
    private function __deleteconfirm() {
        // Get Id
        $id = $this->getParam('id');

        // Get Value
        $deletevalue = $this->getParam('deletevalue');

        // Get File
        $file = $this->objFiles->getFile($id);

        // Check that File Exists
        if ($file == FALSE) {
            return $this->nextAction('home', array('error' => 'norecord'));
        }

        // Check that user is owner of file, or is admin -> then delete
        if ($file['creatorid'] == $this->objUser->userId() || $this->isValid('admindelete')) {
            if ($deletevalue == $this->getSession('delete_' . $id) && $this->getParam('confirm') == 'yes') {
                $this->objFiles->deleteFile($id);
                $this->objSearch->removeIndex('podcaster_' . $id);
                return $this->nextAction(NULL);
            } else {
                return $this->nextAction('view', array('id' => $id, 'message' => 'deletecancelled'));
            }

            // Else User cannot delete files of others
        } else {
            return $this->nextAction('view', array('id' => $id, 'error' => 'cannotdeleteslidesofothers'));
        }
    }

    /**
     * Used to display the latest presentations RSS Feed
     *
     */
    function __latestrssfeed() {
        $objViewer = $this->getObject('viewer');
        echo $objViewer->getLatestFeed();
    }

    /**
     * Used to show a RSS Feed of presentations matching a tag
     *
     */
    function __tagrss() {
        $tag = $this->getParam('tag');
        $objViewer = $this->getObject('viewer');
        echo $objViewer->getTagFeed($tag);
    }

    /**
     * Used to display the latest presentations of a user RSS Feed
     *
     */
    public function __userrss() {
        $userid = $this->getParam('userid');
        $objViewer = $this->getObject('viewer');
        echo $objViewer->getUserFeed($userid);
    }

    /**
     * Used to rebuild the search index
     */
    public function __rebuildsearch() {
        $files = $this->objFiles->getAll();

        //$objBackground = $this->newObject('background', 'utilities');
        //check the users connection status,
        //only needs to be done once, then it becomes internal
        //$status = $objBackground->isUserConn();
        //keep the user connection alive, even if browser is closed!
        //$callback = $objBackground->keepAlive();

        if (count($files) > 0) {
            $file = $files[0];
            foreach ($files as $file) {
                $tags = $this->objTags->getTagsAsArray($file['id']);
                $slides = $this->objSlides->getSlides($file['id']);

                $file['tags'] = $tags;
                $file['slides'] = $slides;

                $this->_prepareDataForSearch($file);
            }
        }

        //$call2 = $objBackground->setCallback("tohir@tohir.co.za","Search rebuild", "The really long running process that you requested is complete!");
    }

    /**
     * Used to take file information and make as much of that information available
     * for search purposes
     *
     * @param array $file File Information
     */
    private function _prepareDataForSearch($file) {
        $content = $file['filename'];

        $content .= ( $file['description'] == '') ? '' : ', ' . $file['description'];
        $content .= ( $file['title'] == '') ? '' : ', ' . $file['title'];

        $tagcontent = ' ';

        if (count($file['tags']) > 0) {
            $divider = '';
            foreach ($file['tags'] as $tag) {
                $tagcontent .= $divider . $tag;
                $divider = ', ';
            }

            $content .= $tagcontent;
        }

        $file['tags'] = $tagcontent;


        $content .= ', ';

        $divider = '';
        foreach ($file['slides'] as $slide) {
            if (preg_match('/slide \d+/', $slide['slidetitle'])) {
                $content .= $divider . $slide['slidetitle'];
                $divider = ', ';
            }

            if ($slide['slidecontent'] != '<h1></h1>') {
                $content .= $divider . strip_tags($slide['slidecontent']);
                $divider = ',';
            }
        }

        $file['numslides'] = count($file['slides']);

        $file['content'] = $content;

        $this->_luceneIndex($file);
    }

    /**
     * Used to add a file to the search index
     *
     * @param array $file File Information
     */
    private function _luceneIndex($file) {


        $docId = 'podcaster_' . $file['id'];
        $docDate = $file['dateuploaded'];
        $url = $this->uri(array('action' => 'view', 'id' => $file['id']));
        $title = $file['title'];
        $contents = $file['content'];
        $teaser = $file['description'];
        $module = 'podcaster';
        $userId = $file['creatorid'];
        $tags = $file['tags'];
        $license = $file['cclicense'];
        $context = 'nocontext';
        $workgroup = 'noworkgroup';
        $permissions = NULL;
        $dateAvailable = NULL;
        $dateUnavailable = NULL;
        $extra = array('numslides' => $file['numslides'], 'filename' => $file['filename'], 'filetype' => $file['filetype'], 'mimetype' => $file['mimetype']);

        $this->objSearch->luceneIndex($docId, $docDate, $url, $title, $contents, $teaser, $module, $userId, $tags, $license, $context, $workgroup, $permissions, $dateAvailable, $dateUnavailable, $extra);
    }

    /**
     * Method to regenerate the Flash or PDF version of a file
     */
    public function __regenerate() {
        $id = $this->getParam('id');
        $type = $this->getParam('type');

        $result = $this->objFiles->regenerateFile($id, $type);

        return $this->nextAction('view', array('id' => $id, 'message' => 'regeneration', 'type' => $type, 'result' => $result));
    }

    /**
     * Method to listall Presentations
     * Used for testing purposes
     * @access private
     */
    private function __listall() {
        $results = $this->objFiles->getAll(' ORDER BY dateuploaded DESC');

        if (count($results) > 0) {
            $this->loadClass('link', 'htmlelements');

            echo '<ol>';

            foreach ($results as $file) {
                echo '<li>' . $file['title'];

                $link = new link($this->uri(array('action' => 'regenerate', 'type' => 'flash', 'id' => $file['id'])));
                $link->link = 'Flash';

                echo ' - ' . $link->show();

                $link = new link($this->uri(array('action' => 'regenerate', 'type' => 'flash', 'id' => $file['id'])));
                $link->link = 'Slides';

                echo ' / ' . $link->show() . '<br />&nbsp;</li>';
            }

            echo '</ol>';
        }
    }

    /**
     * Batch script to convert presentations to version 2
     */
    private function __converttov2() {
        $results = $this->objFiles->getAll(' ORDER BY dateuploaded DESC');

        if (count($results) > 0) {


            foreach ($results as $file) {
                log_debug($file['id'] . ' - ' . $file['title']);

                echo '<hr />' . $file['title'];

                $ok = $this->objFiles->checkpodcasterVersion2($file['id']);


                var_dump($ok);
            }
        }
    }

}

?>