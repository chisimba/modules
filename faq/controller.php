<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Controller class for FAQ2 module
* @author Joel kimilu 2008
* @copyright 2008 University of the Western Cape

*/
class faq2 extends controller
{
    public $objUser;
    public $ojUserContext;
	
    public $objDate;
	
    public $objLanguage;
	
    public $objDbFaqCategories;
	
    public $objDbFaqEntries;
	
    public $contextId;
	
    public $contextTitle;
	
    public $categoryId;
     
    public $objCreativecommons;
    public $objConfig;
    public $objLicenseChooser;
    public  $languageCodes;
   

    /**
    * The Init function
    */
    public function init()
    {
        $this->objConfig =& $this->getObject('altconfig', 'config');
        $this->objUser =& $this->getObject('user', 'security');
        $this->objDate =& $this->getObject('dateandtime', 'utilities');
        $this->objHelp =& $this->getObject('helplink','help');
        $this->objLanguage =& $this->getObject('language','language');
        $this->objFeatureBox = $this->newObject('featurebox', 'navigation');
        $this->objDbFaqCategories =& $this->getObject('dbfaqcategories');
        $this->faq2Tools = $this->getObject('faq2tools');
        $this->objDbFaqEntries =& $this->getObject('dbfaqentries');
        $this->objCreativecommons=$this->newObject('licensechooserdropdown','creativecommons');
        $this->objLicenseChooser=$this->getObject('licensechooser','creativecommons');
        $this->objLicense=$this->getObject('displaylicense','creativecommons');
        

        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Set it to log once per session
        //$this->objLog->logOncePerSession = TRUE;
        //Log this module call
        $this->objLog->log();
       
    }

    /**
    * The dispatch funtion
    * @param string $action The action
    */
    public function dispatch($action=NULL)
    {
        // Set the layout template for faq - includes the context menu
      
        $this->setLayoutTemplate("context_layout_tpl.php");
        $this->setVarByRef('objUser', $this->objUser);
        $this->setVarByRef('objHelp', $this->objHelp);
        $this->setVarByRef('objLanguage', $this->objLanguage);
        // Set the error string
        $error = "";
        $this->setVarByRef("error", $error);
        // Get the context
        $this->objDbContext = &$this->getObject('dbcontext','context');
        $this->contextCode = $this->objDbContext->getContextCode();
        // If we are not in a context...
        if ($this->contextCode == null) {
            $this->contextId = "";
            $this->setVarByRef('contextId', $this->contextId);
            $this->contextTitle = "Lobby";
            $this->setVarByRef('contextTitle', $this->contextTitle);
        }
        // ... we are in a context
        else {
            $this->contextId = $this->contextCode;
            $this->setVarByRef('contextId', $this->contextId);
            $contextRecord = $this->objDbContext->getContextDetails($this->contextCode);
            $this->contextTitle = $contextRecord['title'];
            $this->setVarByRef('contextTitle', $this->contextTitle);
        }
        $action = strtolower($action);
        
    

        // return the name of the template to use  because it is a page content template
        // the file must live in the templates/content subdir of the module directory
        switch($action){
            // Change the category
            case 'changecategory':
                return $this->view();
           // Add an entry
            case "addfaqentry":
                return $this->addFaqEntry();
            //Add confirm
            case "addconfirm":
                return $this->addFaqEntryConfirm();
            // Edit an entry
            case "edit":
                return $this->edit();
            //Edit confirm
            case "editfaqentryconfirm":
                return $this->editFaqEntryConfirm();
            // Delete an entry
            case "deletefaqentryconfirm":
                return $this->deleteFaqEntryConfirm();
            // translate this faq
            case "translate":
                return $this->translateFaq();
            break;
            case "translateconfirm":
                $this->translateConfirm();
                break;
            case "edittranslationconfirm":
                return $this->editFaqTranslationConfirm();
            break;
   // Add an entry
            case "addcategory":
                
                $this->addCategory();
                return "add_category_tpl.php";
            // Add Confirm
            case "addcategoryconfirm":
                $this->addCategoryConfirm();
                break;
            // Edit an entry
            case "editcategory":
                $this->editCategory();
                return "edit_category_tpl.php";
            break;
            // Edit Confirm
            case "editcategoryconfirm":
                $this->editCategoryConfirm();
                break;
            // Delete an entry
            case "deletecategoryconfirm":
                $this->deleteCategoryConfirm();
                break;
            case "managecats":
              	return $this->showCats();
   		break;
            case "translation":
                return $this->translation();
            break;
 //************************************************************************************************/
            case "view":
            default:
                return $this->view();
        } // switch

 
    }
     /**
         * 
        * This is a method that overrides the parent class to stipulate whether
        * the current module requires login. Having it set to false gives public
        * access to this module including all its actions.
        *
        * @access public
        * @return bool FALSE
        */
     public function showCats()
     {
        
      // Get all the categories
        $categories =  $this->objDbFaqCategories->getCategoryList();
        $this->setVarByRef('categories', $categories);
        return "manage_cats_tpl.php";
                

        
     }
        public function requiresLogin() 
        {
            return FALSE;

        }

    /**
    * View all FAQ entries.
    */
    //$_POST["catid"].pop;

    public function view()
    {
        //get first row of categorylist, then display fag entries for this category
        $oldestcatid=$this->objDbFaqCategories->getFirstRow($this->contextId);
        //get last row of category, then display fag entries for this category
        $latestcatid=$this->objDbFaqCategories->getLastRow($this->contextId);
        $oldestcatid=$oldestcatid[0]['id'];
        $latestcatid=$latestcatid[0]['id'];
        //get category id from user click selection
        $id = html_entity_decode($this->getParam('catid'));
       
     
        if(empty($id))
        {
        //if not id then user has justed entered the module,there4 view the first category
        $catid=$oldestcatid;
        }
        else
        {
         //otherwise user has clicked a category, so change view to show this category
        $catid=$id;
        }
      
        //set add faq2 entry link
        if(!empty($catid))
        {
            $addurl= $this->uri(array(
    		    		'module'=>'faq2',
    		   			'action'=>'addfaqentry',
    					'categoryid'=>$catid
    		));
            $norecords=$this->objLanguage->languageText("faq2_noentries","faq2");  
        }
        else
        {
            $addCaturl= $this->uri(array(
    		    		'module'=>'faq2',
    		   			'action'=>'addcategory'
    					
    		));   
            $norecords=$this->objLanguage->languageText("faq2_norecords","faq2");    
        }
        $this->setVarByRef('addurl',$addurl);
        $this->setVarByRef('addCaturl',$addCaturl);
         $this->setVarByRef('norecords',$norecords);
         
       //get category row of the first category on the list
       $categoryarr=$this->objDbFaqCategories->getcategory($catid);
       $this->setVarByRef('categoryname',$categoryarr[0]['categoryname']);
        // Get all FAQ entries
        
        $list = $this->objDbFaqEntries->listAll($catid);
        $this->setVarByRef('list', $list);
        ////get entrylicense
        //$entrylicense =  $this->objDbFaqEntries->getLicenseCode($list[0]['entryid']);
        //$this->setVarByRef('license', $entrylicense);
    
        // Get all the categories
        $categories =  $this->objDbFaqCategories->listAll($this->contextId);
        $this->setVarByRef('categories', $categories);
        //use css style and pass it template
        $str .= $this->objFeatureBox->showContent();
        $this->setVarByRef('display',$str);
        
        return "view_tpl.php";
        
    }
    public function translation()
    {
        $id = $this->getParam('id', null);
    
            
         
        //fetch fag entry lang
        $list = $this->objDbFaqEntries->listSingle($id);
        $this->setVarByRef('list', $list);
        //fetch category entry
        $entry=$this->objDbFaqEntries->listCatEntry($list[0]['entryid']);
        $this->setVarByRef('entry', $entry);
        // Get category
        $category =  $this->objDbFaqCategories->listSingle($entry[0]['categoryid']);
        $this->setVarByRef('category', $category);
        
        $previous= $this->uri(array(
    		    		'module'=>'faq2',
    		   			'action'=>'view',
    					'categoryid'=>$entry[0]['categoryid']
    		));
            
        
        
           
        $this->setVarByRef('previous',$previous);
        
        
        //get languangelist
        $lang =  $this->objLanguage->languagelist();
        $this->setVarByRef('language', $lang);
        //get entrylicense
        $entrylicense =  $this->objDbFaqEntries->getLicenseCode($entry[0]['entryid']);
        $this->objCreativecommons->defaultValue=$entrylicense[0]['licenseid'];
        //use css style and pass it template
        $str .= $this->objFeatureBox->showContent();
        $this->setVarByRef('display',$str);
        
        return "translation_tpl.php";
        
    }

    /**
    * Add a FAQ entry.
    */
    public function addFaqEntry()
    {
        // Get all the categories
        //get category row of the first category on the list
        $categoryarr=$this->objDbFaqCategories->getcategory($catid);
        $this->setVarByRef('categoryname',$categoryarr[0]['categoryname']);
        // Get categoryid
        $id = html_entity_decode($this->getParam('categoryid'));
        //fetch row by this category id
        $categoryarr=$this->objDbFaqCategories->getcategory($id);
        //set category name
        $this->setVarByRef('categoryname',$categoryarr[0]['categoryname']);
        $this->setVarByRef('categoryid', $id);
        //use css style and pass it template
        $str .= $this->objFeatureBox->showContent();
        $this->setVarByRef('display',$str);
        return "add_tpl.php";
    }

    /**
    * Confirm add.
    */
    public function addConfirm()
    {
	     $index = $_POST['index'];
        $question = $_POST["question"];
        $answer = $_POST["answer"];
        $category = $_POST["categoryid"];
        // Insert a record into the database
        $this->objDbFaqEntries->insertSingle(
            $this->contextId,
            $category,
	    $index,
            $question,
            $answer,
            $this->objUser->userId(),
            mktime()
        );



        return $this->nextAction("showcat", array('catid'=>$category));
    }

    /**
    * Edit a FAQ entry.
    */
    public function edit()
    {
        $id = $this->getParam('id', null);
        $entryid = $this->getParam('entryid', null);
        
        $translation = $this->getParam('translation', null);
        $this->setVarByRef('translation', $translation);
        //fetch fag entry lang
        $list = $this->objDbFaqEntries->listSingle($id);
        $this->setVarByRef('list', $list);
        //fetch category entry
        $entry=$this->objDbFaqEntries->listCatEntry($list[0]['entryid']);
        $this->setVarByRef('entry', $entry);
        // Get category
        $category =  $this->objDbFaqCategories->listSingle($entry[0]['categoryid']);
        $this->setVarByRef('category', $category);
        //get languangelist
        $lang =  $this->objLanguage->languagelist();
        $this->setVarByRef('language', $lang);
        //get entrylicense
        $entrylicense =  $this->objDbFaqEntries->getLicenseCode($entry[0]['entryid']);
        $this->objCreativecommons->defaultValue=$entrylicense[0]['licenseid'];
        return "edit_tpl.php";
    }

    /**
    * Confirm edit.
    */
    public function editConfirm()
    {
        $id = $this->getParam('id', null);
        $question = $_POST["question"];
        $answer = $_POST["answer"];
        $category = $_POST["category"];
        // Update the record in the database
        $this->objDbFaqEntries->updateEntry(
            $id,
	    $index,
            $question,
            $answer,
            $category,
            $this->objUser->userId(),
            mktime()
        );//
        // Update the record in the database
        $this->objDbFaqEntries->updateEntryLang(
            $id,
	    $index,
            $question,
            $answer,
            $category,
            $this->objUser->userId(),
            mktime()
        );//
        return $this->nextAction('view', array('catid'=>$category));
    }

    /**
    * Confirm delete.
    */
    public function deleteConfirm()
    {
        $id = $this->getParam('id', null);
        // Delete the record from the database
        $this->objDbFaqEntries->deleteSingle($id);
        return $this->nextAction('view', array('category'=>$this->categoryId));
    }

	 /**
     * Method to load an HTML element's class.
     * @param string $name The name of the element
     * @return The element object
     */
     public function loadHTMLElement($name)
     {
         return $this->loadClass($name, 'htmlelements');
     }

	 /**
     * Method to get a new HTML element.
     * @param string $name The name of the element
     * @return The element object
     */
     public function &newHTMLElement($name)
     {
         return $this->newObject($name, 'htmlelements');
     }

	 /**
     * Method to get an HTML element.
     * @param string $name The name of the element
     * @return The element object
     */
     public function getHTMLElement($name)
     {
         return $this->getObject($name, 'htmlelements');
     }
     
     public function translateFaq()
     {
        
        $id = $this->getParam('id', null);
        //fetch fag entry lang
        $list = $this->objDbFaqEntries->listSingle($id);
        $this->setVarByRef('list', $list);
        //fetch category entry
        $entry=$this->objDbFaqEntries->listCatEntry($list[0]['entryid']);
        $this->setVarByRef('entry', $entry);
        // Get category
        $category =  $this->objDbFaqCategories->listSingle($entry[0]['categoryid']);
        $this->setVarByRef('category', $category);
        //get languangelist
        $lang =  $this->objLanguage->languagelist();
        $this->setVarByRef('language', $lang);
        //get entrylicense
        $entrylicense =  $this->objDbFaqEntries->getLicenseCode($entry[0]['entryid']);
        $this->objCreativecommons->defaultValue=$entrylicense[0]['licenseid'];
        
        //use css style and pass it template
        $str .= $this->objFeatureBox->showContent();
        $this->setVarByRef('display',$str);    
        return "translate_tpl.php";
        
     }
   public function translateConfirm()
    {
        $categoryid = $this->getParam("catid");
        $entryid = $this->getParam("id");
        
        $entryorder=$this->objDbFaqEntries->getNextIndex($categoryid);
        $language = $_POST["language"];
       
        $license = $_POST["creativecommons"];
        $question = $_POST["question"];
        $answer = $_POST["answer"];
        $isdefaultlang = $_POST["isdefaultlang"];
        
        if($this->objLanguage->currentLanguage()==$language)
         $isdefaultlang="Y";
         else
          $isdefaultlang="N";
        $userid=$this->objUser->UserId();
        $datelastupdated=$this->objDbFaqEntries->now();
       
        //insert faq entry
        //$entryid=$this->objDbFaqEntries->insertFaqEntry($license,$userid,$datelastupdated);
        if(!$this->objDbFaqEntries->valueExists($question))
        {
        // Insert the fag category entry
        /*$this->objDbFaqEntries->insertFaqCatEntry(
            $categoryid,
            $entryid,
            $entryorder,
            $userid,
            $datelastupdated
        );*/
        // Insert the fag  entry language
        $this->objDbFaqEntries->insertFaqEntryLang(
            $entryid,
            $question,
            $answer,
            $language,
            $isdefaultlang,
            $userid,
            $datelastupdated
        );
        }
    //set focus back to this category    
    return $this->nextAction("view", array('catid'=>$categoryid));
    }
     	 
     	 
     	  /**
    * Add a FAQ category.
    */
    public function addCategory()
    {
    
        $str .= $this->objFeatureBox->showContent();
        $this->setVarByRef('display',$str);
        return "add_category_tpl.php";
        
    }

    /**
    * Confirm add.
    */
    public function addCategoryConfirm()
    {
        
        $contextid=$this->contextId;
        $language = $_POST["language"];
        $license = $_POST["creativecommons"];
        $categoryname = $_POST["category"];
        if($this->objLanguage->currentLanguage()==$language)
         $isdefaultlang="Y";
         else
          $isdefaultlang="N";
        $userid=$this->objUser->UserId();
        $datelastupdated=$this->objDbFaqCategories->now();
        
        //check if entry exists
        $exists=$this->objDbFaqCategories->valueExists("categoryname", $categoryname, "tbl_faq2_categories_lang");
        
        if(!$exists)
        {//insert faq category
        $categoryid=$this->objDbFaqCategories->insertCategory($license,$userid,$datelastupdated);
        //if in context add category into these context
    
        if(!empty($contextid))        
        $this->objDbFaqCategories->insertIntoContext($categoryid,$contextid,$userid,$datelastupdated);
        // then Insert the category into the database
        $this->objDbFaqCategories->insertSingle(
            $categoryid,
            $categoryname, 
            $language, 
            $isdefaultlang,
            $userid,
            $datelastupdated
        );
        
        }
    
    return $this->nextAction("showcat", array('catid'=>$categoryid,'error'=>"value exists"));
    }
   
     /**
    * Confirm add faq entry.
    */
    public function addfaqEntryConfirm()
    {
        
        
        $categoryid = $this->getParam("categoryid");
        $entryorder=$this->objDbFaqEntries->getNextIndex($categoryid);
        $language = $_POST["language"];
       
        $license = $_POST["creativecommons"];
        $question = $_POST["question"];
        $answer = $_POST["answer"];
        $isdefaultlang = $_POST["isdefaultlang"];
        
        if($this->objLanguage->currentLanguage()==$language)
         $isdefaultlang="Y";
         else
          $isdefaultlang="N";
        $userid=$this->objUser->UserId();
        $datelastupdated=$this->objDbFaqEntries->now();
        
        //insert faq entry
        $entryid=$this->objDbFaqEntries->insertFaqEntry($license,$userid,$datelastupdated);
        
        // Insert the fag category entry
        $this->objDbFaqEntries->insertFaqCatEntry(
            $categoryid,
            $entryid,
            $entryorder,
            $userid,
            $datelastupdated
        );
        // Insert the fag  entry language
        $this->objDbFaqEntries->insertFaqEntryLang(
            $entryid,
            $question,
            $answer,
            $language,
            $isdefaultlang,
            $userid,
            $datelastupdated
        );
    //set focus back to this category    
    return $this->nextAction("view", array('catid'=>$categoryid));
    }
    
    /**
    * Edit a FAQ entry.
    */
    public function editCategory()
    {
        $id = $this->getParam('id', null);
        $list = $this->objDbFaqCategories->getCategory($id);
        $this->setVarByRef('list', $list);
        //get entrylicense
        $entrylicense =  $this->objDbFaqCategories->getLicenseCode($list[0]['categoryid']);
        $this->objCreativecommons->defaultValue=$entrylicense[0]['license'];
     
    }

    /**
    * Confirm edit.
    */
    public function editCategoryConfirm()
    {
        $id = $this->getParam('id', null);
        $catid = $this->getParam("catid");
    
        $language = $_POST["language"];
       
        $license = $_POST["creativecommons"];
        $categoryname = $_POST["category"];
        
        
        if($this->objLanguage->currentLanguage()==$language)
         $isdefaultlang="Y";
         else
          $isdefaultlang="N";
        $userid=$this->objUser->UserId();
        $datelastupdated=$this->objDbFaqCategories->now();
        // Update faq categories
        $this->objDbFaqCategories->updateCat(
            $catid, 
            $license, 
            $datelastupdated
        );
        $this->objDbFaqCategories->updateCatLang(
            $catid, 
            $categoryname,
            $language,
            $isdefaultlang,
            $datelastupdated
        );
        return $this->nextAction("managecats");
    
    }
    public function editFaqEntry()
    {
        $id = $this->getParam('id', null);
        $translation = $this->getParam('translation', null);
        $list = $this->objDbFaqEntries->listSingleId($id);
        $this->setVarByRef('list', $list);
    }

    /**
    * Confirm edit.
    */
    public function editFaqEntryConfirm()
    {
        $entryid = $this->getParam("entryid");
        $id = $this->getParam("id");
        $catid = $this->getParam("catid");
    
        $language = $_POST["language"];
       
        $license = $_POST["creativecommons"];
        $question = $_POST["question"];
        $answer = $_POST["answer"];
        $isdefaultlang = $_POST["isdefaultlang"];
        
        if($this->objLanguage->currentLanguage()==$language)
         $isdefaultlang="Y";
         else
          $isdefaultlang="N";
        $userid=$this->objUser->UserId();
        $datelastupdated=$this->objDbFaqEntries->now();
        // Update the record in the database
        $this->objDbFaqEntries->updateEntry(
            $entryid, 
            $license, 
            $userid,
            $datelastupdated
        );
        $this->objDbFaqEntries->updateEntryLang(
            $id, 
            $question,
            $answer,
            $language,
            $isdefaultlang,
            $userid,
            $datelastupdated
        );
        return $this->nextAction("showcat",array("catid"=>$catid));
    }
    

    /**
    * Confirm edit.
    */
    public function editFaqTranslationConfirm()
    {
        $id = $this->getParam("id");
    
        $language = $_POST["language"];
        $question = $_POST["question"];
        $answer = $_POST["answer"];
        $isdefaultlang = $_POST["isdefaultlang"];
        
        if($this->objLanguage->currentLanguage()==$language)
         $isdefaultlang="Y";
         else
          $isdefaultlang="N";
        $userid=$this->objUser->UserId();
        $datelastupdated=$this->objDbFaqEntries->now();
        
        $this->objDbFaqEntries->updateEntryLang(
            $id, 
            $question,
            $answer,
            $language,
            $isdefaultlang,
            $userid,
            $datelastupdated
        );
        return $this->nextAction("translation",array("id"=>$id));
    }


    /**
    * Confirm delete.
    */
    public function deleteCategoryConfirm()
    {
         $id = $this->getParam('id', null);
         $catid=$id;
         //get entry id
         $entryid=$this->objDbFaqEntries->listAll($catid);
         //check if there are entries associated to this category
         $count=count($entryid);
         if($count!=0){
        //delete all entries first if any
        // Delete faq2 entries
            $eid=$entryid[0]['entryid'];
            $this->objDbFaqEntries->deleteSingle("id",$eid,"tbl_faq2_entries");
        // Delete faq2 category entries
            $this->objDbFaqEntries->deleteSingle("entryid",$eid,"tbl_faq2_categories_entries"); 
	// Delete faq2 entries lang
            $this->objDbFaqEntries->deleteSingle("entryid",$eid,"tbl_faq2_entries_lang"); 
        
        }
        $this->objDbFaqCategories->deleteSingle("id",$id,"tbl_faq2_categories");
        // Delete faq2 category entries
        $this->objDbFaqCategories->deleteSingle("categoryid",$id,"tbl_faq2_categories_context"); 
	// Delete faq2 entries lang
        $this->objDbFaqCategories->deleteSingle("categoryid",$id,"tbl_faq2_categories_lang"); 
           
    return $this->nextAction("showcats");
    }
    public function deleteFaqEntryConfirm()
    {
        $id = $this->getParam('id', null);
        $translation = $this->getParam('translation', null);
        //$this->setVarByRef('translation', $translation);
       
        //translation is 0,it means this is the original faq.delete all entries associated with it
        if($translation==0)
        {
        // Delete faq2 entries
        $this->objDbFaqEntries->deleteSingle("id",$id,"tbl_faq2_entries");
        // Delete faq2 category entries
        $this->objDbFaqEntries->deleteSingle("entryid",$id,"tbl_faq2_categories_entries"); 
	// Delete faq2 entries lang
        $this->objDbFaqEntries->deleteSingle("entryid",$id,"tbl_faq2_entries_lang"); 
        }
        else
        {
        //delete only this translation fron entries lang
        //get faq entry id
        $entryid=$id;
        $id=$this->objDbFaqEntries->getCatLangId($entryid);
        $this->objDbFaqEntries->deleteSingle("id",$id[0]['id'],"tbl_faq2_entries_lang"); 
            
        }
        return $this->nextAction(NULL);
    }



}
?>
