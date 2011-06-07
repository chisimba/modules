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

class product extends object
{
    ///these objects may change after refinement
    public $dummyValue;
    public $objLanguage;
    public $objDbResourceTypes;
    public $objDbProductThemes;
    public $objDbProductLanguages;
    public $objThumbUploader;
    public $objDbRelationTypes;


    //TODO move catorgorized parameters into structs. eg. <creationStruct>
    //////////   Creation / description parameters   //////////

    /**Title
     *
     * @var <type>
     */
    private $_title;

    /**Alternative Title
     *
     * @var <type>
     */
    private $_alternativetitle;

    /**Content Type
     *
     * @var <type>
     */
    private $_resourcetype;

    /**Date of Creation
     *
     * @var <type>
     */
    private $_date;

    /**Language
     *
     */
    private $_language;

    /**Authors
     *
     * @var <type>
     */
    private $_creator;

    /**Publisher
     *
     * @var <type>
     */
    private $_publisher;

    /**Description
     *
     * @var <type>
     */
    private $_description;

    /**Description Abstract
     *
     * @var <type>
     */
    private $_abstract;

//    /**Description Table of Contents
//     *
//     * @var <type>
//     */
//    private $_tableOfContents;

    /**Other Contributors
     *
     * @var <type> 
     */
    private $_othercontributors;

    //////////   non-categorized parameters   //////////

    //TODO Try to Categorize these parameters

    /**UNESCO contacts
     *
     * @var <type>
     */
    private $_unescocontacts;

    /**UNESCO Themes
     *
     * @var <type>
     */
    private $_unescothemes;

    /**coverage
     *
     * @var <type>
     */
    private $_coverage;

    /**status
     *
     * @var <type>
     */
    private $_status;

    //////////   Identification parameters   //////////

    /**Identifier
     *
     * @var <type>
     */
    private $_identifier;

    /**Relation
     *
     * @var <type>
     */
    private $_relation;

    /**Relation type;
     *
     * @var <type>
     */
    private $_relationtype;

    /**keywords
     *
     * @var <type>
     */
    private $_keywords;

    /**Thumbnail
     *
     * @var <type>
     */
    private $_thumbnail;

    //////////   Product Rights and Legal Parameters   //////////

    /**Rights
     *
     * @var <type>
     */
    private $_rights;

    /**Rights Holder
     *
     * @var <type>
     */
    private $_rightsholder;

    /**Provenance
     *
     * @var <type>
     */
    private $_provenance;

    //////////   Required database objects   //////////

    /**Database for accessing products
     *
     * @var <type>
     */
    private $_objDbProducts;

    //////////   Content   //////////

    /**Variable for containing content of the product
     *
     * @var <type>
     */
    private $_content;

    //////////   Display objects   //////////

    /**Object that contains some simple display operations
     *
     * @var <type>
     */
    private $_objAddDataUtil;

    //////////   Validation objects   //////////
    var $validationArray;


    //////////   Constructor   //////////

//    public function __construct()
//    {
//
//    }


    public function  init()
    {
        parent::init();
        $this->dummyValue = "I am a product";
        $this->_objDbProducts = $this->getObject('dbproducts');
        $this->_objAddDataUtil = $this->getObject('adddatautil');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objDbResourceTypes = $this->getObject('dbresourcetypes');
        $this->objDbProductThemes = $this->getObject('dbproductthemes');
        $this->objDbProductLanguages = $this->getObject('dbproductlanguages');
        $this->objThumbUploader = $this->getObject('thumbnailuploader');
        $this->objDbRelationTypes = $this->getObject('dbrelationtype');
        $this->validationArray = array();
    }

    ////////////////   METHODS   ////////////////

    //TODO implement getters and setters for every property that needs them

    //TODO remove this method when it is no longer needed.

    /**This is a temporary method used for getting and setting properties
     *
     * @param <type> $method
     * @param <type> $arguments
     * @return <type>
     */
    function __call($method, $arguments)
    {
        $prefix = strtolower(substr($method, 0, 3));
        $property = "_".strtolower(substr($method, 3));

        if (empty($prefix) || empty($property)) {
            return;
        }

        if ($prefix == "get" && isset($this->$property)) {
            return $this->$property;
        }

        if ($prefix == "set") {
            $this->$property = $arguments[0];
        }
    }

    /**This function stores product data in the data base whether the product
     * already exists or not.
     */
    function saveProduct()
    {
        $tempData = array();

//        $sql = 'DESCRIBE tbl_unesco_oer_products';
//        $fields = $this->_objDbProducts->getArray($sql);
//
//        foreach ($fields as $field)
//        {
//            $property = '_'.preg_replace("#_#i", "", $field['field']);
//            $value = $this->$property;
//            echo $property.' : '.$value.'<br>';
//            if (isset($value))
//            {
//                $tempData[$field['field']] = $value;
//            }
//        }

        $tempData['title'] = $this->getTitle();
        $tempData['alternative_title'] = $this->getAlternativeTitle();
        $tempData['resource_type'] = $this->getContentType();
        $tempData['language'] = $this->getLanguageID();
        $tempData['description'] = $this->getDescription();
        $tempData['abstract'] = $this->getAbstract();
        //TODO $tempData['table of contents']
        $tempData['creator'] = $this->getAuthors();
        $tempData['contacts'] = $this->getContacts();
        $tempData['publisher'] = $this->getPublisher();
        //NOTE: themes are saved after an ID has been created
        //TODO $tempData['keywords']
        //TODO $tempData['related_language']
        $tempData['other_contributors'] = $this->getOtherContributers();
        //TODO format ??
        $tempData['coverage'] = $this->getCoverage();
        $tempData['rights'] = $this->getRights();
        $tempData['rights_holder'] = $this->getRightsHolder();
        $tempData['provenance'] = $this->getProvenance();
        //TODO $tempData['relation']
        //TODO $tempData['relation_type']
        $tempData['status'] = $this->getStatus();
        
        
        if ($this->getIdentifier())
        {
            $this->_objDbProducts->updateProduct($this->getIdentifier(), $tempData);
            //$this->dummyValue = "With ID: ".$tempData['title'];
        }
        else
        {
            $tempData['date'] = $this->getDate();
            $this->_objDbProducts->addProduct($tempData);
            $this->_identifier = $this->_objDbProducts->getLastInsertId();
            //$this->dummyValue = "With Out ID: ".$tempData['title'];
        }


        $this->saveThemes($this->getThemes());

        $path = 'unesco_oer/products/' . $this->_identifier . '/thumbnail/';
        $results = $this->uploadThumbNail($path);

        if ($results)
        {
            $this->_objDbProducts->updateProduct(
                                            $this->getIdentifier(),
                                            array( 'thumbnail' => 'usrfiles/'.$results['path'])
                                        );
        }
    }

    /**This function retrieves product information, given the products identifier
     * (ID)
     * @param <type> $id
     */
    function loadProduct($id)
    {
        $product = $this->_objDbProducts->getProductByID($id);
        foreach ($product as $field => $value)
        {
            $property = '_'.preg_replace("#_#i", "", $field);
            $this->$property = $value;
        }

        $this->_identifier = $product['id'];
    }

    /**This function upadates relevant fields of the product provided you use
     * showMetaDataInput as your input means and saves theme.
     *
     */
    function handleUpload()
    {
        $this->setTitle($this->getParam('title'));
        $this->setAlternativeTitle($this->getParam('alternative_title'));
        $this->setContentType($this->getParam('resource_type'));
        $this->setLanguage($this->getParam('language'));
        $this->setAuthors($this->getParam('creator'));
        $this->setPublisher($this->getParam('publisher'));
        $this->setDescription($this->getParam('description'));
        $this->setAbstract($this->getParam('abstract'));
        $this->setOtherContributers($this->getParam('other_contributors'));
        $this->setContacts($this->getParam('contacts'));
        $this->setStatus($this->getParam('status'));
        $this->setRights($this->getParam('rights'));
        $this->setRightsHolder($this->getParam('rights_holder'));
        $this->setProvenance($this->getParam('provenance'));;
        $this->setCoverage($this->getParam('coverage'));
        $this->setStatus($this->getParam('status'));

        $themesSelected = array();
        $umbrellaThemes = $this->objDbProductThemes->getUmbrellaThemes();
        foreach ($umbrellaThemes as $umbrellaTheme) {
            $themesSelected[$umbrellaTheme['id']] = $this->getParam($umbrellaTheme['id']);
        }

        $this->setThemes($themesSelected);

        $this->dummyValue = $themesSelected;
//        $this->setRelation($relation, $relationType);
//        $this->setThemes($themeIDarray);
//        $this->setKeyWords($keyWords);
        if ($this->validateMetaData()){
            $this->saveProduct();
            return TRUE;
        }
        else
        {
            return FALSE;
        }
//        $changed = FALSE;
//        $sql = 'DESCRIBE tbl_unesco_oer_products';
//        $fields = $this->_objDbProducts->getArray($sql);

        //TODO Temporary assignment loop
//        foreach ($fields as $field) {
//            $parameter = $this->getParam($field['field']);
//            if ($parameter){
//                $property = '_'.preg_replace("#_#i", "", $field['field']);
//                $this->$property = $parameter;
//                $changed = TRUE;
//            }
//        }
        
        




        //TODO assign all data memebers like this
//        $this->_unescocontacts = $this->getParam('contacts');
    }

    /**This function validates the input on product meta data input page
     *
     */
    function validateMetaData()
    {
        $valid = TRUE;

//        $sql = 'DESCRIBE tbl_unesco_oer_products';
//        $fields = $this->_objDbProducts->getArray($sql);
//
//        foreach ($fields as $field) {
//            $property = '_'.preg_replace("#_#i", "", $field['field']);
//            if (empty ($this->$property))
//            {
////                $valid = FALSE;
//            }
//        }

        foreach ($this->validationArray as $validation) {
            if (!$validation['valid'])
            {
                $valid = FALSE;
            }
        }

        $fileInfoArray = array();

        if (!$this->objThumbUploader->isFileValid($fileInfoArray))
            {
                $valid = FALSE;
                $this->addValidationMessage('thumbnail', $valid, 'A thumbnail is required');
            }

//            $this->dummyValue = $fileInfoArray;

        return $valid;
    }

    /**This function returns a display for entering product metadata
     *
     * @return string
     */
    function showMetaDataInput()
    {
        $output = '';

        // set up html elements
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('adddatautil','unesco_oer');

        //get parent if any
        $product = $this->_objDbProducts->getProductByID($this->_identifier);

        // setup and show heading
        $header = new htmlHeading();
        $header->str = $this->objLanguage->
                languageText('mod_unesco_oer_product_upload_heading', 'unesco_oer');
        $header->type = 1;
        echo $header->show();
        echo '<font face="Arial" color="#FF2222">(*) indicates fields that are required. </font>';

        /*                                              */
        /*      Identification fields, eg. title        */
        /*                                              */

        // setup table and table headings with input fields
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";


//        $table->startRow();

        //field for the title
        $fieldName = 'title';
        $title = $this->objLanguage->languageText('mod_unesco_oer_title', 'unesco_oer');
        $title .= '<font color="#FF2222">* '. $this->validationArray[$fieldName]['message']. '</font>';
//        $this->_objAddDataUtil->addTitleToRow($title, 4, $table);
//        $this->_objAddDataUtil->addTextInputToRow($fieldName, 0, $this->_title, $table);
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    '90%',
                                                    $this->_title,
                                                    $table
                                                    );

        //field for alternative title
        $fieldName = 'alternative_title';
        $title = $this->objLanguage->languageText('mod_unesco_oer_title_alternative', 'unesco_oer');
//        $this->_objAddDataUtil->addTitleToRow($title, 4, $table);
//        $this->_objAddDataUtil->addTextInputToRow($fieldName, 0, $this->_alternativetitle, $table);
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    '90%',
                                                    $this->_alternativetitle,
                                                    $table
                                                    );

        //field for the thumbnail
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail', 'unesco_oer') . '<font color="#FF2222">* '. $this->validationArray['thumbnail']['message']. '</font>');
        $table->endRow();
        $table->startRow();
        $table->addCell($this->objThumbUploader->show());
        $table->endRow();

//        $table->endRow();
        $fieldset = $this->newObject('fieldset','htmlelements');
        $fieldset->setLegend('Identity Information');
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
        $title = $this->objLanguage->languageText('mod_unesco_oer_creator', 'unesco_oer');
//        $title .= ($this->_creator ? '<font color="#000000">*</font>' : '<font color="#FF2222">*</font>');
        $title .= '<font color="#FF2222">* '. $this->validationArray[$fieldName]['message']. '</font>';
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    '90%',
                                                    $this->_creator,
                                                    $table
                                                    );

        //Field for other contributors
        $fieldName = 'other_contributors';
        $title = $this->objLanguage->languageText('mod_unesco_oer_other_contributors', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    '90%',
                                                    $this->_othercontributors,
                                                    $table
                                                    );

        //field for publisher
        $fieldName = 'publisher';
        $title = $this->objLanguage->languageText('mod_unesco_oer_publisher', 'unesco_oer');
        $title .= '<font color="#FF2222">* '. $this->validationArray[$fieldName]['message']. '</font>';
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    '90%',
                                                    $this->_publisher,
                                                    $table
                                                    );
        
        $fieldset = $this->newObject('fieldset','htmlelements');
        $fieldset->setLegend('Creators Information');
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

        //TODO Implement themes fully!
        //field for the theme
        $tableTheme = $this->newObject('htmltable', 'htmlelements');
        $tableTheme->cssClass = "moduleHeader";

        $tableTheme->startRow();
        $umbrellaThemes = $this->objDbProductThemes->getUmbrellaThemes();
        foreach ($umbrellaThemes as $umbrellaTheme) {
            $this->_objAddDataUtil->addTitleToRow($umbrellaTheme['theme'], 4 , $tableTheme);
        }
        $tableTheme->endRow();

        $tableTheme->startRow();
        $umbrellaThemes = $this->objDbProductThemes->getUmbrellaThemes();
        foreach ($umbrellaThemes as $umbrellaTheme) {
            $themes = $this->objDbProductThemes->getThemesByUmbrellaID($umbrellaTheme['id']);
            $this->_objAddDataUtil->addDropDownToRow($umbrellaTheme['id'], $themes, $this->_unescothemes[$umbrellaTheme['id']], 'theme', $tableTheme, 'id');
        }
        $tableTheme->endRow();

        $themefieldset = $this->newObject('fieldset','htmlelements');
        $themefieldset->setLegend('Themes'.'<font color="#FF2222">* '. $this->validationArray['theme']['message']. '</font>');
        $themefieldset->addContent($tableTheme->show());

//        $fieldName = 'theme';
//        $title = $this->objLanguage->languageText('mod_unesco_oer_theme', 'unesco_oer');
//        $title .= ($this->_unescothemes ? '<font color="#000000">*</font>' : '<font color="#FF2222">*</font>');
//        $productThemes = $this->objDbProductThemes->getProductThemes();
//        $this->_objAddDataUtil->addDropDownToTable(
//                                                    $title,
//                                                    4,
//                                                    $fieldName,
//                                                    $productThemes,
//                                                    $product[$fieldName],
//                                                    'description',
//                                                    $table,
//                                                    'id'
//                                                    );

         //field for the language
        $fieldName = 'language';
        $title = $this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer');
        $title .= '<font color="#FF2222">* '. $this->validationArray[$fieldName]['message']. '</font>';
        $productLanguages = $this->objDbProductLanguages->getProductLanguages();
        $this->_objAddDataUtil->addDropDownToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    $productLanguages,
                                                    $this->_language,
                                                    'code',
                                                    $table,
                                                    'id'
                                                    );

         //field for the description
        $fieldName = 'description';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '150px';
        $editor->width = '70%';
        $editor->setBasicToolBar();
        $editor->setContent($this->_description);
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->endRow();
        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

        //field description abstract
        $fieldName = 'abstract';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '150px';
        $editor->width = '70%';
        $editor->setBasicToolBar();
        $editor->setContent($this->_abstract);
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_unesco_oer_description_abstract', 'unesco_oer'));
        $table->endRow();
        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

        //TODO Implement keywords fully
        //field for keywords
        $fieldName = 'keywords';
        $title = $this->objLanguage->languageText('mod_unesco_oer_keywords', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    '90%',
                                                    $this->_keywords,
                                                    $table
                                                    );

        //field for resource type
        $fieldName = 'resource_type';
        $title = $this->objLanguage->languageText('mod_unesco_oer_resource', 'unesco_oer');
        $title .= '<font color="#FF2222">* '. $this->validationArray[$fieldName]['message']. '</font>';
        $resourceTypes = $this->objDbResourceTypes->getResourceTypes();
        $this->_objAddDataUtil->addDropDownToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    $resourceTypes,
                                                    $this->_resourcetype,
                                                    'description',
                                                    $table,
                                                    'id'
                                                    );

        $fieldset = $this->newObject('fieldset','htmlelements');
        $fieldset->setLegend('Description Information');
        $fieldset->addContent($themefieldset->show());
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
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    '90%',
                                                    $this->_rights,
                                                    $table
                                                    );

        //field for rights holder
        $fieldName = 'rights_holder';
        $title = $this->objLanguage->languageText('mod_unesco_oer_rights_holder', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    '90%',
                                                    $this->_rightsholder,
                                                    $table
                                                    );
        //field for provenance
        $fieldName = 'provenance';
        $title = $this->objLanguage->languageText('mod_unesco_oer_provenance', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    '90%',
                                                    $this->_provenance,
                                                    $table
                                                    );

        $fieldset = $this->newObject('fieldset','htmlelements');
        $fieldset->setLegend('Legal Information');
        $fieldset->addContent($table->show());
        $output .= $fieldset->show();
        /*               end of                         */
        /*         Legal fields, eg. rights             */
        /*                                              */

        /*                                              */
        /*         Misc. fields, eg. rights             */
        /*                                              */

        // setup table and table headings with input fields
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";

        //Field for Contacts
        $fieldName = 'contacts';
        $title = $this->objLanguage->languageText('mod_unesco_oer_contacts', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    '90%',
                                                    $this->_unescocontacts,
                                                    $table
                                                    );

        //field for relation types
        $fieldName = 'relation_type';
        $title = $this->objLanguage->languageText('mod_unesco_oer_relation_type', 'unesco_oer');
        $relationTypes = $this->objDbRelationTypes->getRelationTypes();
        $this->_objAddDataUtil->addDropDownToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    $relationTypes,
                                                    $this->_relationtype,
                                                    'description',
                                                    $table,
                                                    'id'
                                                    );

        //field for relation types
        $fieldName = 'relation';
        $title = $this->objLanguage->languageText('mod_unesco_oer_relation', 'unesco_oer');
        $products = $this->_objDbProducts->getAll();
        $this->_objAddDataUtil->addDropDownToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    $products,
                                                    $this->_relation,
                                                    'title',
                                                    $table,
                                                    'id'
                                                    );

        //field for coverage
        $fieldName = 'coverage';
        $title = $this->objLanguage->languageText('mod_unesco_oer_coverage', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    '90%',
                                                    $this->_coverage,
                                                    $table
                                                    );

        //TODO Implement status fully
        //Field for status
        $fieldName = 'status';
        $title = $this->objLanguage->languageText('mod_unesco_oer_status', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    '90%',
                                                    $this->_status,
                                                    $table
                                                    );

        $fieldset = $this->newObject('fieldset','htmlelements');
        $fieldset->setLegend('Misc. Information');
        $fieldset->addContent($table->show());
        $output .= $fieldset->show();
        /*               end of                         */
        /*         Misc. fields, eg. rights             */
        /*                                              */

        // setup button for submission
        $buttonSubmit = new button('submit', $this->objLanguage->
                                languageText('mod_unesco_oer_product_upload_button', 'unesco_oer'));
        $buttonSubmit->setToSubmit();

        // setup button for cancellation
        $buttonCancel = new button('submit', $this->objLanguage->
                                languageText('mod_unesco_oer_product_cancel_button', 'unesco_oer'));
        $buttonCancel->setToSubmit();

        //createform, add fields to it and display
        $uri = $this->uri(array(
                    'action' => "savetest",
                    'productID' => $this->_identifier,
                    'prevAction' => $prevAction));
        $form_data = new form('add_products_ui', $uri);
        $form_data->extra = 'enctype="multipart/form-data"';
        $form_data->addToForm($output .'<br />' . $buttonSubmit->show() . $buttonCancel->show());

        return $form_data->show();

        //TODO Date published ??
        //TODO Related Languages ??
    }

    ////////////////   sETTERS   ////////////////

    private function addValidationMessage($field, $valid, $message)
    {
        $this->validationArray[$field]['valid'] = $valid;
        $this->validationArray[$field]['message'] = $message;
    }

    function setTitle($title)
    {
        $this->_title = $title;
        if (empty($title))
        {
            $this->addValidationMessage('title', FALSE, 'Product must have a Title');
        }
        else
        {
            $this->addValidationMessage('title', TRUE, NULL);
        }

    }

    function setAlternativeTitle($alternativeTitle)
    {
        $this->_alternativetitle = $alternativeTitle;
    }

    function setContentType($resourceType)
    {
        $this->_resourcetype = $resourceType;
        if (empty($resourceType))
        {
            $this->addValidationMessage('resource_type', FALSE, 'Product must have content');
        }
        else
        {
            $this->addValidationMessage('resource_type', TRUE, NULL);
        }
    }

    private function setDate($date){
        $this->_date = $date;
    }

    function setLanguage($language)
    {
        $this->_language = $language;
        if (empty($language))
        {
            $this->addValidationMessage('language', FALSE, 'Product must have a language');
        }
        else
        {
            $this->addValidationMessage('language', TRUE, NULL);
        }
    }

    function setAuthors($creator)
    {
        $this->_creator = $creator;
        if (empty($creator))
        {
            $this->addValidationMessage('creator', FALSE, 'Product must have an Author');
        }
        else
        {
            $this->addValidationMessage('creator', TRUE, NULL);
        }
    }

    function setPublisher($publisher)
    {
        $this->_publisher = $publisher;
        if (empty($publisher))
        {
            $this->addValidationMessage('publisher', FALSE, 'Product must have an Publisher');
        }
        else
        {
            $this->addValidationMessage('publisher', TRUE, NULL);
        }
    }

    function setDescription($description)
    {
        $this->_description = $description;
    }

    function setAbstract($abstract)
    {
        $this->_abstract = $abstract;
    }

    function setOtherContributers($contributors)
    {
        $this->_othercontributors = $contributors;
    }

    function setContacts($contacts)
    {
        $this->_unescocontacts = $contacts;
    }

    function saveThemes($themeIDarray)
    {
        if($themeIDarray != NULL && !is_array($themeIDarray)){
            $themeIDarray = array($themeIDarray);
        }

        foreach ($themeIDarray as $themeID) {
            if ($themID != NULL){
                $this->objDbProductThemes->addProductThemeJxn($this->_identifier, $themeID);
            }
        }

    }

    function setThemes($themeIDarray)
    {
        $this->_unescothemes = $themeIDarray;

        $flag  = TRUE;
        foreach ($themeIDarray as $theme)
        {
            if (empty($theme))
            {
                $flag = FALSE;
            }
        }
        if ($flag)
        {
            $this->addValidationMessage('theme', TRUE, NULL);
        }
        else
        {
            $this->addValidationMessage('theme', FALSE, 'Product must have at least one Theme');
        }
    }

   function setCoverage($coverage)
   {
       $this->_coverage = $coverage;
   }

   function setStatus($status)
   {
       $this->_status = $status;
   }

   private function setIdentifier($id)
   {
       $this->_identifier = $id;
   }

   function setRelation($relation, $relationType)
   {
       $this->_relation = $relation;
       $this->_relationtype = $relationType;
   }

   function setKeyWords($keyWords) 
   {
       
   }
   
   function uploadThumbNail($path)
   {
       $result = FALSE;
       try {
            $results = $this->objThumbUploader->uploadThumbnail($path);
        } catch (customException $e) {
            echo customException::cleanUp();
            exit();
        }
        
        return $results;
   }

   function setRights($rights)
   {
       $this->_rights = $rights;
   }

   function setRightsHolder($rightsholder)
   {
       $this->_rightsholder = $rightsholder;
   }

   function setProvenance($provenance)
   {
       $this->_provenance = $provenance;
   }

   function setContent($content)
   {
       $this->_content = $content;
   }

   ////////////////   GETTERS   ////////////////

    function getTitle()
    {
        return $this->_title;
    }

    function getAlternativeTitle()
    {
        return $this->_alternativetitle;
    }

    function getContentType()
    {
        return $this->_resourcetype;
    }

    function getProductDate(){
        return $this->_date;
    }

    private function getDate(){
        return $this->_objDbProducts->now();
    }

    private function getLanguageID()
    {
        return $this->_language;
    }

    function getLanguageDescription()
    {
        return $this->objLanguage->getLanguageDescriptionByID( $this->getLanguageID() );
    }

    function getAuthors()
    {
        return $this->_creator;
    }

    function getPublisher()
    {
        return $this->_publisher;
    }

    function getDescription()
    {
        return $this->_description;
    }

    function getAbstract()
    {
        return $this->_abstract;
    }

    function getOtherContributers()
    {
        return $this->_othercontributors;
    }

    function getContacts()
    {
        return $this->_unescocontacts;
    }

    private function loadThemes()
    {
        $themeIDarray = array();

        foreach ($this->objDbProductThemes-> getThemesByProductID($this->getIdentifier()) as $theme) {
            $themeIDarray[$theme['umbrella_theme_id']][$theme['id']];
        }

        return $this->setThemes($themeIDarray);

    }

    function getThemes()
    {
        return $this->_unescothemes;
    }

   function getCoverage()
   {
       return $this->_coverage;
   }

   function getStatus()
   {
       return $this->_status;
   }

   function getIdentifier()
   {
       return $this->_identifier;
   }

   function getRelation()
   {
       $relation = $this->_relation;
       $relationType = $this->_relationtype;

       return array($relation, $relationType);
   }

   function getKeyWords()
   {

   }

   function getThumbnailPath()
   {
       return $this->$_thumbnail;
   }

   function getRights()
   {
       return $this->_rights;
   }

   function getRightsHolder()
   {
       return $this->_rightsholder;
   }

   function getProvenance()
   {
       return $this->_provenance;
   }

   function getContent()
   {
       return $this->_content;
   }

}

?>
