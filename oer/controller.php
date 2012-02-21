<?php

/*
 * Entry point of oer module
 */

class oer extends controller {

    private $objMakeAdaptation;
    private $objDBProducts;
    private $objDBdownloaders;

    /**
     * Constructor for the Module
     */
    public function init() {
        //// Set the jQuery version to the one required
        $this->setVar('JQUERY_VERSION', '1.6.1');
        // Load the login helper scripts, they won't load in the template
        $objLogin = $this->getObject('showlogin', 'login');
        //$objLogin->loadAllScripts();
        $this->objMakeAdaptation = $this->getObject("makeadaptation", "oer");
        $this->objDBProducts = $this->getObject("dbproducts", "oer");
        $this->objDBdownloaders = $this->getObject('dboer_downloaders');
    }

    /**
     * this determines if the action received in the controller should need login
     * or not
     * @param type $action
     * @return type 
     */
    function requiresLogin($action = 'home') {
        $allowedActions = array(NULL, 'home', 'vieworiginalproduct', "adaptationlist",
            "viewadaptation", "fullviewadaptation", "selfregister",
            "viewsection", "checkusernameajax", "userdetailssave", "viewinstitution",
            "showcaptcha", "verifycaptcha", "viewrootsection", "printpdf",
            "downloaderedit", "printproduct", "downloadersave", "filteroriginalproduct",
            "viewgroups", "viewgroup","showproductlistingaslist");
        if (in_array($action, $allowedActions)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Standard Dispatch Function for Controller
     *
     * @access public
     * @param string $action Action being run
     * @return string Filename of template to be displayed
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
        $this->setLayoutTemplate('layout_tpl.php');
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

    // Beginning of Functions Relating to Actions in the Controller //

    /**
     *
     * This is the default function
     */
    private function __home() {
        $this->setVar("mode", "grid");
        return "productlisting_tpl.php";
    }

    /**
     * renders available groupd
     * @return strings
     */
    private function __viewgroups() {
        return "grouplist_tpl.php";
    }

    /**
     * renders details of a given group
     * @return string 
     */
    private function __viewgroup() {
        $contextCode = $this->getParam("contextcode");
        $dbContent=  $this->getObject("dbcontext", "context");
        $dbContent->joinContext($contextCode);
        $this->setVarByRef("contextcode", $contextCode);
        return "viewgroup_tpl.php";
    }

    private function __showproductlistingaslist() {
        $this->setVar("mode", "list");
        return "productlisting_tpl.php";
    }

    /**
     * joins the current user to the selected context. If the user cant, probably
     * due to permissions issue, they are redirected to an error message
     * @return type 
     */
    private function __joincontext() {
        $contextCode = $this->getParam("contextcode");
        $dbContext = $this->getObject("dbcontext", "context");
        if ($dbContext->joinContext($contextCode)) {
            return $this->nextAction('viewgroup', array("contextcode" => $contextCode));
        } else {
            return $this->nextAction('join', array('error' => 'unabletoenter'), 'context');
        }
    }

    /**
     *
     * This is loads adaptation home
     */
    private function __adaptationlist() {
        $productId = $this->getParam('productid', Null);
        $this->setVar("productid", $productId);
        return "adaptationlist_tpl.php";
    }

    /**
     *
     * This is loads list of products that can be adapted
     */
    private function __adaptableproductslist() {
        return "adaptableproductslist_tpl.php";
    }

    /**
     * this launches control panel
     * @return type 
     */
    private function __cpanel() {
        return "cpanel_tpl.php";
    }

    private function __viewthemes() {
        return "themes_tpl.php";
    }

    /////////////////////////////////////////////////////////////////
    /*

      KEYWORDS FUNCTIONS
     */
    //////////////////////////////////////////////////////////////////

    function __viewkeywords() {
        return "keywords_tpl.php";
    }

    function __newkeyword() {
        return "addeditkeywords_tpl.php";
    }

    function __savenewkeyword() {
        $objCommentsManager = $this->getObject("commentsmanager", "oer");
        return $objCommentsManager->addNewComment();
    }

    /////////////////////////////////////////////////////////////////
    /*

      COMMENTS FUNCTIONS
     */
    //////////////////////////////////////////////////////////////////

    function __addcomment() {
        $objCommentsManager = $this->getObject("commentsmanager", "oer");
        $resultsArr = $objCommentsManager->addNewComment();
        return $this->nextAction('viewadaptation', $resultsArr);
    }

    /////////////////////////////////////////////////////////////////
    /*

      ORIGINAL PRODUCT FUNCTIONS
     */
    ///////////////////////////////////////////////////////////////

    /**
     * this returns the template for displaying details of the selected product
     * @return string
     */
    function __vieworiginalproduct() {
        $id = $this->getParam("id");
        $this->setVarByRef("id", $id);
        return "vieworiginalproduct_tpl.php";
    }

    /**
     * makes the selected product featured
     * @return type 
     */
    function __featureoriginalproduct() {
        $objProductManager = $this->getObject("productmanager", "oer");
        $objProductManager->makefeatured();
        $id = $this->getParam("productid");
        $params = array("id" => $id);
        $isOriginalProduct = $this->objDBProducts->isOriginalProduct($id);

        if ($isOriginalProduct)
            return $this->nextAction('vieworiginalproduct', $params);
        else
            return $this->nextAction('viewadaptation', $params);
    }

    /**
     * this returns the template for displaying the selected adaptation
     * @return string
     */
    function __viewadaptation() {
        $id = $this->getParam("id");
        $this->setVarByRef("id", $id);        
        return "viewadaptation_tpl.php";
    }

    /**
     * this returns the template for displaying the selected adaptation
     * @return string
     */
    function __fullviewadaptation() {
        $id = $this->getParam("id");
        $this->setVarByRef("id", $id);
        return "fullviewadaptation_tpl.php";
    }

    /**
     * this returns a template for creating a new product
     */
    function __newproductstep1() {
        $this->setVar("step", "1");
        $this->setVar("id", "");
        return "addeditproduct_tpl.php";
    }

    /**
     * Saves the original product in step 1
     */
    function __saveoriginalproductstep1() {
        $objProductManager = $this->getObject("productmanager", "oer");
        $id = $objProductManager->saveNewProductStep1();
        die($id);
    }

    /**
     * Saves the original product in step 2
     */
    function __saveoriginalproductstep2() {
        $objProductManager = $this->getObject("productmanager", "oer");
        $id = $objProductManager->updateProductStep2();
        $this->setVar("step", "3");
        $this->setVarByRef('id', $id);
        return "addeditproduct_tpl.php";
    }

    /**
     * Saves the original product in step 3
     */
    function __saveoriginalproductstep3() {
        $objProductManager = $this->getObject("productmanager", "oer");
        $id = $objProductManager->updateProductStep3();
        die($id);
    }

    /**
     * Saves the original product in step 4
     */
    function __saveoriginalproductstep4() {
        $objProductManager = $this->getObject("productmanager", "oer");
        $id = $objProductManager->updateProductStep4();
        return $this->nextAction("vieworiginalproduct", array("id"=>$id));
    }

    //EDIT Functions
    // Adaptations home
    // Manage adaptations
    function __makeadaptation() {
        $id = $this->getParam("id", Null);
        $productid = $this->getParam("productid", Null);
        $mode = $this->getParam("mode", "new");
        $this->setVarByRef("id", $id);
        $this->setVarByRef("productid", $productid);
        $this->setVarByRef("mode", $mode);
        $errors = $this->getParam("errors", "");
        $this->setVarByRef("errors", $errors);
        $this->setVar("step", "1");
        return "makeadaptation_tpl.php";
    }

    /**
     * Saves the section adaptation data
     */
    function __addadaptationsection() {
        $mode = $this->getParam("mode", "new");
        $id = $this->getParam("id", Null);
        $productid = $this->getParam("productid", Null);

        if ($mode == "edit" && ($id != Null || !empty($id))) {
            $id = $this->objMakeAdaptation->updateAdaptationSection();
            $mode = "edit";
        } else {
            $id = $this->objMakeAdaptation->addNewAdaptationSection();
            $mode = "edit";
        }
        $this->setVarByRef("id", $id);
        $this->setVarByRef("productid", $productid);
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("errors", $errors);
        $params = array("id" => $id, "productid" => $productid, "mode" => $mode);
        return $this->nextAction('makeadaptation', $params);
    }

    function __editoriginalproductstep1() {
        $id = $this->getParam("id");
        $this->setVarByRef("id", $id);
        $this->setVar("step", "1");
        return "addeditproduct_tpl.php";
    }

    function __editoriginalproductstep2() {
        $id = $this->getParam("id");
        $this->setVarByRef("id", $id);
        $this->setVar("step", "2");
        return "addeditproduct_tpl.php";
    }

    function __editoriginalproductstep3() {
        $id = $this->getParam("id");
        $this->setVarByRef("id", $id);
        $this->setVar("step", "3");
        return "addeditproduct_tpl.php";
    }

    function __editoriginalproductstep4() {
        $id = $this->getParam("id");
        $this->setVarByRef("id", $id);
        $this->setVar("step", "4");
        return "productstep4_tpl.php";
    }

    // Adaptations home
    // record downloader data if not logged in
    function __downloaderedit() {
        $id = $this->getParam("id", Null);
        $productid = $this->getParam("productid", Null);
        $producttype = $this->getParam("producttype", "adaptation");
        $mode = $this->getParam("mode", "add");
        $this->setVarByRef("id", $id);
        $this->setVarByRef("productid", $productid);
        $this->setVarByRef("producttype", $producttype);
        $this->setVarByRef("mode", $mode);
        $errors = $this->getParam("errors", "");
        $this->setVarByRef("errors", $errors);
        return "downloaderedit_tpl.php";
    }

    /**
     * Saves the downloader info
     */
    function __downloadersave() {
        $data = array(
            'fname' => $this->getParam("fname"),
            'lname' => $this->getParam("lname"),
            'email' => $this->getParam("email"),
            'organisation' => $this->getParam("organisation"),
            'occupation' => $this->getParam("occupation"),
            'downloadreason' => $this->getParam("downloadreason"),
            'useterms' => $this->getParam("useterms"),
            'productid' => $this->getParam("productid")
        );
        //die(print_r($data));
        $id = $this->objDBdownloaders->insertSingle($data);
        // Note we are not returning a template as this is an AJAX save.
        if ($id !== NULL && $id !== FALSE) {
            die($id);
        } else {
            die("ERROR_DATA_INSERT_FAIL");
        }
    }

    // Manage adaptations
    function __editadaptationstep1() {
        $id = $this->getParam("id");
        $mode = $this->getParam("mode", "edit");
        $this->setVarByRef("id", $id);
        $this->setVarByRef("mode", $mode);
        $errors = $this->getParam("errors", "");
        $this->setVarByRef("errors", $errors);
        $this->setVar("step", "1");
        return "adaptation_tpl.php";
    }

    function __editadaptationstep2() {
        $id = $this->getParam("id");
        $mode = $this->getParam("mode", "edit");
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("id", $id);
        $this->setVar("step", "2");
        return "adaptation_tpl.php";
    }

    function __editadaptationstep3() {
        $id = $this->getParam("id");
        $mode = $this->getParam("mode", "edit");
        $this->setVarByRef("mode", $mode);
        $errors = $this->getParam("errors", "");
        $this->setVarByRef("errors", $errors);
        $this->setVarByRef("id", $id);
        $this->setVar("step", "3");
        return "adaptation_tpl.php";
    }

    function __editadaptationstep4() {
        $id = $this->getParam("id");
        $mode = $this->getParam("mode", "edit");
        $this->setVarByRef("mode", $mode);
        $errors = $this->getParam("errors", "");
        $this->setVarByRef("errors", $errors);
        $this->setVarByRef("id", $id);
        $this->setVar("step", "3");
        return "uploadadaptation_tpl.php";
    }

    /**
     * displays the form for uploading product theme
     * @return string 
     */
    function __editoriginalproductstep5() {
        $id = $this->getParam("id");
        $this->setVarByRef("id", $id);
        return "upload_tpl.php";
    }

    /**
     * Saves the adaptation in step 1
     */
    function __saveadaptationstep1() {
        $result = $this->objMakeAdaptation->saveNewAdaptationStep1();
        $mode = $this->getParam("mode", "edit");
        $errors = $this->getParam("errors", "");
        $this->setVarByRef("errors", $errors);
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("id", $result);
        $this->setVar("step", "2");
        return "adaptation_tpl.php";
    }

    /**
     * Saves the adaptation in step 2
     */
    function __saveadaptationstep2() {
        $id = $this->objMakeAdaptation->updateAdaptationStep2();
        $mode = $this->getParam("mode", "edit");
        $this->setVarByRef("mode", $mode);
        $errors = $this->getParam("errors", "");
        $this->setVarByRef("errors", $errors);
        $this->setVarByRef("id", $id);
        $this->setVar("step", "3");
        return "adaptation_tpl.php";
    }

    /**
     * Saves the adaptation in step 3
     */
    function __saveadaptationstep3() {
        $id = $this->objMakeAdaptation->updateAdaptationStep3();
        $mode = $this->getParam("mode", "edit");
        $errors = $this->getParam("errors", "");
        $this->setVarByRef("errors", $errors);
        $this->setVarByRef("mode", $mode);
        $this->setVarByRef("id", $id);
        return "uploadadaptation_tpl.php";
    }

    /**
     * Updates the original product in step 1
     */
    function __updateoriginalproductstep1() {
        $objProductManager = $this->getObject("productmanager", "oer");
        $id = $objProductManager->updateProductStep1();
        die($id);
    }

    //DELETE Functions

    /**
     * deletes a product. Assumes the deletion is already confirmed
     */
    function __deleteoriginalproduct() {
        $id = $this->getParam("id");
        $objProductManager = $this->getObject("productmanager", "oer");
        $objProductManager->deleteOriginalProduct();
        $this->setVar("mode", "grid");
        return "productlisting_tpl.php";
    }

    /*
     * Function that prints product data in pdf format
     */

    public function __printproduct() {
        $generator = $this->getObject('documentgenerator', 'oer');
        $prodType = $this->getParam('producttype', "adaptation");
        $fileExt = "." . $this->getParam('downloadformat', "pdf");
        $notifyupdateadaptation = $this->getParam('notifyupdateadaptation', "undefined");
        $notifyupdateoriginal = $this->getParam('notifyupdateoriginal', "undefined");
        $productId = $this->getParam('productid');
        $id = $this->getParam('id');
        $data = array(
            'notifyadaptation' => $notifyupdateadaptation,
            'notifyoriginal' => $notifyupdateoriginal,
            'downloadformat' => $fileExt,
            'downloadtime' => date("Y-m-d H:i:s")
        );
        $upid = $this->objDBdownloaders->updateSingle($id, $data);

        if ($fileExt == ".pdf") {
            if ($prodType == "adaptation") {
                die($generator->showProductPDF($productId, $prodType));
            }
        } else {
            if ($prodType == "adaptation") {
                die($generator->showProductWordFormats($productId, $prodType, $fileExt));
            }
        }
    }

    /*
     * Function that prints product data in MSWord format
     */

    public function __printmsword() {
        $generator = $this->getObject('documentgenerator', 'oer');
        $prodType = $this->getParam('type');
        if ($prodType == "adaptation") {
            return $generator->showProductMSWord($this->getParam('id'), $prodType);
        }
    }

    /**
     * deletes a product. Assumes the deletion is already confirmed
     */
    function __deleteadaptation() {
        $objProductManager = $this->getObject("productmanager", "oer");
        $id = $this->getParam("id");
        $objProductManager->deleteOriginalProduct($id);
        $this->setVar("mode", "grid");
        return "adaptationlist_tpl.php";
    }

    /**
     * Used to do the actual upload of product thumbnail
     *
     */
    function __uploadproductthumbnail() {
        $objProductManager = $this->getObject("productmanager", "oer");
        $params = $objProductManager->doajaxupload();
        return $this->nextAction('showthumbnailuploadresults', $params);
    }

    function __uploadgroupthumbnail() {
        $objGroupManager = $this->getObject("groupmanager", "oer");
        $params = $objGroupManager->doajaxupload();
        return $this->nextAction('showthumbnailuploadresults', $params);
    }

    ///////////////////////////////////////////////////////////////////////
    /*

      AUX product functions : Rate, comment, bookmark etc

     */
    //////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * This rates a given product
     */
    function __rateproduct() {
        $objProductManager = $this->getObject("productmanager", "oer");
        $totalRating = $objProductManager->rateProduct();
        echo '{"votes":' . $totalRating . ',"sum":0,"avg":"3"}';
        die();
    }

    /*


      PRODUCT SECTION FUCNTIONS


     *//////////////////////////////////////////////////////////////////

    /**
     * returns the template for adding a new sections
     */
    function __addsectionnode() {
        $productid = $this->getParam("productid");
        $sectionId = '';
        $nodeType = '';
        $isOriginalProduct = $this->objDBProducts->isOriginalProduct($productid);
        if ($isOriginalProduct)
            $isOriginalProduct = 1;
        else
            $isOriginalProduct = 0;
        $data = $productid . '|' . $sectionId . '|' . $isOriginalProduct . '|' . $nodeType;
        $this->setVarByRef("data", $data);
        return "addeditsectionnode_tpl.php";
    }

    /**
     * allows user to edit section node
     * @return type 
     */
    function __editsectionnode() {
        $id = $this->getParam("id");
        $productid = $this->getParam("editproductid");
        $nodeType = $this->getParam("nodetype", "curriculum");
        $isOriginalProduct = $this->objDBProducts->isOriginalProduct($productid);
        if ($isOriginalProduct)
            $isOriginalProduct = 1;
        else
            $isOriginalProduct = 0;
        $data = $productid . '|' . $id . '|' . $isOriginalProduct . '|' . $nodeType;
        $this->setVarByRef("data", $data);
        return "addeditsectionnode_tpl.php";
    }

    /**
     * Allows the user to add section content
     * @return type 
     */
    function __editsectioncontent() {
        $sectionid = $this->getParam("id");
        $productid = $this->getParam("productid");
        $isOriginalProduct = $this->objDBProducts->isOriginalProduct($productid);
        $data = $productid . '|' . $sectionid;
            $this->setVarByRef("data", $data);            
        if ($isOriginalProduct) {            
            return "addeditsectioncontent_tpl.php";
        } else {
            $mode = $this->getParam("mode", "new");
            $this->setVarByRef("id", $sectionid);
            $this->setVarByRef("productid", $productid);
            $this->setVarByRef("mode", $mode);
            $errors = $this->getParam("errors", "");
            $this->setVarByRef("errors", $errors);
            $this->setVar("step", "1");
            return "makeadaptation_tpl.php";
        }
    }

    function __viewsection() {
        $sectionid = $this->getParam("sectionid");
        $productid = $this->getParam("productid");
        $nodeType = $this->getParam("nodetype");
        if ($nodeType == 'section') {
            $data = $productid . '|' . $sectionid;
            $this->setVarByRef("data", $data);
            return "viewproductsection_tpl.php";
        } else {

            $this->setVarByRef("id", $productid);
            return "vieworiginalproduct_tpl.php";
        }
    }

    /**
     * Creates a new curriculum and returns to view product page
     * @return type 
     */
    function __createcurriculum() {
        $productid = $this->getParam("productid");
        $sectionManager = $this->getObject("sectionmanager", "oer");
        $id = $sectionManager->saveCurriculum();
        $params = array("id" => $id);
        $isOriginalProduct = $this->objDBProducts->isOriginalProduct($productid);
        if ($isOriginalProduct) {
            return $this->nextAction('vieworiginalproduct', $params);
        } else {
            return $this->nextAction('viewadaptation', $params);
        }
    }

    function __editcurriculum() {
        $productid = $this->getParam("productid");
        $sectionManager = $this->getObject("sectionmanager", "oer");
        $id = $sectionManager->updateCurriculum();
        $params = array("id" => $id);
        $isOriginalProduct = $this->objDBProducts->isOriginalProduct($productid);
        if ($isOriginalProduct) {
            return $this->nextAction('vieworiginalproduct', $params);
        } else {
            return $this->nextAction('viewadaptation', $params);
        }
    }

    /**
     * saves a new section
     * @return type 
     */
    function __savesection() {
        $sectionManager = $this->getObject("sectionmanager", "oer");
        $id = $sectionManager->saveSection();
        $params = array("id" => $id);
        return $this->nextAction('vieworiginalproduct', $params);
    }

    function __deletesectionnode() {
        $sectionManager = $this->getObject("sectionmanager", "oer");
        $sectionManager->deleteSectionNode();
        $params = array("id" => $this->getParam("editproductid"));
        return $this->nextAction('vieworiginalproduct', $params);
    }

    /**
     * Creates a new node in the sections tree
     * @return type 
     */
    function __createsectionnode() {
        $sectionManager = $this->getObject("sectionmanager", "oer");
        $productId = $this->getParam("productid");
        $isOriginalProduct = $this->getParam("isoriginalproduct");
        $id = $sectionManager->saveSectionNode();
        if ($isOriginalProduct == 1) {
            $params = array("nodeid" => $id, "id" => $productId);
            return $this->nextAction('vieworiginalproduct', $params);
        } else {
            $params = array("nodeid" => $id, "id" => $productId);
            return $this->nextAction('viewadaptation', $params);
        }
    }

    /**
     * Creates new content on a section
     * @return type 
     */
    function __createsectioncontent() {
        $sectionManager = $this->getObject("sectionmanager", "oer");
        $sectionId = $this->getParam("sectionid");
        $productId = $this->getParam("productid");
        $id = $sectionManager->saveSectionContent();
        $params = array("nodeid" => $sectionId, "id" => $productId);
        return $this->nextAction('vieworiginalproduct', $params);
    }

    /**
     * Updates section node info
     * @return type 
     */
    function __updatesectionnode() {
        $sectionManager = $this->getObject("sectionmanager", "oer");
        $productId = $this->getParam("productid");
        $isOriginalProduct = $this->getParam("isoriginalproduct");
        $id = $sectionManager->updateSectionNode();
        if ($isOriginalProduct == 1) {
            $params = array("nodeid" => $id, "id" => $productId);
            return $this->nextAction('vieworiginalproduct', $params);
        } else {
            $params = array("id" => $productId);
            return $this->nextAction('viewadaptation', $params);
        }
    }

    /**
     * updates the sections content
     * @return type 
     */
    function __updatesectioncontent() {
        $sectionManager = $this->getObject("sectionmanager", "oer");
        $sectionId = $this->getParam("sectionid");
        $productId = $this->getParam("productid");
        $id = $sectionManager->updateSectionContent();
        $params = array("nodeid" => $sectionId, "id" => $productId);
        return $this->nextAction('vieworiginalproduct', $params);
    }

    ///////////////////////////////////////////////////////////////
    /*

      The themes functions

     */
    ///////////////////////////////////////////////////////////////
    /**
     * returns form for creating new umbrella theme
     * @return type 
     */
    function __newumbrellatheme() {
        return "addeditumbrellatheme_tpl.php";
    }

    /**
     * Save a new umbrella theme
     * @return type 
     */
    function __saveumbrellatheme() {
        $objThemeManager = $this->getObject("thememanager", "oer");
        $objThemeManager->addNewUmbrellaTheme();
    }

    /**
     * returns form for creating new theme
     */
    function __newtheme() {
        return "addedittheme_tpl.php";
    }

    /**
     * Saves the new theme
     */
    function __savetheme() {
        $objThemeManager = $this->getObject("thememanager", "oer");
        $objThemeManager->addNewTheme();
    }

    ///////////////////////////////////////////////////////////////////
    /*


      GROUP FUNCTIONS

     */
    //////////////////////////////////////////////////////////////////////

    /**
     *
     * Method to open the edit/add form for groups
     *
     * @return string Template
     */
    function __creategroupstep1() {
        $this->setVar("step", "1");
        $this->setVar("contextcode", "");
        return 'groupedit_tpl.php';
    }

    function __editgroupstep1() {
        $this->setVar("step", "1");
        $this->setVar("contextcode", $this->getParam("contextcode"));
        return 'groupedit_tpl.php';
    }

    function __editgroupstep2() {
        $this->setVar("step", "2");
        $this->setVar("contextcode", $this->getParam("contextcode"));
        return 'groupedit_tpl.php';
    }

    function __editgroupstep3() {
        $this->setVar("step", "3");
        $this->setVar("contextcode", $this->getParam("contextcode"));
        return 'groupedit_tpl.php';
    }

    function __updategroupstep3() {
        $groupManager = $this->getObject("groupmanager", "oer");
        $groupManager->updateGroupStep3();
        return $this->nextAction("viewgroup", array("contextcode" => $this->getParam("contextcode")));
    }

    /**
     *
     *
     * @return string Template
     */
    function __savegroupstep1() {
        $groupManager = $this->getObject("groupmanager", "oer");
        $contextCode = $groupManager->saveGroupStep1();
        return $this->nextAction("editgroupstep3", array("contextcode" => $contextCode));
    }

    /**
     * updates a group with modified data. We go via the group manager to accomplish
     * this. Two entities are updated: the context, which is the primary group entity,
     * and the extra group params, stored diffirently
     * @return type 
     */
    function __updategroupstep1() {
        $groupManager = $this->getObject("groupmanager", "oer");
        $contextCode = $groupManager->updateGroupStep1();
        return $this->nextAction("editgroupstep3", array("contextcode" => $contextCode));
    }

    /**
     *
     * Method to open the edit/add form for insitution types.
     *
     * @return string Template
     * @access public
     * 
     */
    public function __institutiontypeedit() {
        return 'institutiontypeedit_tpl.php';
    }

    /**
     *
     * Method to open the edit/add form for insitution types
     *
     * @return string Template
     * @access public
     * 
     */
    public function __institutiontypesave() {
        // Initialise the object that will do the saving.
        $objDbInstitutionTypes = $this->getObject('dbinstitutiontypes');
        // Get the mode (edit or add).
        $mode = $this->getParam('mode', 'add');
        $id = $this->getParam('id', NULL);
        $type = $this->getParam('type');
        if ($mode == 'edit') {
            $objDbInstitutionTypes->editType($id, $type);
        } else {
            $id = $objDbInstitutionTypes->addType($type);
            die($id);
        }
        // Note we are not returning a template as this is an AJAX save.
        if ($id !== NULL && $id !== FALSE) {
            die($id);
        } else {
            die("ERROR_DATA_INSERT_FAIL");
        }
    }

    public function __institutionlisting() {
        return "institutionlisting_tpl.php";
    }

    /**
     *
     * Method to open the edit/add form for insitutions
     *    
     *
     * @return string Template
     * @access public
     * 
     */
    public function __institutionedit() {
        $id = $this->getParam("id");
        $institutionManager = $this->getObject("institutionmanager", "oer");
        if ($id == null) {
            $id = $institutionManager->addInstitution(
                    'Unknown', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', '');
        }
        $this->setVarByRef("id", $id);
        return 'institutionedit_tpl.php';
    }

    /**
     * used for uploading institution thumbnail
     * @return type 
     */
    function __uploadinstitutionthumbnail() {
        $institutionManager = $this->getObject("institutionmanager", "oer");
        $params = $institutionManager->doajaxupload();
        return $this->nextAction('showthumbnailuploadresults', $params);
    }

    /**
     *
     * Method to open view of an insitution
     *
     * @return string Template
     * @access public
     *
     */
    public function __viewinstitution() {
        $id = $this->getParam("id");
        $this->setVarByRef("id", $id);
        return 'viewinstitution_tpl.php';
    }

    /**
     *
     * Method to open the edit/add form for insitutions
     *     Added by DWK to refactor function from origional
     *
     * @return string Template
     * @access public
     * 
     */
    public function __institutionsave() {
        // Initialise the object that will do the saving.
        $objInstitutionManager = $this->getObject('institutionmanager');
        // Get all the params from the form.
        $name = $this->getParam('name');
        $description = $this->getParam('description');

        $type = $this->getParam('type');
        $country = $this->getParam('country');
        $address1 = $this->getParam('address1');
        $address2 = $this->getParam('address2');
        $address3 = $this->getParam('address3');
        $zip = $this->getParam('zip');
        $city = $this->getParam('city');
        $websiteLink = $this->getParam('websitelink');
        $keyword1 = $this->getParam('keyword1');
        $keyword2 = $this->getParam('keyword2');
        $thumbnail = $this->getParam('thumbnail'); // ====== Where is this from?

        $onestepid = $this->getParam('productID'); // ====== Where is this from?
        $groupid = $this->getParam('groupid'); // ====== Where is this from?
        // Get the mode (edit or add).
        $mode = $this->getParam('mode', 'add');
        $id = $this->getParam('id', NULL);
        $objInstitutionManager->editInstitution(
                $id, $name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail);
        $this->nextAction("institutionlisting", array("id" => $id));
    }

    /**
     * This method is used to display the results of uploading product thumbnail
     * 
     */
    function __showthumbnailuploadresults() {
        $this->setVar('pageSuppressToolbar', TRUE);
        $this->setVar('pageSuppressBanner', TRUE);
        $this->setVar('suppressFooter', TRUE);

        $id = $this->getParam('id');
        $this->setVarByRef('id', $id);

        $fileid = $this->getParam('fileid');
        $this->setVarByRef('fileid', $fileid);

        $filename = $this->getParam('filename');
        $this->setVarByRef('filename', $filename);

        return 'thumbnailuploadresults_tpl.php';
    }

}

?>