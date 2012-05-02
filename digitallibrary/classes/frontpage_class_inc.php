<?php

/*
 * 
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
 * 
 * This class contains functions that construct most of the GUI forms for the 
 * digitallibrary
 *
 * @author davidwaf
 */
class frontpage extends object {

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');

        $this->loadClass('form', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('hiddeninput', 'htmlelements');
        $this->loadClass('fieldset', 'htmlelements');
        $this->loadClass('link', 'htmlelements');

        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objUser = $this->getObject("user", "security");
        $this->objFileIcons = $this->getObject('fileicons', 'files');
    }

    /**
     * this creates the front page of the digital library. 
     */
    public function createDigitalFrontPage() {
        // Createform, add fields to it and display.
        $formData = new form('searchForm', $this->uri(array("action" => "search")));
        // Table for the buttons
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "search form";


        $table->startRow();
        $textinput = new textinput('searchfield');
        $textinput->size = 60;

        $buttonTitle = $this->objLanguage->languageText('mod_digitallibrary_search', 'digitallibrary');
        $button = new button('searchfile', $buttonTitle);
        $button->setToSubmit();

        $table->addCell($textinput->show() . '&nbsp;' . $button->show());

        $table->endRow();


        $digitalDB = $this->getObject("dbdigitallibrary", "digitallibrary");
        $files = $digitalDB->getLatest(5);
        $latestFilesTable = $this->getObject("htmltable", "htmlelements");

        $latestFilesTable->startHeaderRow();
        $latestFilesTable->addHeaderCell($this->objLanguage->languageText('mod_digitallibrary_name', 'digitallibrary'));
        $latestFilesTable->addHeaderCell($this->objLanguage->languageText('mod_digitallibrary_date', 'digitallibrary'));
        $latestFilesTable->addHeaderCell($this->objLanguage->languageText('mod_digitallibrary_author', 'digitallibrary'));

        $latestFilesTable->endHeaderRow();

        foreach ($files as $file) {

            $link = new link($this->uri(array("action" => 'viewfile')));
            $link->link = $file['filename'];
            $latestFilesTable->startRow();
            $latestFilesTable->addCell($this->objFileIcons->getFileIcon($file['filename']) . '&nbsp;' . $link->show());
            $latestFilesTable->addCell($file['datecreated']);
            $latestFilesTable->addCell($this->objUser->fullname($file['creatorid']));
            $latestFilesTable->endRow();
        }

        $title = $this->objLanguage->languageText('mod_digitallibrary_latestfiles', 'digitallibrary');

        $htmlheading = new htmlheading();
        $htmlheading->cssClass = "dl_h1";
        $htmlheading->type = 1;
        $htmlheading->str = $title;

        $formData->addToForm($table->show());
        $formData->addToForm($htmlheading->show() . $latestFilesTable->show());


        $uploadForm = $this->getObject("digitallibraryupload");

        $uploadForm->formaction = $this->uri(array('action' => 'upload'), "digitallibrary");


        $uploadTitle = '<h3>' . $this->objLanguage->languageText('phrase_uploadfiles', 'system', 'Upload Files') . '</h3>';
        return $formData->show() . $uploadTitle . $uploadForm->show();
    }

    /**
     *builds the tag cloud for files uploaded to digital library
     * @return type 
     */
    function getTagCloud() {
        $dbFileTags = $this->getObject("dbfiletags", "filemanager");
        $objTagCloud = $this->getObject("tagcloud", "utilities");
        $tags = $dbFileTags->getAllTagCloudResults(" AND tbl_files.path like '/digitallibrary/%'");
        // $this->tags->addElement($tags['name'], $tags['url'], $tags['weight'], $tags['time']);
        $tagCloud = '<p><strong>' . $this->objLanguage->languageText('word_tags', 'system', 'Tags') . ':</strong> ';

        if (count($tags) == 0) {
            $tagCloud.= '<em>' . $this->objLanguage->languageText('phrase_notags', 'system', 'no tags') . '</em>';
        } else {
            $tagsArray = array();
            foreach ($tags as $tag) {
                $tagArray = array();
                $tagLink = new link($this->uri(array('action' => 'viewbytag', 'tag' => $tag['tag']), "digitallibrary"));
                $tagLink->link = $tag['tag'];
                $tagArray['name'] = $tag['tag'];
                $tagArray['url'] = $tagLink->href;
                $tagArray['weight'] = $tag['weight'];
                $tagArray['time'] = time();
                $tagsArray[] = $tagArray;
            }
            $tagCloud = $objTagCloud->buildCloud($tagsArray);
        }


        return $tagCloud;
    }

}

?>
