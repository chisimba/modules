<?php

//require('classes/googlemapapi_class_inc.php');

class unesco_oer extends controller {

    public $objProductUtil;
    public $objInstitutionGUI;
    public $objDbProducts;
    public $objDbResourceTypes;
    public $objDbProductThemes;
    public $objDbProductLanguages;
    public $objDbFeaturedProduct;
    public $objFeaturedProducUtil;
    public $objDbGroups;
    public $objDbInstitution;
    public $objInstitutionManager;
    public $objDbInstitutionTypes;
    public $objDbCountries;
    public $objDbLinkedGroups;
    public $objDbComments;
    public $objDbProductRatings;
    public $objGoogleMap;
    public $objDbAvailableProductLanguages;
    public $objUseExtra;
    public $objfilterdisplay;
    public $objbookmarkmanager;
    public $objDbRelationType;
    public $objDbProductKeywords;
    public $objDbProductStatus;
    public $objjavafilt;
    public $objThumbUploader;
    public $objConfig;
    /**
     * @var object $objLanguage Language Object
     */
    public $objLanguage;
    /**
     * @var object $objUserAdmin User Administration \ Object
     */
    public $objUserAdmin;
    /**
     * @var object $objUser User Object Object
     */
    public $objUser;

    /**
     *
     * @var string $objLanguage String object property for holding the
     * language object
     * @access public
     *
     */
    function init() {
        //session_start();
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objfilterdisplay = $this->getobject('filterdisplay', 'unesco_oer');
        $this->objProductUtil = $this->getObject('productutil');
        $this->objDbProducts = $this->getObject('dbproducts');
        $this->objDbResourceTypes = $this->getObject('dbresourcetypes');
        $this->objDbProductThemes = $this->getObject('dbproductthemes');
        $this->objDbProductLanguages = $this->getObject('dbproductlanguages');
        $this->objDbAvailableProductLanguages = $this->getObject('dbavailableproductlanguages');
        $this->objDbFeaturedProduct = $this->getObject('dbfeaturedproduct');
        $this->objFeaturedProducUtil = $this->getObject('featuredproductutil');
        $this->objDbProductRatings = $this->getObject('dbproductratings');
        $this->objDbGroups = $this->getObject('dbgroups');
        $this->objDbInstitution = $this->getObject('dbinstitution');
        $this->objDbInstitutionTypes = $this->getObject('dbinstitutiontypes');
        $this->objInstitutionManager = $this->getObject('institutionmanager');
        $this->objDbLinkedGroups = $this->getObject('dblinkedgroups');
        $this->objDbCountries = $this->getObject('dbcountries');
        $this->objDbComments = $this->getObject('dbcomments');
        $this->objProductRatings = $this->getObject('dbproductratings');
        $this->objUseExtra = $this->getObject('dbuserextra');
        $this->objDbRelationType = $this->getObject('dbrelationtype');
        $this->objDbProductStatus = $this->getObject('dbproductstatus');
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->objUserAdmin = $this->getObject('useradmin_model2', 'security');
        $this->objUser = $this->getObject('user', 'security');
        $this->objUrl = $this->getObject('url', 'strings');
        $this->objDbProductKeywords = $this->getObject('dbproductkeywords');
        $this->objjavafilt = $this->getObject('javafilt');
        $this->objThumbUploader = $this->getObject('thumbnailuploader');
        $this->objbookmarkmanager = $this->getObject('bookmarkmanager');
        //$this->objUtils = $this->getObject('utilities');
        //$this->objGoogleMap=$this->getObject('googlemapapi');
        //$this->objGoogleMap = new googlemapapi();
    }

    /**
     * Standard Dispatch Function for Controller
     * @param <type> $action
     * @return <type>
     */
    public function dispatch($action) {
        /*
         * Convert the action into a method (alternative to
         * using case selections)
         */
        $method = $this->getMethod($action);
        /*
         * Return the template determined by the method resulting
         * from action
         */
        return $this->$method();
    }

    /**
     *
     * Method to convert the action parameter into the name of
     * a method of this class.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return string the name of the method
     *
     */
    function getMethod(& $action) {
        if ($this->validAction($action)) {
            return '__' . $action;
        } else {
            return '__home';
        }
    }

    /**
     *
     * Method to check if a given action is a valid method
     * of this class preceded by double underscore (__). If it __action
     * is not a valid method it returns FALSE, if it is a valid method
     * of this class it returns TRUE.
     *
     * @access private
     * @param string $action The action parameter passed byref
     * @return boolean TRUE|FALSE
     *
     */
    function validAction(& $action) {
        if (method_exists($this, '__' . $action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Method to show the Home Page of the Module
     */
    public function __home() {
//        $displayAllMostAdaptedProducts = false;s', $displayAllMostAdaptedProducts
//        $this->setVarByRef('displayAllMostAdaptedProducts', $displayAllMostAdaptedProducts);

        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return "1a_tpl.php";
    }

    public function __1b() {
        return "1b_tpl.php";
    }

    public function __2a() {
        return "2a_tpl.php";
    }

    public function __2b() {
        return "2b_tpl.php";
    }

    public function __3a() {
        return "3a_tpl.php";
    }
    
    
    public function __Bookmarks() {
        return "Bookmarks_tpl.php";
    }
    
    public function __deleteBookmarks() {
        
          $users = $this->getParam("selectedusers");
          $userid = $this->objUser->userId(); 
         $bookmark = $this->objbookmarkmanager->deletebookmark($users);
             
           $this->setVarByRef("users", $users);
        
        
        return "Bookmarks_tpl.php";
    }


    public function __4() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        $institutionId = $this->getParam('institutionId');
        $this->setVarByRef('institutionId', $institutionId);

        return "4_tpl.php";
    }

    public function __5a() {
        return "5a_tpl.php";
    }

//    public function __5a()
//    {
//        return "5a_tpl.php";
//    }


    public function __8a() {
        return "8a_tpl.php";
    }

    public function __8b() {
        return "8b_tpl.php";
    }

    public function __10() {
        return "10_tpl.php";
    }

    public function __11a() {
        return "11a_tpl.php";
    }

    public function __11b() {
        return "11b_tpl.php";
    }

    public function __11c() {
        return "11c_tpl.php";
    }

    public function __JavaFilter() {

        return "JavaFilter_tpl.php";
       
    }

    
    public function __JavaFilternum() {



        $temp = $this->objjavafilt->displayprods();
        echo $temp;
        
           

        die();
    }

    public function __BookmarkData() {
          echo "ttttttttttttttttttttttttt";
        $label = $this->getParam('Title');
        $description = $this->getParam('Description');
        $time = $this->getParam('time');
        $url = $this->getParam('location');
         $parentid = $this->getParam('parentid');
          $userid = $this->getParam('userid');
    

        $this->objbookmarkmanager->addbookmark($label, $description, $url,$parentid,$userid);
        
        die();
    }

    
    
    public function __PaginationFilter() {


        return "paginationfilter_tpl.php";
    }

    public function __ViewProduct() {

        $id = $this->getParam('id');

        $this->setVarByRef('productID', $id);

        if ($this->objDbProducts->isAdaptation($id)) {
            $this->setLayoutTemplate('5a_layout_tpl.php');
            return "5a_tpl.php";
        } else {
            $this->setLayoutTemplate('3a_layout_tpl.php');
            return "3a_tpl.php";
        }
    }

    public function __viewAllMostAdaptedProducts() {

        $displayAllMostAdaptedProducts = $this->getParam('displayAllMostAdaptedProducts');
        $this->setVarByRef('displayAllMostAdaptedProducts', $displayAllMostAdaptedProducts);
        return "1a_tpl.php";
    }

    public function __Search() {

        $SearchField = $this->getParam('SearchInput');
        $SearchOption = $this->getParam('SearchFilter');

        if ($SearchOption == 'Date')
            $SearchOptionString = 'created_on';
        else
            $SearchOptionString = $SearchOption;

        $page = $this->getParam('page');



        $Buildstring = $SearchOptionString . ' Like ' . "'%" . $SearchField . "%'";
        $totalentries = $Buildstring;








        $this->setVarByRef("finalstring", $Buildstring);
        $this->setVarByRef("TotalEntries", $totalentries);
        $this->setVarByRef("SearchField", $SearchField);
        $this->setVarByRef("SearchOption", $SearchOption);








        return "results_tpl.php";
    }

    public function __BrowseAdaptation() {

        $lat = $this->getParam('lat');
        $lng = $this->getparam('Lng');
        $page = $this->getParam('page');



        $AuthFilter = $this->getParam('AuthorFilter');
        $ThemeFilter = $this->getParam('ThemeFilter');
        $LangFilter = $this->getParam('LanguageFilter');

        $sort = $this->getParam('SortFilter');
        $NumFilter = $this->getParam('NumFilter');
        $PageNum = $this->getParam('PageNum');
        $TotalPages = $this->getParam('TotalPages');
        $adaptationstring = $this->getParam('adaptationstring');
        $Model = $this->getParam('Model');
        $Handbook = $this->getParam('Handbook');
        $Guide = $this->getParam('Guide');
        $Manual = $this->getParam('Manual');
        $Besoractile = $this->getParam('Besoractile');

        $latShort = round($lat, 3);
        $lngshort = round($lng, 3);
        $ProdId = $this->objProductUtil->BrowseAdaptation($latShort, $lngshort);
        $string = $this->objDbProducts->getAdaptedProducts($ProdId);


        $temporary = $string[0]['name'];
        $Buildstring = 'creator = ' . "'$temporary'" . ' and relation is not null';







        $this->setVarByRef("AuthFilter", $AuthFilter);
        $this->setVarByRef("temp", $ProdId);
        $this->setVarByRef("ThemeFilter", $ThemeFilter);
        $this->setVarByRef("LangFilter", $LangFilter);
        $this->setVarByRef("SortFilter", $sort);
        $this->setVarByRef("NumFilter", $NumFilter);
        $this->setVarByRef("PageNum", $PageNum);
        $this->setVarByRef("TotalPages", $TotalPages);
        $this->setVarByRef("Model", $Model);
        $this->setVarByRef("Guide", $Guide);
        $this->setVarByRef("Handbook", $Handbook);
        $this->setVarByRef("Manual", $Manual);
        $this->setVarByRef("Besoractile", $Besoractile);
        $this->setVarByRef("adaptationstring", $adaptationstring);



        $this->setVarByRef("finalstring", $Buildstring);
        $this->setVarByRef("TotalEntries", $Buildstring);
        $this->setVarByRef("MapEntries", $Buildstring);


        return "$page";
    }

    public function __FilterAdaptations() {

        $parentid = $this->getParam('parentid');
        $adaptationstring = 'relation = ' . "'$parentid'";

        $TotalEntries = $this->objProductUtil->FilterTotalProducts($AuthFilter, $ThemeFilter, $LangFilter, $page, $sort, $TotalPages, $adaptationstring, $Model, $Handbook, $Guide, $Manual, $Besoractile);
        $Buildstring = $this->objProductUtil->FilterAllProducts($NumFilter, $PageNum, $TotalEntries);


        $this->setVarByRef("finalstring", $Buildstring);
        $this->setVarByRef("TotalEntries", $TotalEntries);
        $this->setVarByRef("adaptationstring", $adaptationstring);




        return "2a_tpl.php";
    }

    public function __FilterProducts() {


        $page = $this->getParam('page');
        $sort = $this->getParam('SortFilter');
        $NumFilter = $this->getParam('NumFilter');
        $PageNum = $this->getParam('PageNum');
        $TotalPages = $this->getParam('TotalPages');
        $adaptationstring = $this->getParam('adaptationstring');
        $browsemapstring = $this->getParam('MapEntries');

        $TotalEntries = $this->objfilterdisplay->FilterTotalProducts($page, $sort, $TotalPages, $adaptationstring, $browsemapstring);
        $Buildstring = $this->objfilterdisplay->FilterAllProducts($NumFilter, $PageNum, $TotalEntries);




        $this->setVarByRef("SortFilter", $sort);
        $this->setVarByRef("NumFilter", $NumFilter);
        $this->setVarByRef("PageNum", $PageNum);
        $this->setVarByRef("TotalPages", $TotalPages);
        $this->setVarByRef("finalstring", $Buildstring);
        $this->setVarByRef("TotalEntries", $TotalEntries);
        $this->setVarByRef("MapEntries", $browsemapstring);



        $this->setLayoutTemplate('maincontent_layout_tpl.php');

        return "$page";
    }

    public function requiresLogin($action) {

        if($action == null){
            return FALSE;
        }
        $required = array('filterproducts','viewproduct');
        
        if (in_array($action, $required)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /*
     * Method to display page for creating a new product
     */

    public function __productsUi() {
        return "products_ui_tpl.php";
    }

    /*
     * Method to display page for selecting input option
     */

    public function __controlpanel() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return "controlpanel_tpl.php";
    }

    public function __adddata() {
        return "addData_tpl.php";
    }

    public function __editusers() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return "controlpanel_tpl.php";
    }

    public function __editgroups() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return "controlpanel_tpl.php";
    }

    public function __editinstitutions() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return "controlpanel_tpl.php";
    }

    /*
     * Method to display page with adaptable products
     */

    public function __chooseProductToAdapt() {
        return 'newAdaptation_tpl.php';
    }

    public function __createProduct() {
        $id = $this->getParam('id');
        $prevAction = $this->getParam('prevAction');
        $isNewProduct = TRUE;

        $this->setVar('productID', $id);
        $this->setVar('prevAction', $prevAction);
        $this->setVar('isNewProduct', $isNewProduct);

        $this->setLayoutTemplate('1a_layout_tpl.php');

        return $this->__productsUi();
    }

    public function __editProduct() {
        $id = $this->getParam('id');
        $prevAction = $this->getParam('prevAction');
        $isNewProduct = FALSE;

        $this->setVar('productID', $id);
        $this->setVar('prevAction', $prevAction);
        $this->setVar('isNewProduct', $isNewProduct);

        if ($this->objDbProducts->isAdaptation($id)) {
            $this->setLayoutTemplate('5a_layout_tpl.php');
        } else {
            $this->setLayoutTemplate('3a_layout_tpl.php');
        }
        //$this->setLayoutTemplate('1a_layout_tpl.php');

        return $this->__productsUi();
    }

    /*
     * Method to retrieve entries from user on the tbl_products_ui_tpl.php page
     * and add it to the tbl_unesco_oer_products table
     */

    public function __uploadSubmit() {
        //Retrieve thumbnail and save it
        $isNewProduct = $this->getParam('isNewProduct');
        if ($isNewProduct === NULL)
            throw new customException('Product state is not specified');
        $parentID = $this->getParam('parentID');
        $thumbnailPath = '';
        $results = FALSE;
        /*
         * This conditional statement only allows the uploading of a new thumbnail
         * for a new original product or the editing of a original product.
         * Firstly it tests if the product is an adpaptation. If it isn't then
         * it is a new original product only if the the parameter @isNewProduct
         * is true and parameter @parentID is NULL. It is an existing original product
         * only if the parameter @parentID is not NULL and the parameter @isNewProduct
         * is false
         */
        if (!$this->objDbProducts->isAdaptation($parentID) && ($isNewProduct == ($parentID == null))) {
            $path = 'unesco_oer/products/' . $this->getParam('title') . '/thumbnail/';
            try {
                $results = $this->uploadFile($path);
            } catch (customException $e) {
                // echo customException::cleanUp();
                echo "test";
                exit();
            }
        }
        if ($results) {
            $thumbnailPath = 'usrfiles/' . $results['path'];
        } else {
            $product = $this->objDbProducts->getProductByID($parentID);
            $thumbnailPath = $product['thumbnail'];
        }

        //Determine and Validate the creator
        $institutionCount = $this->objDbInstitution->isInstitution($this->getParam('institution'));
        $groupCount = $this->objDbGroups->isGroup($this->getParam('group'));
        $creatorName = '';
        if (($institutionCount > 1) || ($groupCount > 1)) {
            throw new customException('Group or institution has duplicate name');
        }
        if ($groupCount == 1) {
            $creatorName = $this->getParam('group');
        } else {
            if ($institutionCount == 1)
                $creatorName = $this->getParam('institution');
            else
                throw new customException('No group or institution specified : ' . $this->getParam('group'));
        }

        //create array for uploading into data base
        $data = array(
            'title' => $this->getParam('title'),
            'creator' => $creatorName,
            'keywords' => trim($this->getParam('keywords')),
            'description' => $this->getParam('description'),
            'created_on' => $this->objDbProducts->now(),
            'resource_type' => $this->getParam('resourceType'),
            'content_type' => NULL, //TODO find out about this data type
            'format' => NULL, //TODO find out about this data type
            'source' => NULL, //TODO this must be removed
            'theme' => $this->getParam('theme'),
            'language' => $this->getParam('language'),
            'content' => NULL,
            'thumbnail' => $thumbnailPath
        );

        //determine if a new product must be added or an old one must be updated
        if ($isNewProduct) {
            $data = array_merge($data, array('relation' => $parentID));
            $this->objDbProducts->addProduct($data);
        } else {
            $this->objDbProducts->updateProduct($parentID, $data);
        }

        return $this->nextAction($this->getParam('prevAction'));
    }

    /*
     * Method to display page for creating a new resource type
     */

    public function __newResourceTypeUI() {
        return "newResourceTypeUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the newResourceTypeUI_tpl.php page
     * and add it to the tbl_unesco_oer_resource_types table
     */

    public function __resourceTypeSubmit() {
        $description = $this->getParam('newTypeDescription');
        $table = $this->getParam('newTypeTable');
        $this->objDbResourceTypes->addType($description, $table);
        return $this->__addData();
    }

    /*
     * Method to display page for creating a new theme
     */

    public function __createThemeUI() {
        return "createThemeUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createThemeUI_tpl.php page
     * and add it to the tbl_unesco_oer_product_themes table
     */

    public function __createThemeSubmit() {
        $description = $this->getParam('newTheme');
        $umbrella = $this->getParam('umbrellatheme');
        $this->objDbProductThemes->addTheme($description, $umbrella);
        return $this->__addData();
    }

    /*
     * Method to display page for creating a new theme
     */

    public function __createUmbrellaThemeUI() {
        return "createUmbrellaThemeUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createThemeUI_tpl.php page
     * and add it to the tbl_unesco_oer_product_themes table
     */

    public function __createUmbrellaThemeSubmit() {
        $description = $this->getParam('newUmbrellaTheme');
        $this->objDbProductThemes->addUmbrellaTheme($description);
        return $this->__addData();
    }

    /*     * Method to display page for creating a new keyword
     *
     * @return <type>
     */

    public function __createKeywordUI() {
        return "createKeywordUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createKeywordUI_tpl.php page
     * and add it to the tbl_unesco_oer_product_keywords table
     */

    public function __createKeywordSubmit() {
        $keyword = $this->getParam('newKeyword');
        $this->objDbProductKeywords->addKeyword($keyword);
        return $this->__addData();
    }

    /*
     * Method to display page for creating a new theme
     */

    public function __createLanguageUI() {
        return "createLanguageUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createThemeUI_tpl.php page
     * and add it to the tbl_unesco_oer_product_themes table
     */

    public function __createLanguageSubmit() {
        $code = $this->getParam('newLanguageCode');
        $name = $this->getParam('newLanguageName');
        if (strlen($code) == 0)
            $code = $name;

        $this->objDbProductLanguages->addLanguage($code, $name);
        return $this->__addData();
    }

    /*
     * Method to retrieve the current featured unesco product from user on the featuredProductUI_tpl.php
     * return a page 1a_tpl.php with the current featured product
     */

    public function __createFeaturedProduct() {

        $featuredproduct = $this->getParam('id');
        $this->objDbFeaturedProduct->overRightCurrentFeaturedProduct($featuredproduct);
        return "1a_tpl.php";
    }

    /*
     * Method to retrieve the current featured unesco product from user on the featuredAdaptationUI_tpl.php
     * return a page 1a_tpl.php with the current featured product
     */

    public function __createFeaturedAdaptation() {
        $featuredproduct = $this->getParam('id');
        $this->objDbFeaturedProduct->overRightCurrentFeaturedAdaptation($featuredproduct);
        return "1a_tpl.php";
    }

    /*
     * method to dispaly page to create a new unesco featured product
     */

    public function __featuredProductUI() {
        return "featuredProductUI_tpl.php";
    }

    /*
     * method to dispaly page to create a new unesco featured product
     */

    public function __featuredAdaptationUI() {
        return "featuredAdaptationUI_tpl.php";
    }

    /*
     * Method to display page for creating a new group
     */

    public function __createGroupUI() {
        return "createGroupUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createGroupUI_tpl.php page
     * and add it to the tbl_unesco_oer_group table
     */

    public function __createGroupSubmit() {
        $name = $this->getParam('newGroup');
        $loclat = $this->getParam('loclat');
        $loclong = $this->getParam('loclong');
        $country = $this->getParam('country');
        $path = 'unesco_oer/groups/' . $name . '/thumbnail/';
        try {
            $results = $this->uploadFile($path);
        } catch (Exception $e) {
            //     echo customException::cleanUp();
            echo "test";
            exit();
        }
        $thumbnailPath = 'usrfiles/' . $results['path'];

        $this->objDbGroups->addGroup($name, $loclat, $loclong, $thumbnailPath, $country);
        return $this->__addData();
    }

    /*
     * Method to display page for creating a new group
     */

    public function __createInstitutionUI() {
        return "createInstitutionUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createGroupUI_tpl.php page
     * and add it to the tbl_unesco_oer_group table
     */

    public function __createInstitutionSubmit() {
        $name = $this->getParam('name');
        $description = $this->getParam('description');
        $type = $this->getParam('type');
        $country = $this->getParam('country');
        $address1 = $this->getParam('address1');
        $address2 = $this->getParam('address2');
        $address3 = $this->getParam('address3');
        $zip = $this->getParam('zip');
        $city = $this->getParam('city');
        $websiteLink = $this->getParam('websiteLink');
        $keyword1 = $this->getParam('keyword1');
        $keyword2 = $this->getParam('keyword2');

        //Form related data members
        $formAction = 'createInstitutionSubmit';
        $formError = false;
        $this->setVarByRef('formError', $formError);

        $path = 'unesco_oer/institutions/' . $name . '/thumbnail/';
        try {
            $results = $this->uploadFile($path);
        } catch (customException $e) {
            echo customException::cleanUp();
            exit();
        }
        $thumbnail = 'usrfiles/' . $results['path'];

        $validate = $this->objInstitutionManager->validate($name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail);
        $fileInfoArray = array();
        if(!$this->objThumbUploader->isFileValid($fileInfoArray)){
        $validate['valid'] = $this->objThumbUploader->isFileValid($fileInfoArray);
        $validate['thumbnail'] = "Please provide a valid thumbnail";
        }

        if ($validate['valid']) {

            $this->setLayoutTemplate('maincontent_layout_tpl.php');
            $this->objInstitutionManager->addInstitution($name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail);

            return "viewInstitutions_tpl.php";
        } else {

            //There has been an error, go back to the form to fix it
            $formError = TRUE;
            $this->setVarByRef('formError', $formError);

            $this->setVarByRef('name', $name);
            $this->setVarByRef('description', $description);
            $this->setVarByRef('type', $type);
            $this->setVarByRef('country', $country);
            $this->setVarByRef('address1', $address1);
            $this->setVarByRef('address2', $address2);
            $this->setVarByRef('address3', $address3);
            $this->setVarByRef('zip', $zip);
            $this->setVarByRef('city', $city);
            $this->setVarByRef('websiteLink', $websiteLink);
            $this->setVarByRef('keyword1', $keyword1);
            $this->setVarByRef('keyword2', $keyword2);
            $this->setVarByRef('formAction', $formAction);
            $this->setVarByRef('errorMessage', $validate);

            $this->setLayoutTemplate('maincontent_layout_tpl.php');
            return "institutionEditor_tpl.php";
        }
    }

    /*
     * Method to display page for creating a new institution type
     */

    public function __createInstitutionTypeUI() {
        return "createInstitutionTypeUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createGroupUI_tpl.php page
     * and add it to the tbl_unesco_oer_group table
     */

    public function __createInstitutionTypeSubmit() {
        $name = $this->getParam('newInstitutionType');

        $this->objDbInstitutionTypes->addType($name);
        return $this->__addData();
    }

    private function uploadFile($path) {
        $uploadedFile = $this->getObject('uploadinput', 'filemanager');
        $uploadedFile->enableOverwriteIncrement = TRUE;
        $uploadedFile->customuploadpath = $path;
        $results = $uploadedFile->handleUpload($this->getParam('fileupload'));
        //Test if file was successfully uploaded
        // Technically, FALSE can never be returned, this is just a precaution
        // FALSE means there is no fileinput with that name
        if ($results == FALSE) {
            //TODO return proper error page
            throw new customException('Upload failed: FATAL <br />');
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

    /*
     * Method to display page with products to comment on
     */

    public function __chooseProductToComment() {
        return 'chooseComment_tpl.php';
    }

    /*
     * Method to display page with products to rate
     */

    public function __chooseProductToRate() {
        return 'chooseProductToRate_tpl.php';
    }

    /*
     * Method to display page with entry options for comment
     */

    public function __createComment() {
        $id = $this->getParam('id');
        $this->setVar('productID', $id);
        return 'createComment_tpl.php';
    }

    /*
     * Method to display page with entry options for rating
     */

    public function __addProductRating() {
        $id = $this->getParam('id');
        $this->setVar('productID', $id);
        return 'addProductRating_tpl.php';
    }

    /*
     * Method to retrieve entries from user on the createGroupUI_tpl.php page
     * and add it to the tbl_unesco_oer_group table
     */

    public function __createCommentSubmit() {
        $id = $this->getParam('id');
        $comment = $this->getParam('newComment');
        $nextPage = $this->getParam('pageName');

        $this->objDbComments->addComment($id, $this->objUser->fullname(), $comment);

        if ($nextPage != NULL) {
            $this->nextAction($nextPage, array('id' => $id));
        } else {
            return $this->__addData();
        }
    }

    /*
     * Method to retrieve entries from user on the addProductRating_tpl.php page
     * and add it to the tbl_unesco_oer_products_ratings table
     */

    public function __addProductRatingSubmit() {
        $id = $this->getParam('id');
        $rating = $this->getParam('newRating');

        $this->objDbProductRatings->addRating($id, $rating);
        return $this->__addData();
    }

    /*
     * Method to retrieve entries from user on the addProductRating_tpl.php page
     * and add it to the tbl_unesco_oer_products_ratings table
     */

    public function __submitProductRating() {
        $id = $this->getParam('id');
        $rating = $this->getParam("rateSubmit");

        $this->objDbProductRatings->addRating($id, $rating);
        return $this->nextAction($this->getParam('prevAction'), array('id' => $id));
    }

    public function __deleteInstitution() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        $institutionId = $this->getParam('institutionId');

        $this->objDbInstitution->deleteInstitution($institutionId);
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return 'viewInstitutions_tpl.php';
    }

    public function __cancelEditInstitution() {
        return 'addData_tpl.php';
    }

    public function __editInstitutionSubmit() {
        $institutionId = $this->getParam('institutionId');
        $name = $this->getParam('name');
        $description = $this->getParam('description');
        $type = $this->getParam('type');
        $country = $this->getParam('country');
        $address1 = $this->getParam('address1');
        $address2 = $this->getParam('address2');
        $address3 = $this->getParam('address3');
        $zip = $this->getParam('zip');
        $city = $this->getParam('city');
        $websiteLink = $this->getParam('websiteLink');
        $keyword1 = $this->getParam('keyword1');
        $keyword2 = $this->getParam('keyword2');

        //$thumbnail = $this->getParam('thumbnail');


        $path = 'unesco_oer/institutions/' . $name . '/thumbnail/';
        try {
            $results = $this->uploadFile($path);
        } catch (customException $e) {
            echo customException::cleanUp();
            exit();
        }
        $thumbnail = 'usrfiles/' . $results['path'];

        $formAction = 'createInstitutionSubmit';
        $formError = false;
        $this->setVarByRef('formError', $formError);

        $validate = $this->objInstitutionManager->validate($name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail);
        $fileInfoArray = array();
        if(!$this->objThumbUploader->isFileValid($fileInfoArray)){
        $validate['valid'] = $this->objThumbUploader->isFileValid($fileInfoArray);
        $validate['thumbnail'] = "Please provide a valid thumbnail";
        }

        if ($validate['valid']) {
            $this->setLayoutTemplate('maincontent_layout_tpl.php');
            $this->objInstitutionManager->editInstitution($institutionId, $name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail);

            return "viewInstitutions_tpl.php";
        } else {

            //There has been an error, go back to the form to fix it
            $formError = TRUE;
            $this->setVarByRef('formError', $formError);

            $this->setVarByRef('name', $name);
            $this->setVarByRef('description', $description);
            $this->setVarByRef('type', $type);
            $this->setVarByRef('country', $country);
            $this->setVarByRef('address1', $address1);
            $this->setVarByRef('address2', $address2);
            $this->setVarByRef('address3', $address3);
            $this->setVarByRef('zip', $zip);
            $this->setVarByRef('city', $city);
            $this->setVarByRef('websiteLink', $websiteLink);
            $this->setVarByRef('keyword1', $keyword1);
            $this->setVarByRef('keyword2', $keyword2);
            $this->setVarByRef('formAction', $formAction);
            $this->setVarByRef('errorMessage', $validate);

            $this->setLayoutTemplate('maincontent_layout_tpl.php');
            return "institutionEditor_tpl.php";
        }
    }

    public function __editInstitutionUI2() {
        $puid = $this->getParam('puid');
        $id = $this->getParam('id');
        $this->setVar('InstitutionPUID', $puid);
        $this->setVar('InstitutionID', $id);
        return 'editInstitutionUI2_tpl.php';
    }

    public function __editInstitutionUI1() {
        return 'editInstitutionUI1_tpl.php';
    }

    /*
     * Method to display page with products to comment on
     */

    public function __chooseProductToAddLanguageTo() {
        return 'chooseAdditionalLanguageUI_tpl.php';
    }

    /*
     * Method to display page with entry options for comment
     */

    //public function __createComment()
    public function __addAdditionalLanguage() {
        $id = $this->getParam('id');
        $this->setVar('ID', $id);
        return 'createAdditionalLanguageUI_tpl.php';
    }

    /*
     * Method to retrieve entries from user on the createAdditionalLanguageUI_tpl.php page
     * and add it to the tbl_unesco_oer_group table
     */

    //public function __addLanguageSubmit()
    public function __addAdditionalLanguageSubmit() {
        $language = $this->getParam('language');
        $id = $this->getParam('id');
//        if (strlen($language) == 0)
//            $code = $name;

        $this->objDbAvailableProductLanguages->addProductLanguage($id, $language);
        return $this->__addData();
    }

    public function __userRegistrationForm() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return 'userRegistration_tpl.php';
    }

    public function __userListingForm() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return 'UserListingForm_tpl.php';
    }

    public function __userEditRegistrationForm() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        $id = $this->getParam('id');
        $this->setVar('id', $id);
        return 'userRegistration_tpl.php';
    }

    function __deleteUser() {
        $this->objUserAdmin->apiUserDelete($this->getParam('id'));
        $this->objUseExtra->deleteUser($this->getParam('id'), $this->getParam('userid'));
        return $this->__userListingForm();
    }

    function __saveNewUser() {
        if (!$_POST) { // Check that user has submitted a page
            return $this->nextAction(NULL);
        }
        // Generate User Id
        $userId = $this->objUserAdmin->generateUserId();
        // Capture all Submitted Fields
        $captcha = $this->getParam('request_captcha');
        $username = $this->getParam('register_username');
        $password = $this->getParam('register_password');
        $repeatpassword = $this->getParam('register_confirmpassword');
        $title = $this->getParam('register_title');
        $firstname = $this->getParam('register_firstname');
        $surname = $this->getParam('register_surname');
        $email = $this->getParam('register_email');
        $repeatemail = $this->getParam('register_confirmemail');
        $sex = $this->getParam('register_sex');
        $cellnumber = $this->getParam('register_cellnum');
        $birthdate = $this->getParam('Date_of_birth');
        $address = $this->getParam('Address');
        $city = $this->getParam('city');
        $state = $this->getParam('state');
        $postaladdress = $this->getParam('postaladdress');
        $organisation = $this->getParam('organisation');
        $jobtittle = $this->getParam('jobtittle');
        $WorkingPhone = $this->getParam('workingphone');
        $DescriptionText = $this->getParam('description');
        $WebsiteLink = $this->getParam('websitelink');
        $GroupMembership = $this->getParam('groupmembership');
        $country = $this->getParam('country');
        $typeOfOccupation = $this->getParam('type_of_occupation');
        $accountstatus = 1; // Default Status Active
        // Create an array of fields that cannot be empty
//        $checkFields = array(
//            $captcha,
//            $username,
//            $firstname,
//            $surname,
//            $email,
//            $repeatemail,
//            $password,
//            $repeatpassword
//        );
        
        // now differentiate if registration is by user or Admin
        //replace the code
           $checkFields='';
         if($this->objUser->isAdmin()){
             $checkFields = array(
                 $captcha,
                 $username,
                 $firstname,
                 $surname,
                 $email,
                 $repeatemail,
                 $password,
                 $repeatpassword
                 );
           }else{
               $checkFields = array(
                   $captcha,
                   $username,
                   $firstname,
                   $surname,
                   $email,
                   $repeatemail,
                   $password,
                   $repeatpassword,
                   $address,
                   $city,
                   $state,
                   $postaladdress
                   );
           }//

        // Create an Array to store problems
        $problems = array();
        
        //check that the resiter is not a user manager
        if(!$this->objUser->isAdmin()){
            if($address ==''){
                $problems[]='noAddress';
            }

            if($city == ''){
                $problems[]='noCity';
            }

            if($state ==''){
                $problems[]='noState';
            }
            if ($postaladdress==''){
                $problems[]='noPostalCode';
            }
        }
        //

        // Check that username is available
        if ($this->objUserAdmin->userNameAvailable($username) == FALSE) {
            $problems[] = 'usernametaken';
        }
        //check that the email address is unique
        if ($this->objUserAdmin->emailAvailable($email) == FALSE) {
            $problems[] = 'emailtaken';
        }
        // Check for any problems with password
        if ($password == '') {
            $problems[] = 'nopasswordentered';
        } else if ($repeatpassword == '') {
            $problems[] = 'norepeatpasswordentered';
        } else if ($password != $repeatpassword) {
            $problems[] = 'passwordsdontmatch';
        }
        // Check that all required field are not empty
        if (!$this->__checkFields($checkFields)) {
            $problems[] = 'missingfields';
        }
        // Check that email address is valid
        if (!$this->objUrl->isValidFormedEmailAddress($email)) {
            $problems[] = 'emailnotvalid';
        }
        // Check whether user matched captcha
        if (md5(strtoupper($captcha)) != $this->getParam('captcha')) {
            $problems[] = 'captchadoesntmatch';
        }
        // If there are problems, present from to user to fix
        if (count($problems) > 0) {
            //check that is admin
            if(!$this->objUser->isAdmin()){
                $this->setLayoutTemplate('maincontent_layout_tpl.php');
                $this->setVar('mode', 'addfixup');
                $this->setVarByRef('problems', $problems);
                return 'RegistrationForm_tpl.php';

            }else{
                $this->setLayoutTemplate('maincontent_layout_tpl.php');
                $this->setVar('mode', 'addfixup');
                $this->setVarByRef('problems', $problems);
                return 'userRegistration_tpl.php';
                }

//
//            $this->setLayoutTemplate('maincontent_layout_tpl.php');
//            $this->setVar('mode', 'addfixup');
//            $this->setVarByRef('problems', $problems);
//            return 'userRegistration_tpl.php';
        } else {
            // Else add to database
            $pkid = $this->objUserAdmin->addUser($userId, $username, $password, $title, $firstname, $surname, $email, $sex, $country, $cellnumber, $staffnumber = NULL, 'useradmin', $accountstatus);
            //add to table userextra
            $id = $this->objUseExtra->getLastInsertedId($userId, $username, $password, $title, $firstname, $surname, $email, $sex);
            $this->objUseExtra->SaveNewUser($id, $userId, $birthdate, $address, $city, $state, $postaladdress, $organisation, $jobtittle, $typeOfOccupation, $WorkingPhone, $DescriptionText, $WebsiteLink, $GroupMembership);

            // Email Details to User
            $this->objUserAdmin->sendRegistrationMessage($pkid, $password);

//Now differentiate if registration is by user or Admin
//and replace the  code
//
            if($this->objUser->isAdmin()){
                return $this->__userListingForm();
            }else{
              $this->setSession('id', $pkid);
              $this->setSession('password', $password);
              $this->setSession('time', $password);
              return $this->nextAction('detailssent');
              }
//
            //$this->setSession('id', $pkid);
            //$this->setSession('password', $password);
            //$this->setSession('time', $password);
            //return $this->nextAction('detailssent');
            //return $this->__userListingForm();
        }
    }


    function __RegistrationForm(){
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return 'RegistrationForm_tpl.php';

    }

    function __editUserDetailsForm() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        $id = $this->getParam('id');
        $userId = $this->getParam('userid');
        $username = $this->getParam('username');
        $this->setVar('id', $id);
        $this->setVar('userid', $userId);
        $this->setVar('username', $username);
        return 'editUserDetails_tpl.php';
    }

//search user by username or by name
    function __searchUser() {
        if (count($this->objUseExtra->searchUserByUsername($this->getParam('search')))!=0) {
            $user = $this->objUseExtra->searchUserByUsername($this->getParam('search')); //search user by username
            $this->setVar('user', $user);
            $this->setLayoutTemplate('maincontent_layout_tpl.php');
            return 'UserListingForm_tpl.php';

        } elseif(count($this->objUseExtra->searchUserByName($this->getParam('search')))!=0) {
            $user = $this->objUseExtra->searchUserByName($this->getParam('search')); //search user by name
            $this->setVar('user', $user);
            $this->setLayoutTemplate('maincontent_layout_tpl.php');
            return 'UserListingForm_tpl.php';
        }else{
            $user='';
            $this->setVar('user', $user);
            $this->setLayoutTemplate('maincontent_layout_tpl.php');
            return 'UserListingForm_tpl.php';
        }
    }

    function __updateUserDetails() {

        $this->user = $this->objUserAdmin->getUserDetails($this->getParam('id'));
        $this->setVarByRef('user', $this->user);
        $userId = $this->getParam('userid');

        if (!$_POST) {
            return $this->nextAction(NULL);
        }
        // Get Details from Form

        $password = $this->getParam('input_useradmin_password');
        $repeatpassword = $this->getParam('useradmin_repeatpassword');
        $title = $this->getParam('useradmin_title');
        $firstname = $this->getParam('useradmin_firstname');
        $surname = $this->getParam('useradmin_surname');
        $email = $this->getParam('useradmin_email');
        //$cellnumber = $this->getParam('useradmin_cellnumber');
        $cellnumber = $this->getParam('register_cellnum');
        $sex = $this->getParam('useradmin_sex');
        $country = $this->getParam('country');
        $birthdate = $this->getParam('Date_of_birth');
        $address = $this->getParam('Address');
        $city = $this->getParam('city');
        $state = $this->getParam('state');
        $postaladdress = $this->getParam('postaladdress');
        $organisation = $this->getParam('organisation');
        $jobtittle = $this->getParam('jobtittle');
        $TypeOccapation = $this->getParam('typeofoccapation');
        $WorkingPhone = $this->getParam('workingphone');
        $DescriptionText = $this->getParam('description');
        $WebsiteLink = $this->getParam('websitelink');
        $GroupMembership = $this->getParam('groupmembership');

        $userDetails = array(
            'password' => $password,
            'repeatpassword' => $repeatpassword,
            'title' => $title,
            'firstname' => $firstname,
            'surname' => $surname,
            'email' => $email,
            'sex' => $sex,
            'country' => $country
        );

        $this->setSession('userDetails', $userDetails);

        // List Compulsory Fields, Cannot be Null
        $checkFields = array($firstname, $surname, $email);

        $results = array();
        echo $this->getParam('id');
        // Check Fields
        if (!$this->__checkFields($checkFields)) {
            $this->setVar('mode', 'addfixup');
            $this->setSession('showconfirmation', FALSE);
            return 'editUserDetails_tpl.php';
        }

        // Check Email Address
        if (!$this->objUrl->isValidFormedEmailAddress($email) && $email != $this->user['emailaddress']) {
            $this->setVar('mode', 'addfixup');
            $this->setSession('showconfirmation', FALSE);
            return 'editUserDetails_tpl.php';
        }

        $results['detailschanged'] = TRUE;

        // check for password changed
        if ($password == '') { // none given, user does not want to change password
            $password = '';
            $results['passwordchanged'] = FALSE;
        } else if ($password != $repeatpassword) { // do not match, user tried to change, but didn't match
            $password = '';
            $results['passwordchanged'] = FALSE;
            $results['passworderror'] = 'passworddonotmatch';
        } else { // OK - user tried, and passwords match
            $results['passwordchanged'] = TRUE;
        }

        // Process Update
        $update = $this->objUserAdmin->updateUserDetails($this->user['id'], $this->user['username'], $firstname, $surname, $title, $email, $sex, $country, $cellnumber, $staffnumber, $password);

        if (count($results) > 0) {
            $results['change'] = 'details';
        }

        $this->setSession('showconfirmation', TRUE);
        $this->objUser->updateUserSession();
        // Process Update Results
        if ($update) {
            $this->objUseExtra->updateUserInfo($this->getParam('id'), $userId, $birthdate, $address, $city, $state, $postaladdress, $organisation, $jobtittle, $TypeOccapation, $WorkingPhone, $DescriptionText, $WebsiteLink, $GroupMembership);
            $this->setLayoutTemplate('maincontent_layout_tpl.php');
            return "UserListingForm_tpl.php";
        } else {
            return $this->nextAction(NULL, array('change' => 'details', 'error' => 'detailscouldnotbeupdated'));
        }
    }

    function __userDetails($id) {
        $user = $this->isValidUser($id, 'userviewdoesnotexist');

        $this->setVarByRef('user', $user);
        $this->setVar('mode', 'edit');


        $confirmation = $this->getSession('showconfirmation', FALSE);
        $this->setVar('showconfirmation', $confirmation);

        $this->setSession('showconfirmation', FALSE);

        return 'editUserDetails_tpl.php';
    }

    function __isValidUser($id, $errorcode='userviewdoesnotexist') {
        if ($id == '') {
            return $this->nextAction(NULL, array('error' => 'noidgiven'));
        }

        $user = $this->objUserAdmin->getUserDetails($id);

        if ($user == FALSE) {
            return $this->nextAction(NULL, array('error' => $errorcode));
        } else {
            return $user;
        }
    }

    /**
     * Method to display the error messages/problems in the user registration
     * @param string $problem Problem Code
     * @return string Explanation of Problem
     */
    function __explainProblemsInfo($problem) {
        switch ($problem) {
            case 'usernametaken':
                return 'The username you have chosen has been taken already.';
            case 'emailtaken':
                return 'The supplied email address has been taken already.';
            case 'passwordsdontmatch':
                return 'The passwords you have entered do not match.';
            case 'missingfields':
                return 'Some of the required fields are missing.';
            case 'emailnotvalid':
                return 'The email address you enter is not a valid format.';
            case 'captchadoesntmatch':
                return 'The image code you entered was incorrect';
            case 'nopasswordentered':
                return 'No password was entered';
            case 'norepeatpasswordentered':
                return 'No Repeat password was entered';
//            case 'noAddress'    :
//                return 'No address entered';
//            case 'noCity':
//                return 'Please provide your residential city';
//            case 'noState':
//                return 'Please provide your residential state';
//            case 'noPostalCode':
//                return 'please provide your postal code';
           
        }
    }

    /**
     * Method to check that all required fields are entered
     * @param array $checkFields List of fields to check
     * @return boolean Whether all fields are entered or not
     */
    function __checkFields($checkFields) {
        $allFieldsOk = TRUE;
        $this->messages = array();
        foreach ($checkFields as $field) {
            if ($field == '') {
                $allFieldsOk = FALSE;
            }
        }
        return $allFieldsOk;
    }

    /**
     * Method to inform the user that their registration has been successful
     */
    function __detailsSent() {
        $user = $this->objUserAdmin->getUserDetails($this->getSession('id'));
        if ($user == FALSE) {
            return $this->nextAction(NULL, NULL, '_default');
        } else {
            $this->setVarByRef('user', $user);
        }
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return 'confirm_tpl.php';
    }

    function __groupRegistationForm() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return 'groupRegistrationForm_tpl.php';
    }

    function __groupListingForm() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return 'groupListingForm_tpl.php';
    }

    /*
     *
     */

    function __saveNewGroup() {
        if (!$_POST) { // Check that user has submitted a page
            return $this->nextAction(NULL);
        }
        $name = $this->getParam('group_name');
        $email = $this->getParam('group_email');
        $address = $this->getParam('group_address');
        $city = $this->getParam('group_city');
        $state = $this->getParam('group_state');
        $country = $this->getParam('country');
        $postalcode = $this->getParam('group_postalcode');
        $website = $this->getParam('group_website');
        $institution = $this->getParam('group_institutionlink');
        $description = $this->getParam('description');
        $loclat = $this->getParam('loclat');
        $loclong = $this->getParam('loclong');

        $checkFields = array(
            $name,
            $email,
            $address,
            $city,
            $state,
            $postalcode,
            $website,
            $loclat,
            $loclong
            );
         $problems = array();
        if ($name == '') {
            $problems[] = 'noname';
        }
        if (!$this->objUrl->isValidFormedEmailAddress($email)) {
            $problems[] = 'emailnotvalid';
        }
        if ($address == '') {
            $problems[] = 'noAddress';
        }
        if ($city == '') {
            $problems[] = 'noCity';
        }

        if ($state == '') {
            $problems[] = 'noState';
        }

        if ($postalcode == '') {
            $problems[] = 'noPostalCode';
        }
        if ($website == '') {
            $problems[] = 'nowebsite';
        }
        if ($loclat == '') {
            $problems[] = 'noloclate';
        }
        if ($loclong == '') {
            $problems[] = 'noloclong';
        }
//          if (!$this->__checkFields($checkFields)) {
//          $problems[] = 'missingfields';}

        if(count($problems) > 0){
            $this->setLayoutTemplate('maincontent_layout_tpl.php');
            $this->setVar('mode', 'addfixup');
            $this->setVarByRef('problems', $problems);
            return 'groupRegistrationForm_tpl.php';

        }else{
            $this->objDbGroups->saveNewGroup($name, $email, $address, $city, $state, $country, $postalcode, $website, $institution, $loclat, $loclong, $description);
            $this->setLayoutTemplate('maincontent_layout_tpl.php');
            return 'groupListingForm_tpl.php';

        }
 }

    function __groupEditingForm() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return 'groupEditingForm_tpl.php';
    }

    function __editGroup() {
        $id = $this->getParam('id');
        $name = $this->getParam('group_name');
        $email = $this->getParam('group_email');
        $address = $this->getParam('group_address');
        $city = $this->getParam('group_city');
        $state = $this->getParam('group_state');
        $country = $this->getParam('country');
        $postalcode = $this->getParam('group_postalcode');
        $website = $this->getParam('group_website');
        $institution = $this->getParam('group_institutionlink');
        $description = $this->getParam('description');
        $loclat = $this->getParam('loclat');
        $loclong = $this->getParam('loclong');
        $this->objDbGroups->editgroup($id, $name, $email, $address, $city, $state, $country, $postalcode, $website, $institution, $loclat, $loclong, $description);
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return 'groupListingForm_tpl.php';
    }

    function __deleteGroup() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        $id = $this->getParam('id');
        $this->objDbGroups->deleteGroup($id);
        return 'groupListingForm_tpl.php';
    }

    /*     * This function handles the uploading of product metadata
     *
     * @return string
     */

    public function __saveProductMetaData() {
        $defaultTemplate = "ProductMetaData_tpl.php";echo "gdsgdf";
        $product = $this->getObject('product');
        
        //test for edit
        if ($this->getParam('productID')){
            $product->loadProduct($this->getParam('productID'));
        }
            
        $this->setVarByRef('product', $product);

        switch (strtolower($this->getParam('add_product_submit'))) {
            case "cancel":
                $this->nextAction($this->getParam('nextAction'), array('id' => $this->getParam('productID')));
                break;

            case "upload":
                //test if all fields are valid
                if ($product->handleUpload()) {
                    //$this->nextAction($this->getParam('nextAction'), array('id' => $this->getParam('productID')));
                    $this->nextAction($this->getParam('nextAction'), array('id' => $product->getIdentifier()));
                } else {
                    return $defaultTemplate;
                }
                break;
            case "createcontent":
                //test if all fields are valid
                if ($product->handleUpload()) {
                    $this->nextAction('saveContent', array('path' => $product->getIdentifier(), 'nextAction' => $this->getParam('nextAction')));
                } else {
                    return $defaultTemplate;
                }
                break;

            default:
                return $defaultTemplate;
                break;
        }
    }

    function __deleteProduct() {
        $productID = $this->getParam('productID');
        $product = $this->getObject('product', 'unesco_oer');
        $product->loadProduct($productID);
        $product->deleteProduct();

        $currentFeaturedProductID = $this->objDbFeaturedProduct->getCurrentFeaturedProductID();

        if ($currentFeaturedProductID == $productID)
        {
            $filter = " where parent_id is null and deleted = 0";
            $lastProductEntered = $this->objDbProducts->getLastEntry($filter, 'puid');
            $this->objDbFeaturedProduct->overRightCurrentFeaturedProduct($lastProductEntered[0]['id']);
        }

        return $this->__home();
    }

    function __adaptProduct() {
        $product = $this->getObject('product', 'unesco_oer');
        $product->loadProduct($this->getParam('productID'));
        $adaptation = $product->makeAdaptation();
        $this->setVarByRef('product', $adaptation);
        return "ProductMetaData_tpl.php";
    }

    function __saveContent()
    {
        $path = $this->getParam('path');
        $option = $this->getParam('option');
        $content = $this->getObject('content');
        $product = $this->getObject('product');

        if($path)
        {            
            $pathArray = $content->getPathArray($path);            
            $product->loadProduct($pathArray[0]);
            if ($product->getContent()) {
                $this->setVarByRef('content', $product->getContent());
            } else {
                //$content->setType($product->getContentType());
                $content->setPath($product->getIdentifier());
                $content->setValidTypes(
                        array(
                            'curriculum' => $product->getContentTypeDescription()
                        )
                );
                $this->setVarByRef('content', $content);
            }
        }

        switch ($option) {
            case 'new':
                $newContent = $content->generateNewContent($path);
                echo $newContent->showInput();
                die();
                break;

            default:
                break;
        }
        

        return "CreateContent_tpl.php";
    }

    //Function to display the institution editor page
    public function __institutionEditor() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        $institutionId = $this->getParam('institutionId');
        $this->setVarByRef('institutionId', $institutionId);

        return "institutionEditor_tpl.php";
    }

    /*
     * Method to display page for creating a new relation type
     */

    public function __createRelationTypeUI() {
        return "createRelationTypeUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createRelationUI_tpl.php page
     * and add it to the tbl_unesco_oer_relation_types table
     */

    public function __createRelationTypeSubmit() {
        $name = $this->getParam('newRelationType');

        $this->objDbRelationType->addRelation($name);
        return $this->__addData();
    }

    /*
     * Method to display page for creating a new relation type
     */

    public function __createStatusUI() {
        return "createStatusUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createRelationUI_tpl.php page
     * and add it to the tbl_unesco_oer_relation_types table
     */

    public function __createStatusSubmit() {
        $name = $this->getParam('newStatus');

        $this->objDbProductStatus->addStatus($name);
        return $this->__addData();
    }

    /*
     * Method to view all institutions editing
     */

    public function __viewInstitutions() {
        $this->setLayoutTemplate('maincontent_layout_tpl.php');
        return "viewInstitutions_tpl.php";
    }

    public function __mypage() {
        //$this->setLayoutTemplate('maincontent_layout_tpl.php');


        return "myPage_tpl.php";
    }

    public function __gotoURL() {
        $url = $this->getParam('url');
        return $url;
    }

}

?>
