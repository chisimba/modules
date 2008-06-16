<?php
/**
*
* Personal blocks render classs to help templates
*
* Allows the creation of personal blocks for display on sidebar block areas. 
* Requires the blockalicious module to function. Personal blocks allow the 
* addition of web widgets in locations such as a blog.
* 
*/
/* ----------- data class extends dbTable for tbl_quotes------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* 
* Render class for the personalblocks module. This is a view layer
* class that helps render output to templates
*
* @author Derek Keats
*
* $Id: dbpersonalblocks_class_inc.php,v 1.1 2006/09/14 08:19:14 Abdurahim Ported to PHP5
*
*/
class pbrender extends dbTable
{
    /**
    *
    * @var string $objConfig String object property for holding the
    * configuration object
    * @access public
    *
    */
    public $objConfig;
    /**
    *
    * @var string $objLanguage String object property for holding the
    * language object
    * @access public
    *
    */
    public $objLanguage;

    /**
    * Constructor method to define the table
    */
    public function init() {
        $this->objUser = $this->getObject("user", "security");
        $this->objLanguage = $this->getObject("language", "language");
        $this->loadClass("htmltable", "htmlelements");
        $this->objDb = $this->getObject("dbpersonalblocks", "personalblocks");
    }
    
    /**
    * 
    * Show the title from the language item for module title. It shows the title inside
    * a H3 heading using the htmlheading class.
    * 
    * @return string The module title in a H3 heading
    * @access public
    *  
    */
    public function showTitle()
    {
        $this->loadClass("htmlheading", "htmlelements");
        $h = new htmlheading();
        $h->str = $this->objLanguage->languageText("mod_personalblocks_title", "personalblocks");
    	return $h->show();
    }
    
    /**
    * 
    * Method to try to figure out which user's blocks should be displayed
    * and display accordingly. For example, if you are viewing my blog, you
    * should see my blocks, not your own.
    * 
    * @return integer The user id of the person whose blocks should be displayed
    * @access private
    *  
    */
    private function findUser()
    {
        $module = $this->getParam("module", NULL);
        // If they are in the personalblocks module use own userid
        if ($module=="personalblocks") {
        	$userId = $this->objUser->userId();
        // Else try to figure out the appropriate blocks to display
        } else {
            $userId = $this->getParam("userid", NULL);
            if ($userId == NULL || $userId == "") {
            	$userName = $this->getParam("username", NULL);
                if ($userName == NULL || $userName=="") {
                	$userId = $this->objUser->userId();
                } else {
                	$userId = $this->objUser->getUserId("userName");
                }
            }
        }
    	return $userId;
    }
    
    /**
    * 
    * Render the personal blocks for the left hand panel
    * 
    * @param boolean $showName If true the block name is shown.
    * @return string The left blocks rendered out
    * @access public
    * 
    */
    public function renderLeft($showName=FALSE)
    {
        $creatorId = $this->findUser();
    	$ar = $this->objDb->getLeftBlocks($creatorId);
        $ret ="";
        $blockname="";
        if (isset($ar)) {
            if (count($ar) > 0) {
                foreach ($ar as $line) {
                    if ($showName) {
                        $blockname = "-- <span class=\"error\">" . $line['blockname'] . "</span> --<br />";
                    }
                    $blockcontent = $blockname . $line['blockcontent'];
                    $ret .= "<br />" . $blockcontent . "<br />";
                }
            } else {
                $ret = $this->emptyBlocks("left");
            }
        } else {
            $ret = $this->emptyBlocks("left");
        }
        return $ret;
    }
   
    /**
    * 
    * Render the personal blocks for the right hand panel
    * 
    * @param boolean $showName If true the block name is shown.
    * @return string The right blocks rendered out
    * @access public
    * 
    */
    public function renderRight($showName=FALSE)
    {
        $creatorId = $this->findUser();
        $ar = $this->objDb->getRightBlocks($creatorId);
        $ret ="";
        $blockname="";
        if (isset($ar)) {
            if (count($ar) > 0) {
                foreach ($ar as $line) {
                    if ($showName) {
                    	$blockname = "-- <span class=\"error\">" . $line['blockname'] . "</span> --<br />";
                    }
                    $blockcontent = $blockname . $line['blockcontent'];
                    $ret .= "<br />" . $blockcontent . "<br />";
                }
            } else {
                $ret = $this->emptyBlocks("right");
            }
        } else {
            $ret = $this->emptyBlocks("right");
        }
        return $ret;
    }

    /**
    * 
    * Render the personal blocks for the middle panel
    * 
    * @param boolean $showName If true the block name is shown.
    * @return string The middle blocks rendered out
    * @access public
    * 
    */
    public function renderMiddle($showName=FALSE)
    {
        $creatorId = $this->findUser();
        $ar = $this->objDb->getMiddleBlocks($creatorId);
        $ret ="";
        $blockname="";
        if (isset($ar)) {
            if (count($ar) > 0) {
                foreach ($ar as $line) {
                    if ($showName) {
                        $blockname = "-- <span class=\"error\">" . $line['blockname'] . "</span> --<br />";
                    }
                    $blockcontent = $blockname . $line['blockcontent'];
                    $ret .= "<br />" . $blockcontent . "<br />";
                }
            } else {
                $ret = $this->emptyBlocks("middle");
            }
        } else {
            $ret = $this->emptyBlocks("middle");
        }
        return $ret;
    }

    /**
    *
    * Returns all personal block records formatted in a table for edit, 
    * add, delete or view. Records are not paginated because the use is unlikely
    * to have very large numbers of blocks.
    * 
    * @return string A table with all records.
    * @access public 
    *  
    */
    public function showAll()
    {
        $userId = $this->objUser->userId();
        $ar = $this->objDb->getBlocks($userId);
        $objTable = new htmltable("personalblocks");
        $objTable->cellspacing="2";
        $objTable->cellpadding="2";
        $objTable->border=0;
        $objTable->width="98%";
        //Create the array for the table header
        $tableHd=array();
        $tableHd[]=$this->objLanguage->languageText('mod_personalblocks_title','personalblocks');
        $tableHd[]=$this->objLanguage->languageText('mod_personalblocks_location','personalblocks');
        $tableHd[]=$this->objLanguage->languageText('mod_personalblocks_active','personalblocks');
        $tableHd[]=$this->objLanguage->languageText('mod_personalblocks_datecreated','personalblocks');
        $tableHd[]=$this->objLanguage->languageText('mod_personalblocks_createdby','personalblocks');
        $tableHd[]=$this->objLanguage->languageText('mod_personalblocks_datemodified','personalblocks');
        $tableHd[]=$this->objLanguage->languageText('mod_personalblocks_modifiedby','personalblocks');
        $tableHd[]=$this->getAddButton();
        $objTable->addHeader($tableHd, "heading");
        
        if (isset($ar)) {
            if (count($ar) > 0) {
                $objDate =  $this->getObject("dateandtime", "utilities");
                foreach ($ar as $line) {
                    $oddOrEven = ($rowcount == 0) ? "odd" : "even";
                    $id = $line['id'];
                    if(!empty($line['blockname'])){
                        $blockname = $line['blockname'];
                        $tableRow[]=$blockname;
                    } else {
                        $tableRow[]= '';
                        $blockname ="";
                    }
                    if(!empty($line['location'])){
                        $location = $line['location'];
                        // Get location text from word
                        // @todo - add function for that purpose
                        $tableRow[]= $location;
                    } else {
                        $tableRow[]= '';
                    }
                    if(!empty($line['active'])){
                        $active = $line['active'];
                        // Get active text from code
                        // @todo - add function for that purpose
                        $tableRow[] = $active;
                    } else {
                        $tableRow[]= '';
                    }
                    if(!empty($line['datecreated'])){
                        $tableRow[]=$objDate->formatDate($line['datecreated']);
                    } else {
                        $tableRow[]= '';
                    }
                    if(!empty($line['creatorid'])){
                        $creatorid = $line['creatorid'];
                        $tableRow[] = $this->objUser->fullName($creatorid);
                    } else {
                        $tableRow[]= '';
                    }
                    if(!empty($line['datemodified'])){
                        $tableRow[]=$objDate->formatDate($line['datemodified']);
                    } else {
                        $tableRow[]= '';
                    }
                    if(!empty($line['modifierid'])){
                        $modifierid = $line['modifierid'];
                        $tableRow[] = $this->objUser->fullName($modifierid);
                    } else {
                        $tableRow[]= '';
                    }
                    $tableRow[] = $this->getEditButton($id)
                     . " " . $this->getDeleteIcon($id, $blockname);
                    //Add the row to the table for output
                    $objTable->addRow($tableRow, $oddOrEven);
                    // clear out the array
                    $tableRow=array(); 
                    // Set rowcount for bitwise determination of odd or even
                    $rowcount = ($rowcount == 0) ? 1 : 0;
                }
                $ret = $objTable->show();
            } else {
                $ret = $objTable->show();
            	$ret .= $this->noRecordsMsg();
            }
        } else {
            $ret = $objTable->show();
        	$ret .= $this->noRecordsMsg();
        }
        return $ret;
        
    }
    
    /**
    *
    * Returns the message for when no blocks are found for insertion underneath
    * the empty table within the norecorrdsmessage div
    * 
    * @return string The norecords message
    * @access private 
    *  
    */
    private function noRecordsMsg()
    {
    	return "<span class=\"noRecordsMessage\">"
          . $this->objLanguage->languageText("mod_personalblocks_noblocksfound",'personalblocks')
          . "</span>";
    }
    
    private function emptyBlocks($location)
    {
        $langElem = "mod_personalblocks_no" . $location;
    	return $this->objLanguage->languageText($langElem,'personalblocks');
    }
    
    /**
    * 
    * Return an add button
    * @return string A rendered add button with the correct URL
    * @access private
    *  
    */
    private function getAddButton()
    {
        $objGetIcon = $this->newObject('geticon', 'htmlelements');
        $paramAr = array(
          'action' => 'addpblock',
          'mode' => 'add');
        $addButton = $objGetIcon->getAddIcon($this->uri($paramAr, "personalblocks"));
    	return $addButton;
    }

    /**
    * 
    * Return an edit button based on the id of the record to edit
    * 
    * @param string $id The key of the record to edit
    * @return string A rendered edit button with the correct URL
    * @access private
    *  
    */
    private function getEditButton(&$id) 
    {
        $objEditIcon = $this->getObject('geticon', 'htmlelements');
        //The URL for the edit link
        $editLink=$this->uri(array('action' => 'editblock',
          'mode' => 'edit',
          'id' =>$id));
        $objEditIcon->alt=$this->objLanguage->languageText("mod_personalblocks_editblock",'personalblocks');
        return $objEditIcon->getEditIcon($editLink);
    }
    
    /**
    * 
    * Return an delete icon
    * 
    * @param string $id The key of the record to delete
    * @param string $blockname The name of the block for use in the confirm message
    * @return string A rendered delete icon with the correct URL
    * @access private
    *  
    */
    private function getDeleteIcon(&$id, $blockname)
    {
    	$objDelIcon = $this->newObject('geticon', 'htmlelements');
        // The delete icon with link uses confirm delete utility.
        $objDelIcon->setIcon("delete");
        $objDelIcon->alt=$this->objLanguage->languageText("mod_personalblocks_delblock",'personalblocks');
        $delLink = $this->uri(array(
          'action' => 'delete',
          'confirm' => 'yes',
          'id' => $id));
        $objConfirm = $this->getObject('confirm','utilities');
        $rep = array('TITLE' => $blockname);
        $objConfirm->setConfirm($objDelIcon->show(), $delLink, 
          $this->objLanguage->code2Txt("mod_personalblocks_confmsg",
          'personalblocks', $rep));
        return $objConfirm->show();
    }
    
    /**
    * 
    * Render an edit / add form for editing or adding a personal block
    * 
    * @return string The rendered form
    * @access public
    *  
    */
    public function renderEditAddForm()
    {
        $mode=$this->getParam("mode", "add");
        //Set up the form action
        $paramArray=array(
        'action' => 'save',
        'mode' => $mode);
        $formAction=$this->uri($paramArray);
        //Load the form class
        $this->loadClass('form','htmlelements');
        //Load the textinput class
        $this->loadClass('textinput','htmlelements');
        //Load the textarea class
        $this->loadClass('textarea','htmlelements');
        //Load the label class
        $this->loadClass('label','htmlelements');
        //Create and instance of the form class
        $objForm = new form('personalblock');
        //Set the action for the form to the uri with paramArray
        $objForm->setAction($formAction);
        //Set the displayType to 3 for freeform
        $objForm->displayType=3;
        //See if its edit or add
        $mode = $this->getParam("mode", "add");
        if ($mode=="edit") {
        	$keyvalue=$this->getParam("id", NULL);
            if (!$keyvalue) {
                die($this->objLanguage->languageText("modules_badkey").": ".$keyvalue);
            }
            $ar = $this->objDb->getRow('id', $keyvalue);
            $id = $ar['id'];
            $blockname = $ar['blockname'];
            $location = $ar['location'];
            $active = $ar['active'];
            $blockcontent = $ar['blockcontent'];
        } else {
        	$location="left";
            $blockname="";
            $blockcontent="";
            $active="1";
        }
        //Create an element for the hidden text input
        $objElement = new textinput("id");
        //Set the value to the primary keyid
        if (isset($id)) {
            $objElement->setValue($id);
        }
        //Set the field type to hidden for the primary key
        $objElement->fldType="hidden";
        //Add the hidden PK field to the form
        $objForm->addToForm($objElement->show());
        //Create an element for the input of blockname
        $objElement = new textinput ("blockname");
        //Set the value of the element to $blockid
        if (isset($blockname)) {
            $objElement->setValue($blockname);
        }
        $ifTable= "<table>\n"
          . "<tr><td>" 
          . $this->objLanguage->languageText("mod_personalblocks_blname", "personalblocks") 
          . "</td><td>".$objElement->show()."</td></tr>";
        // Add a text area for the block contents.
        $this->loadClass('textarea', 'htmlelements');
        $widgetTxt = new textarea('blockcontent', $blockcontent, 8, 60);
        $ifTable .= "<tr><td valign='top'>"
          . $this->objLanguage->languageText("mod_personalblocks_content", "personalblocks")
          . "</td><td>" . $widgetTxt->show() . "</td></tr>";
        
        // Add a radio set for choosing location.
        $this->loadClass("radio", "htmlelements");
        $objRadioElement = new radio('location');
        $objRadioElement->addOption('left',  "&nbsp;" . $this->objLanguage->languageText("mod_personalblocks_left", "personalblocks") . "&nbsp;");
        $objRadioElement->addOption('middle', "&nbsp;" . $this->objLanguage->languageText("mod_personalblocks_middle", "personalblocks") . "&nbsp;");
        $objRadioElement->addOption('right', "&nbsp;" . $this->objLanguage->languageText("mod_personalblocks_right", "personalblocks") . "&nbsp;");
        $objRadioElement->setSelected($location);
        $ifTable .= "<tr><td>" 
          . $this->objLanguage->languageText("mod_personalblocks_location", "personalblocks")
          . "</td><td>" . $objRadioElement->show() . "</td></tr>";
        
        
        // Add a radio set for active / not active.
        $objRadioActive = new radio('active');
        $objRadioActive->addOption('1',  "&nbsp;" . $this->objLanguage->languageText("mod_personalblocks_isactive", "personalblocks") . "&nbsp;");
        $objRadioActive->addOption('0', "&nbsp;" . $this->objLanguage->languageText("mod_personalblocks_inactive", "personalblocks") . "&nbsp;");
        $objRadioActive->setSelected($active);
        $ifTable .= "<tr><td>" 
          . $this->objLanguage->languageText("mod_personalblocks_active", "personalblocks")
          . "</td><td>" . $objRadioActive->show() . "</td></tr>";
        
        // Create an instance of the button object
        $this->loadClass('button', 'htmlelements');
        // Create a submit button
        $objElement = new button('submit');
        // Set the button type to submit
        $objElement->setToSubmit();
        // Use the language object to add the word save
        $objElement->setValue(' '.$this->objLanguage->languageText("word_save").' ');
    	// Add the buttons to the form
        $ifTable .= "<tr><td>" . $objElement->show() . "</td><td>&nbsp;</td></tr>";
        $ifTable .= "</table>";
        $objForm->addToForm($ifTable);
        return $objForm->show();
    }
    

}
?>