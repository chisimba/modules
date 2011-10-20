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
    public $ObjDbUserGroups;
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
    public $objGroupUtil;
    public $objDbgroupInstitutions;
    public $objDbmodules;
    public $objDbcurricula;
    public $objPagination;
    public $groupmanager;
    public $objDbOERresources;
    public $objDbreporting;
    public $objchartgenerator;

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
        $this->ObjDbUserGroups = $this->getObject('dbusergroups');
        $this->objGroupUtil = $this->getObject('grouputil');
        $this->objDbgroupInstitutions = $this->getObject('dbgroupinstitutions');
        $this->objDbmodules = $this->getObject('dbmodules');
        $this->objDbcurricula = $this->getObject('dbcurricula');
        $this->objPagination = $this->getObject('pagination');
        $this->groupmanager = $this->getObject('groupmanager');
        $this->objForum = & $this->getObject('dbforum', 'forum');

// Load Forum Subscription classes
        $this->objForumSubscriptions = & $this->getObject('dbforumsubscriptions', 'forum');
        $this->objTopicSubscriptions = & $this->getObject('dbtopicsubscriptions', 'forum');
        $this->objDiscussionType = $this->getObject('dbdiscussiontypes', 'forum');
        $this->objDbOERresources = $this->getObject('dbgroupoerresource');
        $this->objDbreporting = $this->getObject('dbreporting');
        $this->objchartgenerator = $this->getObject('chartgenerator');
//$this->objUtils = $this->getObject('utilities');
//$this->objGoogleMap=$this->getObject('googlemapapi');
//$this->objGoogleMap = new googlemapapi();
        $this->objdisplayreportgenerator = $this->getObject('displayreportgenerator');
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

    function __pagination() {
        return "pagination.php";
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

    public function __CompareProducts() {

        $users = $this->getParam("selectedusers");
        $this->setVarByRef('selectedproducts', $users);


        return "6a_tpl.php";
    }

    public function __CompareSelected() {

        $moduleid = $this->getParam("id");
        $chosenid = $this->getParam("chosenid");
        $productid = $this->getParam("productid");
        $this->setVarByRef('moduleid', $moduleid);
        $this->setVarByRef('productid', $productid);
        $this->setVar('chosenid', $chosenid);

        return "6b_tpl.php";
    }

    public function __Comparechosen() {

        $moduleid = $this->getParam("id");
        $chosenid = $this->getParam("chosenid");
        $productid = $this->getParam("productid");
        $this->setVarByRef('moduleid', $moduleid);
        $this->setVarByRef('productid', $productid);
        $this->setVar('chosenid', $chosenid);

        return "6b2_tpl.php";
    }

    public function __loadmodule() {

        $productID = $this->getParam('id');
        $moduleID = $this->getParam('moduleid');


        $product = $this->getObject('product');
        $product->loadProduct($productID);
        $content = $product->getContentManager();


        $temp = $this->objDbmodules->getmoduleparent($moduleID);
        $parentid = $temp[0]['parentid'];

        if ($parentid != 'NULL') {                                              //check if origional product was selected
            $modules = $this->objDbmodules->getmodulebyparent($parentid);
        } else {

            $modules = $this->objDbmodules->getmodulebyparent($moduleID);
        }

        if ($parentid == '') {
            $temp = $this->objDbcurricula->getCurriculaparent($moduleID);
            $parentid = $temp[0]['parentid'];

            if ($parentid != NULL) {                                              //check if origional product was selected
                $modules = $this->objDbcurricula->getCurriculabyparent($parentid);
            } else {

                $modules = $this->objDbcurricula->getCurriculabyparent($moduleID);
            }
        }

// var_dump($modules);

        $check = FAlSE;
        foreach ($modules as $module) {  // run through modules till matching module and product are selected
            $existingContent = $content->getContentByContentID($module['id']);

            if ($existingContent != FALSE) {

                $check = TRUE;
                $existingContent = $content->getContentByContentID($module['id']);
                echo $existingContent->showReadOnlyInput();
            }
        }

        if ($check == FALSE) {

            if ($parentid != 'NULL') { // check if origional product was selected
                $existingContent = $content->getContentByContentID($temp[0]['parentid']);
            } else {
                $existingContent = $content->getContentByContentID($moduleID);
            }
            echo $existingContent->showReadOnlyInput();
        }





//   var_dump($modules);
//  echo  $existingContent->showReadOnlyInput();
    }

    public function __loadtree() {

//return 'test_tree_tpl.php';

        $productID = $this->getParam('id');



        $product = $this->newObject('product');
        $product->loadProduct($productID);
        $content = $product->getContentManager();

//   $test=  $content->getProductID();
//   echo $test; echo '<br />';
        $test = '<script src="/unesco_oer/core_modules/tree/resources/TreeMenu.js" language="JavaScript" type="text/javascript"></script>';
        $test .= $content->getContentTree(FALSE, FALSE);

        echo $test;
    }

    public function __Bookmarks() {
        return "Bookmarks_tpl.php";
    }

    public function __Bookmarkpop() {
        return "bookmarkpop_tpl.php";
    }

    public function __deleteBookmark() {

        $id = $this->getParam("selectedusers");
        $userid = $this->objUser->userId();

        $bookmark = $this->objbookmarkmanager->deleteBookmark($id, $userid);




        return "Bookmarks_tpl.php";
    }

    public function __BookmarkData() {

        $label = $this->getParam('Title');
        $description = $this->getParam('Description');
        $time = $this->getParam('time');
        $url = $this->getParam('location');
        $parentid = $this->getParam('parentid');
        $userid = $this->getParam('userid');


        $this->objbookmarkmanager->addBookmark($label, $description, $url, $parentid, $userid);

        return "Bookmarks_tpl.php";
    }

    public function __Bookmarkupdate() {
        $bookmarkid = $this->getParam('bookmarkid');
        $label = $this->getParam('Title');
        $description = $this->getParam('Description');
        $time = $this->getParam('time');
        $this->objbookmarkmanager->updateBookmark($label, $description, $bookmarkid, $time);





        return "Bookmarks_tpl.php";
    }

    public function __4() {
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

    public function __11d() {
        return"11d_tpl.php";
    }

    public function __JavaFilter() {

        $temp = $this->objjavafilt->replaceprods();
        echo $temp;
    }

    public function __JavaFilternum() {



        $temp = $this->objjavafilt->displayprods();
        echo $temp;



        die();
    }

    public function __PaginationFilter() {


        return "paginationfilter_tpl.php";
    }

    public function __ViewProduct() {

        $id = $this->getParam('id');

        $this->setVarByRef('productID', $id);

        if ($this->objDbProducts->isAdaptation($id)) {
            return "5a_tpl.php";
        } else {
            return "3a_tpl.php";
        }
    }

    public function __ViewProductSection() {

        $id = $this->getParam('productID');
        $path = $this->getParam('path');
        $display = $this->getParam('displaytype');

        $this->setVarByRef('productID', $id);
        $this->setVarByRef('path', $path);
        $this->setVarByRef('displaytype', $display);


        if ($this->objDbProducts->isAdaptation($id)) {
            return "5b_tpl.php";
        } else {
            return "3c_tpl.php";
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




        $ProdId = $this->objDbGroups->getidbylocation($lat, $lng);
        $products = $this->objDbGroups->getGroupProductadaptation($ProdId[0]['id']);
//   echo $ProdId[0]['id'];
        $temp = TRUE;

        $this->setVarByRef("finalstring", $products);
        $this->setVarByRef('browsecheck', $temp);
        $this->setVarByRef('ProdID', $ProdId[0]['id']);

        return $page;
    }

    public function __FilterAdaptations() {
        $parentid = $this->getParam('parentid');
        $this->setVarByRef("productID", $parentid);
        return "3b_tpl.php";
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
        return "$page";
    }

    public function requiresLogin($action) {

        if ($action == null) {

            return FALSE;
        }
        $notrequired = array('filterproducts', 'viewproduct', 'login', 'changelang', 'home', 'ViewProductSection');

        if (in_array($action, $notrequired)) {
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
        return "controlpanel_tpl.php";
    }

    public function __adddata() {
        return "addData_tpl.php";
    }

    public function __editusers() {
        return "controlpanel_tpl.php";
    }

    public function __editgroups() {
        return "controlpanel_tpl.php";
    }

    public function __editinstitutions() {
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

        return $this->__productsUi();
    }

    public function __editProduct() {
        $id = $this->getParam('id');
        $prevAction = $this->getParam('prevAction');
        $isNewProduct = FALSE;

        $this->setVar('productID', $id);
        $this->setVar('prevAction', $prevAction);
        $this->setVar('isNewProduct', $isNewProduct);


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

    public function __createResourceTypeSubmit() {
        $description = $this->getParam('newTypeDescription');
        $table = $this->getParam('newTypeTable');
        $this->objDbResourceTypes->addType($description, $table);

        return $this->__viewProductTypes();
    }

    /*
     * Method to edir entries from user on the newResourceTypeUI_tpl.php page
     * and add it to the tbl_unesco_oer_resource_types table
     */

    public function __editResourceTypeSubmit() {
        $description = $this->getParam('newTypeDescription');
        $table = $this->getParam('newTypeTable');
        $id = $this->getParam('typeId');
        $this->objDbResourceTypes->updateType($id, $description, $table);
        return $this->__viewProductTypes();
    }

    /*
     * Method to edir entries from user on the newResourceTypeUI_tpl.php page
     * and add it to the tbl_unesco_oer_resource_types table
     */

    public function __editResourceType() {
        $description = $this->getParam('newTypeDescription');
        $table = $this->getParam('newTypeTable');
        $typeId = $this->getParam('productTypeId');
        $this->setVarByRef('typeId', $typeId);
        $this->setVarByRef('newTypeTable', $table);
        $this->setVarByRef('newTypeDescription', $description);


        return "newResourceTypeUI_tpl.php";
    }

    /*
     * Method to display page for creating a new theme
     */

    public function __createThemeUI() {

        return "createThemeUI_tpl.php";
    }

    public function __editTheme() {

        $themeId = $this->getParam('themeId');
        $this->setVarByRef('themeId', $themeId);

        return "createThemeUI_tpl.php";
    }

    public function __editLanguage() {

        $languageId = $this->getParam('languageId');
//        $name = $this->getParam('name');
//        $code = $this->getParam('code');
//        $this->setVarByRef('name', $name);
//        $this->setVarByRef('code', $code);
        $this->setVarByRef('languageId', $languageId);

        return "createLanguageUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createThemeUI_tpl.php page
     * and add it to the tbl_unesco_oer_product_themes table
     */

    public function __editThemeSubmit() {
        $theme = $this->getParam('newTheme');
        $umbrellaId = $this->getParam('umbrellaTheme');
        $themeId = $this->getParam('themeId');
        $this->objDbProductThemes->updateTheme($themeId, $theme, $umbrellaId);

        return $this->__viewProductThemes();
    }

    /*
     * Method to retrieve entries from user on the createThemeUI_tpl.php page
     * and add it to the tbl_unesco_oer_product_themes table
     */

    public function __editLanguageSubmit() {
        $name = $this->getParam('name');
        $languageId = $this->getParam('languageId');
        $code = $this->getParam('code');
        $this->objDbProductLanguages->updateLanguage($languageId, $code, $name);

        return $this->__viewLanguages();
    }

    /*
     * Method to retrieve entries from user on the createThemeUI_tpl.php page
     * and add it to the tbl_unesco_oer_product_themes table
     */

    public function __createThemeSubmit() {
        $description = $this->getParam('newTheme');
        $umbrella = $this->getParam('umbrellaTheme');
        $this->objDbProductThemes->addTheme($description, $umbrella);
        return $this->__viewProductThemes();
    }

    /*
     * Method to display page for creating a new theme
     */

    public function __createUmbrellaThemeUI() {

        return "createUmbrellaThemeUI_tpl.php";
    }

    public function __editUmbrellaTheme() {

        $themeId = $this->getParam('themeId');
        $this->setVarByRef('themeId', $themeId);

        return "createUmbrellaThemeUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createThemeUI_tpl.php page
     * and add it to the tbl_unesco_oer_product_themes table
     */

    public function __editUmbrellaThemeSubmit() {
        $theme = $this->getParam('newUmbrellaTheme');
        $themeId = $this->getParam('themeId');
        $this->objDbProductThemes->updateUmbrellaTheme($themeId, $theme);

        return $this->__viewProductThemes();
    }

    /*
     * Method to retrieve entries from user on the createThemeUI_tpl.php page
     * and add it to the tbl_unesco_oer_product_themes table
     */

    public function __createUmbrellaThemeSubmit() {
        $description = $this->getParam('newUmbrellaTheme');
        $this->objDbProductThemes->addUmbrellaTheme($description);

        return $this->__viewProductThemes();
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
        return $this->__viewKeywords();
    }

    public function __editKeywordSubmit() {
        $keyword = $this->getParam('newKeyword');
        $this->objDbProductKeywords->updateKeyword($this->getParam('id'), $keyword);
        return $this->__viewKeywords();
    }

    public function __viewKeywords() {

        return 'viewKeywords_tpl.php';
    }

    public function __deleteKeyword() {
        $this->objDbProductKeywords->deleteKeyword($this->getParam('keywordId'));
        return $this->__viewKeywords();
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

        $code = $this->getParam('code');
        $name = $this->getParam('name');


//Form related data members
        $formAction = 'createLanguageSubmit';
        $formError = false;
        $this->setVarByRef('formError', $formError);

        $this->objDbProductLanguages->addLanguage($code, $name);
        return $this->__viewLanguages();
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
        $prevThumbnail = $this->getParam('thumbnail');
            $onestepid = $this->getParam('productID');
            $groupid = $this->getParam('groupid');

//Form related data members
        $formAction = 'createInstitutionSubmit';
        $formError = false;
        $this->setVarByRef('formError', $formError);
//Check if the thumbnail has been set
        if (empty($prevThumbnail)) {
            $path = 'unesco_oer/institutions/' . $name . '/thumbnail/';
            try {
                $results = $this->uploadFile($path);
            } catch (customException $e) {
                echo customException::cleanUp();
                exit();
            }
            $thumbnail = 'usrfiles/' . $results['path'];
        } else {
            $thumbnail = $prevThumbnail;
//Validate the thumbnail
            $fileInfoArray = array();
            if (!$this->objThumbUploader->isFileValid($fileInfoArray)) {
                $validate['valid'] = $this->objThumbUploader->isFileValid($fileInfoArray);
                $validate['thumbnail'] = "Please provide a valid thumbnail";
            }
        }

        $validate = $this->objInstitutionManager->validate($name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail);

        if ($validate['valid']) {

       $id =     $this->objInstitutionManager->addInstitution($name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail);
            
             if ($onestepid == null){
            
                 return $this->__viewInstitutions();
                
            } else if (!(($onestepid != null) && ($groupid == null)))  {
                
                   $this->ObjDbUserGroups->joingroup($this->objUser->userId(), $groupid);
         
                 $this->objDbgroupInstitutions->add_group_institutions($groupid,$id);
                return $this->__adaptProduct($onestepid);
            } else {
                
                  $this->setVarByRef('onestepid', $onestepid);
                 return $this->__groupRegistationForm($onestepid);
                 
                
                
                
            }
         
           
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
            $this->setVarByRef('thumbnail', $thumbnail);
        
            
          
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

    public function __commentmanager() {


        return 'comments_tpl.php';
    }

    public function __savecomment() {
        $productID = $this->getParam('productid');
        $userid = $this->getParam('user_id');
        $comment = $this->getParam('commentbox');

        $this->objDbComments->addComment($productID, $userid, $comment);

        return 'comments_tpl.php';
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

        $institutionId = $this->getParam('institutionId');

        $this->objDbInstitution->deleteInstitution($institutionId);

        return $this->__viewInstitutions();
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
        $prevThumbnail = $this->getParam('thumbnail');



        if ($prevThumbnail == 'usrfiles/') {
            $path = 'unesco_oer/institutions/' . $name . '/thumbnail/';
            try {
                $results = $this->uploadFile($path);
            } catch (customException $e) {
                echo customException::cleanUp();
                exit();
            }
            $thumbnail = 'usrfiles/' . $results['path'];
        } else {
// echo " old == $prevThumbnail<br/>";
// First Check if user has uploaded
            $fileInputName = "fileupload";
            if (array_key_exists($fileInputName, $_FILES)) {
                $file = $_FILES[$fileInputName];
            }
            $newFileName = $file['name'];
            if (empty($newFileName)) {
                $thumbnail = $prevThumbnail;
            } else {
                $path = 'unesco_oer/institutions/' . $name . '/thumbnail/';
                try {
                    $results = $this->uploadFile($path);
                } catch (customException $e) {
                    echo customException::cleanUp();
                    exit();
                }
                $thumbnail = 'usrfiles/' . $results['path'];
            }
        }

        /* else {
          $thumbnail = $prevThumbnail;
          //Validate the thumbnail
          $fileInfoArray = array();
          if (!$this->objThumbUploader->isFileValid($fileInfoArray)) {
          $validate['valid'] = $this->objThumbUploader->isFileValid($fileInfoArray);
          $validate['thumbnail'] = "Please provide a valid thumbnail";
          }
          }
         */

        $formAction = 'editInstitutionSubmit';
        $formError = false;
        $this->setVarByRef('formError', $formError);

        $validate = $this->objInstitutionManager->validate($name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail);

        if ($validate['valid']) {

            $this->objInstitutionManager->editInstitution($institutionId, $name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail);

            return $this->__viewInstitutions();
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
            $this->setVarByRef('thumbnail', $thumnail);
//Stores the address of the previous thumbnail;


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

        return 'userRegistration_tpl.php';
    }

    public function __userListingForm() {

        return 'UserListingForm_tpl.php';
    }

    public function __userEditRegistrationForm() {

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
        $rightData = $this->getParam('rightList');
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
        $checkFields = '';
        if ($this->objUser->isAdmin()) {
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
        } else {
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
        if (!$this->objUser->isAdmin()) {
            if ($address == '') {
                $problems[] = 'noAddress';
            }

            if ($city == '') {
                $problems[] = 'noCity';
            }

            if ($state == '') {
                $problems[] = 'noState';
            }
            if ($postaladdress == '') {
                $problems[] = 'noPostalCode';
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
            if (!$this->objUser->isAdmin()) {

                $this->setVar('mode', 'addfixup');
                $this->setVarByRef('problems', $problems);
                return 'RegistrationForm_tpl.php';
            } else {

                $this->setVar('mode', 'addfixup');
                $this->setVarByRef('problems', $problems);
                return 'userRegistration_tpl.php';
            }

//
//            
//            $this->setVar('mode', 'addfixup');
//            $this->setVarByRef('problems', $problems);
//            return 'userRegistration_tpl.php';
        } else {
// Else add to database

            $pkid = $this->objUserAdmin->addUser($userId, $username, $password, $title, $firstname, $surname, $email, $sex, $country, $cellnumber, $staffnumber = NULL, 'useradmin', $accountstatus);
//add to table userextra

            $this->objUseExtra->SaveNewUser($id, $userId, $birthdate, $address, $city, $state, $postaladdress, $organisation, $jobtittle, $typeOfOccupation, $WorkingPhone, $DescriptionText, $WebsiteLink);
            foreach ($rightData as $right) {
                $this->ObjDbUserGroups->joingroup($userId, $right);
            }



// Email Details to User
            $this->objUserAdmin->sendRegistrationMessage($pkid, $password);

//Now differentiate if registration is by user or Admin
//and replace the  code
//
            if ($this->objUser->isAdmin()) {
                return $this->__userListingForm();
            } else {
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

    function __userPagination($page, $start, $end) {
        $allUser = $this->objUseExtra->getAllUser();
        $itemPerPage = 10;
        $NumberOfPages = count($allUser) / $itemPerPage;




        $user = $this->objUseExtra->getUser($start, $end);
        $this->setVar('page', $page);
        $this->setVar('user', $user);

        return 'UserListingForm_tpl.php';
        $this->__userPagination($page + 1, $end, $end + 10);
    }

    function __RegistrationForm() {

        return 'RegistrationForm_tpl.php';
    }

    function __editUserDetailsForm() {

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
        if ($this->getParam('search') == '') {
            return $this->__userListingForm();
        } else {
            if (count($this->objUseExtra->searchUserByUsername($this->getParam('search'))) > 0) {
                $user = $this->objUseExtra->searchUserByUsername($this->getParam('search')); //search user by username
                $this->setVar('user', $user);
                $mode = 'addfixup';
                $this->setVar('mode', $mode);

                return 'UserListingForm_tpl.php';
            } else {
                if (count($this->objUseExtra->searchUserByName($this->getParam('search'))) > 0) {
                    $user = $this->objUseExtra->searchUserByName($this->getParam('search')); //search user by name
                    $this->setVar('user', $user);
                    $this->setVar('mode', 'addfixup');

                    return 'UserListingForm_tpl.php';
                }
            }
        }
        if (count($this->objUseExtra->searchUserByUsername($this->getParam('search'))) == count($this->objUseExtra->searchUserByName($this->getParam('search')))) {
            $useruserfound = "No user found";
            $this->setVar('nouserfound', $useruserfound);
            $mode = 'addfixup';
            $this->setVar('mode', $mode);

            return 'UserListingForm_tpl.php';
        }
    }

    function __updateUserDetails() {

        $this->user = $this->objUserAdmin->getUserDetails($this->getParam('id'));
        $this->setVarByRef('user', $this->user);
        $userId = $this->getParam('userid');
        $id = $this->getParam('id');

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
        $rightList = $this->getParam('rightList');

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
            foreach ($rightList as $list) {
                if ($this->ObjDbUserGroups->check_availableUserGroup($id, $list) != TRUE) {
                    $this->ObjDbUserGroups->joingroup($this->getParam('id'), $list);
                }
            }

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
            case 'novalidThumbnail' :
                return 'Thumbnail is invalid';
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

        return 'confirm_tpl.php';
    }

    function __groupRegistationForm($onestepid) {
        if ($onestepid == null){
            $onestepid = $this->getParam('onestepid');
        } 
        $this->setVarByRef('onestepid', $onestepid);

        return 'groupRegistrationForm_tpl.php';
    }

    function __groupListingForm() {

        return 'groupListingForm_tpl.php';
    }

    function __groupListingFormMain() {

        return 'groupListingFormMain_tpl.php';
    }

    public function __addOERform() {
        return "addOERform_tpl.php";
    }

    function __saveOER() {
        $groupid = $this->getParam('groupid');
        $resource_name = $this->getParam('resource_name');
        $resource_type = $this->getParam('resource_type');
        $author = $this->getParam('resource_author');
        $publisher = $this->getParam('resource_publisher');
//$file = $this->getParam('group_name');

        $checkFields = array(
            $resource_name,
            $resource_type,
            $author,
            $publisher,
                //$file
        );

        $problems = array();
        if (!$this->__checkFields($checkFields)) {
            $problems[] = 'missingfields';
        }
        if (count($problems) > 0) {
            $this->setVar('mode', 'addfixup');
            $this->setVarByRef('problems', $problems);
            return 'addOERform_tpl.php';
        } else {
            $OERid = $this->objDbOERresources->addGroupOerResource($groupid, $resource_name, $resource_type, $author, $publisher, $file);
            $groupresources = $this->objDbOERresources->getResource($groupid);
            $this->setVar('resources', $groupresources);
            $mode = 'addfixup';
            $this->setVar('mode', $mode);
            $this->setVar('groupid', $groupid);
            return 'manageOERresources_tpl.php';
        }
    }

    public function __manageOERresource() {
        return "manageOERresources_tpl.php";
    }

    public function __deleteOERresource() {
        $groupid = $this->getParam("groupid");
        $id = $this->getParam("id");
        $this->objDbOERresources->deleteGroupResource($id, $groupid);
        return "manageOERresources_tpl.php";
    }

    function __joinGroup() {
        $groupid = $this->getParam("groupid");
        $userid = $this->getParam("userid");
        $this->ObjDbUserGroups->joingroup($userid, $groupid);
        return $this->__10();
    }

    function __onestepjoinGroup() {
        $groupid = $this->getParam("groupid");
        $userid = $this->getParam("userid");
        $productID = $this->getparam('productID');
        $this->ObjDbUserGroups->joingroup($userid, $groupid);
        $this->setVarByRef('productID', $productID);
        return $this->__adaptProduct();
    }

    function __leaveGroup() {
        $id = $this->getParam("id");
        $groupid = $this->getParam("groupid");
        echo $groupid;
// $currLoggedInID = $this->objUser->userId();
//$id=$this->objUseExtra->getUserbyUserIdbyUserID($currLoggedInID);
        $this->ObjDbUserGroups->leaveGroup($id, $groupid);
        return $this->__8a();
    }

    /*
     *
     */

    function __linkInstitution() {
        $rightList = $this->getParam('rightList');
        $id = $this->getParam('id');
        $onestepid = $this->getParam('productID');
 
        $user_current_membership = $this->objDbGroups->getGroupInstitutions($this->getParam('id'));
        $currentMembership = array();
        foreach ($user_current_membership as $membership) {
        array_push($currentMembership, $membership['institution_id']);
    
        }
        
    
        $results = array_diff($rightList,$currentMembership);
        $this->setVarByRef('rightList', $currentMembership);
        foreach ($results as $array) {

            $this->objDbgroupInstitutions->add_group_institutions($id, $array);
        }
        
        if ( $onestepid == null){
            
             return $this->__11a();
        } else{
               $this->ObjDbUserGroups->joingroup($this->objUser->userId(), $id);
               return $this->__adaptProduct($onestepid);
            
        }
       
    }

    function __saveNewGroup() {
        if (!$_POST) { // Check that user has submitted a page
            return $this->nextAction(NULL);
        }
        $name = $this->getParam('group_name');
        $email = $this->getParam('register_email');
        $onestepid = $this->getParam('productID');
        $address = $this->getParam('group_address');
        $city = $this->getParam('group_city');
        $state = $this->getParam('group_state');
        $country = $this->getParam('country');
        $postalcode = $this->getParam('group_postalcode');
        $website = $this->getParam('group_website');
        $institution = $this->getParam('group_institutionlink');
        $description = $this->getParam('description');
        $loclat = $this->getParam('group_loclat');
        $loclong = $this->getParam('group_loclong');
        $admin = $this->objUser->userId();
        $rightList = $this->getParam('rightList');
        $description_one = $this->getParam('description_one');
        $description_two = $this->getParam('description_two');
        $description_three = $this->getParam('description_three');
        $description_four = $this->getParam('description_four');



        $path = 'unesco_oer/groups/' . $name . '/thumbnail/';
        $results = $this->objThumbUploader->uploadThumbnail($path);
        $thumbnail = 'usrfiles/' . $results['path'];

        $checkFields = array(
            $name,
            //$confirmemail,
            $description_one,
            $address,
            $city,
            $postalcode,
            $website,
            $description,
            $loclat,
            $loclong
        );

        $problems = array();

        if (!$this->__checkFields($checkFields)) {
            $problems[] = 'missingfields';
        }


        if (!$this->objUrl->isValidFormedEmailAddress($email)) {
            $problems[] = 'emailnotvalid';
        }

        $fileInfoArray = array();
        if (!$this->objThumbUploader->isFileValid($fileInfoArray)) {
            $problems[] = 'novalidThumbnail';
        }

        if (count($problems) > 0) {

            $this->setVar('mode', 'addfixup');
            $this->setVarByRef('problems', $problems);
            return 'groupRegistrationForm_tpl.php';
        } else {
            $id = $this->objDbGroups->saveNewGroup($name, $email, $address, $city, $state, $country, $postalcode, $website, $institution, $loclat, $loclong, $description, $admin, $thumbnail, $description_one, $description_two, $description_three, $description_four);
// $id = $this->objDbGroups->getLastInsertId();
            $userid = $this->objUser->userId();
            $this->ObjDbUserGroups->autoInsert_Joingroup_admin($userid, $id);
            $this->objDbgroupInstitutions->add_group($id);
            foreach ($rightList as $array) {
                $this->objDbgroupInstitutions->add_group_institutions($id, $array);
            }


//create group forum
            $this->groupmanager->saveForum($id, $name, $description);

            if ($onestepid != null) {
                $this->setVarByRef('productID', $onestepid);
                return $this->__adaptProduct($onestepid);
            }else
                return $this->__groupListingForm();
        }
    }

    function __groupEditingForm() {

        return 'groupEditingForm_tpl.php';
    }

    function __groupMembersForm() {
        return 'groupmembers_tpl.php';
    }

    function __subgroupForm() {
        return 'subGroupRegistrationForm_tpl.php';
    }

    function __subgroupListingForm() {
        return 'subGroupListingForm_tpl.php';
    }

    function __saveSubgroup() {
        $parent_id = $this->getParam("parent_id");
        $this->setVar('parent_id', $parent_id);
        $subgroupname = $this->getParam('subgroup_name');
        $description = $this->getParam('description');
        $brief_description = $this->getParam('briefdescription');
        $interest = $this->getParam('interest');
        $website = $this->getParam('group_website');
//oer resource
        $checkFields = array(
            $subgroupname,
            $description,
        );
        $problems = array();
        if (!$this->__checkFields($checkFields)) {
            $problems[] = 'missingfields';
        }
        if (count($problems) > 0) {
            $this->setVar('mode', 'addfixup');
            $this->setVarByRef('problems', $problems);
            return 'subGroupRegistrationForm_tpl.php';
        } else {
            $this->objDbGroups->saveSubGroup($subgroupname, $website, $description, $brief_description, $interest, $parent_id);
            return 'subGroupListingForm_tpl.php';
        }
    }

    function __test() {
        return "test_tpl.php";
    }

    function __editGroup() {
        $id = $this->getParam('id');
        $name = $this->getParam('group_name');
        $website = $this->getParam('group_website');
        $email = $this->getParam('register_email');
        $address = $this->getParam('group_address');
        $city = $this->getParam('group_city');
        $state = $this->getParam('group_state');
        $country = $this->getParam('country');
        $postalcode = $this->getParam('group_postalcode');

        $institution = $this->getParam('group_institutionlink');
        $description = $this->getParam('description');
        $loclat = $this->getParam('group_loclat');
        $loclong = $this->getParam('group_loclong');
        $rightList = $this->getParam('rightList');
        $description_one = $this->getParam('description_one');
        $description_two = $this->getParam('description_two');
        $description_three = $this->getParam('description_three');
        $description_four = $this->getParam('description_four');


        $prevousPath = $this->objDbGroups->getThumbnail($id);

        $path = 'unesco_oer/groups/' . $name . '/thumbnail/';
        $results = $this->objThumbUploader->uploadThumbnail($path);
        if ($results != '') {
            $thumbnail = 'usrfiles/' . $results['path'];
        }
//$thumbnail = 'usrfiles/' . $results['path'];

        $checkFields = array(
            $name,
            $address,
            $city,
            $description_one,
            //$state,
            $postalcode,
            $website,
            $description,
            $loclat,
            $loclong
        );

        $problems = array();

        if (!$this->__checkFields($checkFields)) {
            $problems[] = 'missingfields';
        }


        if (!$this->objUrl->isValidFormedEmailAddress($email)) {
            $problems[] = 'emailnotvalid';
        }
        $fileInfoArray = array();
        if ($results != '') {
            if (strcmp($thumbnail, $prevousPath) != 0) {
                if (!$this->objThumbUploader->isFileValid($fileInfoArray)) {
                    $problems[] = 'novalidThumbnail';
                }
            }
        }
        if (count($problems) > 0) {

            $this->setVar('mode', 'addfixup');
            $this->setVarByRef('problems', $problems);
            return 'groupEditingForm_tpl.php';
        } else {
            $this->objDbGroups->editgroup($id, $name, $email, $address, $city, $state, $country, $postalcode, $website, $institution, $loclat, $loclong, $description, $thumbnail, $description_one, $description_two, $description_three, $description_four);
            foreach ($rightList as $array) {
                if ($this->objDbgroupInstitutions->check_availableGroupInstitution($id, $array) != TRUE) {
                    $this->objDbgroupInstitutions->add_group_institutions($id, $array);
                }
            }
//check if the group has a forum
            if (!$this->objDbGroups->forumExist($id)) {
                $this->groupmanager->saveForum($id, $name, $description);
            }

            return $this->__groupListingForm();
        }
    }

    function __deleteGroup() {

        $id = $this->getParam('id');
        $this->objDbGroups->deleteGroup($id);
        return 'groupListingForm_tpl.php';
    }

//    function __searchGroup() {
//        if ($this->getParam('search') == '') {
//            return $this->__groupListingForm();
//        } else {
//            if (count($this->objDbGroups->searchGroupByName($this->getParam('search'))) > 0) {
//                $group = $this->objDbGroups->searchGroupByName($this->getParam('search'));
//                $this->setVar('group', $group);
//                $mode = 'addfixup';
//                $this->setVarByRef('mode', $mode);
//                
//                return 'groupListingForm_tpl.php';
//            } else {
//                $nogroupfound = "No group found";    // this must be some be a script
//                $this->setVar('nogroupfound', $nogroupfound);
//                $mode = 'addfixup';
//                $this->setVarByRef('mode', $mode);
//                
//                return 'groupListingForm_tpl.php';
//            }
//        }
//    }



    function __groupProductForm() {
        $page = $this->getParam('page');
        $id = $this->getParam('id');
        $products = $this->objDbGroups->getGroupProductadaptation($id);
//   echo $ProdId[0]['id'];
        $temp = TRUE;

        $this->setVarByRef("finalstring", $products);
        $this->setVarByRef('browsecheck', $temp);
        $this->setVarByRef('ProdID', $ProdId[0]['id']);



        return $page;
    }

    public function __groupGrid() {
        return'groupGridView_tpl.php';
    }

    public function __groupList() {
        return 'groupListView_tpl.php';
    }

    public function __addOER() {
        $this->groupmanager->AddOerResource();
        return;
    }

    /*     * This function handles the uploading of product metadata
     *
     * @return string
     */

    public function __newProduct() {
        $product = $this->newObject('product', 'unesco_oer');
        $product->createBlankProduct();

        $params = array(
            'productID' => $product->getIdentifier(),
            'nextAction' => $this->getParam('nextAction'),
            'cancelAction' => $this->getParam('cancelAction')
        );

        $this->nextAction('saveProductMetaData', $params);
    }

    public function __saveProductMetaData() {
        $license = $this->getParam('creativecommons');


        $defaultTemplate = "ProductMetaData_tpl.php";

        $product = $this->getObject('product');

//test for edit
        if ($this->getParam('productID')) {
            $product->loadProduct($this->getParam('productID'));
        }

        $this->setVarByRef('product', $product);

        switch (strtolower($this->getParam('add_product_submit'))) {
            case "cancel":
                $this->nextAction($this->getParam('cancelAction'), $this->objProductUtil->createParameterArray($this->getParam('cancelParams')));
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
                    $this->nextAction('saveContent', array('productID' => $product->getIdentifier(), 'nextAction' => $this->getParam('nextAction')));
                } else {
                    return $defaultTemplate;
                }
                break;
            case "getinstitutions":
                $dropdown = $product->makeInstitutionDropDown($this->getParam('group_id'));
                echo $dropdown->show();
                die();
                break;
            default:
//echo 'invalid action!'; die();
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

        if ($currentFeaturedProductID == $productID) {
            $filter = " where parent_id is null and deleted = 0";
            $lastProductEntered = $this->objDbProducts->getLastEntry($filter, 'puid');
            $this->objDbFeaturedProduct->overRightCurrentFeaturedProduct($lastProductEntered[0]['id']);
        }

        return $this->__home();
    }

    function __adaptProduct($onestepid) {
        $product = $this->getObject('product', 'unesco_oer');
        $product->loadProduct($this->getParam('productID'));
        $adaptation = $product->makeAdaptation();
        $this->setVarByRef('product', $adaptation);

        if ($this->ObjDbUserGroups->getGroupsByUserID($this->objUser->userId()) == null) {

            $this->setVarByRef('onestepid', $this->getParam('productID'));
            return '10_tpl.php';
        } else
            return "ProductMetaData_tpl.php";
    }

    function __saveContent() {
        $productID = $this->getParam('productID');
        $pair = $this->getParam('pair');
        $option = $this->getParam('option');
        $contentManager = $this->getObject('contentmanager');
        $product = $this->newObject('product');


        if ($productID) {
            $product->loadProduct($productID);
            $contentManager = $product->getContentManager();
        }

        $this->setVarByRef('contentManager', $contentManager);
        $this->setVarByRef('product', $product);

        switch ($option) {
            case 'new':
                $newContent = $contentManager->generateNewContent($pair);
                echo $newContent->showInput($productID);
                die();
                break;

            case 'save':
                $newContent = $contentManager->generateNewContent($pair);
                $newContent->handleUpload();
                $contentManager->addNewContent($newContent);
                return "CreateContent_tpl.php";
                break;

            case 'edit':
                $pairArray = $contentManager->getPairArray($pair);
                $existingContent = $contentManager->getContentByContentID($pairArray[1]);
                echo $existingContent->showInput($productID);
                die;
                break;

            case 'saveedit':
                $pairArray = $contentManager->getPairArray($pair);
                $existingContent = $contentManager->getContentByContentID($pairArray[1]);
                $existingContent->handleUpload();
                break;

            case 'delete':
                $pairArray = $contentManager->getPairArray($pair);
                $existingContent = $contentManager->getContentByContentID($pairArray[1]);
                $existingContent->delete();
                break;

            default:
                break;
        }


        return "CreateContent_tpl.php";
    }

    public function __deleteContent() {
        $productID = $this->getParam('productID');
        $contentID = $this->getParam('contentID');
        $contentManager = $this->getObject('contentmanager');
        $product = $this->newObject('product');

        if ($productID) {
            $product->loadProduct($productID);
            $contentManager = $product->getContentManager();
        }

        $existingContent = $contentManager->getContentByContentID($contentID);
        $existingContent->delete();

        $action = $this->getParam('nextAction');
        $params = $this->objProductUtil->createParameterArray($this->getParam('params'));

        $this->nextAction($action, $params);
    }

//Function to display the institution editor page
    public function __institutionEditor() {

        $institutionId = $this->getParam('institutionId');
         $onestepid = $this->getParam('onestepid');
         $groupid = $this->getParam('groupid');
                 
        $this->setVarByRef('institutionId', $institutionId);
             $this->setVarByRef('onestepid', $onestepid);
                $this->setVarByRef('groupid', $groupid);

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

        return "viewInstitutions_tpl.php";
    }

    public function __mypage() {

        return "myPage_tpl.php";
    }

    public function __gotoURL() {
        $url = $this->getParam('url');
        return $url;
    }

    public function __viewProductThemes() {

        return 'viewProductThemes_tpl.php';
    }

    function __deleteTheme() {
        $this->objDbProductThemes->deleteTheme($this->getParam('themeId'));
        return $this->__viewProductThemes();
    }

    function __deleteUmbrellaTheme() {
        $this->objDbProductThemes->deleteUmbrellaTheme($this->getParam('themeId'));
        return $this->__viewProductThemes();
    }

    public function __viewLanguages() {

        return 'viewLanguages_tpl.php';
    }

    function __deleteLanguage() {
        $this->objDbProductLanguages->deleteLanguage($this->getParam('languageId'));

        return $this->__viewLanguages();
    }

    public function __viewProductTypes() {

        return 'viewProductTypes_tpl.php';
    }

    function __deleteProductType() {
        $this->objDbResourceTypes->deleteType($this->getParam('productTypeId'));
        return $this->__viewProductTypes();
    }

    function __login() {
        return "login_tpl.php";
    }

    function __changelang() {
        $langid = $this->getParam("langid");
//$this->objConfig->setdefaultLanguageAbbrev($langid);
        setcookie("language", $langid);
        $_SESSION['language'] = $langid;
        return $this->nextAction("home");
    }

    /**
     * Post a new message. This shows the form to do that.
     *
     * @param string $id - Record ID of the Forum message will be posted in
     * @return string Template - forum_newtopic.php
     */

    /**
     *
     *
     */
    function __searchSite() {
        $query = $this->getParam('q');


        $objLucene = $this->newObject('searchresults');
        $searchResults = $objLucene->show($query);
// echo $searchResults; die();
        $searchResults = str_replace('&', '&amp;', $searchResults);
        $searchResults = str_replace(urlencode('[HIGHLIGHT]'), urlencode($query), $searchResults);

        $this->setVarByRef('searchResults', $searchResults);
        $this->setVarByRef('searchQuery', $query);


        return 'searchresults.php';
    }

    public function __testPDF() {
        $generator = $this->getObject('documentgenerator', 'unesco_oer');
        return $generator->showProductPDF($this->getParam('id'));
    }

    public function __help() {
        return 'help_tpl.php';
    }

    /**
     * Generic report viewer
     */
    public function __viewreport() {

        $objReportManager = $this->getObject("reportmanager");
        $objReportManager->generatePDFReport("The text", "output.pdf", "report1.jrxml");
    }

    public function __processReportingForm() {
        $adaptationTypes = $this->getParam('AdaptationType');
        $institutionTypes = $this->getParam('InstitutionType');
        $countryNames = $this->getParam('countryDropdown');
        $themeNames = $this->getParam('themeDropdown');
        $langNames = $this->getParam('langDropdown');
        $regions = $this->getParam('regionDropdown');

        $query = $this->objDbreporting->createReportQuery($adaptationTypes, $institutionTypes, $countryNames, $themeNames, $langNames, $regions);



//        var_dump($adaptationTypes);
//        var_dump($institutionTypes);
//        var_dump($countryNames);
//        var_dump($themeNames);
//        var_dump($langNames);
//        var_dump($regions);
        die();
    }

}

?>