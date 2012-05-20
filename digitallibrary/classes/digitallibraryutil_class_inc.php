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
        $this->objFileTags = $this->getObject('dbfiletags', 'filemanager');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objCleanUrl = $this->getObject("cleanurl", "filemanager");
        $this->loadClass("htmlheading", "htmlelements");
    }

    /**
     * Method to upload files to the server
     *
     * @access private
     */
    public function upload() {
        $folder = $this->objFolders->getFolder($this->getParam('folder'));
        $keywords = $this->getParam("tagsfield");
        if ($keywords == null) {
            return "ERROR:No tags provided";
        }
        if ($folder != FALSE) {
            $this->objUpload->setUploadFolder($folder['folderpath']);
        } else {
            $uploadFolder = '/digitallibrary';
            $this->objUpload->setUploadFolder($uploadFolder);
        }
        $this->objUpload->enableOverwriteIncrement = TRUE;

        // Upload Files
        $results = $this->objUpload->uploadFiles();

        // Check if User entered page by typing in URL
        if ($results == FALSE) {
            return "Error: Invalid upload action";
        }

        // Check if no files were provided
        if (count($results) == 1 && array_key_exists('nofileprovided', $results)) {
            return "ERROR: No file provided";
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
        return $folder['id']; //  $this->nextAction('home', array("folder" => $folder['id']));
    }

    /**
     * this is for viewing files that match the selected tag
     * @return type 
     */
    public function showFilesWithTag($tag) {

        if (trim($tag) == '') {

            return 'Empty tag supplied';
        }



        $files = $this->objFileTags->getFilesWithTagByFilter(" AND tbl_files.path like '/digitallibrary/%' AND tbl_files_filetags.tag='$tag' ");

        if (count($files) == 0) {
            return "No files with selected tag";
        }

        $objPreviewFolder = $this->getObject('previewfolder', "filemanager");
        $objPreviewFolder->targetModule = "digitallibrary";

        $table = $objPreviewFolder->previewContent(array(), $files, "", "dl");


        $this->appendArrayVar('headerParams', $this->getJavascriptFile('selectall.js', 'htmlelements'));

        $content = "";
        $content.= '<h1>' . $this->objLanguage->languageText('mod_filemanager_fileswithtag', 'filemanager', 'Files with tag') . ': ' . $tag . '</h1>';

        if (count($files) > 0) {
            $form = new form('deletefiles', $this->uri(array('action' => 'multidelete')));
            $form->addToForm($table);

            $button = new button('submitform', $this->objLanguage->languageText('mod_filemanager_deleteselecteditems', 'filemanager', 'Delete Selected Items'));
            $button->setToSubmit();

            $selectallbutton = new button('selectall', $this->objLanguage->languageText('phrase_selectall', 'system', 'Select All'));
            $selectallbutton->setOnClick("javascript:SetAllCheckBoxes('deletefiles', 'files[]', true);");

            $deselectallbutton = new button('deselectall', $this->objLanguage->languageText('phrase_deselectall', 'system', 'Deselect all'));
            $deselectallbutton->setOnClick("javascript:SetAllCheckBoxes('deletefiles', 'files[]', false);");

            $form->addToForm($button->show() . ' &nbsp; &nbsp; ' . $selectallbutton->show() . ' ' . $deselectallbutton->show());

            $content.= $form->show();
        } else {
            $content.= $table;
        }


        return $content;
    }

    /**
     * get files that match the filter
     * @return type 
     */
    public function showFilesMatchingFilter($searchQuery) {

        if (trim($searchQuery) == '') {

            return 'Empty search querry supplied';
        }



        $files = $this->objFiles->getMatchingFiles(" WHERE filename like '%$searchQuery%' or description like '%$searchQuery%'");

        if (count($files) == 0) {
            return "No files found";
        }

        $objPreviewFolder = $this->getObject('previewfolder', "filemanager");
        $objPreviewFolder->targetModule = "digitallibrary";

        $table = $objPreviewFolder->previewContent(array(), $files, "", "dl");


        $this->appendArrayVar('headerParams', $this->getJavascriptFile('selectall.js', 'htmlelements'));

        $content = "";
        $content.= '<h1>' . $this->objLanguage->languageText('mod_filemanager_fileswithtag', 'filemanager', 'Files with tag') . ': ' . $searchQuery . '</h1>';

        if (count($files) > 0) {
            $form = new form('deletefiles', $this->uri(array('action' => 'multidelete')));
            $form->addToForm($table);

            $button = new button('submitform', $this->objLanguage->languageText('mod_filemanager_deleteselecteditems', 'filemanager', 'Delete Selected Items'));
            $button->setToSubmit();

            $selectallbutton = new button('selectall', $this->objLanguage->languageText('phrase_selectall', 'system', 'Select All'));
            $selectallbutton->setOnClick("javascript:SetAllCheckBoxes('deletefiles', 'files[]', true);");

            $deselectallbutton = new button('deselectall', $this->objLanguage->languageText('phrase_deselectall', 'system', 'Deselect all'));
            $deselectallbutton->setOnClick("javascript:SetAllCheckBoxes('deletefiles', 'files[]', false);");

            $form->addToForm($button->show() . ' &nbsp; &nbsp; ' . $selectallbutton->show() . ' ' . $deselectallbutton->show());

            $content.= $form->show();
        } else {
            $content.= $table;
        }


        return $content;
    }

    function showFileInfo($fileId) {
        $file = $this->objFiles->getFile($fileId);
        $content = "";

        $objIcon = $this->newObject('geticon', 'htmlelements');
        $objFileIcons = $this->getObject('fileicons', 'files');
        $objFileIcons->size = 'large';
        $objIcon->setIcon('edit');

        $editLink = new link($this->uri(array('action' => 'editfiledetails', 'id' => $file['id'])));
        $editLink->link = $objIcon->show();

        $header = new htmlheading();
        $header->type = 1;
        $header->str = $objFileIcons->getFileIcon($file['filename']) . ' ' . str_replace('_', ' ', htmlentities($file['filename']));


        $fileDownloadPath = $this->objConfig->getcontentPath() . $file['path'];
        $fileDownloadPath = $this->objCleanUrl->cleanUpUrl($fileDownloadPath);

        $fileFolder = $file['filefolder'];

        $folderId = $this->objFolders->getFolderId($fileFolder);

        $folder = $this->objFolders->getFolder($folderId);
        if ($folder['access'] == 'private_all' || $folder['access'] == 'private_selected') {
            $fileDownloadPath = $this->uri(array("action" => "downloadsecurefile", "path" => $file['path'], "filename" => $file['filename']));
        }

        if ($file['access'] == 'private_all' || $file['access'] == 'private_selected') {
            $fileDownloadPath = $this->uri(array("action" => "downloadsecurefile", "path" => $file['path'], "filename" => $file['filename']));
        }


        $objIcon->setIcon('download');
        $link = new link($fileDownloadPath);
        $link2 = new link($fileDownloadPath);

        $link->link = $objIcon->show();
        $link2->link = $this->objLanguage->languageText('phrase_downloadfile', 'filemanager', 'Download File');
        $copyToClipBoardJS = '
    
  <script type="text/javascript">
  function copyToClipboard(text) {
   if (window.clipboardData) {
      window.clipboardData.setData("Text",text);
  }
}
         </script>
';
        $this->appendArrayVar('headerParams', $copyToClipBoardJS);
        $header->str .= ' ' . $link->show() . ' ';

        $folderParts = explode('/', $file['filefolder']);


        $folderPermission = $this->objFolders->checkPermissionUploadFolder($folderParts[0], $folderParts[1]);

        if ($folderPermission) {
            $header->str .= $editLink->show();
        }

        $content.= $header->show();


        $content.= '<br /><p><strong>' . $this->objLanguage->languageText('word_description', 'system', 'Description') . ':</strong> <em>' . $file['description'] . '</em></p>';
        $content.= '<p><strong>' . $this->objLanguage->languageText('word_tags', 'system', 'Tags') . ':</strong> ';

        $tags = $this->objFileTags->getFileTags($file['id']);
        if (count($tags) == 0) {
            $content.= '<em>' . $this->objLanguage->languageText('phrase_notags', 'system', 'no tags') . '</em>';
        } else {
            $comma = '';
            foreach ($tags as $tag) {
                $tagLink = new link($this->uri(array('action' => 'viewbytag', 'tag' => $tag)));
                $tagLink->link = $tag;

                $content.= $comma . $tagLink->show();
                $comma = ', ';
            }
        }

        $content.= '</p>';
        $tabContent = $this->newObject('tabber', 'htmlelements');
        $tabContent->width = '90%';
        $objFilePreview = $this->getObject('filepreview', 'filemanager');
        $preview = $objFilePreview->previewFile($file['id']);
        $embedCode = "";
        if ($preview != '') {

            if ($file['category'] == 'images') {

                $preview = '<div id="filemanagerimagepreview">' . $preview . '</div>';
            }

            $objWashout = $this->getObject('washout', 'utilities');
            $embedCode = '<h2>' . $this->objLanguage->languageText('mod_filemanager_embedcode', 'filemanager', 'Embed Code') . '</h2>';

            $embedCode .= '<p>' . $this->objLanguage->languageText('mod_filemanager_embedinstructions', 'filemanager', 'Copy this code and paste it into any text box to display this file.') . '</p>';

            $embedCode .= '<form name="formtocopy">

    <input name="texttocopy" readonly="readonly" style="width:70%" type="text" value="' . $embedCode . '" />';
            $embedCode .= '
    <br /><input type="button" onclick="javascript:copyToClipboard(document.formtocopy.texttocopy);" value="Copy to Clipboard" />
    </form>';
        }

        $embedValue = htmlentities('[FILEPREVIEW id="' . $file['id'] . '" comment="' . $file['filename'] . '" /]');

        $objWashout = $this->getObject("washout", "utilities");
        $embedValue = $objWashout->parseText($embedValue);

        $previewContent = '<h2>' . $this->objLanguage->languageText('mod_filemanager_filepreview', 'filemanager', 'File Preview') . '</h2>' . $preview;


        //$tabContent->addTab($this->objLanguage->languageText('mod_filemanager_filepreview', 'filemanager', 'File Preview'), $previewContent);
        $tabContent->addTab(array('name' => $this->objLanguage->languageText('mod_filemanager_filepreview', 'filemanager', 'File Preview'), 'content' => $previewContent));

        //$tabContent->addTab($this->objLanguage->languageText('mod_filemanager_embedcode', 'filemanager', 'Embed Code'), $embedCode);
        $tabContent->addTab(array('name' => $this->objLanguage->languageText('mod_filemanager_embedcode', 'filemanager', 'Embed Code'), 'content' => $embedCode));


        $fileInfo = $this->objLanguage->languageText('mod_filemanager_fileinfo', 'filemanager', 'File Information');

        $fileInfoContent = '<h2>' . $fileInfo . '</h2>' . $this->objFiles->getFileInfoTable($file['id']);



        if (array_key_exists('width', $file)) {


            $mediaInfo = $this->objLanguage->languageText('mod_filemanager_mediainfo', 'filemanager', 'Media Information');

            $fileInfoContent .= '<br /><h2>' . $mediaInfo . '</h2>' . $this->objFiles->getFileMediaInfoTable($file['id']);
        }


        $tabContent->addTab(array('name' => $fileInfo, 'content' => $fileInfoContent));
        $fileAccess = $this->getObject("folderaccess", "filemanager");
        $tabContent->addTab(array('name' => "Access", 'content' => $fileAccess->createFileAccessControlForm($file['id']) . '<br/>' . $fileAccess->createFileVisibilityForm($file['id'])));


        $content.= $tabContent->show();

        if ($file['category'] == 'archives' && $file['datatype'] == 'zip') {

            $folderParts = explode('/', $file['filefolder']);

            $form = new form('extractarchive', $this->uri(array('action' => 'extractarchive')));
            $form->addToForm($this->objLanguage->languageText('mod_filemanager_extractarchiveto', 'filemanager', 'Extract Archive to') . ': ' . $this->objFolders->getTree($folderParts[0], $folderParts[1], 'htmldropdown', $folderId));

            $button = new button('submitform', $this->objLanguage->languageText('mod_filemanager_extractfiles', 'filemanager', 'Extract Files'));
            $button->setToSubmit();

            $form->addToForm($button->show());

            $hiddeninput = new hiddeninput('file', $file['id']);
            $form->addToForm($hiddeninput->show());
            $content.= $form->show();
        }



        $content.= '<p><br />' . $link->show() . ' ' . $link2->show() . '</p>';
        return $content;
    }

}

?>
