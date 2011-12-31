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

public function xxinit(){}

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
        $this->objThumbUploader = $this->getObject('thumbnailuploader');
        $this->objDbInstitution = $this->getObject('dbinstitution');
        // Load the helper Javascript
        $this->appendArrayVar('headerParams',
          $this->getJavaScriptFile('groupedit.js',
          'oer'));
        // Configure the userstring
        $this->userstring = $this->getParam('userstring', NULL);
        if($this->userstring !== NULL) {
            $this->userstring = base64_decode($this->userstring);
            $this->userstring = explode(',', $this->userstring);
        }
        // Load all the required HTML classes from HTMLElements module
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable','htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('fieldset','htmlelements');
        //Get Group details
        $this->objDbGroups = $this->getObject('dbgroups');
        $this->group=$this->objDbGroups->getGroupInfo($this->getParam('id', NULL));
        //$this->linkedInstitution=$this->objDbGroups->getLinkedInstitution($this->getParam('id'));
    }

    public function show()
    {
        return $this->makeHeading();
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
        $header->str = $this->group[0]['name'].":"."Profile";  //objLang @ToDo
        return $header->show() . $this->buildForm();
    }

    private function buildForm()
    {
        $uri=$this->uri(array(
            'action'=>'editGroup',
            'id'=>$this->getParam('id')
        )); // =========== CHANGE TO SAVEGROUP

        // Create the form
        $form = new form ('editer',$uri);

        // Create a table to hold the layout
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->width = '100%';
        $table->border = '0';
        $tableable->cellspacing = '0';
        $table->cellpadding = '2';

        // Group name
        $name = new textinput('group_name');
        $name->size = 80;
        $name->value = $this->group[0]['name'];
        $ruleLabel = $this->objLanguage->languageText(
                'mod_oer_grouprule_namerequired', 'oer')
        $form->addRule('group_name',$ruleLabel,'required');


/*---------------------------
        if ($mode == 'addfixup') {
            $name->value = $this->getParam('group_name');

            if ($this->getParam('group_name') == '') {
                $messages[] = $this->objLanguage->languageText(
                  'mod_oer_group_message1', 'oer');
            }
        }
        if (isset($this->userstring[0]) && $mode == 'add') {
            $name->value = $userstring[0];
        }
 --------*/


        $table->startRow();
        $table->addCell(
          $this->objLanguage->languageText('mod_oer_group_name',
          'oer'));
        $table->addCell($name->show());
        $table->endRow();
        //group website
        $website = new textinput('group_website');
        $website->size = 80;
        $website->value = $this->group[0]['website'];

/*-----------------
        if ($mode == 'addfixup') {
            $website->value = $this->getParam('group_website');

            if ($this->getParam('group_website') == '') {
                $messages[] =$this->objLanguage->languageText('mod_oer_group_message2', 'oer');
            }
        }
        if (isset($userstring[1]) && $mode == 'add')
        {
            $website->value = $userstring[1];
        }
--------*/

        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_website', 'oer')); // obj lang
        $table->addCell($website->show());
        $table->endRow();


        $content.='
                  <img src="' . $group[0]['thumbnail']
        . '" alt"="Featured" width="30" height="30"><br>

                          '; // objLang ====

        $table->startRow();
        $table->addCell($content);
        $table->addCell("Change Avatar" .'&nbsp;'
          . $this->objThumbUploader->show()); // objLang ====
        $table->endRow();
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend =$this->objLanguage->languageText('mod_oer_group_fieldset1', 'oer');
        $fieldset->contents = $table->show();
        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');

        //Descriprion line one
        $table = $this->newObject('htmltable', 'htmlelements');
        $description_one = new textinput('description_one');
        $description_one ->size = 80;
        $description_one->value = $group[0]['description_one'];
        if ($mode == 'addfixup') {
            $description_one->value = $this->getParam('description_one');

            if ($this->getParam('description_one') == '') {
                $messages[] = $this->objLanguage->languageText('mod_oer_group_description_line', 'oer');
            }
        }
        if (isset($userstring[1]) && $mode == 'add')
        {
            $description_one->value = $userstring[1];
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_description_one','oer').$required); // obj lang
        $table->addCell($description_one ->show());
        $table->endRow();
        //Descriprion line two
        $description_two = new textinput('description_two');
        $description_two->size = 80;
        $description_two->value = $group[0]['description_two'];
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_description_two','oer')); // obj lang
        $table->addCell($description_two->show());
        $table->endRow();
        //Descriprion line three
        $description_three = new textinput('description_three');
        $description_three->size = 80;
        $description_three->value = $group[0]['description_three'];
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_description_three','oer')); // obj lang
        $table->addCell($description_three->show());
        $table->endRow();
        //Descriprion line four
        $description_four= new textinput('description_four');
        $description_four->size = 80;
        $description_four->value = $group[0]['description_four'];
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_description_four','oer')); // obj lang
        $table->addCell($description_four->show());
        $table->endRow();
        //group description
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'description';
        $editor->height = '150px';
        $editor->width = '85%';
        $editor->setBasicToolBar();
        $editor->setContent($group[0]['description']);
        if ($mode == 'addfixup') {
            $editor->value = $this->getParam('description');

            if ($this->getParam('description') == '') {
                $messages[] = $this->objLanguage->languageText('mod_oer_group_message3', 'oer');
                }
        }
        if (isset($userstring[2]) && $mode == 'add')
        {
           $editor->value = $userstring[2];
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_description', 'oer'));
        $table->addCell($editor->show());
        $table->endRow();
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend =$this->objLanguage->languageText('mod_oer_group_fieldset5', 'oer');
        $fieldset->contents = $table->show();
        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');

        //group contact details
        $table = $this->newObject('htmltable', 'htmlelements');

        $email = new textinput('register_email');
        $email->size = 80;
        $email->value = $group[0]['email'];
        if ($mode == 'addfixup') {
            $email->value = $this->getParam('register_email');
           }
        if (isset($userstring[3]) && $mode == 'add')
        {
            $email->value = $userstring[3];

        }

        $table->addCell($this->objLanguage->languageText('mod_oer_group_email', 'oer'));
        $table->addCell($email->show());
        $table->endRow();

        //address
        $address = new textinput('group_address');
        $address->size = 80;
        $address->value = $this->group[0]['address'];
        if ($mode == 'addfixup') {
            $address->value = $this->getParam('group_address');

            if ($this->getParam('group_address') == '') {
                $messages[] =$this->objLanguage->languageText('mod_oer_group_message4', 'oer');
            }
        }
        if (isset($userstring[4]) && $mode == 'add')
        {
            $address->value = $userstring[4];
        }

        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_address', 'oer'));
        $table->addCell($address->show());
        $table->endRow();

        //CITY
        $city = new textinput('group_city');
        $city->size = 80;
        $city->value = $group[0]['city'];
        if ($mode == 'addfixup') {
            $city->value = $this->getParam('group_city');

            if ($this->getParam('group_city') == '') {
                $messages[] = $this->objLanguage->languageText('mod_oer_group_message5', 'oer');
            }
        }
        if (isset($userstring[5]) && $mode == 'add')
        {
            $city->value = $userstring[5];
        }

        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_city', 'oer'));
        $table->addCell($city->show());
        $table->endRow();

        //STATE
        $state = new textinput('group_state');
        $state->size = 80;
        $state->value = $group[0]['state'];
        if ($mode == 'addfixup') {
            $state->value = $this->getParam('group_state');

            if ($this->getParam('group_state') == '') {
                //$messages[] = $this->objLanguage->languageText('mod_oer_group_message6', 'oer');
            }
        }
        if (isset($userstring[6]) && $mode == 'add')
        {
            $state->value = $userstring[6];
        }

        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_state', 'oer'));// obj lang
        $table->addCell($state->show());
        $table->endRow();

        //postal code
        $code = new textinput('group_postalcode');
        $code->size = 80;
        $code->value = $this->group[0]['postalcode'];
        if ($mode == 'addfixup') {
            $code->value = $this->getParam('group_postalcode');

            if ($this->getParam('group_postalcode') == '') {
                $messages[] =$this->objLanguage->languageText('mod_oer_group_message7', 'oer');
                }
        }
        if (isset($this->userstring[7]) && $mode == 'add')
        {
            $code->value = $this->userstring[7];
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_postalcode', 'oer'));
        $table->addCell($code->show());
        $table->endRow();
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend =$this->objLanguage->languageText('mod_oer_group_fieldset2', 'oer');
        $fieldset->contents = $table->show();
        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');

        //latitude
        $table = $this->newObject('htmltable', 'htmlelements');
        $latitude = new textinput('group_loclat');
        $latitude->size = 38;
        $latitude->value = $this->group[0]['loclat'];
        if ($mode == 'addfixup') {
            $latitude->value = $this->getParam('group_loclong');

            if ($this->getParam('group_loclong') == '') {
                $messages[] =$this->objLanguage->languageText('mod_oer_group_message8', 'oer');
            }
        }
        if (isset($userstring[8]) && $mode == 'add')
        {
            $latitude->value = $this->userstring[8];
        }

        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_latitude', 'oer'));
        $table->addCell($latitude->show());
        $table->endRow();
        //longitude
        $longitude = new textinput('group_loclong');
        $longitude->size = 38;
        $longitude->value = $group[0]['loclong'];
        if ($mode == 'addfixup') {
            $longitude->value = $this->getParam('group_loclong');

            if ($this->getParam('group_loclong') == '') {
                $messages[] = $this->objLanguage->languageText('mod_oer_group_message9', 'oer');
            }
        }
        if (isset($userstring[9]) && $mode == 'add')
        {
            $longitude->value = $userstring[9];
        }

        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_longitude', 'oer'));
        $table->addCell($longitude->show());
        $table->endRow();
        //country
        $table->startRow();
        $objCountries = &$this->getObject('languagecode', 'language');
        $table->addCell($this->objLanguage->languageText('word_country', 'system'));
        if ($mode == 'addfixup') {
            $table->addCell($objCountries->countryAlpha($this->getParam('country')));
        } else {
            $table->addCell($objCountries->countryAlpha());
        }
        $table->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend =$this->objLanguage->languageText('mod_oer_group_fieldset3', 'oer');

        $fieldset->contents = '<label>Address: </label><input id="address"  type="text"/> Please enter Your location if the provided location is incorrect
            <div id="map_edit" style="width:600px; height:300px"></div><br/>' . $table->show();

        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');

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

        $button = new button ('submitform',$this->objLanguage->languageText('mod_oer_group_save_button', 'oer'));
        //$button->setToSubmit();
        $action = $objSelectBox->selectAllOptions( $objSelectBox->objRightList )." SubmitProduct();";
        $button->setOnClick('javascript: ' . $action);

        $Cancelbutton = new button ('submitform', $this->objLanguage->languageText('mod_oer_group_cancel_button', 'oer'));
        $Cancelbutton->setToSubmit();
        $CancelLink = new link($this->uri(array('action' => "groupListingForm")));
        $CancelLink->link =$Cancelbutton->show();

        $form->extra = 'enctype="multipart/form-data"';
        $form->addToForm('<p align="right">'.$button->show().$CancelLink->show().'</p>');

        // Send the form
        return $form->show();
    }

}
?>