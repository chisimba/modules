<?php

/*

 * This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the
 *  Free Software Foundation, Inc.,
 *  59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 * 

 */

/**
 * Description of digitallibrary_class_inc
 *
 * @author davidwaf
 */
class digitallibraryutil extends object {

    function init() {
        $this->objUploadMessages = $this->getObject('uploadmessages', 'filemanager');
        $this->objFiles = $this->getObject('dbfile', 'filemanager');
        $this->objFolders = $this->getObject('dbfolder', 'filemanager');
        $this->objUpload = $this->getObject('digitallibraryupload');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->userObj = $this->getObject("user", "security");
    }

    /**
     * Method to upload files to the server
     *
     * @access private
     */
    public function upload() {
        $folder = $this->objFolders->getFolder($this->getParam('folder'));

        if ($folder != FALSE) {
            $this->objUpload->setUploadFolder($folder['folderpath']);
        } else {
            $uploadFolder ='/digitallibrary';
            $this->objUpload->setUploadFolder($uploadFolder);
        }
          $this->objUpload->enableOverwriteIncrement = TRUE;

        // Upload Files
        $results = $this->objUpload->uploadFiles();
        
        // Check if User entered page by typing in URL
        if ($results == FALSE) {
            return $this->nextAction(NULL);
        }

        // Check if no files were provided
        if (count($results) == 1 && array_key_exists('nofileprovided', $results)) {
            return $this->nextAction('uploadresults', array('error' => 'nofilesprovided'));
        }
        $overwrite = $this->objUploadMessages->processOverwriteMessages();
        $index = 0;
        $reason = "";
        foreach ($results as $row) {
            if (array_key_exists("reason", $row)) {
                $reason = $row['reason'];
                $index++;
                if ($index > 0) {
                    break;
                }
            }
        }

        if ($reason == 'needsoverwrite') {
            $messages = $this->objUploadMessages->processMessageUrl($results);
            $messages['folder'] = $this->getParam('folder');
            return $this->nextAction('uploadresults', $messages);
        }

        if ($folder != null) {

            $alertVal = null;

            if (key_exists("alerts", $folder)) {
                $alertVal = $folder['alerts'];
            }

            if ($alertVal == 'y') {
                $objContext = $this->getObject('dbcontext', 'context');
                $emailUtils = $this->getObject("emailutils", "filemanager");
                $folderParts = explode('/', $folder['folderpath']);

                if ($folderParts[0] == 'context') {
                    $contextcode = $folderParts[1];
                    $context = $objContext->getContext($contextcode);

                    $emailUtils->sendFileEmailAlert($folder['id'], $contextcode, $context['title']);
                }
            }
        }
        return $folder['id'];//  $this->nextAction('home', array("folder" => $folder['id']));
    }

}

?>
