<?php

/*
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
 */

/**
 * Description of product_class_inc
 *
 * @author manie
 */
class product extends object {

    ///these objects may change after refinement
    public $dummyValue;
    public $objLanguage;
    public $objDbResourceTypes;
    public $objDbProductThemes;
    public $objThumbUploader;
    public $objDbRelationTypes;
    public $objDbProductKeywords;
    public $objDbProductStatus;
    public $objDbRegions;
    //TODO move catorgorized parameters into structs. eg. <creationStruct>
    //////////   Creation / description parameters   //////////

    /*     * Title
     *
     * @var <type>
     */
    private $_title;

    /*     * Alternative Title
     *
     * @var <type>
     */
    private $_alternativetitle;

    /*     * Content Type
     *
     * @var <type>
     */
    private $_resourcetype;

    /*     * Date of Creation
     *
     * @var <type>
     */
    private $_date;

    /*     * Language
     *
     */
    private $_language;

    /*     * Translation ID
     *
     */
    private $_translationOf;

    /*     * Authors
     *
     * @var <type>
     */
    private $_creator;

    /*     * Publisher
     *
     * @var <type>
     */
    private $_publisher;

    /*     * Description
     *
     * @var <type>
     */
    private $_description;

    /*     * Description Abstract
     *
     * @var <type>
     */
    private $_remark;

    /*     * Reason for adaptation
     *
     * @var <type>
     */
    private $_abstract;
//    /**Description Table of Contents
//     *
//     * @var <type>
//     */
//    private $_tableOfContents;

    /*     * Other Contributors
     *
     * @var <type> 
     */
    private $_othercontributors;
    //////////   non-categorized parameters   //////////
    //TODO Try to Categorize these parameters

    /*     * UNESCO contacts
     *
     * @var <type>
     */
    private $_unescocontacts;

    /*     * UNESCO Themes
     *
     * @var <type>
     */
    private $_unescothemes;

    /*     * coverage
     *
     * @var <type>
     */
    private $_coverage;

    /** Accreditation Data Array
     *
     * @var array
     */
    private $_accreditationdata;

    /*     * status
     *
     * @var <type>
     */
    private $_status;
    //////////   Identification parameters   //////////

    /*     * Identifier
     *
     * @var <type>
     */
    private $_identifier;

    /*     * Parent ID
     *
     * @var <type>
     */
    private $_parentid;

    /*     * Relation
     *
     * @var <type>
     */
    private $_relation;

    /*     * Relation type;
     *
     * @var <type>
     */
    private $_relationtype;

    /*     * keywords
     *
     * @var <type>
     */
    private $_keywords = array();

    /*     * Thumbnail
     *
     * @var <type>
     */
    private $_thumbnail;
    //////////   Product Rights and Legal Parameters   //////////

    /*     * Rights
     *
     * @var <type>
     */
    private $_rights;

    /*     * Rights Holder
     *
     * @var <type>
     */
    private $_rightsholder;

    /*     * Provenance
     *
     * @var <type>
     */
    private $_provenance;
    ////////// Adaptation Specific Parameters /////////

    /*     * Region
     *
     * @var <type>
     */
    private $_region;

    /*     * Country
     *
     * @var <type>
     */
    private $_country;

    /*     * Group
     *
     * @var <type>
     */
    private $_group;

    /*     * Institution
     *
     * @var <type>
     */
    private $_institution;

    /*     * Currently logged in user
     *
     * @var <type>
     */
    private $_user;
    //////////   Required database objects   //////////

    /*     * Database for accessing products
     *
     * @var <type>
     */
    private $_objDbProducts;
    //////////   Content   //////////

    /*     * Variable for containing and managing content of the product
     *
     * @var <type>
     */
    private $_contentManager;
    //////////   Display objects   //////////

    /*     * Object that contains some simple display operations
     *
     * @var <type>
     */
    private $_objAddDataUtil;
    //////////   Validation objects   //////////

    var $validationArray;
    private $_deletionstatus;

    //////////   Constructor   //////////

    public function init() {
        parent::init();
        $this->dummyValue = "I am a product";
        $this->_objDbProducts = $this->getObject('dbproducts');
        $this->_objAddDataUtil = $this->getObject('adddatautil');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objDbResourceTypes = $this->getObject('dbresourcetypes');
        $this->objDbProductThemes = $this->getObject('dbproductthemes');
        $this->objThumbUploader = $this->getObject('thumbnailuploader');
        $this->objDbRelationTypes = $this->getObject('dbrelationtype');
        $this->objDbProductKeywords = $this->getObject('dbproductkeywords');
        $this->objDbProductStatus = $this->getObject('dbproductstatus');
        $this->validationArray = array();
        $this->objCC = $this->getObject('displaylicense', 'creativecommons'); //TODO use this licence stuff
        $this->objDbRegions = $this->getObject('dbregions');

        $this->setContentManager($this->newObject('contentmanager'));
        $this->_user = $this->getObject('user', 'security');
        $this->_accreditationdata = array();
    }

    ////////////////   METHODS   ////////////////

    /*     * This function stores product data in the data base whether the product
     * already exists or not.
     */
    function saveProduct() {
        $tempData = array();

        $tempData['parent_id'] = $this->getParentID();
        $tempData['title'] = $this->getTitle();
        $tempData['alternative_title'] = $this->getAlternativeTitle();
        $tempData['resource_type'] = $this->getContentType();
        $tempData['language'] = $this->getLanguageID();
        $tempData['translation_of'] = $this->getTranslationID();
        $tempData['description'] = $this->getDescription();
        $tempData['abstract'] = $this->getAbstract();

        $tempData['creator'] = $this->getAuthors();
        $tempData['contacts'] = $this->getContacts();
        $tempData['publisher'] = $this->getPublisher();
        //TODO $tempData['related_language']
        $tempData['other_contributors'] = $this->getOtherContributers();
        $tempData['coverage'] = $this->getCoverage();
        $tempData['rights'] = $this->getRights();
        $tempData['rights_holder'] = $this->getRightsHolder();
        $tempData['provenance'] = $this->getProvenance();
        $tempData['relation'] = $this->getRelation();
        $tempData['relation_type'] = $this->getRelationType();
        $tempData['status'] = $this->getStatus();
        $tempData['deleted'] = $this->isDeleted();
        $tempData['thumbnail'] = $this->getThumbnailPath();
        $tempData['is_accredited'] = $this->_accreditationdata['is_accredited'];
        $tempData['accreditation_body'] = $this->_accreditationdata['accreditation_body'];
        $tempData['accreditation_date'] = $this->_accreditationdata['accreditation_date'];

        $keywords = array();

        foreach ($this->getKeyWords() as $keyword) {
            $keywords[] = $keyword['keyword'];
        }

        $tempData['lucene_tags'] = $keywords;

        if ($this->getIdentifier()) {
            if ($this->isAdaptation())
                $this->_objDbProducts->updateProduct($this->getIdentifier(), $tempData, $this->getAdaptationMetaDataArray());
            else
                $this->_objDbProducts->updateProduct($this->getIdentifier(), $tempData);
        }
        else {
            $tempData['date'] = $this->getDate();
            if ($this->isAdaptation()) {
                $parentArray = $this->_objDbProducts->getProductByID($this->_parentid);
                $tempData['thumbnail'] = $parentArray['thumbnail'];
                $this->_identifier = $this->_objDbProducts->addProduct($tempData, $this->getAdaptationMetaDataArray());
            }
            else
                $this->_identifier = $this->_objDbProducts->addProduct($tempData);
            //$this->_identifier = $this->_objDbProducts->getLastInsertId();
        }

        //NOTE: themes are saved after an ID has been created
        $this->saveThemes($this->getThemes());
        //NOTE: keywords are saved after an ID has been created
        $this->saveKeyWords($this->getKeyWords());

        $path = 'unesco_oer/products/' . $this->_identifier . '/thumbnail/';
        $results = FALSE;
        if (!$this->isDeleted() && !$this->isAdaptation()) {
            $results = $this->uploadThumbNail($path);
        }

        if ($results) {
            $this->_objDbProducts->updateProduct(
                    $this->getIdentifier(), array('thumbnail' => 'usrfiles/' . $results['path'])
            );
            $this->setThumbnailPath($results['path']);
        }

        if ($this->isAdaptation() && (strcmp($this->_contentManager->getProductID(), $this->getParentID()) != 0 )) {
            $validTypes = array(//TODO this line should be inside a database or some managing class
                'curriculum' => $this->getContentTypeDescription()
            );

            $this->_contentManager = $this->_contentManager->copyContentsToProduct($this->getParentID(), $this->_identifier, $validTypes);
        }

        return $this->_identifier;
    }

    function createBlankProduct() { //TODO complete this train of thought
        return $this->deleteProduct();
    }

    /*     * This is an internal function for saving adaptation specific metadata
     *
     * @return <type>
     */

    private function getAdaptationMetaDataArray() {
        $tempData = array();

        $tempData['region'] = $this->getRegion();
        $tempData['country_code'] = $this->getCountryCode();
        $tempData['group_id'] = $this->getGroupID();
        $tempData['institution_id'] = $this->getInstitutionID();
        $tempData['remark'] = $this->getRemark();

        return $tempData;
    }

    /*     * This function retrieves product information, given the products identifier
     * (ID)
     * @param <type> $id
     */

    function loadProduct($id) {
        $product = NULL;
        if (is_array($id)) {
            $product = $id;
        } else {
            $product = $this->_objDbProducts->getProductByID($id);
        }

        $this->setIdentifier($product['id']);
        $this->setParentID($product['parent_id']);
        $this->setTitle($product['title']);
        $this->setAlternativeTitle($product['alternative_title']);
        $this->setContentType($product['resource_type']);
        $this->setLanguage($product['language']);
        $this->setTranslationID($product['translation_of']);
        $this->setAuthors($product['creator']);
        $this->setPublisher($product['publisher']);
        $this->setDescription($product['description']);
        $this->setAbstract($product['abstract']);

        $this->setOtherContributers($product['other_contributors']);
        $this->setContacts($product['contacts']);
        $this->setStatus($product['status']);
        $this->setRights($product['rights']);
        $this->setRightsHolder($product['rights_holder']);
        $this->setProvenance($product['provenance']);
        $this->setCoverage($product['coverage']);
        $this->setStatus($product['status']);
        $this->setRelation($product['relation'], $product['relation_type']);
        $this->setThumbnailPath($product['thumbnail']);
        $this->loadThemes($product['id']);
        $this->loadKeyWords($product['id']);
        $this->setDeletionStatus($product['deleted']);

        $this->setAccreditationData($product['is_accredited'], $product['accreditation_body'], $product['accreditation_date']);

        $this->getContentManager();

        if ($this->isAdaptation()) {
            $this->loadAdaptationData($this->getIdentifier());
        }
    }

    /*     * This is a private function to load data for an adaptation
     *
     * @param <type> $id
     */

    private function loadAdaptationData($id) {
        $data = $this->_objDbProducts->getAdaptationDataByProductID($id);

        $this->setRegion($data['region']);
        $this->setCountryCode($data['country_code']);
        $this->setGroupID($data['group_id']);
        $this->setInstitutionID($data['institution_id']);
        $this->setRemark($data['remark']);
    }

    /*     * This function upadates relevant fields of the product provided you use
     * showMetaDataInput as your input means and saves theme.
     *
     */

    function handleUpload() {
        $this->setTitle($this->getParam('title'));
        $this->setAlternativeTitle($this->getParam('alternative_title'));
        $this->setContentType($this->getParam('resource_type'));
        $this->setLanguage($this->getParam('language'));
        $this->setTranslationID($this->getParam('translation_of'));
        $this->setAuthors($this->getParam('creator'));
        $this->setPublisher($this->getParam('publisher'));
        $this->setDescription($this->getParam('description'));
        $this->setAbstract($this->getParam('abstract'));

        $this->setOtherContributers($this->getParam('other_contributors'));
        $this->setContacts($this->getParam('contacts'));
        $this->setStatus($this->getParam('status'));
        $this->setRights($this->getParam('rights'));
        $this->setRightsHolder($this->getParam('creativecommons'));
        $this->setProvenance($this->getParam('provenance'));
        $this->setCoverage($this->getParam('coverage'));
        $this->setStatus($this->getParam('status'));
        $this->setKeyWords($this->getParam('keywords'));
        $this->setRelation($this->getParam('relation'), $this->getParam('relation_type'));
        $this->setDeletionStatus(0);
        $this->setParentID($this->getParam('parentID'));
        $this->setAccreditationData($this->getParam('is_accredited'), $this->getParam('accreditation_body'), $this->getParam('accreditation_date'));

        $themesSelected = array();
        $umbrellaThemes = $this->objDbProductThemes->getUmbrellaThemes();
        foreach ($umbrellaThemes as $umbrellaTheme) {
            $themesSelected[$umbrellaTheme['id']] = $this->getParam($umbrellaTheme['id']);
        }

        $this->setThemes($themesSelected);

        if ($this->isAdaptation())
            $this->handleAdaptationUpload();

        if ($this->validateMetaData()) {
            $this->saveProduct();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function handleAdaptationUpload() {
        $this->setRegion($this->getParam('region'));
        $this->setCountryCode($this->getParam('country'));
        $this->setGroupID($this->getParam('group'));
        $this->setInstitutionID($this->getParam('institution'));
        $this->setRemark($this->getParam('remark'));
    }

    /*     * This function validates the input on product meta data input page
     *
     */

    function validateMetaData() {
        $valid = TRUE;

        foreach ($this->validationArray as $validation) {
            if (!$validation['valid']) {
                $valid = FALSE;
            }
        }

        $fileInfoArray = array();

        if (!$this->objThumbUploader->isFileValid($fileInfoArray) && !$this->isAdaptation()) {
            $errorMessage1 = $this->objLanguage->languageText('mod_unesco_oer_thumbnail_error1', 'unesco_oer');
            $errorMessage2 = $this->objLanguage->languageText('mod_unesco_oer_thumbnail_error2', 'unesco_oer');
            $errorMessage3 = $this->objLanguage->languageText('mod_unesco_oer_thumbnail_error3', 'unesco_oer');
            switch ($fileInfoArray['reason']) {
                case 'nouploadedfileprovided':
                    if (!$this->getThumbnailPath()) {
                        $valid = FALSE;
                        $this->addValidationMessage('thumbnail', $valid, $errorMessage1);
                    }
                    break;
                case 'bannedfile':
                    $valid = FALSE;
                    $this->addValidationMessage('thumbnail', $valid, $errorMessage2);
                    break;
                case 'doesnotmeetextension':
                    $valid = FALSE;
                    $this->addValidationMessage('thumbnail', $valid, $errorMessage2);
                    break;
                case 'partialuploaded':
                    $valid = FALSE;
                    $this->addValidationMessage('thumbnail', $valid, $errorMessage3);
                    break;

                default:
                    break;
            }
        } elseif ($this->objThumbUploader->isFileValid($fileInfoArray) && !$this->isAdaptation()) {
            $path = 'unesco_oer/products/' . $this->_identifier . '/thumbnail/';
            $results = FALSE;
            if (!$this->isDeleted() && !$this->isAdaptation()) {
                $results = $this->uploadThumbNail($path);
            }

            if ($results) {
                $this->_objDbProducts->updateProduct(
                        $this->getIdentifier(), array('thumbnail' => 'usrfiles/' . $results['path'])
                );
                $this->setThumbnailPath('usrfiles/' . $results['path']);
            }
        }

        return $valid;
    }

    //TODO add language elements where required
    //TODO move this to the Template
    /*     * This function returns a display for entering product metadata
     *
     * @return string
     */
    function showMetaDataInput($nextAction = NULL, $cancelAction = NULL, $cancelParams = NULL) {
        $output = '';

        // set up html elements
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('adddatautil', 'unesco_oer');
        $this->loadClass('hiddeninput', 'htmlelements');

        $productUtil = $this->getObject('productutil', 'unesco_oer');
        $objHelpLink = $this->getObject('helplink','unesco_oer');

        // setup and show heading
        $header = new htmlHeading();
        if ($this->isDeleted() || empty($this->_identifier)) {
            $header->str = $this->objLanguage->
                    languageText('mod_unesco_oer_product_upload_heading', 'unesco_oer');
        } else {
            $header->str = $this->objLanguage->
                    languageText('mod_unesco_oer_product_upload_edit_heading', 'unesco_oer');
        }
        $header->type = 1;
        echo '<div id="productmetaheading">';
        echo $header->show();
        if ($this->isAdaptation())
            echo '<font face="Arial" color="#FF2222">This is an ADAPTATION</font><br>';
        echo '<font face="Arial" color="#FF2222">(*) indicates fields that are required. </font>';
        echo '</div>';


        /*                                              */
        /*      Identification fields, eg. title        */
        /*                                              */

        // setup table and table headings with input fields
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";


//        $table->startRow();
        //field for the title
        $fieldName = 'title';
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_title', 'unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_title', 'unesco_oer');
        $title .= '<font color="#FF2222">* ' . $this->validationArray[$fieldName]['message'] . '</font>';
        $title .= $productUtil->getToolTip($tooltip,$objHelpLink->show('mod_unesco_oer_tooltip_title',$title));
//        $title='<a href="#"  onmouseover="showToolTip(event,\''.$tooltip.'\');return false" onmouseout="hideToolTip()">'.$title.'</a>';
        $this->_objAddDataUtil->addTextInputToTable(
                $title, 4, $fieldName, '90%', $this->getTitle(), $table
        );

        //field for alternative title
        $fieldName = 'alternative_title';
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_alt_title_long', 'unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_title_alternative', 'unesco_oer');
        $title .= $productUtil->getToolTip($tooltip,$objHelpLink->show('mod_unesco_oer_tooltip_alt_title_long',$title));
        $this->_objAddDataUtil->addTextInputToTable(
                $title, 4, $fieldName, '90%', $this->getAlternativeTitle(), $table
        );

        //field for the thumbnail
        if (!$this->isAdaptation()) {
            $tableThumb = $this->newObject('htmltable', 'htmlelements');
            $tableThumb->cssClass = "moduleHeader";
            $tableThumb->startRow();
            $tableThumb->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail', 'unesco_oer'));
            $tableThumb->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail_preview', 'unesco_oer'));
            $tableThumb->endRow();
            $tableThumb->startRow();
            $tableThumb->addCell($this->objThumbUploader->show());
            if ($this->getThumbnailPath()) {
                $tableThumb->addCell("<img width='79' height='101' style='border:1px solid black;' src='{$this->getThumbnailPath()}'>");
            } else {
                $tableThumb->addCell("<img width='79' height='101' src='skins/_common/icons/imagepreview.gif'>");
            }
            $tableThumb->endRow();

            $fieldset = $this->newObject('fieldset', 'htmlelements');
            $fieldsetTitle = $this->objLanguage->languageText('mod_unesco_oer_thumbnail_product', 'unesco_oer');
            $fieldset->setLegend($fieldsetTitle . '<font color="#FF2222">* ' . $this->validationArray['thumbnail']['message'] . '</font>');
            $fieldset->addContent($tableThumb->show());

            $table->startRow();
            $table->addCell($fieldset->show());
            $table->endRow();
        }

//        $table->endRow();
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldsetTitle = $this->objLanguage->languageText('mod_unesco_oer_id_info', 'unesco_oer');
        $fieldset->setLegend($fieldsetTitle);
        $fieldset->addContent($table->show());
        $output .= $fieldset->show();
        /*                 end of                       */
        /*      Identification fields, eg. title        */
        /*                                              */

        /*                                              */
        /*      Creator fields, eg. Authors             */
        /*                                              */

        // setup table and table headings with input fields
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";

        //Field for Authors
        $fieldName = 'creator';
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_creator_long', 'unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_creator', 'unesco_oer');
        $title .= '<font color="#FF2222">* ' . $this->validationArray[$fieldName]['message'] . '</font>';
        $title .= $productUtil->getToolTip($tooltip,$objHelpLink->show('mod_unesco_oer_tooltip_creator_long',$title));
        $this->_objAddDataUtil->addTextInputToTable(
                $title, 4, $fieldName, '90%', $this->getAuthors(), $table
        );

        //Field for other contributors
        $fieldName = 'other_contributors';
        $title = $this->objLanguage->languageText('mod_unesco_oer_other_contributors', 'unesco_oer');
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_contributor_long', 'unesco_oer');
        $title .= $productUtil->getToolTip($tooltip,$objHelpLink->show('mod_unesco_oer_tooltip_contributor_long',$title));
        $this->_objAddDataUtil->addTextInputToTable(
                $title, 4, $fieldName, '90%', $this->getOtherContributers(), $table
        );

        //field for publisher
        $fieldName = 'publisher';
        $title = $this->objLanguage->languageText('mod_unesco_oer_publisher', 'unesco_oer');
        $title .= '<font color="#FF2222">* ' . $this->validationArray[$fieldName]['message'] . '</font>';
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_publisher', 'unesco_oer');
        $title .= $productUtil->getToolTip($tooltip,$objHelpLink->show('mod_unesco_oer_tooltip_publisher',$title));
        $this->_objAddDataUtil->addTextInputToTable(
                $title, 4, $fieldName, '90%', $this->getPublisher(), $table
        );

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend($this->objLanguage->languageText('mod_unesco_oer_creator_info', 'unesco_oer'));
        $fieldset->addContent($table->show());
        $output .= $fieldset->show();
        /*               end of                         */
        /*      Creator fields, eg. Authors             */
        /*                                              */

        /*                                              */
        /*   Description fields, eg. Themes             */
        /*                                              */

        // setup table and table headings with input fields
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";

        //field for the theme
        $tableTheme = $this->newObject('htmltable', 'htmlelements');
        $tableTheme->cssClass = "moduleHeader";

        $tableTheme->startRow();
        $umbrellaThemes = $this->objDbProductThemes->getUmbrellaThemes();
        $themeCount = count();
        for ($index = 0; $index < count($umbrellaThemes); $index++) {
            for ($index1 = 0; $index1 < 3; $index1++) {
                if (!empty($umbrellaThemes[$index + $index1]))
                    $this->_objAddDataUtil->addTitleToRow($umbrellaThemes[$index + $index1]['theme'], 4, $tableTheme);
            }
            $tableTheme->endRow();

            $tableTheme->startRow();
            for ($index1 = 0; $index1 < 3; $index1++) {
                if (!empty($umbrellaThemes[$index + $index1])) {
                    $themes = $this->objDbProductThemes->getThemesByUmbrellaID($umbrellaThemes[$index + $index1]['id']);
                    $this->_objAddDataUtil->addDropDownToRow($umbrellaThemes[$index + $index1]['id'], $themes, $this->_unescothemes[$umbrellaThemes[$index + $index1]['id']], 'theme', $tableTheme, 'id');
                }
            }
            $tableTheme->endRow();
            $index += 2;
        }

        $themefieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldsetTitle = $this->objLanguage->languageText('mod_unesco_oer_product_themes', 'unesco_oer');
        $themefieldset->setLegend($fieldsetTitle . '<font color="#FF2222">* ' . $this->validationArray['theme']['message'] . '</font>');
        $themefieldset->addContent($tableTheme->show());

        //field for the language
        $fieldName = 'language';
        $title = $this->objLanguage->languageText('mod_unesco_oer_languages', 'unesco_oer');
        $title .= '<font color="#FF2222">* ' . $this->validationArray[$fieldName]['message'] . '</font>';
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_language', 'unesco_oer');
        $title .= $productUtil->getToolTip($tooltip,$objHelpLink->show('mod_unesco_oer_tooltip_language',$title));
        $langs = $this->objLanguage->getLangs();
        $langs['en'] = 'English';
        $productLanguages = array();
        foreach ($langs as $key => $value) {
            $productLanguages[] = array('code' => $value, 'id' => $key);
        }
        $this->_objAddDataUtil->addDropDownToTable(
                $title, 4, $fieldName, $productLanguages, $this->getLanguageID(), 'code', $table, 'id'
        );

        //field for the translation
        $fieldName = 'translation_of';
        $title = $this->objLanguage->languageText('mod_unesco_oer_translation', 'unesco_oer');
//        $title .= '<font color="#FF2222">* '. $this->validationArray[$fieldName]['message']. '</font>';
        $translatableProducts = $this->_objDbProducts->getTranslatableOriginals();
        $this->_objAddDataUtil->addDropDownToTable(
                $title, 4, $fieldName, $translatableProducts, $this->getTranslationID(), 'title', $table, 'id'
        );

        //field for the description
        $fieldName = 'description';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '450px';
        $editor->width = '98%';
       // $editor->setBasicToolBar();
        $editor->setContent($this->getDescription());
        $table->startRow();
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_description_long', 'unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer') . $productUtil->getToolTip($tooltip,$objHelpLink->show('mod_unesco_oer_tooltip_description_long',$title));
        $this->_objAddDataUtil->addTitleToRow($title, 4, $table);
        $table->endRow();
        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

        //field description abstract
        $fieldName = 'abstract';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '450px';
        $editor->width = '98%';
       // $editor->setBasicToolBar();
        $editor->setContent($this->getAbstract());
        $table->startRow();
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_abstract', 'unesco_oer');
        $title = $this->objLanguage->languageText('mod_unesco_oer_description_abstract', 'unesco_oer') . $productUtil->getToolTip($tooltip,$objHelpLink->show('mod_unesco_oer_tooltip_abstract',$title));
        $this->_objAddDataUtil->addTitleToRow($title, 4, $table);
        $table->endRow();
        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

        //field for keywords
        $fieldName = 'keywords';
        $uri = $this->uri(array(
            'action' => "saveProductMetaData",
            'productID' => $this->_identifier,
            'parentID' => $this->_parentid,
            'nextAction' => $nextAction,
            'cancelAction' => $cancelAction,
            'cancelParams' => $cancelParams));
        $form_data = new form('add_products_ui', $uri); //////created here in order to include select boxes for keywords//////
        // Create the selectbox object
        $objSelectBox = $this->newObject('selectbox', 'htmlelements');
        // Initialise the selectbox.
        $headAvailableKeywords = $this->objLanguage->languageText('mod_unesco_oer_keywords_available', 'unesco_oer');
        $headChosenKeywords = $this->objLanguage->languageText('mod_unesco_oer_keywords_chosen', 'unesco_oer');
        $objSelectBox->create($form_data, 'leftList[]', $headAvailableKeywords, $fieldName . '[]', $headChosenKeywords);
        //// Populate the selectboxes
        $productKeywords = $this->objDbProductKeywords->getProductKeywords();
        $diff = array_udiff(
                $productKeywords, $this->getKeyWords(), create_function(
                        '$a,$b', 'if ($a[' . '"puid"' . '] == $b[' . '"puid"' . ']) return 0;
                         elseif (($a[' . '"puid"' . '] > $b[' . '"puid"' . '])) return 1;
                         else return -1;'
                )
        );
        $objSelectBox->insertLeftOptions($diff, 'id', 'keyword');
        $objSelectBox->insertRightOptions($this->getKeyWords(), 'id', 'keyword');
        //Construct tables for left selectboxes
        $tblLeft = $this->newObject('htmltable', 'htmlelements');
        $tblLeft->cssClass = "moduleHeader";
        $objSelectBox->selectBoxTable($tblLeft, $objSelectBox->objLeftList);
        //Construct tables for right selectboxes
        $tblRight = $this->newObject('htmltable', 'htmlelements');
        $tblRight->cssClass = "moduleHeader";
        $objSelectBox->selectBoxTable($tblRight, $objSelectBox->objRightList);
        //Construct tables for selectboxes and headings
        $tblSelectBox = $this->newObject('htmltable', 'htmlelements');
        $tblSelectBox->cssClass = "moduleHeader";
        $tblSelectBox->width = '90%';
        $tblSelectBox->startRow();
        $tblSelectBox->addCell('<h4>'.$objSelectBox->arrHeaders['hdrLeft'].'</h4>', '100pt');
        $tblSelectBox->addCell('<h4>'.$objSelectBox->arrHeaders['hdrRight'].'</h4>', '100pt');
        $tblSelectBox->endRow();
        $tblSelectBox->startRow();
        $tblSelectBox->addCell($tblLeft->show(), '100pt');
        $tblSelectBox->addCell($tblRight->show(), '100pt');
        $tblSelectBox->endRow();

        $keywordfieldset = $this->newObject('fieldset', 'htmlelements');
        $title = $this->objLanguage->languageText('mod_unesco_oer_keywords', 'unesco_oer');
        $keywordfieldset->setLegend($title . '<font color="#FF2222">* ' . $this->validationArray['keyword']['message'] . '</font>');
        $keywordfieldset->addContent($tblSelectBox->show());

        //field for resource type
        $fieldName = 'resource_type';
        $title = $this->objLanguage->languageText('mod_unesco_oer_resource', 'unesco_oer');
        $title .= '<font color="#FF2222">* ' . $this->validationArray[$fieldName]['message'] . '</font>';
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_type_long', 'unesco_oer');
        $title .= $productUtil->getToolTip($tooltip,$objHelpLink->show('mod_unesco_oer_tooltip_type_long',$title));
        $resourceTypes = $this->objDbResourceTypes->getResourceTypes();
        $this->_objAddDataUtil->addDropDownToTable(
                $title, 4, $fieldName, $resourceTypes, $this->getContentType(), 'description', $table, 'id'
        );

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend($this->objLanguage->languageText('mod_unesco_oer_description_info', 'unesco_oer'));
        $fieldset->addContent($themefieldset->show());
        $fieldset->addContent($keywordfieldset->show());
        $fieldset->addContent($table->show());
        $output .= $fieldset->show();
        /*               end of                         */
        /*   Description fields, eg. Themes             */
        /*                                              */

        /*                                              */
        /*         Legal fields, eg. rights             */
        /*                                              */

        // setup table and table headings with input fields
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";

        //field for rights
        $fieldName = 'rights';
        $title = $this->objLanguage->languageText('mod_unesco_oer_rights', 'unesco_oer');
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_rights_long', 'unesco_oer');
        $title .= $productUtil->getToolTip($tooltip,$objHelpLink->show('mod_unesco_oer_tooltip_rights_long',$title));
        $fieldName = 'rights_holder';
        $detailsLink = new link($this->uri(array(),"creativecommons"));
        $licenceinfo= $this->objLanguage->languageText('mod_unesco_oer_licencinginfo', 'unesco_oer');
        $detailsLink->link = "<h3>$licenceinfo</h3>";

        $table->startRow();
        $table->addCell($detailsLink->show());
//        $this->_objAddDataUtil->addTitleToRow($detailsLink->show(), 4, $table);
        $table->endRow();

        $objLicenseChooser = $this->newObject('licensechooser', 'creativecommons');
        $objLicenseChooser->icontype = 'small';
        $objLicenseChooser->defaultValue = $this->getRightsHolder();
        //field for rights holder

        $title = $this->objLanguage->languageText('mod_unesco_oer_rights_holder', 'unesco_oer');
        $this->_objAddDataUtil->addContentAsRowToTable($objLicenseChooser->show(), $table
        );
        //field for provenance
        $fieldName = 'provenance';
        $title = $this->objLanguage->languageText('mod_unesco_oer_provenance', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title, 4, $fieldName, '90%', $this->getProvenance(), $table
        );

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend($this->objLanguage->languageText('mod_unesco_oer_legal_info', 'unesco_oer'));
        $fieldset->addContent($table->show());
        $output .= $fieldset->show();
        /*               end of                         */
        /*         Legal fields, eg. rights             */
        /*                                              */

        /*                                              */
        /*         Misc. fields                         */
        /*                                              */

        // setup table and table headings with input fields
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";

        //Field for accreditation
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend($this->objLanguage->languageText('mod_unesco_oer_accreditation', 'unesco_oer'));
        $acredTable = $this->newObject('htmltable', 'htmlelements');
        $acredTable->cssClass = "moduleHeader";

        $fieldName = 'is_accredited';
        $title = $this->objLanguage->languageText('mod_unesco_oer_accreditation_is', 'unesco_oer');
        $acredTable->startRow();
        $this->_objAddDataUtil->addTitleToRow($title, 4, $acredTable);
        $acredTable->endRow();
        $acredTable->startRow();
        $objRadio = $this->newObject('radio','htmlelements');
        $objRadio->name = $fieldName;
        $objRadio->addOption('YES',$this->objLanguage->languageText('mod_unesco_oer_yes', 'unesco_oer'));
        $objRadio->addOption('NO',$this->objLanguage->languageText('mod_unesco_oer_no', 'unesco_oer'));
        $selected = $this->_accreditationdata[$fieldName];
        if (empty($selected)) $selected = 'NO';
        $objRadio->setSelected($selected);
        $acredTable->addCell($objRadio->show());
        $acredTable->endRow();

        $fieldName = 'accreditation_body';
        $title = $this->objLanguage->languageText('mod_unesco_oer_accreditation_body', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title, 4, $fieldName, '90%', $this->_accreditationdata[$fieldName], $acredTable
        );

        $fieldName = 'accreditation_date';
        $title = $this->objLanguage->languageText('mod_unesco_oer_accreditation_date', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title, 4, $fieldName, '90%', $this->_accreditationdata[$fieldName], $acredTable
        );

        $fieldset->addContent($acredTable->show());
        $table->startRow();
        $table->addCell($fieldset->show());
        $table->endRow();

        //Field for Contacts
        $fieldName = 'contacts';
        $title = $this->objLanguage->languageText('mod_unesco_oer_contacts', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title, 4, $fieldName, '90%', $this->getContacts(), $table
        );

        //field for relation types
        $fieldName = 'relation_type';
        $title = $this->objLanguage->languageText('mod_unesco_oer_relation_type', 'unesco_oer');
        $title .= '<font color="#FF2222"> ' . $this->validationArray[$fieldName]['message'] . '</font>';
        $relationTypes = $this->objDbRelationTypes->getRelationTypes();
        $this->_objAddDataUtil->addDropDownToTable(
                $title, 4, $fieldName, $relationTypes, $this->getRelationType(), 'description', $table, 'id', "javascript: toggleRelationDropDown('input_relation_type', 'input_relation');"
        );

        //field for relations
        $fieldName = 'relation';
        $title = $this->objLanguage->languageText('mod_unesco_oer_relation', 'unesco_oer');
        $title .= '<font color="#FF2222"> ' . $this->validationArray[$fieldName]['message'] . '</font>';
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_relation', 'unesco_oer');
        $title .= $productUtil->getToolTip($tooltip,$objHelpLink->show('mod_unesco_oer_tooltip_relation',$title));
        $products = $this->_objDbProducts->getAll();
        $this->_objAddDataUtil->addDropDownToTable(
                $title, 4, $fieldName, $products, $this->_relation, 'title', $table, 'id'
        );

        //field for coverage
        $fieldName = 'coverage';
        $title = $this->objLanguage->languageText('mod_unesco_oer_coverage', 'unesco_oer');
        $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_coverage', 'unesco_oer');
        $title .= $productUtil->getToolTip($tooltip,$objHelpLink->show('mod_unesco_oer_tooltip_coverage',$title));
        $this->_objAddDataUtil->addTextInputToTable(
                $title, 4, $fieldName, '90%', $this->getCoverage(), $table
        );

        //field for status
        $fieldName = 'status';
        $title = $this->objLanguage->languageText('mod_unesco_oer_status', 'unesco_oer');
        $title .= '<font color="#FF2222">* ' . $this->validationArray[$fieldName]['message'] . '</font>';
        $statuses = $this->objDbProductStatus->getAllStatuses();
        $this->_objAddDataUtil->addDropDownToTable(
                $title, 4, $fieldName, $statuses, $this->getStatus(), 'status', $table, 'id'
        );


        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend('Misc. Information');
        $fieldset->addContent($table->show());
        $output .= $fieldset->show();
        /*               end of                         */
        /*         Misc. fields                         */
        /*                                              */

        /*                                              */
        /*         Adaptation Fields                    */
        /*                                              */

        if ($this->isAdaptation()) {
            // setup table and table headings with input fields
            $table = $this->newObject('htmltable', 'htmlelements');
            $table->cssClass = "moduleHeader";


            $fieldName = 'remark';
            $editor = $this->newObject('htmlarea', 'htmlelements');
            $editor->name = $fieldName;
            $editor->height = '100px';
            $editor->width = '98%';
            $editor->setBasicToolBar();
            $editor->setContent($this->getRemark());
            $table->startRow();
            $tooltip = $this->objLanguage->languageText('mod_unesco_oer_tooltip_remark','unesco_oer');
            $title = $this->objLanguage->languageText('mod_unesco_oer_tooltip_remark','unesco_oer');
            $table->addCell( $this->objLanguage->languageText('mod_unesco_oer_module_remark', 'unesco_oer'). $productUtil->getToolTip($tooltip,$objHelpLink->show('mod_unesco_oer_tooltip_remark',$title)));
            $table->endRow();
            $table->startRow();
            $table->addCell($editor->show());
            $table->endRow();


        


            //Field for Region
            $fieldName = 'region';
            $title = $this->objLanguage->languageText('mod_unesco_oer_adaptation_region', 'unesco_oer');
            $regions = $this->objDbRegions->getAll();
            $this->_objAddDataUtil->addDropDownToTable(
                    $title, 4, $fieldName, $regions, $this->getRegion(), 'region', $table, 'id'
            );

            //field for country
            $fieldName = 'country';
            $table->startRow();
            $title = $this->objLanguage->languageText('mod_unesco_oer_adaptation_country', 'unesco_oer');
            $table->addCell($title);
            $table->endRow();
            //$title .= '<font color="#FF2222"> '. $this->validationArray[$fieldName]['message']. '</font>';
            $table->startRow();
            $objCountries = &$this->getObject('languagecode', 'language');
            $table->addCell($objCountries->countryAlpha($this->getCountryCode()));
            $table->endRow();

            $objDbUserGroups = $this->getObject('dbusergroups', 'unesco_oer');
            $arrayUserGroups = $objDbUserGroups->getGroupsByUserID($this->_user->userId());

            /*
              if (empty($arrayUserGroups)) {
              $fieldset = $this->newObject('fieldset', 'htmlelements');
              $fieldset->setLegend('ERROR');
              $fieldset->addContent('You are not permitted to perform this operation! You must belong to a group');
              return $fieldset->show();
              } */

            $objDbGroups = $this->getObject('dbgroups', 'unesco_oer');

//            $groups = array(array("id" => NULL, "name" => "default"));
            $groups = array();
            foreach ($arrayUserGroups as $userGroupRow) {
                $groupArray = $objDbGroups->getGroupInfo($userGroupRow['groupid']);
                array_push($groups, $groupArray[0]);
            }

            //field for groups
            $fieldName = 'group';
            $title = $this->objLanguage->languageText('mod_unesco_oer_adaptation_group', 'unesco_oer');
            $title .= '<font color="#FF2222"> ' . $this->validationArray[$fieldName]['message'] . '</font>';
            $this->_objAddDataUtil->addDropDownToTable(
                    $title, 4, $fieldName, $groups, $this->getGroupID(), 'name', $table, 'id', "javascript: toggleInstitutionDropDown(this.value, 'institution_div', '{$this->getIdentifier()}');"
            );



            //field for institution
            $title = $this->objLanguage->languageText('mod_unesco_oer_adaptation_institution', 'unesco_oer');
            $table->startRow();
            $table->addCell($title);
            $table->endRow();
            $dropdown = $this->makeInstitutionDropDown();
            $institutionDiv = "<div id='institution_div'>{$dropdown->show()}<div>";
            $table->startRow();
            $table->addCell($institutionDiv);
            $table->endRow();


            $fieldset = $this->newObject('fieldset', 'htmlelements');
            $fieldset->setLegend('Adaptation information');
            $fieldset->addContent($table->show());
            $output .= $fieldset->show();
        } 
        
        
        
        /*               end of                         */
        /*         Adaptation Fields                    */
        /*                                              */

        //load required javascript file
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('addProduct.js', 'unesco_oer'));

        $hiddenInput = new hiddeninput('add_product_submit');

        $content = $this->getContentManager();
        //$submitOption = ($content->hasContents() && !empty($this->_identifier)) ? "'upload'" : "'createContent'"; //NOTE here we add support to create new content
//        $submitOption = ($this->getContent()) ? "'upload'" : "'upload'";
        $submitOption = "'upload'";
        // setup button for submission
        $buttonSubmit = new button('upload', $this->objLanguage->
                                languageText('mod_unesco_oer_product_upload_button', 'unesco_oer'));
        $action = $objSelectBox->selectAllOptions($objSelectBox->objRightList) . "SubmitProduct('add_product_submit', $submitOption);";
        $buttonSubmit->setOnClick('javascript: ' . $action);

        // setup button for cancellation
        $buttonCancel = new button('upload', $this->objLanguage->
                                languageText('mod_unesco_oer_product_cancel_button', 'unesco_oer'));
        $buttonCancel->setOnClick("javascript: SubmitProduct('add_product_submit', 'cancel')");



        //createform, add fields to it and display

        $form_data->extra = 'enctype="multipart/form-data"';
        $form_data->addToForm($output . '<br />' . $buttonSubmit->show() . $buttonCancel->show() . $hiddenInput->show());

        return $form_data->show();
    }

    //////// operations for external tables //////
    //TODO move some of these functions into the db classes they belong

    /*     * This function adds keyword relationships for the current product
     *
     * @param <type> $keyWords
     */
    function saveKeyWords($keyWords) {
        if ($keyWords != NULL && !is_array($keyWords)) {
            $keyWords = array($keyWords);
        }

//        $this->objDbProductKeywords->deleteProductKeywordJxnByProductID($this->getIdentifier());
        $this->objDbProductKeywords->deleteProductKeywordJxnByProductID($this->_identifier);

        foreach ($keyWords as $keyWord) {
            if ($keyWord != NULL) {
                $this->objDbProductKeywords->addProductKeywordJxn($this->_identifier, $keyWord['id']);
            }
        }
    }

    /*     * This function adds theme relationships for the current product
     *
     * @param <type> $themeIDarray
     */

    function saveThemes($themeIDarray) {
        if ($themeIDarray != NULL && !is_array($themeIDarray)) {
            $themeIDarray = array($themeIDarray);
        }

        $this->objDbProductThemes->deleteProductThemesJxnByProductID($this->_identifier);

        foreach ($themeIDarray as $themeID) {
            if ($themeID != NULL) {
                $this->objDbProductThemes->addProductThemeJxn($this->_identifier, $themeID);
            }
        }
    }

    function uploadThumbNail($path) {
        $results = FALSE;
        try {
            $results = $this->objThumbUploader->uploadThumbnail($path, $this->getThumbnailPath());
        } catch (customException $e) {
            echo customException::cleanUp();
            exit();
        }

        return $results;
    }

    private function loadThemes($id) {
        $themeIDarray = array();
        foreach ($this->objDbProductThemes->getThemesByProductID($id) as $theme) {
            $themeIDarray[$theme['umbrella_theme_id']] = $theme['id'];
        }

        return $this->setThemes($themeIDarray);
    }

    private function loadKeyWords($id) {
        $this->_keywords = $this->objDbProductKeywords->getKeywordsByProductID($id);
    }

    ////////////////   SETTERS   ////////////////

    private function addValidationMessage($field, $valid, $message) {
        $this->validationArray[$field]['valid'] = $valid;
        $this->validationArray[$field]['message'] = $message;
    }

    function setTitle($title) {
        $this->_title = $title;
        if (empty($title)) {
            $this->addValidationMessage('title', FALSE, 'Product must have a Title');
        } else {
            $this->addValidationMessage('title', TRUE, NULL);
        }
    }

    function setAlternativeTitle($alternativeTitle) {
        $this->_alternativetitle = $alternativeTitle;
    }

    function setContentType($resourceType) {
        $this->_resourcetype = $resourceType;
        if (empty($resourceType)) {
            $this->addValidationMessage('resource_type', FALSE, 'Product must have content');
        } else {
            $this->addValidationMessage('resource_type', TRUE, NULL);
        }
    }

    private function setDate($date) {
        $this->_date = $date;
    }

    function setLanguage($language) {
        $this->_language = $language;
        if (empty($language)) {
            $this->addValidationMessage('language', FALSE, 'Product must have a language');
        } else {
            $this->addValidationMessage('language', TRUE, NULL);
        }
    }

    function setTranslationID($translationID) {
        $this->_translationOf = $translationID;
//        if (empty($language))
//        {
//            $this->addValidationMessage('language', FALSE, 'Product must have a language');
//        }
//        else
//        {
//            $this->addValidationMessage('language', TRUE, NULL);
//        }
    }

    function setAuthors($creator) {
        $this->_creator = $creator;
        if (empty($creator)) {
            $this->addValidationMessage('creator', FALSE, 'Product must have an Author');
        } else {
            $this->addValidationMessage('creator', TRUE, NULL);
        }
    }

    function setPublisher($publisher) {
        $this->_publisher = $publisher;
        if (empty($publisher)) {
            $this->addValidationMessage('publisher', FALSE, 'Product must have an Publisher');
        } else {
            $this->addValidationMessage('publisher', TRUE, NULL);
        }
    }

    function setDescription($description) {
        $this->_description = $description;
    }

    function setAbstract($abstract) {
        $this->_abstract = $abstract;
    }

    function setRemark($Remark) {
        $this->_remark = $Remark;
    }

    function setOtherContributers($contributors) {
        $this->_othercontributors = $contributors;
    }

    function setContacts($contacts) {
        $this->_unescocontacts = $contacts;
    }

    function setThemes($themeIDarray) {
        $this->_unescothemes = $themeIDarray;

        $flag = FALSE;
        foreach ($themeIDarray as $theme) {
            if (!empty($theme)) {
                $flag = TRUE;
            }
        }
        if ($flag) {
            $this->addValidationMessage('theme', TRUE, NULL);
        } else {
            $this->addValidationMessage('theme', FALSE, 'Product must have at least one Theme');
        }
    }

    function setCoverage($coverage) {
        $this->_coverage = $coverage;
    }

    function setStatus($status) {
        $this->_status = $status;

        if (empty($status)) {
            $this->addValidationMessage('status', FALSE, 'Product must have a status');
        } else {
            $this->addValidationMessage('status', TRUE, NULL);
        }
    }

    private function setIdentifier($id) {
        $this->_identifier = $id;
    }

    function setRelation($relation, $relationType) {
        $this->_relation = $relation;
        $this->_relationtype = $relationType;

        if (empty($relation) && !empty($relationType)) {
            $this->addValidationMessage('relation', FALSE, 'Given a relation type, a relation must be selected');
        } elseif (!empty($relation) && empty($relationType)) {
            $this->addValidationMessage('relation_type', FALSE, 'Given a relation, a relation type must be selected');
        } else {
            $this->addValidationMessage('relation', TRUE, NULL);
            $this->addValidationMessage('relation_type', TRUE, NULL);
        }
    }

    function setKeyWords($keyWords) {
        $this->_keywords = array();

        foreach ($keyWords as $keyword) {
            array_push($this->_keywords, $this->objDbProductKeywords->getProductKeywordByID($keyword));
        }

        if (empty($keyWords)) {
            $this->addValidationMessage('keyword', FALSE, 'Product must have at least one Keyword');
        } else {
            $this->addValidationMessage('keyword', TRUE, NULL);
        }
    }

    function setThumbnailPath($path) {
        $this->_thumbnail = $path;
    }

    function setRights($rights) {
        $this->_rights = $rights;
    }

    function setRightsHolder($rightsholder) {
        $this->_rightsholder = $rightsholder;
    }

    function setProvenance($provenance) {
        $this->_provenance = $provenance;
    }

    function setContentManager($content) {
        $this->_contentManager = $content;
    }

    private function setDeletionStatus($delete) {
        $this->_deletionstatus = $delete;
    }

    private function setParentID($id) {
        $this->_parentid = $id;
    }

    private function setRegion($region) {
        $this->_region = $region;
    }

    private function setCountryCode($countryCode) {
        $this->_country = $countryCode;
    }

    private function setGroupID($groupID) {
        $this->_group = $groupID;
        if (empty($groupID)) {
            $this->addValidationMessage('group', FALSE, 'Product must have a group associated with it');
        } else {
            $this->addValidationMessage('group', TRUE, NULL);
        }
    }

    private function setInstitutionID($institutionID) {
        $this->_institution = $institutionID;
    }

    private function  setAccreditationData($isAccredited, $body, $date) {
        $this->_accreditationdata['is_accredited'] = $isAccredited;
        $this->_accreditationdata['accreditation_body'] = $body;
        $this->_accreditationdata['accreditation_date'] = $date;
    }

    ////////////////   GETTERS   ////////////////




    function getTitle() {
        return $this->_title;
    }

    function getAlternativeTitle() {
        return $this->_alternativetitle;
    }

    function getContentType() {
        return $this->_resourcetype;
    }

    function getContentTypeDescription() {
        return $this->objDbResourceTypes->getResourceTypeDescription(
                        $this->getContentType()
        );
    }

    function getProductDate() {
        return $this->_date;
    }

    private function getDate() {
        return $this->_objDbProducts->now();
    }

    private function getLanguageID() {
        return $this->_language;
    }

    function getLanguageName() {
        $langs = $this->objLanguage->getLangs();
        $langs['en'] = 'English';
        return $langs[$this->getLanguageID()];
    }

    public function getTranslationID() {
        return $this->_translationOf;
    }

    public function getTranslationsList() {
        $translations = $this->_objDbProducts->getAllTranslationsByID($this->_identifier);
        return $translations;
    }

    function getAuthors() {
        return $this->_creator;
    }

    function getPublisher() {
        return $this->_publisher;
    }

    function getDescription() {
        return $this->_description;
    }

    function getAbstract() {
        return $this->_abstract;
    }

    function getRemark() {
        return $this->_remark;
    }

    function getOtherContributers() {
        return $this->_othercontributors;
    }

    function getContacts() {
        return $this->_unescocontacts;
    }

    function getThemes() {
        return $this->_unescothemes;
    }

    function getThemeNames() {
        $themeNameArray = array();

        foreach ($this->getThemes() as $themeID) {
            $theme = $this->objDbProductThemes->getThemeByID($themeID);
            array_push($themeNameArray, $theme['theme']);
        }

        return $themeNameArray;
    }

    function getCoverage() {
        return $this->_coverage;
    }

    function getStatus() {
        return $this->_status;
    }

    function getIdentifier() {
        return $this->_identifier;
    }

    function getRelation() {
        return $this->_relation;
    }

    function getRelationType() {
        return $this->_relationtype;
    }

    function getKeyWords() {
        return $this->_keywords;
    }

    function getThumbnailPath() {
        if ($this->isAdaptation()) {
            $parent = $this->newObject('product', 'unesco_oer');
            $parent->loadProduct($this->getParentID());
            return $parent->getThumbnailPath();
        }
        return $this->_thumbnail;
    }

    function getRights() {
        return $this->_rights;
    }

    function getRightsHolder() {
        return $this->_rightsholder;
    }

    function getProvenance() {
        return $this->_provenance;
    }

    function getContentManager() {
        //if (!$this->_contentManager->hasContents()){
        $this->_contentManager = $this->newObject('contentmanager');
//           $this->_contentManager->setProductID($this->getIdentifier());
//           $this->_contentManager->setValidTypes( //TODO this line should be inside a database or some managing class
//                            array(
//                                'curriculum' => $this->getContentTypeDescription()
//                            )
//                    );
//           $this->_contentManager->getAllContents();
        $validTypes = array(//TODO this line should be inside a database or some managing class
            'curriculum' => $this->getContentTypeDescription()
        );

        $this->_contentManager->loadContents($this->getIdentifier(), $validTypes);
        // }
        return $this->_contentManager;
    }

    function getParentID() {
        return $this->_parentid;
    }

    function getRegion() {
        return $this->_region;
    }

    function getRegionName() {
        $regionArray = $this->objDbRegions->getRegionByID($this->_region);
        return $regionArray['region'];
    }

    function getCountryCode() {
        return $this->_country;
    }

    //TODO return country name in text with this
    function getCountryName() {
        return "";
    }

    function getGroupName() {
        $info = $this->getGroupInfo();
        return $info['name'];
    }

    function getGroupInfo() {
        $objDbGroups = $this->getObject('dbgroups', 'unesco_oer');
        $info = $objDbGroups->getGroupInfo($this->getGroupID());
        return $info[0];
    }

    function getGroupID() {
        return $this->_group;
    }

    function getInstitutionName() {
        if ($institution = $this->getInstitution()) {
            return $institution->getName();
        } else {
            return 'No Institution Linked';
        }
    }

    function getInstitution() {
        if ($this->hasInstitutionLink()) {
            $objInstitutionManager = $this->getObject('institutionmanager', 'unesco_oer');
            return $objInstitutionManager->getInstitution($this->getInstitutionID());
        } else {
            return NULL;
        }
    }

    function getInstitutionID() {
        return $this->_institution;
    }

    function hasInstitutionLink() {
        $institutionID = $this->getInstitutionID();
        return!empty($institutionID);
    }

    function makeInstitutionDropDown($group_id = NULL) {
        if (empty($group_id))
            $group_id = $this->getGroupID();
        $objDbGroups = $this->getObject('dbgroups', 'unesco_oer');
        $idArray = $objDbGroups->getInstitutions($group_id);

        $objDbInstitution = $this->getObject('dbinstitution', 'unesco_oer');

        $arrayInstitutions = array();
        foreach ($idArray as $id) {
            $Instition2Darray = $objDbInstitution->getInstitutionById($id);
            array_push($arrayInstitutions, $Instition2Darray[0]);
        }

        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";

        //field for institution
        $fieldName = 'institution';
        $title = $this->objLanguage->languageText('mod_unesco_oer_adaptation_institution', 'unesco_oer');
        //$title .= '<font color="#FF2222">* '. $this->validationArray[$fieldName]['message']. '</font>';
        //$institutions = $objInstitutionManager->getAllInstitutions("where name='{$groups[0]["linkedinstitution"]}'");
        return $this->_objAddDataUtil->addDropDownToTable(
                        $title, 4, $fieldName, $arrayInstitutions, $this->getInstitutionID(), 'name', $table, 'id'
        );
    }

    function getNoOfAdaptations() {
        return $this->_objDbProducts->getNoOfAdaptations($this->getIdentifier());
    }

    function isDeleted() {
        return $this->_deletionstatus;
    }

    function isAdaptation() {
        $temp = $this->getParentID();
        return!empty($temp);
    }

    function hasAdaptation() {
        return ($this->_objDbProducts->getNoOfAdaptations($this->getIdentifier()) != 0);
    }

    function deleteProduct() {
        if (!$this->hasAdaptation()) {
            $this->setDeletionStatus(1);
        }

        $this->_contentManager->deleteAllContents();

        return $this->saveProduct();
    }

    function makeAdaptation() {
        $tempId = $this->getIdentifier();
        $tempParentID = $this->getParentID();

        $this->setParentID($this->getIdentifier());
        $this->setIdentifier(NULL);

        $tempProduct = clone $this;
        //$tempProduct->saveProduct();

        $this->setParentID($tempParentID);
        $this->setIdentifier($tempId);

        return $tempProduct;
    }

    function getAccreditationData() {
        return $this->_accreditationdata;
    }

}

?>