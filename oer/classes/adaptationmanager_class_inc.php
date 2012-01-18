<?php

/**
 * Contains util methods for managing adaptations
 *
 * @author pwando
 */
class adaptationmanager extends object {

    private $dbproducts;
    private $objLanguage;
    public $objConfig;
    private $objUser;

    function init() {
        $this->dbproducts = $this->getObject('dbproducts', 'oer');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objUser = $this->getObject("user", "security");
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('fieldset', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('hiddeninput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('radio', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('fieldset', 'htmlelements');
        $this->addJS();
        $this->setupLanguageItems();
    }

    /**
     * Validates the contents of new adaptation field values in step 1. If all are valid, these
     * are save, else the form is returned with the errors highlighted
     * @return type 
     */
    function saveNewAdaptationStep1() {
        $parentid = $this->getParam("id");
        $mode = $this->getParam("mode", "edit");        
        if ($mode == "edit") {
            $data = array(
                "title" => $this->getParam("title"),
                "alternative_title" => $this->getParam("alternative_title"),
                "author" => $this->getParam("author"),
                "othercontributors" => $this->getParam("othercontributors"),
                "publisher" => $this->getParam("publisher"),
                "language" => $this->getParam("language"));
            
            $this->dbproducts->updateOriginalProduct($data, $parentid);
            return $parentid;
        } else {
            $data = array(
                "title" => $this->getParam("title"),
                "alternative_title" => $this->getParam("alternative_title"),
                "author" => $this->getParam("author"),
                "othercontributors" => $this->getParam("othercontributors"),
                "publisher" => $this->getParam("publisher"),
                "language" => $this->getParam("language"),
                "parent_id" => $parentid,
                "translation_of" => "",
                "description" => "",
                "abstract" => "",
                "oerresource" => "",
                "provenonce" => "",
                "accredited" => "",
                "accreditation_body" => "",
                "accreditation_date" => "",
                "contacts" => "",
                "relation_type" => "",
                "relation" => "",
                "coverage" => "",
                "status" => "",
            );
            $result = $this->dbproducts->saveOriginalProduct($data);
            return $result;
        }
    }

    /**
     * updates adaptation step 1 details
     * @return type 
     */
    function updateAdaptationStep1() {
        $id = $this->getParam("id");
        $data = array(
            "title" => $this->getParam("title"),
            "alternative_title" => $this->getParam("alternative_title"),
            "author" => $this->getParam("author"),
            "othercontributors" => $this->getParam("othercontributors"),
            "publisher" => $this->getParam("publisher"),
            "language" => $this->getParam("language"),
        );

        $this->dbproducts->updateOriginalProduct($data, $id);
        return $id;
    }

    /**
     * used for deleting an adaptation
     */
    function deleteAdaptation() {
        $id = $this->getParam("id");
        $this->dbproducts->deleteOriginalProduct($id);
    }

    /**
     * Updates the adaptation's step 2 details
     * @return type 
     */
    function updateAdaptationStep2() {
        $id = $this->getParam("id");
        $data = array(
            "translation_of" => $this->getParam("translation"),
            "description" => $this->getParam("description"),
            "abstract" => $this->getParam("abstract"),
            "provenonce" => $this->getParam("provenonce"),
        );

        $this->dbproducts->updateOriginalProduct($data, $id);
        return $id;
    }

    /**
     * Updates the adaptation's step 3 details
     * @return type 
     */
    function updateAdaptationStep3() {
        $id = $this->getParam("id");
        $data = array(
            "oerresource" => $this->getParam("oerresource"),
            "accredited" => $this->getParam("accredited"),
            "accreditation_body" => $this->getparam("accreditationbody"),
            "accreditation_date" => $this->getParam("accreditationdate"),
            "contacts" => $this->getParam("contacts"),
            "relation_type" => $this->getParam("relationtype"),
            "relation" => $this->getParam("relatedproduct"),
            "coverage" => $this->getParam("coverage"),
            "status" => $this->getParam("status"),
            "rights" => $this->getParam("creativecommons")
        );

        $this->dbproducts->updateOriginalProduct($data, $id);
        return $id;
    }

    /**
     * Used fo uploading product thumbnail
     *
     */
    function uploadProductThumbnail() {
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

        $result = $objUpload->doUpload(TRUE, "thumbnail");


        if ($result['success'] == FALSE) {

            $filename = isset($_FILES['fileupload']['name']) ? $_FILES['fileupload']['name'] : '';
            $error = $this->objLanguage->languageText('mod_oer_uploaderror', 'oer');
            return array('message' => $error, 'file' => $filename, 'id' => $generatedid);
        } else {
            $data = array("thumbnail" => "/oer/products/" . $productid . "/thumbnail.png");
            $this->dbproducts->updateOriginalProduct($data, $productid);
            $filename = $result['filename'];

            $params = array('action' => 'showproductthumbnailuploadresults', 'id' => $generatedid, 'fileid' => $id, 'filename' => $filename);

            return $params;
        }
    }

    /**
     * adds essential js
     */
    function addJS() {
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/validate/jquery.validate.min.js', 'jquery'));
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('originalproduct.js', 'oer'));
    }

    /**
     * sets up necessary lang items for use in js
     */
    function setupLanguageItems() {
        // Serialize language items to Javascript
        $arrayVars['status_success'] = "mod_oer_status_success";
        $arrayVars['status_fail'] = "mod_oer_status_fail";
        $arrayVars['confirm_delete_original_product'] = "mod_oer_confirm_delete_original_product";
        $arrayVars['loading'] = "mod_oer_loading";
        $objSerialize = $this->getObject('serializevars', 'utilities');
        $objSerialize->languagetojs($arrayVars, 'oer');
    }

    /**
     * this constructs the  form for managing an adaptation
     * @return type FORM
     */
    public function buildAdaptationFormStep1($id, $mode) {


        $objTable = $this->getObject('htmltable', 'htmlelements');

        if ($id != null) {
            $product = $this->dbproducts->getProduct($id);
            $hidId = new hiddeninput('id');
            $hidId->cssId = "id";
            $hidId->value = $id;
            $hidMode = new hiddeninput('mode');
            $hidMode->cssId = "mode";
            $hidMode->value = $mode;
            $objTable->startRow();
            $objTable->addCell($hidId->show().$hidMode->show());
            $objTable->endRow();
        }
        //the title
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_title', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textinput = new textinput('title');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        if ($product != null) {
            $textinput->value = $product['title'];
        }
        $objTable->addCell($textinput->show());
        $objTable->endRow();


        //alternative title
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_alttitle', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textinput = new textinput('alternative_title');
        $textinput->size = 60;
        if ($product != null) {
            $textinput->value = $product['alternative_title'];
        }
        $objTable->addCell($textinput->show());
        $objTable->endRow();



        //author
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_author', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textinput = new textinput('author');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        if ($product != null) {
            $textinput->value = $product['author'];
        }
        $objTable->addCell($textinput->show());
        $objTable->endRow();



        //other contributors
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_othercontributors', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textarea = new textarea('othercontributors', '', 5, 55);
        $textarea->cssClass = 'required';
        if ($product != null) {
            $textarea->value = $product['othercontributors'];
        }
        $objTable->addCell($textarea->show());
        $objTable->endRow();


        //publisher
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_publisher', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textinput = new textinput('publisher');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        if ($product != null) {
            $textinput->value = $product['publisher'];
        }
        $objTable->addCell($textinput->show());
        $objTable->endRow();


        //language
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_language', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $language = new dropdown('language');
        $language->cssClass = 'required';
        
        if ($product != null) {
            $language->selected = $product['language'];
        } else {
            $language->selected = "en";
        }
        $language->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $language->addOption('en', $this->objLanguage->languageText('mod_oer_english', 'oer'));
        $objTable->addCell($language->show());
        $objTable->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend($this->objLanguage->languageText('mod_oer_originalproduct_heading_new_step1', 'oer'));
        $fieldset->addContent($objTable->show());


        $action = "saveadaptationstep1";
        $formData = new form('adaptationForm1', $this->uri(array("action" => $action, "id" => $id, "mode" => $mode)));
        $formData->addToForm($fieldset);

        $button = new button('save', $this->objLanguage->languageText('word_next', 'system', 'Next'));
        $button->setToSubmit();
        $formData->addToForm('<br/>' . $button->show());


        $button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
        $uri = $this->uri(array("action" => "1b"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $formData->addToForm('&nbsp;&nbsp;' . $button->show());

        $header = new htmlheading();
        $header->type = 2;
        $header->cssClass = "original_product_title";
        $header->str = $product['title'];


        return $header->show() . $formData->show();
    }

    public function buildAdaptationFormStep2($id) {

        $objTable = $this->getObject('htmltable', 'htmlelements');

        if ($id != null) {
            //Check if adaptation has data
            $adaptation = $this->dbproducts->getProduct($id);
            if (empty($adaptation['description']) && empty($adaptation['abstract']) && empty($adaptation['provenonce'])) {
                $product = $this->dbproducts->getParentData($id);
            } else {
                $product = $adaptation;
            }

            $hidId = new hiddeninput('id');
            $hidId->cssId = "id";
            $hidId->value = $id;
            $objTable->startRow();
            $objTable->addCell($hidId->show());
            $objTable->endRow();
        }
        //translation
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_translationof', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $translation = new dropdown('translation');
        $translation->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $translation->addOption('none', $this->objLanguage->languageText('mod_oer_none', 'oer'));

        $originalProducts = $this->dbproducts->getOriginalProducts();
        foreach ($originalProducts as $originalProduct) {
            if ($originalProduct['id'] != $id) {
                $translation->addOption($originalProduct['id'], $originalProduct['title']);
            }
        }

        if ($product != null) {
            $translation->selected = $product['translation_of'];
        }
        $objTable->addCell($translation->show());
        $objTable->endRow();


        //description
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_description', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $description = $this->newObject('htmlarea', 'htmlelements');
        $description->name = 'description';
        if ($product != null) {
            $description->value = $product['description'];
        }
        $description->height = '150px';
        $description->setBasicToolBar();
        $objTable->addCell($description->show());
        $objTable->endRow();


        //abstract
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_abstract', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $abstract = $this->newObject('htmlarea', 'htmlelements');
        $abstract->name = 'abstract';
        $abstract->height = '150px';
        if ($product != null) {
            $abstract->value = $product['abstract'];
        }
        $abstract->setBasicToolBar();
        $objTable->addCell($abstract->show());
        $objTable->endRow();


        //provenonce
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_provenonce', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $provenonce = $this->newObject('htmlarea', 'htmlelements');
        $provenonce->name = 'provenonce';
        $provenonce->height = '150px';
        if ($product != null) {
            $provenonce->value = $product['provenonce'];
        }
        $provenonce->setBasicToolBar();
        $objTable->addCell($provenonce->show());
        $objTable->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend($this->objLanguage->languageText('mod_oer_originalproduct_heading_new_step2', 'oer'));
        $fieldset->addContent($objTable->show());

        $formData = new form('originalProductForm2', $this->uri(array("action" => "saveadaptationstep2")));
        $formData->addToForm($fieldset);

        $button = new button('save', $this->objLanguage->languageText('word_next', 'system', 'Next'));
        $button->setToSubmit();
        $formData->addToForm('<br/>' . $button->show());


        $button = new button('back', $this->objLanguage->languageText('word_back'));
        $uri = $this->uri(array("action" => "editadaptationstep1", "id" => $id, "mode" => "edit"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $formData->addToForm('&nbsp;&nbsp;' . $button->show());

        $button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
        $uri = $this->uri(array("action" => "1b"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $formData->addToForm('&nbsp;&nbsp;' . $button->show());

        $header = new htmlheading();
        $header->type = 2;
        $header->cssClass = "original_product_title";
        $header->str = $product['title'];


        return $header->show() . $formData->show();
    }

    /**
     * Builds the step 3 adaptation form
     * @param type $id 
     */
    public function buildAdaptationFormStep3($id) {

        $objTable = $this->getObject('htmltable', 'htmlelements');
        if ($id != null) {
            //Check if adaptation has data
            $adaptation = $this->dbproducts->getProduct($id);
            if (empty($adaptation['accreditation_date']) &&
                    empty($adaptation['contacts']) &&
                    empty($adaptation['relation_type']) &&
                    empty($adaptation['relation']) &&
                    empty($adaptation['coverage']) &&
                    empty($adaptation['status']) &&
                    empty($adaptation['rights'])) {
                $product = $this->dbproducts->getParentData($id);
            } else {
                $product = $adaptation;
            }

            $hidId = new hiddeninput('id');
            $hidId->cssId = "id";
            $hidId->value = $id;
            $objTable->startRow();
            $objTable->addCell($hidId->show());
            $objTable->endRow();
        }

        //resource type
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_oerresource', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $oerresource = new dropdown('oerresource');
        $oerresource->cssClass = 'required';
        $oerresource->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $oerresource->addOption('curriculum', $this->objLanguage->languageText('mod_oer_curriculum', 'oer'));
        if($product != null) {
            $oerresource->selected = $product['oerresource'];
        }
        $objTable->addCell($oerresource->show());
        $objTable->endRow();

        //licence
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_licence', 'oer'));
        $objTable->endRow();

        $objDisplayLicense = $this->getObject('licensechooserdropdown', 'creativecommons');

        $license = $product['rights'] == '' ? 'copyright' : $product['rights'];
        $rightCell = $objDisplayLicense->show($license);

        $objTable->startRow();
        $objTable->addCell($rightCell);
        $objTable->endRow();



        //needs accredited
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_accredited', 'oer') . '?');
        $objTable->endRow();

        $radio = new radio('accredited');
        $radio->addOption('yes', $this->objLanguage->languageText('word_yes', 'system'));
        $radio->addOption('no', $this->objLanguage->languageText('word_no', 'system'));
        if ($product != null) {
            $radio->selected = $product['accredited'];
        }
        $objTable->startRow();
        $objTable->addCell($radio->show());
        $objTable->endRow();

        //accreditationbody
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_accreditationbody', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('accreditationbody');
        $textinput->size = 60;
        if ($product != null) {
            $textinput->value = $product['accreditation_body'];
        }


        $objTable->addCell($textinput->show());
        $objTable->endRow();

        //accreditationdate
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_accreditationdate', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('accreditationdate');
        $textinput->size = 60;
        if ($product != null) {
            $textinput->value = $product['accreditation_date'];
        }

        $objTable->addCell($textinput->show());
        $objTable->endRow();

        //contacts
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_contacts', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textarea = new textarea('contacts', '', 5, 55);
        if ($product != null) {
            $textarea->value = $product['contacts'];
        }
        $objTable->addCell($textarea->show());
        $objTable->endRow();


        //relationtype
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_relationtype', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $relationtype = new dropdown('relationtype');
        $relationtype->addOption('select', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $relationtype->addOption('ispartof', $this->objLanguage->languageText('mod_oer_ispartof', 'oer'));
        $relationtype->addOption('requires', $this->objLanguage->languageText('mod_oer_requires', 'oer'));
        $relationtype->addOption('isrequiredby', $this->objLanguage->languageText('mod_oer_isrequiredby', 'oer'));
        $relationtype->addOption('haspartof', $this->objLanguage->languageText('mod_oer_haspartof', 'oer'));
        $relationtype->addOption('references', $this->objLanguage->languageText('mod_oer_references', 'oer'));
        $relationtype->addOption('isversionof', $this->objLanguage->languageText('mod_oer_isversionof', 'oer'));
        if($product != null) {            
            $relationtype->selected = $product['relation_type'];
        }
        $objTable->addCell($relationtype->show());
        $objTable->endRow();

        //relatedproduct
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_relatedproduct', 'oer'));
        $objTable->endRow();
        $objTable->startRow();
        $relatedproduct = new dropdown('relatedproduct');
        $relatedproduct->addOption('none', $this->objLanguage->languageText('mod_oer_none', 'oer'));

        $originalProducts = $this->dbproducts->getOriginalProducts();
        foreach ($originalProducts as $originalProduct) {
            if ($originalProduct['id'] != $id) {
                $relatedproduct->addOption($originalProduct['id'], $originalProduct['title']);
            }
        }
        if($product != null) {            
            $relatedproduct->selected = $product['relation'];
        }

        $objTable->addCell($relatedproduct->show());
        $objTable->endRow();


        //coverage
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_coverage', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $textarea = new textarea('coverage', '', 5, 55);
        if ($product != null) {
            $textarea->value = $product['coverage'];
        }
        $objTable->addCell($textarea->show());
        $objTable->endRow();

        //published status
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_published', 'oer'));
        $objTable->endRow();
        $objTable->startRow();
        $published = new dropdown('status');
        $published->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $published->cssClass = "required";
        $published->addOption('disabled', $this->objLanguage->languageText('mod_oer_disabled', 'oer'));
        $published->addOption('draft', $this->objLanguage->languageText('mod_oer_draft', 'oer'));
        $published->addOption('published', $this->objLanguage->languageText('mod_oer_published', 'oer'));
        if ($product != null) {
            $published->selected = $product['status'];
        }
        $objTable->addCell($published->show());
        $objTable->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend($this->objLanguage->languageText('mod_oer_originalproduct_heading_new_step3', 'oer'));
        $fieldset->addContent($objTable->show());

        $formData = new form('adaptationForm2', $this->uri(array("action" => "saveadaptationstep3")));
        $formData->addToForm($fieldset);

        $button = new button('save', $this->objLanguage->languageText('word_next', 'system', 'Next'));
        $button->setToSubmit();
        $formData->addToForm('<br/>' . $button->show());


        $button = new button('back', $this->objLanguage->languageText('word_back'));
        $uri = $this->uri(array("action" => "editadaptationstep2", "id" => $id));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $formData->addToForm('&nbsp;&nbsp;' . $button->show());

        $button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
        $uri = $this->uri(array("action" => "1b"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $formData->addToForm('&nbsp;&nbsp;' . $button->show());

        $header = new htmlheading();
        $header->type = 2;
        $header->cssClass = "original_product_title";
        $header->str = $product['title'];


        return $header->show() . $formData->show();
    }
    /**
     * creates a table and returns the list of current adaptations
     * @return type 
     */
    public function getAdaptationsListingAsGrid() {
        $originalProducts = $this->dbproducts->getAdaptedProducts();


        $controlBand.=
                '<div id="originalproducts_controlband">';

        $controlBand.='<br/>&nbsp;' . $this->objLanguage->languageText('mod_oer_viewas', 'oer') . ': ';
        $gridthumbnail = '<img src="skins/oer/images/sort-by-grid.png"/>';
        $gridlink = new link($this->uri(array("action" => "1b")));
        $gridlink->link = $gridthumbnail . '&nbsp;' . $this->objLanguage->languageText('mod_oer_grid', 'oer');
        $controlBand.=$gridlink->show();

        $listthumbnail = '&nbsp;|&nbsp;<img src="skins/oer/images/sort-by-list.png"/>';
        $listlink = new link($this->uri(array("action" => "1b")));
        $listlink->link = $listthumbnail . '&nbsp;' . $this->objLanguage->languageText('mod_oer_list', 'oer');
        $controlBand.=$listlink->show();

        $sortbydropdown = new dropdown('sortby');
        $sortbydropdown->addOption('', $this->objLanguage->languageText('mod_oer_none', 'oer'));

        $controlBand.='<br/><br/>' . $this->objLanguage->languageText('mod_oer_sortby', 'oer');
        $controlBand.=$sortbydropdown->show();

        $controlBand.= '</div> ';
        $startNewRow = TRUE;
        $count = 2;
        $table = $this->getObject('htmltable', 'htmlelements');
        $objGroups = $this->getObject('groupadminmodel', 'groupadmin');
        $groupId = $objGroups->getId("ProductCreators");
        $objGroupOps = $this->getObject("groupops", "groupadmin");
        $userId = $this->objUser->userId();

        foreach ($originalProducts as $originalProduct) {
            if ($startNewRow) {
                $startNewRow = FALSE;
                $table->startRow();
            }
            $thumbnail = '<img src="usrfiles/' . $originalProduct['thumbnail'] . '"  width="79" height="101" align="bottom"/>';
            if ($originalProduct['thumbnail'] == '') {
                $thumbnail = '<img src="skins/oer/images/documentdefault.png"  width="79" height="101" align="bottom"/>';
            }
            $link = new link($this->uri(array("action" => "vieworiginalproduct", "id" => $originalProduct['id'])));
            $link->link = $thumbnail . '<br/>';
            $product = $link->show();

            $link->link = $originalProduct['title'];
            $product.= $link->show();
            if ($objGroupOps->isGroupMember($groupId, $userId)) {
                $editImg = '<img src="skins/oer/images/icons/edit.png">';
                $deleteImg = '<img src="skins/oer/images/icons/delete.png">';
                $adaptImg = '<img src="skins/oer/images/icons/add.png">';

                $adaptLink = new link($this->uri(array("action" => "editadaptationstep1", "id" => $originalProduct['id'], "mode" => "new")));
                $adaptLink->link = $adaptImg;
                $product.="<br />" . $adaptLink->show();

                $editLink = new link($this->uri(array("action" => "editadaptationstep1", "id" => $originalProduct['id'], "mode" => "edit")));
                $editLink->link = $editImg;
                $product.="&nbsp;" . $editLink->show();

                $deleteLink = new link($this->uri(array("action" => "deleteadaptation", "id" => $originalProduct['id'])));
                $deleteLink->link = $deleteImg;
                $deleteLink->cssClass = "deleteoriginalproduct";
                $product.="&nbsp;" . $deleteLink->show();
            }

            $commentsThumbnail = '<img src="skins/oer/images/comments.png"/>';

            $languageField = new dropdown('language');
            $languageField->cssClass = 'original_product_languageField';
            $languageField->setSelected($product['language']);
            $languageField->addOption('en', $this->objLanguage->languageText('mod_oer_english', 'oer'));
            $product.='<br/><br/>' . $commentsThumbnail . '&nbsp;' . $languageField->show();

            $adaptionsCount = 0;
            $adaptationsLink = new link($this->uri(array("action" => "viewadaptions", "id" => $originalProduct['id'])));
            $adaptationsLink->link = $this->objLanguage->languageText('mod_oer_adaptationscount', 'oer');
            $product.="<br/>" . $adaptionsCount . '&nbsp;' . $adaptationsLink->show();

            //addCell($str, $width=null, $valign="top", $align=null, $class=null, $attrib=Null,$border = '0')
            $table->addCell($product, null, null, null, "view_original_product");
            if ($count > 3) {
                $table->endRow();
                $startNewRow = TRUE;
                $count = 1;
            }
            $count++;
        }
        return $controlBand . $table->show();
    }

    /**
     * creates a table and returns the list of adaptable products
     * @return type
     */
    public function getAdaptatableProductListAsGrid() {
        $originalProducts = $this->dbproducts->getOriginalProducts();


        $controlBand.=
                '<div id="originalproducts_controlband">';

        $controlBand.='<br/>&nbsp;' . $this->objLanguage->languageText('mod_oer_viewas', 'oer') . ':';
        $gridthumbnail = '<img src="skins/oer/images/sort-by-grid.png"/>';
        $gridlink = new link($this->uri(array("action" => "1b")));
        $gridlink->link = $gridthumbnail . '&nbsp;' . $this->objLanguage->languageText('mod_oer_grid', 'oer');
        $controlBand.=$gridlink->show();

        $listthumbnail = '&nbsp;|&nbsp;<img src="skins/oer/images/sort-by-list.png"/>';
        $listlink = new link($this->uri(array("action" => "1a")));
        $listlink->link = $listthumbnail . '&nbsp;' . $this->objLanguage->languageText('mod_oer_list', 'oer');
        $controlBand.=$listlink->show();

        $sortbydropdown = new dropdown('sortby');
        $sortbydropdown->addOption('', $this->objLanguage->languageText('mod_oer_none', 'oer'));

        $controlBand.='<br/><br/>' . $this->objLanguage->languageText('mod_oer_sortby', 'oer');
        $controlBand.=$sortbydropdown->show();



        $controlBand.= '</div> ';
        $startNewRow = TRUE;
        $count = 2;
        $table = $this->getObject('htmltable', 'htmlelements');
        $objGroups = $this->getObject('groupadminmodel', 'groupadmin');
        $groupId = $objGroups->getId("ProductCreators");
        $objGroupOps = $this->getObject("groupops", "groupadmin");
        $userId = $this->objUser->userId();

        foreach ($originalProducts as $originalProduct) {
            if ($startNewRow) {
                $startNewRow = FALSE;
                $table->startRow();
            }
            $thumbnail = '<img src="usrfiles/' . $originalProduct['thumbnail'] . '"  width="79" height="101" align="bottom"/>';
            if ($originalProduct['thumbnail'] == '') {
                $thumbnail = '<img src="skins/oer/images/documentdefault.png"  width="79" height="101" align="bottom"/>';
            }
            $link = new link($this->uri(array("action" => "vieworiginalproduct", "id" => $originalProduct['id'])));
            $link->link = $thumbnail . '<br/>';
            $product = $link->show();

            $link->link = $originalProduct['title'];
            $product.= $link->show();
            if ($objGroupOps->isGroupMember($groupId, $userId)) {
                $editImg = '<img src="skins/oer/images/icons/edit.png">';
                $deleteImg = '<img src="skins/oer/images/icons/delete.png">';

                $editLink = new link($this->uri(array("action" => "editadaptationstep1", "id" => $originalProduct['id'])));
                $editLink->link = $editImg;
                $product.=$editLink->show();

                $deleteLink = new link($this->uri(array("action" => "deleteadaptation", "id" => $originalProduct['id'])));
                $deleteLink->link = $deleteImg;
                $deleteLink->cssClass = "deleteadaptation";
                $product.=$deleteLink->show();
            }

            $commentsThumbnail = '<img src="skins/oer/images/comments.png"/>';

            $languageField = new dropdown('language');
            $languageField->cssClass = 'original_product_languageField';
            $languageField->setSelected($product['language']);
            $languageField->addOption('en', $this->objLanguage->languageText('mod_oer_english', 'oer'));
            $product.='<br/><br/>' . $commentsThumbnail . '&nbsp;' . $languageField->show();

            $adaptionsCount = 0;
            $adaptationsLink = new link($this->uri(array("action" => "viewadaptions", "id" => $originalProduct['id'])));
            $adaptationsLink->link = $this->objLanguage->languageText('mod_oer_adaptationscount', 'oer');
            $product.="<br/>" . $adaptionsCount . '&nbsp;' . $adaptationsLink->show();

            //addCell($str, $width=null, $valign="top", $align=null, $class=null, $attrib=Null,$border = '0')
            $table->addCell($product, null, null, null, "view_original_product");
            if ($count > 3) {
                $table->endRow();
                $startNewRow = TRUE;
                $count = 1;
            }
            $count++;
        }
        return $controlBand . $table->show();
    }

    /**
     * Creates side navigation links for moving in between forms when managing
     * an adaptation
     */
    function buildAdaptationStepsNav($id) {

        $header = new htmlheading();
        $header->type = 2;
        $header->cssClass = "build_product_steps_nav";
        $header->str = $this->objLanguage->languageText('mod_oer_jumpto', 'oer');

        $content = $header->show();

        $content.='<ul id="nav-secondary">';

        $link = new link($this->uri(array("action" => "editoriginalproductstep1", "id" => $id)));
        $link->link = $this->objLanguage->languageText('mod_oer_step1', 'oer');
        $content.='<li>' . $link->show() . '</li>';


        $link = new link($this->uri(array("action" => "editoriginalproductstep2", "id" => $id)));
        $link->link = $this->objLanguage->languageText('mod_oer_step2', 'oer');
        $content.='<li>' . $link->show() . '</li>';


        $link = new link($this->uri(array("action" => "editoriginalproductstep3", "id" => $id)));
        $link->link = $this->objLanguage->languageText('mod_oer_step3', 'oer');
        $content.='<li>' . $link->show() . '</li>';

        $link = new link($this->uri(array("action" => "editoriginalproductstep4", "id" => $id)));
        $link->link = $this->objLanguage->languageText('mod_oer_step4', 'oer');
        $content.='<li>' . $link->show() . '</li>';


        $content.="</ul>";


        return $content;
    }

}

?>
