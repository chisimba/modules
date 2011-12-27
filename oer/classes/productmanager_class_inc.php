<?php

/**
 * Contains util methods for managing product
 *
 * @author davidwaf
 */
class productmanager extends object {

    private $dbproduct;
    private $objLanguage;
    public $objConfig;

    function init() {
        $this->dbproduct = $this->getObject('dbproducts', 'oer');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objConfig = $this->getObject('altconfig', 'config');
    }

    /**
     * Validates the contents of new product field values. If all are valid, these
     * are save, else the form is returned with the errors highlighted
     * @return type 
     */
    function saveNewProduct() {
        $errors = array();
        $title = $this->getParam('title');
        if ($title == '') {
            $errors[] = $this->objLanguage->languageText('mod_oer_title', 'oer');
        }

        $thumbnailPath = '';
        $results = FALSE;
        $path = 'oer/products/' . $this->getParam('title') . '/thumbnail/';
        
        if ($title != '' && $this->getParam('fileupload') != '') {
            $results = $this->uploadFile($path);

            if ($results) {
                $thumbnailPath = 'usrfiles/' . $results['path'];
            }
        } else {
            $errors[] = $this->objLanguage->languageText('mod_oer_thumbnail', 'oer');
        }

        if (count($errors) > 0) {
            $this->setVar('fieldsrequired', 'true');
            $this->setVar('errors', $errors);
            $this->setVar('title', $title);
            $this->setVar('mode', "fixup");

            return "newproduct_tpl.php";
        }

        //add lucene for the search
    }

    /**
     * uploads a file 
     * @param type $path
     * @return type 
     */
    private function uploadFile($path) {

        $objMkDir = $this->getObject('mkdir', 'files');

        $destinationDir = $this->objConfig->getcontentBasePath() . '/'.$path;
        $objMkDir->mkdirs($destinationDir);

        @chmod($destinationDir, 0777);

        $uploadedFile = $this->getObject('uploadinput', 'filemanager');
        $uploadedFile->enableOverwriteIncrement = TRUE;
        $uploadedFile->customuploadpath = $path;

        $results = $uploadedFile->handleUpload($this->getParam('fileupload'));
        //Test if file was successfully uploaded
        // Technically, FALSE can never be returned, this is just a precaution
        // FALSE means there is no fileinput with that name
        if ($results == FALSE) {
            //TODO return proper error page
            throw new customException('Upload failed: FATAL');
        } else {
            if (!$results['success']) { // upload was unsuccessful
                if ($results['reason'] != 'nouploadedfileprovided') {
                    throw new customException('Upload failed: ' . $results['reason']); //TODO return proper error page containing error
                } else {
                    return FALSE;
                }
            }
        }
        return $results;
    }

}

?>
