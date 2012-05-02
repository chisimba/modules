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
         $this->objCleanUrl=  $this->getObject("cleanurl", "filemanager");
        $this->loadClass("htmlheading", "htmlelements");
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
            $uploadFolder = '/digitallibrary';
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

        if ($mode == 'selectfilewindow' || $mode == 'selectimagewindow' || $mode == 'fckimage' || $mode == 'fckflash' || $mode == 'fcklink') {
            if (count($restrictions) == 0) {
                $header->str .= ' (<a href="javascript:selectFile();">' . $this->objLanguage->languageText('mod_filemanager_selectfile', 'filemanager', 'Select File') . '</a>) ';
            } else if (in_array(strtolower($file['datatype']), $restrictions)) {
                $header->str .= ' (<a href="javascript:selectFile();">' . $this->objLanguage->languageText('mod_filemanager_selectfile', 'filemanager', 'Select File') . '</a>) ';
            }

            if ($mode == 'fckimage' || $mode == 'fckflash') {
                if (isset($file['width']) && isset($file['height'])) {
                    $widthHeight = ', ' . $file['width'] . ', ' . $file['height'];
                } else {
                    $widthHeight = '';
                }
            } else {
                $widthHeight = '';
            }

            if ($mode == 'fckimage' || $mode == 'fckflash' || $mode == 'fcklink') {

                $checkOpenerScript = '
        <script type="text/javascript">
        //<![CDATA[
        ' . $selectParam . '

        function selectFile()
        {
            if (window.opener) {
                try
                 {
                     window.opener.CKEDITOR.tools.callFunction(1, "' . htmlspecialchars_decode($this->uri(array('action' => 'file', 'id' => $file['id'], 'filename' => $file['filename'], 'type' => '.' . $file['datatype']), 'filemanager', '', TRUE, FALSE, TRUE)) . '"' . $widthHeight . ') ;
            
                 }
                catch(err)
                {
                     window.opener.CKEDITOR.tools.callFunction(2, "' . htmlspecialchars_decode($this->uri(array('action' => 'file', 'id' => $file['id'], 'filename' => $file['filename'], 'type' => '.' . $file['datatype']), 'filemanager', '', TRUE, FALSE, TRUE)) . '"' . $widthHeight . ') ;
             
                }

                 window.top.close() ;
                 window.top.opener.focus() ;
            }
        }
        //]]>
        </script>
                ';

                $this->appendArrayVar('headerParams', $checkOpenerScript);
            } else if ($mode == 'selectfilewindow') {
                $checkOpenerScript = '
        <script type="text/javascript">
        function selectFile()
        {
            if (window.opener) {
                
                //alert(fileName[id]);
                window.opener.document.getElementById("input_selectfile_' . $this->getParam('name') . '").value = "' . htmlspecialchars_decode($file['filename']) . '";
                window.opener.document.getElementById("hidden_' . $this->getParam('name') . '").value = "' . htmlspecialchars_decode($file['id']) . '";
            
                window.close();
                window.opener.focus();
            } else {
                window.parent.document.getElementById("input_selectfile_' . $this->getParam('name') . '").value = "' . htmlspecialchars_decode($file['filename']) . '";
                window.parent.document.getElementById("hidden_' . $this->getParam('name') . '").value = "' . htmlspecialchars_decode($file['id']) . '";
                window.parent.hidePopWin();
            }
        }
        </script>
                ';

                $this->appendArrayVar('headerParams', $checkOpenerScript);
            } else if ($mode == 'selectimagewindow') {

                $objThumbnails = $this->getObject('thumbnails');

                $checkOpenerScript = '
        <script type="text/javascript">
        function selectFile()
        {
            if (window.opener) {
                window.opener.document.getElementById("imagepreview_' . $this->getParam('name') . '").src = "' . $objThumbnails->getThumbnail($file['id'], $file['filename'], $file['path']) . '";
                window.opener.document.getElementById("hidden_' . $this->getParam('name') . '").value = "' . htmlspecialchars_decode($file['id']) . '";
                window.close();
                window.opener.focus();
            } else {
                window.parent.document.getElementById("selectfile_' . $this->getParam('name') . '").value = "' . htmlspecialchars_decode($file['filename']) . '";
                window.parent.document.getElementById("hidden_' . $this->getParam('name') . '").value = "' . htmlspecialchars_decode($file['id']) . '";
                window.parent.hidePopWin();
            }
        }
        </script>
                ';

                $this->appendArrayVar('headerParams', $checkOpenerScript);
            }
        }

        if ($folderPermission) {
            $header->str .= $editLink->show();
        }

        $content.= $header->show();


        $content.= '<br /><p><strong>' . $this->objLanguage->languageText('word_description', 'system', 'Description') . ':</strong> <em>' . $file['filedescription'] . '</em></p>';
        $content.= '<p><strong>' . $this->objLanguage->languageText('word_tags', 'system', 'Tags') . ':</strong> ';

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

        if ($preview != '') {

            if ($file['category'] == 'images') {

                $preview = '<div id="filemanagerimagepreview">' . $preview . '</div>';
            }

            $objWashout = $this->getObject('washout', 'utilities');

            $preview = $objWashout->parseText($embedValue);

            $previewContent = '<h2>' . $this->objLanguage->languageText('mod_filemanager_filepreview', 'filemanager', 'File Preview') . '</h2>' . $preview;


            //$tabContent->addTab($this->objLanguage->languageText('mod_filemanager_filepreview', 'filemanager', 'File Preview'), $previewContent);
            $tabContent->addTab(array('name' => $this->objLanguage->languageText('mod_filemanager_filepreview', 'filemanager', 'File Preview'), 'content' => $previewContent));

            //$tabContent->addTab($this->objLanguage->languageText('mod_filemanager_embedcode', 'filemanager', 'Embed Code'), $embedCode);
            $tabContent->addTab(array('name' => $this->objLanguage->languageText('mod_filemanager_embedcode', 'filemanager', 'Embed Code'), 'content' => $embedCode));
        }

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
