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
    private $_othercontributers;

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

        $sql = 'DESCRIBE tbl_unesco_oer_products';
        $fields = $this->_objDbProducts->getArray($sql);

        foreach ($fields as $field)
        {
            $property = '_'.preg_replace("#_#i", "", $field['field']);
            $value = $this->$property;
            echo $property.' : '.$value.'<br>';
            if (isset($value))
            {
                $tempData[$field['field']] = $value;
            }
        }

        if ($this->getIdentifier())
        {
            $this->_objDbProducts->updateProduct($this->getIdentifier(), $tempData);
            //$this->dummyValue = "With ID: ".$tempData['title'];
        }
        else
        {
            $tempData['date'] = $this->_objDbProducts->now();
            $this->_objDbProducts->addProduct($tempData);
            $this->_identifier = $this->_objDbProducts->getLastInsertId();
            //$this->dummyValue = "With Out ID: ".$tempData['title'];
        }

        if ($this->objThumbUploader->results)
        {
            $this->_objDbProducts->updateProduct(
                                            $this->getIdentifier(),
                                            array( 'thumbnail' => 'usrfiles/'.$this->objThumbUploader->results['path'])
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
        $changed = FALSE;
        $sql = 'DESCRIBE tbl_unesco_oer_products';
        $fields = $this->_objDbProducts->getArray($sql);

        foreach ($fields as $field) {
            $parameter = $this->getParam($field['field']);
            if ($parameter){
                $property = '_'.preg_replace("#_#i", "", $field['field']);
                $this->$property = $parameter;
                $changed = TRUE;
            }
        }

        $results = FALSE;
        $path = 'unesco_oer/products/' . $this->_identifier . '/thumbnail/';
        try {
            $results = $this->objThumbUploader->uploadThumbnail($path);
        } catch (customException $e) {
            echo customException::cleanUp();
            exit();
        }

        if ($changed || $results){
            $this->saveProduct();
        }
    }

    /**This function validates the input on product meta data input page
     *
     */
    function validateMetaData()
    {
        $valid = TRUE;

        $sql = 'DESCRIBE tbl_unesco_oer_products';
        $fields = $this->_objDbProducts->getArray($sql);

        foreach ($fields as $field) {
            $property = '_'.preg_replace("#_#i", "", $field['field']);
            if (empty ($this->$property))
            {
                $valid = false;
            }
        }

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

        // setup table and table headings with input fields
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";

        //field for the title
        $fieldName = 'title';
        $title = $this->objLanguage->languageText('mod_unesco_oer_title', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    0,
                                                    $this->_title,
                                                    $table
                                                    );

        //field for alternative title
        $fieldName = 'alternative_title';
        $title = $this->objLanguage->languageText('mod_unesco_oer_title_alternative', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    0,
                                                    $this->_alternativetitle,
                                                    $table
                                                    );

        //TODO Date published ??
        
        //Field for Authors
        $fieldName = 'creator';
        $title = $this->objLanguage->languageText('mod_unesco_oer_creator', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    0,
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
                                                    0,
                                                    $this->_othercontributers,
                                                    $table
                                                    );

        //field for publisher
        $fieldName = 'publisher';
        $title = $this->objLanguage->languageText('mod_unesco_oer_publisher', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    0,
                                                    $this->_publisher,
                                                    $table
                                                    );

        
        //Field for Contacts
        $fieldName = 'contacts';
        $title = $this->objLanguage->languageText('mod_unesco_oer_contacts', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    0,
                                                    $this->_unescocontacts,
                                                    $table
                                                    );

        //TODO Implement themes fully!
        //field for the theme
        $fieldName = 'theme';
        $title = $this->objLanguage->languageText('mod_unesco_oer_theme', 'unesco_oer');
        $productThemes = $this->objDbProductThemes->getProductThemes();
        $this->_objAddDataUtil->addDropDownToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    $productThemes,
                                                    $product[$fieldName],
                                                    'description',
                                                    $table,
                                                    'id'
                                                    );

         //field for the language
        $fieldName = 'language';
        $title = $this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer');
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

        //TODO Related Languages ??

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
                                                    0,
                                                    $this->_coverage,
                                                    $table
                                                    );

        //field for rights
        $fieldName = 'rights';
        $title = $this->objLanguage->languageText('mod_unesco_oer_rights', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                                                    $title,
                                                    4,
                                                    $fieldName,
                                                    0,
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
                                                    0,
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
                                                    0,
                                                    $this->_provenance,
                                                    $table
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
                                                    0,
                                                    $this->_keywords,
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
                                                    0,
                                                    $this->_keywords,
                                                    $table
                                                    );

        //field for resource type
        $fieldName = 'resource_type';
        $title = $this->objLanguage->languageText('mod_unesco_oer_resource', 'unesco_oer');
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

        //field for the thumbnail
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail', 'unesco_oer'));
        $table->addCell($this->objThumbUploader->show());
        $table->endRow();

        //TODO attach file ??

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
        $form_data->addToForm($table->show() . '<br />' . $buttonSubmit->show() . $buttonCancel->show());
        $output = $form_data->show();

        return $output;
    }

}

?>
