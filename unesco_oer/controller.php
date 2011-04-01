<?php

class unesco_oer extends controller {

    public $objProductUtil;
    public $objDbProducts;
    public $objDbResourceTypes;
    public $objDbProductThemes;
    public $objDbProductLanguages;
    public $objDbFeaturedProduct;
    public $objFeaturedProducUtil;
    public $objDbGroups;
    public $objDbInstitution;

    /**
     *
     * @var string $objLanguage String object property for holding the
     * language object
     * @access public
     *
     */
    public $objLanguage;

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objProductUtil = $this->getObject('productutil');
        $this->objDbProducts = $this->getObject('dbproducts');
        $this->objDbResourceTypes = $this->getObject('dbresourcetypes');
        $this->objDbProductThemes = $this->getObject('dbproductthemes');
        $this->objDbProductLanguages =$this->getObject('dbproductlanguages');
        $this->objDbFeaturedProduct =$this->getObject('dbfeaturedproduct');
        $this->objFeaturedProducUtil=$this->getObject('featuredproductutil');
        $this->objDbGroups = $this->getObject('dbgroups');
        $this->objDbInstitution = $this->getObject('dbinstitution');
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
        $this->setLayoutTemplate("gift_layout_tpl.php");
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

     public function __FilterProducts() {
       $AuthFilter = $this->getParam('AuthorFilter');
       $ThemeFilter = $this->getParam('ThemeFilter');
       $LangFilter = $this->getParam('LanguageFilter');
       $page = $this->getParam('page');
       $sort = $this->getParam('SortFilter');

//     
    $adaptationstring = $this ->getParam('adaptationstring');
 

          $buildstring = $adaptationstring;
          if ($AuthFilter != Null)
              $buildstring .= ' and creator = ' . "'$AuthFilter'";

           if ($ThemeFilter != Null)
              $buildstring .= ' and theme = ' . "'$ThemeFilter'";

            if ($LangFilter != Null)
              $buildstring .= ' and language = ' . "'$LangFilter'";

            if ($sort =='Date Added')
                $buildstring .= ' order by created_on';
            else if ($sort =='Alphabetical')
               $buildstring .= ' order by title';



         $this->setVarByRef("AuthFilter", $AuthFilter);
         $this->setVarByRef("ThemeFilter", $ThemeFilter);
         $this->setVarByRef("LangFilter", $LangFilter);
         $this->setVarByRef("finalstring", $buildstring);
     

        
            return "$page";
         
        

    }

    public function requiresLogin() {
        return false;
    }

    /*
     * Method to display page for creating a new product
     */
    public function __productsUi() {
        return "tbl_products_ui_tpl.php";
    }

    /*
     * Method to display page for selecting input option
     */
    public function __addData() {
        return "addData_tpl.php";
    }

    /*
     *Method to display page with data populated for an Adaptation
     */
    public function __createAdaptation() {
        $id = $this->getParam('id');
        $this->setVar('productID', $id);
        return $this->__productsUi();
    }

    /*
     *Method to display page with adaptable products
     */
    public function __chooseProductToAdapt() {
        return 'newAdaptation_tpl.php';
    }

    public function __createOERproduct(){
        $id = NULL;
        $this->setVar('productID', $id);
        return $this->__productsUi();
    }

    /*
     * Method to retrieve entries from user on the tbl_products_ui_tpl.php page
     * and add it to the tbl_unesco_oer_products table
     */
    public function __uploadSubmit(){
        //Retrieve thumbnail and save it
        $parentID = $this->getParam('parentID');
        $thumbnailPath = '';
        if ($parentID == NULL) {
            $path = 'unesco_oer/products/' . $this->getParam('title') . '/thumbnail/';
            try {
                $results = $this->uploadFile($path);
            } catch (customException $e) {
                echo customException::cleanUp();
                exit();
            }
            $thumbnailPath = 'usrfiles/' . $results['path'];
        } else {
            $product = $this->objDbProducts->getProductByID($parentID);
            $thumbnailPath = $product['thumbnail'];
        }

        //create array for uploading into data base
        $data=array(

            'parent_id'=>$parentID,
            'title'=>$this->getParam('title'),
            'creator'=>$this->getParam('creator'),
            'keywords'=>trim($this->getParam('keywords')),
            'description'=>$this->getParam('description'),
            'created_on'=>$this->objDbProducts->now(),
            'resource_type'=>$this->getParam('resourceType'),
            'content_type'=>NULL, //TODO find out about this data type
            'format'=>NULL, //TODO find out about this data type
            'source'=>NULL, //TODO this must be removed
            'theme'=>$this->getParam('theme'),
            'language'=>$this->getParam('language'),
            'content'=>NULL,
            'thumbnail'=>$thumbnailPath
        );

        $this->objDbProducts->addProduct($data);

        return $this->__addData();
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
    public function __resourceTypeSubmit(){
        $description = $this->getParam('newTypeDescription');
        $this->objDbResourceTypes->addType($description);
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
    public function __createThemeSubmit(){
        $description = $this->getParam('newTheme');
        $this->objDbProductThemes->addTheme($description);
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
    public function __createLanguageSubmit(){
        $code = $this->getParam('newLanguageCode');
        $name = $this->getParam('newLanguageName');
        if (strlen($code) == 0) $code = $name;

        $this->objDbProductLanguages->addLanguage($code, $name);
        return $this->__addData();
    }

    /*
     * Method to retrieve the current featured unesco product from user on the featuredProductUI_tpl.php
     * return a page 1a_tpl.php with the current featured product
     */
    public function __createFeaturedProduct(){
        $featuredproduct= $this->getParam('id');
        $this->objDbFeaturedProduct->overRightCurrentFeaturedProduct($featuredproduct);
        return "1a_tpl.php";
    }

     /*
      * method to dispaly page to create a new unesco featured product
      */
     public function __featuredProductUI() {
        return "featuredProductUI_tpl.php";
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
        $path = 'unesco_oer/groups/' . $name . '/thumbnail/';
        try {
            $results = $this->uploadFile($path);
        } catch (customException $e) {
            echo customException::cleanUp();
            exit();
        }
        $thumbnailPath = 'usrfiles/' . $results['path'];

        $this->objDbGroups->addGroup($name, $loclat, $loclong,$thumbnailPath);
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
    public function __createInstitutionSubmit(){
        $name = $this->getParam('newInstitution');
        $loclat = $this->getParam('loclat');
        $loclong = $this->getParam('loclong');
        $path = 'unesco_oer/institutions/' . $name . '/thumbnail/';
        try {
            $results = $this->uploadFile($path);
        } catch (customException $e) {
            echo customException::cleanUp();
            exit();
        }
        $thumbnailPath = 'usrfiles/' . $results['path'];

        $this->objDbInstitution->addInstitution($name, $loclat, $loclong, $thumbnailPath);
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
            if (!$results['success']) { // upload was unsuccessfule
                throw new customException('Upload failed: ' . $results['reason']); //TODO return proper error page containing error
            }
        }
        return $results;
    }
}

?>
