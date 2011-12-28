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

        $author = $this->getParam('author');
        if ($author == '') {
            $errors[] = $this->objLanguage->languageText('mod_oer_author', 'oer');
        }
        $publisher = $this->getParam('publisher');
        if ($publisher == '') {
            $errors[] = $this->objLanguage->languageText('mod_oer_publisher', 'oer');
        }
       
        if (count($errors) > 0) {
            $this->setVar('fieldsrequired', 'true');
            $this->setVar('errors', $errors);
            $this->setVar('title', $title);
            $this->setVar('mode', "fixup");

            return "newproduct_tpl.php";
        } else {
            //add lucene for the search
            return "upload_tpl.php";
        }
    }

    /**
     * Used to do the actual upload
     *
     */
    function doajaxupload() {
        $dir = $this->objConfig->getcontentBasePath();

        $generatedid = $this->getParam('id');
        $filename = $this->getParam('filename');

        $objMkDir = $this->getObject('mkdir', 'files');

        $productid = $this->getParam('productid');
        $destinationDir = $dir . '/oer/products/' . $productid;

        $objMkDir->mkdirs($destinationDir);
        // @chmod($destinationDir, 0777);

        $objUpload = $this->newObject('upload', 'files');
        $objUpload->permittedTypes = array(
            'all'
        );
        $objUpload->overWrite = TRUE;
        $objUpload->uploadFolder = $destinationDir . '/';

        $result = $objUpload->doUpload(TRUE, "nametesr");


        if ($result['success'] == FALSE) {

            $filename = isset($_FILES['fileupload']['name']) ? $_FILES['fileupload']['name'] : '';
            $error = $this->objLanguage->languageText('mod_oer_uploaderror', 'oer');
            return array('message' => $error, 'file' => $filename, 'id' => $generatedid);
        } else {

            $filename = $result['filename'];

            $params = array('action' => 'ajaxuploadresults', 'id' => $generatedid, 'fileid' => $id, 'filename' => $filename);

            return $params;
        }
    }

}

?>
