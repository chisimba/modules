<?php
/**
 *
 * Renders the create template
 *
 * Renders the create template for cloning a Chisimba site 
 *
 * PHP version 5
 *
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
 *
 * @category  Chisimba
 * @package   clone
 * @author    Derek Keats _EMAIL
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
* 
* Renders the create template for clonesite
* 
* @author Derek Keats
* 
*/
class rendercreatetemplate extends object
{

    /**
    * 
    * @var string object $objLanguage A string to hold the language object
    * 
    */
    public $objLanguage;
    /**
    * 
    * @var string object $objLanguage A string to hold the user object
    * 
    */
    public $objUser;
        
    /**
    * 
    * Constructor method to instantiate the database and 
    * user objects. 
    * 
    */
    public function init() 
    {
        //Create an instance of the language object
        $this->objLanguage = & $this->getObject('language', 'language');
        //Create an instance of the User object
        $this->objUser =  & $this->getObject("user", "security");
    }
    
    /**
    * 
    * Method to render an create form with fields for the required params
    * 
    * @access Public
    * @return The rendered form
    * 
    */
    public function show()
    {
        $paramArray=array(
          'action'=>'docreatesite');
        $formAction=$this->uri($paramArray);
        //Load the form class 
        $this->loadClass('form','htmlelements');
        //Load the textinput class 
        $this->loadClass('textinput','htmlelements');
        // Create an instance of the button object
        $this->loadClass('button', 'htmlelements');
        //Create and instance of the form class
        $objForm = new form('sitecreator');
        //Set the action for the form to the uri with paramArray
        $objForm->setAction($formAction);
        //Set the displayType to 3 for freeform
        $objForm->displayType=3;
        //Put the layout in a table
        $myTable = $this->newObject('htmltable', 'htmlelements');
        $myTable->cellspacing="2";
        //Create an element for the input of site code and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->objLanguage->languageText("mod_clonesite_sitecode", "clonesite"));
        $myTable->endRow();
        $myTable->startRow();
        $myTable->addCell($this->__getSiteCodeElement());
        $myTable->endRow();
        //Create an element for the submit (Create) button and add it to the table
        $myTable->startRow();
        $myTable->addCell($this->__getCreateButton());
        $myTable->endRow();
        //Add the table to the form      
        $objForm->addToForm($myTable->show());
        //Render the form & return it
        return $objForm->show();
    }
    
    
    /**
    * 
    * Method to return a file upload text input
    * 
    * @access private
    * @return the file upload field for the form
    * 
    */ 
    private function __getSiteCodeElement()
    {
        //Create an element for the input of title
        $objElement = new textinput ("sitecode");
        //Set the field type to text
        $objElement->fldType = "text";
        $objElement->size = 30;
        if (isset($this->sitecode)) {
            $objElement->value=$this->sitecode;
        }
        //Add the $title element to the form
        return $objElement->show();
    }

    /**
    * 
    * Method to return upload (submit) button to the form
    * 
    * @access private
    * @return the upload button for the form
    * 
    */ 
    private function __getCreateButton()
    {

        // Create a submit button
        $objElement = new button('submit');	
        // Set the button type to submit
        $objElement->setToSubmit();	
        // Use the language object to add the Phrase Create Site
        $objElement->setValue(' '
          .$this->objLanguage->languageText("mod_clonesite_name", "clonesite").' ');
        // return the button to the form
        return "&nbsp;" . $objElement->show()  
          . "<br />&nbsp;";
    }

}
?>