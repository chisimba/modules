<?php

/*
 * Entry point of oer module
 */

class oer extends controller {

    /**
     * Constructor for the Module
     */
    public function init() {

        //// Set the jQuery version to the one required
        $this->setVar('JQUERY_VERSION', '1.6.1');
    }

    /**
     * this determines if the action received in the controller should need login
     * or not
     * @param type $action
     * @return type 
     */
    function requiresLogin($action = 'home') {
        $allowedActions = array(NULL, 'home', 'vieworiginalproduct', "1b", "viewadaptation");
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

    private function __showproductlistingaslist() {
        $this->setVar("mode", "list");
        return "productlisting_tpl.php";
    }

    /**
     *
     * This is loads adaptation home
     */
    private function __1b() {

        return "1b_tpl.php";
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
        $objKeywordsManager = $this->getObject("keywordsmanager", "oer");
        return $objKeywordsManager->addNewKeyWord();
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
     * this returns the template for displaying the selected adaptation
     * @return string
     */
    function __viewadaptation() {
        $id = $this->getParam("id");
        $this->setVarByRef("id", $id);
        return "viewadaptation_tpl.php";
    }

    /**
     * this returns a template for creating a new product
     */
    function __newproductstep1() {
        $this->setVar("step", "1");

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
        $this->setVarByRef("id", $id);
        $this->setVar("step", "4");
        return "productstep4_tpl.php";
    }

    //EDIT Functions

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
    // Manage adaptations
    function __editadaptationstep1() {
        $id = $this->getParam("id");
        $mode = $this->getParam("mode", "edit");
        $this->setVarByRef("id", $id);
        $this->setVarByRef("mode", $mode);
        $this->setVar("step", "1");
        return "adaptation_tpl.php";
    }

    function __editadaptationstep2() {
        $id = $this->getParam("id");
        $this->setVarByRef("id", $id);
        $this->setVar("step", "2");
        return "adaptation_tpl.php";
    }

    function __editadaptationstep3() {
        $id = $this->getParam("id");
        $this->setVarByRef("id", $id);
        $this->setVar("step", "3");
        return "adaptation_tpl.php";
    }

    function __editadaptationstep4() {
        $id = $this->getParam("id");
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
        $objAdaptationManager = $this->getObject("adaptationmanager", "oer");
        $result = $objAdaptationManager->saveNewAdaptationStep1();

        $this->setVarByRef("id", $result);
        $this->setVar("step", "2");
        return "adaptation_tpl.php";
    }

    /**
     * Saves the adaptation in step 2
     */
    function __saveadaptationstep2() {
        $objAdaptationManager = $this->getObject("adaptationmanager", "oer");
        $id = $objAdaptationManager->updateAdaptationStep2();
        $this->setVarByRef("id", $id);
        $this->setVar("step", "3");
        return "adaptation_tpl.php";
    }

    /**
     * Saves the adaptation in step 3
     */
    function __saveadaptationstep3() {
        $objAdaptationManager = $this->getObject("adaptationmanager", "oer");
        $id = $objAdaptationManager->updateAdaptationStep3();
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

    /**
     * deletes a product. Assumes the deletion is already confirmed
     */
    function __deleteadaptation() {
        $id = $this->getParam("id");
        $objProductManager = $this->getObject("productmanager", "oer");
        $objProductManager->deleteOriginalProduct();
        $this->setVar("mode", "grid");
        return "1b_tpl.php";
    }

    /**
     * Used to do the actual upload of product thumbnail
     *
     */
    function __uploadproductthumbnail() {
        $objProductManager = $this->getObject("productmanager", "oer");
        $params = $objProductManager->doajaxupload();
        return $this->nextAction('showProductthumbnailuploadresults', $params);
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

    /**
     *
     * Method to open the edit/add form for groups
     *
     * @return string Template
     */
    function __groupedit() {
        return 'groupedit_tpl.php';
    }

    /**
     *
     * Method to do ajax save for groups
     *
     * @return string Template
     */
    function __groupsave() {
        $name = $this->getParam('name');
        $email = $this->getParam('email');
        $onestepid = $this->getParam('productID', NULL);
        $address = $this->getParam('address');
        $city = $this->getParam('city');
        $state = $this->getParam('state');
        $country = $this->getParam('country');
        $postalcode = $this->getParam('postalcode');
        $website = $this->getParam('website');
        $institution = $this->getParam('institutionlink');
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
        $objThumbUploader = $this->getObject('thumbnailuploader');
        $results = $objThumbUploader->uploadThumbnail($path);
        $thumbnail = 'usrfiles/' . $results['path'];
        // Get the mode (edit or add).
        $mode = $this->getParam('mode', 'add');
        $id = $this->getParam('id', NULL);
        $objDbGroups = $this->getObject('dbgroups', 'oer');
        if ($mode == 'edit') {
            $id = $objDbGroups->saveNewGroup(
                    $name, $email, $address, $city, $state, $country, $postalcode, $website, $institution, $loclat, $loclong, $description, $admin, $thumbnail, $description_one, $description_two, $description_three, $description_four);
        } else {
            $objDbGroups->updategroup(
                    $id, $name, $email, $address, $city, $state, $country, $postalcode, $website, $institution, $loclat, $loclong, $description, $thumbnail, $description_one, $description_two, $description_three, $description_four);
        }
        // Note we are not returning a template as this is an AJAX save.
        if ($id !== NULL && $id !== FALSE) {
            die($id);
        } else {
            die("ERROR_DATA_INSERT_FAIL");
        }
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

    /**
     *
     * Method to open the edit/add form for insitutions
     *     Added by DWK to refactor function from origional
     *
     * @return string Template
     * @access public
     * 
     */
    public function __institutionedit() {
        return 'institutionedit_tpl.php';
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
        if ($mode == 'edit') {
            $objInstitutionManager->editInstitution(
                    $id, $name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail);
        } else {
            $id = $objInstitutionManager->addInstitution(
                    $name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail);
        }

        // Note we are not returning a template as this is an AJAX save.
        if ($id !== NULL && $id !== FALSE) {
            die($id);
        } else {
            die("ERROR_DATA_INSERT_FAIL");
        }
    }

    /**
     * This method is used to display the results of uploading product thumbnail
     * 
     */
    function __showproductthumbnailuploadresults() {
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