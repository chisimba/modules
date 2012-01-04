<?php
/**
 *
 * Institution editor functionality for OER module
 *
 * Institution editor functionality for OER module provides for the
 * creation of the institution editor form, which is used by the
 * class block_institutionedit_class_inc.php
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
 * @package   oer
 * @author    Derek Keats derek@dkeats.com
 * @author    David Wafula
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   0.001
 * @link      http://www.chisimba.com
 *
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
* Institution editor functionality for OER module
*
* Institution editor functionality for OER module provides for the
* creation of the institution editor form, which is used by the
* class block_institutionedit_class_inc.php
*
* @package   oer
* @author    Derek Keats derek@dkeats.com
*
*/
class institutionedit extends object
{

    public $objLanguage;
    private $mode;
    private $objDbInstitutionType;
    private $objThumbUploader;

    /**
    *
    * Intialiser for insitution editor UI builder class. It instantiates
    * language object and loads the required classes.
    * 
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        // Serialize language items to Javascript
        $arrayVars['status_success'] = "mod_oer_status_success";
        $arrayVars['status_fail'] = "mod_oer_status_fail";
        $objSerialize = $this->getObject('serializevars', 'oer');
        $objSerialize->serializetojs($arrayVars);
        $this->objDbInstitutionType = $this->getObject('dbinstitutiontypes');
        $this->objThumbUploader = $this->getObject('thumbnailuploader');
        // Load scriptaclous since we can no longer guarantee it is there
        $scriptaculous = $this->getObject('scriptaculous', 'prototype');
        $this->appendArrayVar('headerParams', $scriptaculous->show('text/javascript'));
        // Load the jquery validate plugin
        $this->appendArrayVar('headerParams',
        $this->getJavaScriptFile('plugins/validate/jquery.validate.min.js',
          'jquery'));
        // Load the helper Javascript
        $this->appendArrayVar('headerParams',
          $this->getJavaScriptFile('institutionedit.js',
          'oer'));
        // Load all the required HTML classes from HTMLElements module
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable','htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('hiddeninput', 'htmlelements');
        $this->loadClass('fieldset','htmlelements');
    }

    /**
     *
     * Render the input form for the institutional data.
     *
     * @return string The rendered form
     * @access public
     * 
     */
    public function show()
    {
        return $this->makeHeading() 
            . "<div class='formwrapper'>"
            . $this->buildForm()
            . "</div>";
    }
    
    /**
     *
     * Insert an add icon for use by javacript. It will be visitble
     * when you are in edit mode, but invisible when you are in add 
     * mode. After a save, it will be toggled to visible.
     * 
     * @param string $mode The edit|add mode
     * @return string The rendered icon
     * @access private
     *  
     */
    private function insertAddIcon($mode)
    {
        $objIcon = $this->newObject('geticon', 'htmlelements');
        $link =  $this->uri(
           array("action" => "institutionedit"),
           'oer');
        $addlink = new link($link);
        $objIcon->setIcon('add');
        
        $addlink->link = $objIcon->show();
        if ($mode == 'add') { 
            $showCss = "style='visibility:hidden'";
        } else {
            $showCss = " style='visibility:show' ";
        }
        return "&nbsp; <span class='conditional_add' " 
          . $showCss . ">" . $addlink->show() 
          . "</span>";
    }

    /**
     *
     * Make a heading for the form
     *
     * @return string The text of the heading
     * @access private
     *
     */
    private function makeHeading()
    {
        // Get heading based on whether it is edit or add.
        $this->mode = $this->getParam('mode', 'add');
        if ($this->mode == 'edit') {
            $h = $this->objLanguage->languageText(
              'mod_oer_institution_heading_edit',
              'oer');
            $id = $this->getParam('id', NULL);
            $this->loadData($id);
        } else {
            $h = $this->objLanguage->languageText(
              'mod_oer_institution_heading_new',
              'oer');
        }
        // Setup and show heading.
        $header = new htmlHeading();
        $header->str = $h . $this->insertAddIcon($this->mode);
        $header->type = 2;
        return $header->show();
    }

    /**
     *
     * Build a form for inputting the institution data
     *
     * @return string The formatted form
     * @access private
     * 
     */
    private function buildForm()
    {
        // Setup table and table headings with input options.
        $table = $this->newObject('htmltable', 'htmlelements');
        
        //Institution name input options
        $title = $this->objLanguage->languageText(
          'mod_oer_institution_name', 'oer');
        $table->startRow();
        $table->addCell($title);
        $textinput = new textinput('name');
        $textinput->size = 60;
        if ($this->mode == 'edit') {
            $value = $this->name;
            $textinput->setValue($value);
        }
        $textinput->cssClass = 'required';
        $table->addCell($textinput->show());
        $table->endRow();

        // Field for the description.
        if ($this->mode == 'edit') {
            $description = $this->description;
            $textinput->setValue($value);
        } else {
            $description = NULL;
        }
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'description';
        $editor->height = '150px';
        $editor->width = '100%';
        $editor->setBasicToolBar($description);
        $editor->setContent($description);
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('word_description'));
        $table->addCell($editor->show());
        $table->endRow();
        // Field for the thumbnail.
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_thumbnail', 'oer'));
        $tester = new hiddeninput('thumbnail');
        if ($this->mode == 'edit') {
            $tester->value = $this->getParam('thumbnail', NULL);
        }
        $table->addCell($tester->show() . $this->objThumbUploader->show());
        $table->endRow();

        // Institution type selection
        $table->startRow();
        $title = $this->objLanguage->languageText(
          'mod_oer_institution_type', 'oer');
        $table->addCell($title);
        $institutionTypes = $this->objDbInstitutionType->getInstitutionTypes();
        $objInstitutionTypesdd = new dropdown('type');
        if ($this->mode == 'edit') {
            $value = $this->type;
        } else  {
            $value=NULL;
        }
        $objInstitutionTypesdd->addFromDB($institutionTypes, 'type', 'id', $value);
        $table->addCell($objInstitutionTypesdd->show());
        $table->endRow();

        // Field for keyword 1
        $table->startRow();
        $title = $this->objLanguage->languageText(
          'mod_oer_institution_keyword1', 'oer');
        $table->addCell($title);
        if ($this->mode == 'edit') {
            $value = $this->keyword1;
        } else  {
            $value=NULL;
        }
        $textinput = new textinput('keyword1');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        $textinput->setValue($value);
        $table->addCell($textinput->show());
        $table->endRow();
        
        // Field for keyword 2
        $table->startRow();
        $title = $this->objLanguage->languageText(
          'mod_oer_institution_keyword2', 'oer');
        $table->addCell($title);
        if ($this->mode == 'edit') {
            $value = $this->keyword2;
        } else  {
            $value=NULL;
        }
        $textinput = new textinput('keyword2');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        $textinput->setValue($value);
        $table->addCell($textinput->show());
        $table->endRow();

        $fieldsetInstitutionInfo = $this->newObject('fieldset', 'htmlelements');
        $fieldsetInstitutionInfo->setLegend('Institution information');
        $fieldsetInstitutionInfo->addContent($table->show());

        // Reinitialise a new table.
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";
        // Field for address1.
        $table->startRow();
        $title = $this->objLanguage->languageText('mod_oer_institution_address1', 'oer');
        $table->addCell($title);
        if ($this->mode == 'edit') {
            $value = $this->address1;
        } else  {
            $value=NULL;
        }
        $textinput = new textinput('address1');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        $textinput->setValue($value);
        $table->addCell($textinput->show());
        $table->endRow();

        // Field for address2.
        $table->startRow();
        $title = $this->objLanguage->languageText('mod_oer_institution_address2', 'oer');
        $table->addCell($title);
        if ($this->mode == 'edit') {
            $value = $this->address2;
        } else  {
            $value=NULL;
        }
        $textinput = new textinput('address2');
        $textinput->size = 60;
        $textinput->setValue($value);
        $table->addCell($textinput->show());
        $table->endRow();

        // Field for address3.
        $table->startRow();
        $title = $this->objLanguage->languageText(
          'mod_oer_institution_address3', 'oer');
        $table->addCell($title);
        if ($this->mode == 'edit') {
            $value = $this->address3;
        } else  {
            $value=NULL;
        }
        $textinput = new textinput('address3');
        $textinput->size = 60;
        $textinput->setValue($value);
        $table->addCell($textinput->show());
        $table->endRow();

        // Field for ZIP code.
        $table->startRow();
        $title = $this->objLanguage->languageText(
          'mod_oer_institution_zip', 'oer');
        $table->addCell($title);
        if ($this->mode == 'edit') {
            $value = $this->zip;
        } else  {
            $value=NULL;
        }
        $textinput = new textinput('zip');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        $textinput->setValue($value);
        $table->addCell($textinput->show());
        $table->endRow();

        // Field for Country
        $table->startRow();
        $title = $this->objLanguage->languageText(
          'mod_oer_institution_country', 'oer');
        $table->addCell($title);
        // Get the countries
        $objCountries = $this->getObject('languagecode', 'language');
        if ($this->mode == 'edit') {
            $value = $this->country;
        } else  {
            $value=NULL;
        }
        if ($value !== NULL) {
            $table->addCell($objCountries->countryAlpha($value));
        } else {
            $table->addCell($objCountries->countryAlpha());
        }
        $table->endRow();

        // Field for City.
        $table->startRow();
        $title = $this->objLanguage->languageText(
          'mod_oer_institution_city', 'oer');
        $table->addCell($title);
        if ($this->mode == 'edit') {
            $value = $this->city;
        } else  {
            $value=NULL;
        }
        $textinput = new textinput('city');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        $textinput->setValue($value);
        $table->addCell($textinput->show());
        $table->endRow();

        // Field for website link.
        $table->startRow();
        $title = $this->objLanguage->languageText(
          'mod_oer_institution_websitelink', 'oer');
        $table->addCell($title);
        if ($this->mode == 'edit') {
            $value = $this->websitelink;
        } else  {
            $value=NULL;
        }
        $textinput = new textinput('websitelink');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        $textinput->setValue($value);
        $table->addCell($textinput->show());
        $table->endRow();


        $fieldset2 = $this->newObject('fieldset', 'htmlelements');
        $fieldset2->setLegend('Contact Details');
        $fieldset2->addContent($table->show());

        // Table for the buttons
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "buttonHolder";
        // Save button.
        $table->startRow();
        $table->addCell("&nbsp;");
        $buttonTitle = $this->objLanguage->languageText('word_save');
        $button = new button('submitInstitution', $buttonTitle);
        $button->setToSubmit();
        //$button->cssId = "submitInstitution";
        $table->addCell($button->show());
        $table->endRow();

        // Insert a message area for Ajax result to display.
        $msgArea = "<br /><div id='save_results' class='ajax_results'></div>";
        
        // Add hidden fields for use by JS
        $hiddenFields = "\n\n";
        $hidMode = new hiddeninput('mode');
        $hidMode->cssId = "mode";
        $hidMode->value = $this->mode;
        $hiddenFields .= $hidMode->show() . "\n";
        $hidId = new hiddeninput('id');
        $hidId->cssId = "id";
        $hidId->value = $this->getParam('id', NULL);
        $hiddenFields .= $hidId->show() . "\n\n";
        
        // Createform, add fields to it and display.
        $formData = new form('institutionEditor', NULL);
        $formData->addToForm(
          $fieldsetInstitutionInfo->show()
          . $fieldset2->show()
          . $table->show()
          . $hiddenFields
          . $msgArea);
        return $formData->show();
    }

    /**
     *
     * For editing, load the data according to the ID provided. It
     * loads the data into object properties.
     *
     * @param string $id The id of the record to load
     * @return boolean TRUE|FALSE
     * @access private
     *
     */
    private function loadData($id)
    {
        $objDbInstitution = $this->getObject('dbinstitution');
        $arData = $objDbInstitution->getInstitutionById($id);
        if (!empty($arData)) {
            foreach ($arData[0] as $key=>$value) {
                $this->$key =  $value;
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
?>