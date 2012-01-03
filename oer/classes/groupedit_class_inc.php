<?php
/**
 *
 * Group editor functionality for OER module
 *
 * Group editor functionality for OER module provides for the creation of the
 * group editor form, which is used by the class block_groupedit_class_inc.php
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
 * Group editor functionality for OER module
 *
 * Group editor functionality for OER module provides for the creation of the
 * group editor form, which is used by the class block_groupedit_class_inc.php
*
* @package   oer
* @author    Derek Keats derek@dkeats.com
*
*/
class groupedit extends object
{

    public $objLanguage;
    private $objThumbUploader;
    private $objDbInstitution;
    private $userstring;
    private $group;
    private $linkedInstitution;

    /**
    *
    * Intialiser for the oerfixer database connector
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
        $this->objThumbUploader = $this->getObject('thumbnailuploader');
        $this->objDbInstitution = $this->getObject('dbinstitution');
        // Load the jquery validate plugin
        $this->appendArrayVar('headerParams',
        $this->getJavaScriptFile('plugins/validate/jquery.validate.min.js',
          'jquery'));
        // Load the helper Javascript.
        $this->appendArrayVar('headerParams',
          $this->getJavaScriptFile('groupedit.js',
          'oer'));
        // Load all the required HTML classes from HTMLElements module.
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable','htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('fieldset','htmlelements');
        // Get Group details.
        $this->objDbGroups = $this->getObject('dbgroups');
        $this->group=$this->objDbGroups->getGroupInfo($this->getParam('id', NULL));
        // Get edit or add mode from querystring.
        $this->mode = $this->getParam('mode', 'add');
    }

    public function show()
    {
        return $this->makeHeading()  . $this->buildForm();
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
        
        $objDbGroups = $this->getObject('dbgroups');
        $arData = $objDbGroups->getGroupInfo($id);
        if (!empty($arData)) {
            foreach ($arData[0] as $key=>$value) {
                $this->$key =  $value;
            }
            return TRUE;
        } else {
            return FALSE;
        }
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
        // setup and show heading
        $header = new htmlheading();
        $header->type = 1;
        if (isset($this->name)) {
            $header->str = $this->name;
        } else {
            $header->str = $this->objLanguage->languageText(
                  'mod_oer_group_new', 'oer', "Creating a new group");;
        }
        
        return $header->show();
    }

    private function buildForm()
    {
        $uri=$this->uri(array(
            'action'=>'editGroup',
            'id'=>$this->getParam('id')
        )); // =========== CHANGE TO SAVEGROUP

        // Create the form.
        $form = new form ('groupEditor',$uri);

        // Create a table to hold the layout
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->width = '100%';
        $table->border = '0';
        $tableable->cellspacing = '0';
        $table->cellpadding = '2';

        // Group name.
        $name = new textinput('group_name');
        $name->size = 80;
        $name->cssClass = 'required';
        if ($this->mode == 'edit') {
            $name->value = $this->name;
        } else {
            $name->value = NULL;
        }
        $table->startRow();
        $table->addCell(
          $this->objLanguage->languageText('mod_oer_group_name',
          'oer'));
        $table->addCell($name->show());
        $table->endRow();
        
        // Group website.
        $website = new textinput('group_website');
        $website->size = 80;
        $website->cssClass = 'required';
        if ($this->mode == 'edit') {
            $website->value = $this->website;
        } else {
            $website->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_website', 'oer')); // obj lang
        $table->addCell($website->show());
        $table->endRow();

        // Group avatar or thumbnail.
        if ($this->mode == 'edit') {
            $content ="\n<img src='" 
              . $this->thumbnail
              . "' alt'='Featured' width='30' height='30'><br>\n\n"; // objLang ====
        } else {
            $content = NULL;
        }
        $table->startRow();
        $table->addCell($content);
        $table->addCell("Change Avatar" .'&nbsp;'
          . $this->objThumbUploader->show()); // objLang ====
        $table->endRow();
        
        // Put it in a fieldset.
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend =$this->objLanguage->languageText('mod_oer_group_fieldset1', 'oer');
        $fieldset->contents = $table->show();
        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');

        // Descriprion line one.
        $table = $this->newObject('htmltable', 'htmlelements');
        $description_one = new textinput('description_one');
        $description_one ->size = 80;
        $description_one->cssClass = "required";
        if ($this->mode == 'edit') {
            $description_one->value = $this->description_one;
        } else {
            $description_one->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_description_one','oer')); // obj lang
        $table->addCell($description_one ->show());
        $table->endRow();
        
        // Description line two.
        $description_two = new textinput('description_two');
        $description_two->size = 80;
        if ($this->mode == 'edit') {
            $description_two->value = $this->description_two;
        } else {
            $description_two->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_description_two','oer')); // obj lang
        $table->addCell($description_two->show());
        $table->endRow();
        
        //Descriprion line three
        $description_three = new textinput('description_three');
        $description_three->size = 80;
        if ($this->mode == 'edit') {
            $description_three->value = $this->description_two;
        } else {
            $description_three->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_description_three','oer')); // obj lang
        $table->addCell($description_three->show());
        $table->endRow();
        
        //Description line four.
        $description_four= new textinput('description_four');
        $description_four->size = 80;
        if ($this->mode == 'edit') {
            $description_four->value = $this->description_two;
        } else {
            $description_four->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_description_four','oer')); // obj lang
        $table->addCell($description_four->show());
        $table->endRow();
        
        // Group description.
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'description';
        $editor->height = '150px';
        $editor->width = '85%';
        $editor->setBasicToolBar();
        if ($this->mode == 'edit') {
            $editor->setContent($this->description);
        } 
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_description', 'oer'));
        $table->addCell($editor->show());
        $table->endRow();
        
        // Put it in a fieldset.
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend =$this->objLanguage->languageText('mod_oer_group_fieldset5', 'oer');
        $fieldset->contents = $table->show();
        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');

        // Group contact details.
        $table = $this->newObject('htmltable', 'htmlelements');
        $email = new textinput('register_email');
        $email->size = 80;
        $email->cssClass = "requried";
        if ($this->mode == 'edit') {
            $email->value = $this->email;
        } else {
            $email->value = NULL;
        }
        $table->addCell($this->objLanguage->languageText('mod_oer_group_email', 'oer'));
        $table->addCell($email->show());
        $table->endRow();

        // Group address.
        $address = new textinput('group_address');
        $address->size = 80;
        $address->cssClass = 'required';
        if ($this->mode == 'edit') {
            $address->value = $this->address;
        } else {
            $address->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_address', 'oer'));
        $table->addCell($address->show());
        $table->endRow();

        // Group city.
        $city = new textinput('group_city');
        $city->size = 80;
        $city->cssClass='required';
        if ($this->mode == 'edit') {
            $city->value = $this->city;
        } else {
            $city->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_city', 'oer'));
        $table->addCell($city->show());
        $table->endRow();

        // Group state/province.
        $state = new textinput('group_state');
        $state->size = 80;
        $state->cssClass = 'required';
        if ($this->mode == 'edit') {
            $state->value = $this->state;
        } else {
            $state->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_state', 'oer'));// obj lang
        $table->addCell($state->show());
        $table->endRow();

        // Group postal code.
        $code = new textinput('group_postalcode');
        $code->size = 80;
        $code->cssClass = 'required';
        if ($this->mode == 'edit') {
            $code->value = $this->postalcode;
        } else {
            $code->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_postalcode', 'oer'));
        $table->addCell($code->show());
        $table->endRow();
        
        // Put it in a fieldset.
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend =$this->objLanguage->languageText('mod_oer_group_fieldset2', 'oer');
        $fieldset->contents = $table->show();
        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');

        // Group latitude.
        $table = $this->newObject('htmltable', 'htmlelements');
        $latitude = new textinput('group_loclat');
        $latitude->size = 38;
        if ($this->mode == 'edit') {
            $latitude->value = $this->loclat;
        } else {
            $latitude->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_latitude', 'oer'));
        $table->addCell($latitude->show());
        $table->endRow();
        
        // Group longitude.
        $longitude = new textinput('group_loclong');
        $longitude->size = 38;
        if ($this->mode == 'edit') {
            $longitude->value = $this->loclong;
        } else {
            $longitude->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_longitude', 'oer'));
        $table->addCell($longitude->show());
        $table->endRow();
        
        // Group country.
        $table->startRow();
        $objCountries = &$this->getObject('languagecode', 'language');
        $table->addCell($this->objLanguage->languageText('word_country', 'system'));
        if ($this->mode == 'edit') {
            $table->addCell($objCountries->countryAlpha($this->country));
        } else {
            $table->addCell($objCountries->countryAlpha());
        }
        $table->endRow();

        // Put it in a fieldset with a google map.
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend =$this->objLanguage->languageText('mod_oer_group_fieldset3', 'oer');
        $fieldset->contents = '<label>Address: </label><input id="address"  type="text"/> Please enter Your location if the provided location is incorrect
            <div id="map_edit" style="width:600px; height:300px"></div><br/>' . $table->show();
        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');

// DONE TO HERE----------------------
        
        $table = $this->newObject('htmltable', 'htmlelements');
        $user_current_membership = $this->objDbGroups->getGroupInstitutions(
          $this->getParam('id'));
        $currentMembership = array();

        $availablegroups = array();
        $groups = $this->objDbInstitution->getAllInstitutions();
        foreach ($groups as $group) {
            if (count($user_current_membership) > 0) { ///****** undefined
                foreach ($user_current_membership as $membership) {
                    if($membership['institution_id'] !=NULL){
                    if (strcmp($group['id'], $membership['institution_id']) == 0 ) {
                        array_push($currentMembership, $group);
                    }else {
                        array_push($availablegroups, $group);
                    }}
                }
            } else { /// TODO WHY IS NOT SHOWING ON EDIT ADMIN
                array_push($availablegroups, $group);
            }
        }

        $objSelectBox = $this->newObject('selectbox','htmlelements');
        $objSelectBox->create( $form, 'leftList[]', 'Available Institutionss', 'rightList[]', 'Chosen Institutions' );
        $objSelectBox->insertLeftOptions(
                                $availablegroups,
                                'id',
                                'name' );
        $objSelectBox->insertRightOptions(
                                       $currentMembership,
                                       'id',
                                       'name');

        $tblLeft = $this->newObject( 'htmltable','htmlelements');
        $objSelectBox->selectBoxTable( $tblLeft, $objSelectBox->objLeftList);
        //Construct tables for right selectboxes
        $tblRight = $this->newObject( 'htmltable', 'htmlelements');
        $objSelectBox->selectBoxTable( $tblRight, $objSelectBox->objRightList);
        //Construct tables for selectboxes and headings
        $tblSelectBox = $this->newObject( 'htmltable', 'htmlelements' );
        $tblSelectBox->width = '90%';
        $tblSelectBox->startRow();
            $tblSelectBox->addCell( $objSelectBox->arrHeaders['hdrLeft'], '100pt' );
            $tblSelectBox->addCell( $objSelectBox->arrHeaders['hdrRight'], '100pt' );
        $tblSelectBox->endRow();
        $tblSelectBox->startRow();
            $tblSelectBox->addCell( $tblLeft->show(), '100pt' );
            $tblSelectBox->addCell( $tblRight->show(), '100pt' );
        $tblSelectBox->endRow();

        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_institution', 'oer'));
        $table->addCell($tblSelectBox->show());
        $table->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend =$this->objLanguage->languageText('mod_oer_group_fieldset4', 'oer');
        $fieldset->contents = $table->show();
        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');

        //$button = new button ('submitform', $this->objLanguage->languageText('mod_oer_group_update_details_button', 'oer') );
        //$button->setToSubmit();

        $button = new button ('submitGroup',$this->objLanguage->languageText('mod_oer_group_save_button', 'oer'));
        $button->setToSubmit();
        //$action = $objSelectBox->selectAllOptions( $objSelectBox->objRightList )." SubmitProduct();";
        //$button->setOnClick('javascript: ' . $action);
        $form->addToForm($button->show());
        
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

        $form->addToForm($msgArea);
        $form->addToForm($hiddenFields);
                
        // Send the form
        return $form->show();
    }

}
?>