<?php

//require('classes/googlemapapi_class_inc.php');

class unesco_oer extends controller
{

    public $objProductUtil;
    public $objDbProducts;
    public $objDbResourceTypes;
    public $objDbProductThemes;
    public $objDbProductLanguages;
    public $objDbFeaturedProduct;
    public $objFeaturedProducUtil;
    public $objDbGroups;
    public $objDbInstitution;
    public $objDbComments;
    public $objUser;
    public $objDbProductRatings;
    public $objDbInstitutionTypes;
    public $objGoogleMap;
    public $objDbAvailableProductLanguages;
    /**
     *
     * @var string $objLanguage String object property for holding the
     * language object
     * @access public
     *
     */
    public $objLanguage;

    function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
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
        $this->objDbComments = $this->getObject('dbcomments');
        $this->objProductRatings = $this->getObject('dbproductratings');
        $this->objDbInstitutionTypes = $this->getObject('dbinstitutiontypes');
        $this->objUser = $this->getObject('user', 'security');
        //$this->objGoogleMap=$this->getObject('googlemapapi');
        //$this->objGoogleMap = new googlemapapi();
    }

    /**
     * Standard Dispatch Function for Controller
     * @param <type> $action
     * @return <type>
     */
    public function dispatch($action)
    {
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
    function getMethod(& $action)
    {
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
    function validAction(& $action)
    {
        if (method_exists($this, '__' . $action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Method to show the Home Page of the Module
     */
    public function __home()
    {
//        $displayAllMostAdaptedProducts = false;s', $displayAllMostAdaptedProducts
//        $this->setVarByRef('displayAllMostAdaptedProducts', $displayAllMostAdaptedProducts);

        return "1a_tpl.php";
    }

    public function __1b()
    {
        return "1b_tpl.php";
    }

    public function __2a()
    {
        return "2a_tpl.php";
    }

    public function __2b()
    {
        return "2b_tpl.php";
    }

    public function __3a()
    {
        return "3a_tpl.php";
    }

    public function __4()
    {
        return "4_tpl.php";
    }

    public function __ViewProduct()
    {

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

    public function __viewAllMostAdaptedProducts()
    {

        $displayAllMostAdaptedProducts = $this->getParam('displayAllMostAdaptedProducts');
        $this->setVarByRef('displayAllMostAdaptedProducts', $displayAllMostAdaptedProducts);
        return "1a_tpl.php";
    }

    public function __Search()
    {

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

    public function __BrowseAdaptation()
    {

        //   $lat = $this->getParam('lat');
        //  $lng = $this->getparam('lng');
        $page = $this->getParam('page');



        //   $ProdId = $this->objProductUtil->BrowseAdaptation($lat,$lng);
        //  $Adaptation = $this->objDbProducts->getFilteredProducts($ProdId);
        //    $Buildstring = "where id = '$ProdId'";




        $temp = 'parent_id is not null';

        //   $this->setVarByRef("test", $Buildstring);
        //   $this->setVarByRef("prodid", $ProdId);
        $this->setVarByRef("finalstring", $temp);



        return "2a_tpl.php";
    }

    public function __FilterAdaptations()
    {

        $parentid = $this->getParam('parentid');
        $adaptationstring = 'parent_id = ' . "'$parentid'";

        $TotalEntries = $this->objProductUtil->FilterTotalProducts($AuthFilter, $ThemeFilter, $LangFilter, $page, $sort, $TotalPages, $adaptationstring, $Model, $Handbook, $Guide, $Manual, $Besoractile);
        $Buildstring = $this->objProductUtil->FilterAllProducts($NumFilter, $PageNum, $TotalEntries);


        $this->setVarByRef("finalstring", $Buildstring);
        $this->setVarByRef("TotalEntries", $TotalEntries);
        $this->setVarByRef("adaptationstring", $adaptationstring);


        return "2a_tpl.php";
    }

    public function __FilterProducts()
    {

        $AuthFilter = $this->getParam('AuthorFilter');
        $ThemeFilter = $this->getParam('ThemeFilter');
        $LangFilter = $this->getParam('LanguageFilter');
        $page = $this->getParam('page');
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


        $TotalEntries = $this->objProductUtil->FilterTotalProducts($AuthFilter, $ThemeFilter, $LangFilter, $page, $sort, $TotalPages, $adaptationstring, $Model, $Handbook, $Guide, $Manual, $Besoractile);
        $Buildstring = $this->objProductUtil->FilterAllProducts($NumFilter, $PageNum, $TotalEntries);



        $this->setVarByRef("AuthFilter", $AuthFilter);
        $this->setVarByRef("ThemeFilter", $ThemeFilter);
        $this->setVarByRef("LangFilter", $LangFilter);
        $this->setVarByRef("SortFilter", $sort);
        $this->setVarByRef("NumFilter", $NumFilter);
        $this->setVarByRef("PageNum", $PageNum);
        $this->setVarByRef("TotalPages", $TotalPages);
        $this->setVarByRef("Model", $Model);
        $this->setVarByRef("finalstring", $Buildstring);
        $this->setVarByRef("TotalEntries", $TotalEntries);
        $this->setVarByRef("Model", $Model);
        $this->setVarByRef("Guide", $Guide);
        $this->setVarByRef("Handbook", $Handbook);
        $this->setVarByRef("Manual", $Manual);
        $this->setVarByRef("Besoractile", $Besoractile);
        $this->setVarByRef("adaptationstring", $adaptationstring);




        return "$page";
    }

    public function requiresLogin($action)
    {
        $required = array('addData', 'editProduct');
        if (in_array($action, $required)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
     * Method to display page for creating a new product
     */

    public function __productsUi()
    {
        return "products_ui_tpl.php";
    }

    /*
     * Method to display page for selecting input option
     */

    public function __addData()
    {
        return "addData_tpl.php";
    }

    /*
     * Method to display page with adaptable products
     */

    public function __chooseProductToAdapt()
    {
        return 'newAdaptation_tpl.php';
    }

    public function __createProduct()
    {
        $id = $this->getParam('id');
        $prevAction = $this->getParam('prevAction');
        $isNewProduct = TRUE;

        $this->setVar('productID', $id);
        $this->setVar('prevAction', $prevAction);
        $this->setVar('isNewProduct', $isNewProduct);

        $this->setLayoutTemplate('1a_layout_tpl.php');

        return $this->__productsUi();
    }

    public function __editProduct()
    {
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

    public function __uploadSubmit()
    {
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
        if (!$this->objDbProducts->isAdaptation($parentID) && ($isNewProduct == ($parentID == null))){
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
            $data = array_merge($data, array('parent_id' => $parentID));
            $this->objDbProducts->addProduct($data);
        } else {
            $this->objDbProducts->updateProduct($parentID, $data);
        }

        return $this->nextAction($this->getParam('prevAction'));
    }

    /*
     * Method to display page for creating a new resource type
     */

    public function __newResourceTypeUI()
    {
        return "newResourceTypeUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the newResourceTypeUI_tpl.php page
     * and add it to the tbl_unesco_oer_resource_types table
     */

    public function __resourceTypeSubmit()
    {
        $description = $this->getParam('newTypeDescription');
        $this->objDbResourceTypes->addType($description);
        return $this->__addData();
    }

    /*
     * Method to display page for creating a new theme
     */

    public function __createThemeUI()
    {
        return "createThemeUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createThemeUI_tpl.php page
     * and add it to the tbl_unesco_oer_product_themes table
     */

    public function __createThemeSubmit()
    {
        $description = $this->getParam('newTheme');
        $this->objDbProductThemes->addTheme($description);
        return $this->__addData();
    }

    /*
     * Method to display page for creating a new theme
     */

    public function __createLanguageUI()
    {
        return "createLanguageUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createThemeUI_tpl.php page
     * and add it to the tbl_unesco_oer_product_themes table
     */

    public function __createLanguageSubmit()
    {
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

    public function __createFeaturedProduct()
    {
        $featuredproduct = $this->getParam('id');
        $this->objDbFeaturedProduct->overRightCurrentFeaturedProduct($featuredproduct);
        return "1a_tpl.php";
    }

    /*
     * Method to retrieve the current featured unesco product from user on the featuredAdaptationUI_tpl.php
     * return a page 1a_tpl.php with the current featured product
     */

    public function __createFeaturedAdaptation()
    {
        $featuredproduct = $this->getParam('id');
        $this->objDbFeaturedProduct->overRightCurrentFeaturedAdaptation($featuredproduct);
        return "1a_tpl.php";
    }

    /*
     * method to dispaly page to create a new unesco featured product
     */

    public function __featuredProductUI()
    {
        return "featuredProductUI_tpl.php";
    }

    /*
     * method to dispaly page to create a new unesco featured product
     */

    public function __featuredAdaptationUI()
    {
        return "featuredAdaptationUI_tpl.php";
    }

    /*
     * Method to display page for creating a new group
     */

    public function __createGroupUI()
    {
        return "createGroupUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createGroupUI_tpl.php page
     * and add it to the tbl_unesco_oer_group table
     */

    public function __createGroupSubmit()
    {
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

    public function __createInstitutionUI()
    {
        return "createInstitutionUI_tpl.php";
    }

    /*
     * Method to retrieve entries from user on the createGroupUI_tpl.php page
     * and add it to the tbl_unesco_oer_group table
     */

    public function __createInstitutionSubmit()
    {
        $name = $this->getParam('newInstitution');
        $loclat = $this->getParam('loclat');
        $loclong = $this->getParam('loclong');
        $institutionTypeName = $this->getParam('institutionType');
        $institutionTypeID = $this->objDbInstitutionTypes->findTypeID($institutionTypeName);
        $country = $this->getParam('country');
        $path = 'unesco_oer/institutions/' . $name . '/thumbnail/';
        try {
            $results = $this->uploadFile($path);
        } catch (customException $e) {
            echo customException::cleanUp();
            exit();
        }
        $thumbnailPath = 'usrfiles/' . $results['path'];

        $this->objDbInstitution->addInstitution($name, $loclat, $loclong, $thumbnailPath, $institutionTypeID, $country);
        return $this->__addData();
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

    public function __chooseProductToComment()
    {
        return 'chooseComment_tpl.php';
    }

    /*
     * Method to display page with products to rate
     */

    public function __chooseProductToRate()
    {
        return 'chooseProductToRate_tpl.php';
    }

    /*
     * Method to display page with entry options for comment
     */

    public function __createComment()
    {
        $id = $this->getParam('id');
        $this->setVar('productID', $id);
        return 'createComment_tpl.php';
    }

    /*
     * Method to display page with entry options for rating
     */

    public function __addProductRating()
    {
        $id = $this->getParam('id');
        $this->setVar('productID', $id);
        return 'addProductRating_tpl.php';
    }

    /*
     * Method to retrieve entries from user on the createGroupUI_tpl.php page
     * and add it to the tbl_unesco_oer_group table
     */

    public function __createCommentSubmit()
    {
        $id = $this->getParam('id');
        $comment = $this->getParam('newComment');
        $nextPage = $this->getParam('pageName');

        $this->objDbComments->addComment($id, $this->objUser->fullname(), $comment);

        if ($nextPage != NULL) {
            $this->nextAction($nextPage);
        } else {
            return $this->__addData();
        }
    }

    /*
     * Method to retrieve entries from user on the addProductRating_tpl.php page
     * and add it to the tbl_unesco_oer_products_ratings table
     */

    public function __addProductRatingSubmit()
    {
        $id = $this->getParam('id');
        $rating = $this->getParam('newRating');

        $this->objDbProductRatings->addRating($id, $rating);
        return $this->__addData();
    }

    /*
     * Method to retrieve entries from user on the addProductRating_tpl.php page
     * and add it to the tbl_unesco_oer_products_ratings table
     */

    public function __submitProductRating()
    {
        $id = $this->getParam('productID');
        $rating = $this->getParam("rateSubmit");

        $this->objDbProductRatings->addRating($id, $rating);
        return $this->nextAction($this->getParam('prevAction'));
    }

    public function __deleteInstitution()
    {
        $puid = $this->getParam('puid');
        $name = $this->getParam('name');
        $this->objDbInstitution->deleteInstitution($puid, $name);
        return 'editInstitutionUI1_tpl.php';
    }

    public function __cancelEditInstitution()
    {
        return 'addData_tpl.php';
    }

    public function __editInstitution()
    {
        $puid = $this->getParam('puid');
        $id = $this->getParam('id');
        $name = $this->getParam('name');
        $loclat = $this->getParam('loclat');
        $loclong = $this->getParam('loclong');
        //$thumbnail=$this->getParam('thumbnail');
        $this->objDbInstitution->editInstitution($id, $puid, $loclat, $loclong, $name);
        return "editInstitutionUI1_tpl.php";
    }

    public function __editInstitutionUI2()
    {
        $puid = $this->getParam('puid');
        $id = $this->getParam('id');
        $this->setVar('InstitutionPUID', $puid);
        $this->setVar('InstitutionID', $id);
        return 'editInstitutionUI2_tpl.php';
    }

    public function __editInstitutionUI1()
    {
        return 'editInstitutionUI1_tpl.php';
    }

    /*
     * Method to display page with products to comment on
     */

    public function __chooseProductToAddLanguageTo()
    {
        return 'chooseAdditionalLanguageUI_tpl.php';
    }

    /*
     * Method to display page with entry options for comment
     */

    //public function __createComment()
    public function __addAdditionalLanguage()
    {
        $id = $this->getParam('id');
        $this->setVar('ID', $id);
        return 'createAdditionalLanguageUI_tpl.php';
    }

    /*
     * Method to retrieve entries from user on the createAdditionalLanguageUI_tpl.php page
     * and add it to the tbl_unesco_oer_group table
     */

    //public function __addLanguageSubmit()
    public function __addAdditionalLanguageSubmit()
    {
        $language = $this->getParam('language');
        $id = $this->getParam('id');
//        if (strlen($language) == 0)
//            $code = $name;

        $this->objDbAvailableProductLanguages->addProductLanguage($id, $language);
        return $this->__addData();
    }

}
?>
