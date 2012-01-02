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

        $data = array(
            "title" => $this->getParam("title"),
            "alternative_title" => $this->getParam("alternative_title"),
            "author" => $this->getParam("author"),
            "othercontributors" => $this->getParam("othercontributors"),
            "publisher" => $this->getParam("publisher"),
            "language" => $this->getParam("language"),
            "translation_of" => $this->getParam("translation"),
            "description" => $this->getParam("description"),
            "abstract" => $this->getParam("abstract"),
            "oerresource" => $this->getParam("oerresource"),
            "provenonce" => $this->getParam("provenonce"),
            "accredited" => $this->getParam("accredited"),
            "accreditation_body" => $this->getParam("accreditationbody"),
            "accreditation_date" => $this->getParam("accreditationdate"),
            "contacts" => $this->getParam("contacts"),
            "relation_type" => $this->getParam("relationtype"),
            "relation" => $this->getParam("relatedproduct"),
            "coverage" => $this->getParam("coverage"),
            "status" => $this->getParam("status"),
        );

        $id = $this->dbproduct->saveOriginalProduct($data);
        // Note we are not returning a template as this is an AJAX save.
        if ($id !== NULL && $id !== FALSE) {
            die($id);
        } else {
            die("ERROR_DATA_INSERT_FAIL");
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
