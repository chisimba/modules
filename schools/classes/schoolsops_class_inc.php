<?php
/**
 * Class to handle schools elements.
 *
 * This object can be used elsewhere in the system to render certain aspects of the interface.
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
 * @version    0.001
 * @package    schools
 * @author     Kevin Cyster kcyster@gmail.com
 * @copyright  2011 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://www.chisimba.com
 */
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Class to handle blog elements
 *
 * This object can be used elsewhere in the system to render certain aspects of the interface
 *
 * @version    0.001
 * @package    schools
 * @author     Kevin Cyster kcyster@gmail.com
 * @copyright  2011 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://www.chisimba.com
 */
class schoolsops extends object
{
    /**
     * Standard init function called by the constructor call of Object
     *
     * @access public
     * @return NULL
     */
    public function init()
    {
        try {
            // Load core system objects.
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objUser = $this->getObject('user', 'security');
            $this->objUserAdmin = $this->getObject('useradmin_model2', 'security');
            $this->objSvars = $this->getObject('serializevars', 'utilities');
            $this->objGroups = $this->getObject('groupadminmodel', 'groupadmin');
            $this->objConfirm = $this->newObject('confirm', 'utilities');

            // Load html elements.
            $this->objIcon = $this->newObject('geticon', 'htmlelements');
            $this->objTable = $this->loadClass('htmltable', 'htmlelements');
            $this->objLink = $this->loadClass('link', 'htmlelements');
            $this->objInput = $this->loadClass('textinput', 'htmlelements');
            $this->objFieldset = $this->loadClass('fieldset', 'htmlelements');
            $this->objDropdown = $this->loadClass('dropdown', 'htmlelements');
            $this->objForm = $this->loadClass('form', 'htmlelements');
            $this->objLayer = $this->loadClass('layer', 'htmlelements');
            $this->objRadio = $this->loadClass('radio', 'htmlelements');
            
            // Load db classes,
            $this->objDBprovinces = $this->getObject('dbschools_provinces', 'schools');
            $this->objDBdistricts = $this->getObject('dbschools_districts', 'schools');
            $this->objDBcontacts = $this->getObject('dbschools_contacts', 'schools');
            $this->objDBdetail = $this->getObject('dbschools_detail', 'schools');
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }

    /**
     * Method to display schools middle content
     *
     * @return string
     */
    public function showList()
    {
        // Set up language elements.
        $province = $this->objLanguage->languageText('mod_schools_province', 'schools', 'TEXT: mod_schools_province, not found');
        $district = $this->objLanguage->languageText('mod_schools_district', 'schools', 'TEXT: mod_schools_district, not found');
        $principal = $this->objLanguage->languageText('mod_schools_principal', 'schools', 'TEXT: mod_schools_principal, not found');
        $schoolName = $this->objLanguage->languageText('mod_schools_schoolname', 'schools', 'TEXT: mod_schools_schoolname, not found');
        $telephoneNumber = $this->objLanguage->languageText('phrase_telephonenumber', 'system', 'PHRASE: phrase_telephonenumber, not found');
        $emailAddress = $this->objLanguage->languageText('phrase_emailaddress', 'system', 'PHRASE: phrase_emailaddress, not found');
        $addSchool = $this->objLanguage->languageText('mod_schools_addschooldetail', 'schools', 'TEXT: mod_schools_addschooldetail, not found');
        $editSchool = $this->objLanguage->languageText('mod_schools_editschooldetail', 'schools', 'TEXT: mod_schools_editschooldetail, not found');
        $deleteSchool = $this->objLanguage->languageText('mod_schools_deleteschooldetail', 'schools', 'TEXT: mod_schools_deleteschooldetail, not found');        
        $add = $this->objLanguage->languageText('word_add', 'system', 'WORD: word_add, not found');
        $edit = $this->objLanguage->languageText('word_edit', 'system', 'WORD: word_edit, not found');
        $delete = $this->objLanguage->languageText('word_delete', 'system', 'WORD: word_delete, not found');        
        $deleteconfirm = $this->objLanguage->languageText('mod_schools_deleteconfirm', 'schools', 'TEXT: mod_schools_deleteconfirm, not found');        
        
        // get schools detail data.
        $details = $this->objDBdetail->getDetails();

        $this->objIcon->title = $addSchool;
        $this->objIcon->alt = $addSchool;
        $this->objIcon->setIcon('add', 'png');
        $addIcon = $this->objIcon->show();
        
        $objLink = new link($this->uri(array('action' => 'add')));
        $objLink->link = $addIcon . '&nbsp' . $addSchool;
        $addLink = $objLink->show();
               
        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($addLink, '', '', '', 'colspan="7"');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('<b>' . $schoolName . '</b>', '', '', '', '');
        $objTable->addCell('<b>' . $principal . '</b>', '', '', '', '');
        $objTable->addCell('<b>' . $telephoneNumber . '</b>', '', '', '', '');
        $objTable->addCell('<b>' . $emailAddress . '</b>', '', '', '', '');
        $objTable->addCell('<b>' . $district . '</b>', '', '', '', '');
        $objTable->addCell('<b>' . $province . '</b>', '', '', '', '');
        $objTable->addCell('<b>' . $edit . '</b>', '', '', '', '');
        $objTable->addCell('<b>' . $delete . '</b>', '', '', '', '');
        $objTable->endRow();
        foreach ($details as $key => $value)
        {
            $this->objIcon->setIcon('delete', 'png');
            $this->objIcon->title = $deleteSchool;
            $this->objIcon->alt = $deleteSchool;
            $icon = $this->objIcon->show();

            $location = $this->uri(array('action' => 'delete', 'id' => $value['id']));

            $this->objConfirm->setConfirm($icon, $location, $deleteconfirm);
            $deleteIcon = $this->objConfirm->show();
            
            $this->objIcon->title = $editSchool;
            $this->objIcon->alt = $editSchool;
            $editIcon = $this->objIcon->getLinkedIcon($this->uri(array('action' => 'edit', 'id' => $value['id'])), 'edit', 'png');

            $objTable->startRow();
            $objTable->addCell($value['school_name'], '', '', '', '');
            $objTable->addCell($value['firstname'] . '&nbsp' . $value['surname'], '', '', '', '');
            $objTable->addCell($value['telephone_number'], '', '', '', '');
            $objTable->addCell($value['email_address'], '', '', '', '');
            $objTable->addCell($value['district_name'], '', '', '', '');
            $objTable->addCell($value['province_name'], '', '', '', '');
            $objTable->addCell($editIcon, '', '', '', '');
            $objTable->addCell($deleteIcon, '', '', '', '');
            $objTable->endRow();
        }
        $objTable->startRow();
        $objTable->addCell($addLink, '', '', '', 'colspan="7"');
        $objTable->endRow();
        $schoolsTable = $objTable->show();
        
        return $schoolsTable;
    }
    
    /**
     * Method to display the add details form
     * 
     * @return string 
     */
    public function showAddForm()
    {
        // get session data for previously entered data on erorrs.
        $schools = $this->getSession('schools');

        $provinceIdValue = !empty($schools) ? $schools['data']['province_id'] : NULL;
        $districtIdValue = !empty($schools) ? $schools['data']['district_id'] : NULL;
        $schoolNameValue = !empty($schools) ? $schools['data']['school_name'] : NULL;
        $addressOneValue = !empty($schools) ? $schools['data']['address_one'] : NULL;
        $addressTwoValue = !empty($schools) ? $schools['data']['address_two'] : NULL;
        $addressThreeValue = !empty($schools) ? $schools['data']['address_three'] : NULL;
        $addressFourValue = !empty($schools) ? $schools['data']['address_four'] : NULL;
        $emailAddressValue = !empty($schools) ? $schools['data']['email_address'] : NULL;
        $telephoneNumberValue = !empty($schools) ? $schools['data']['telephone_number'] : NULL;
        $faxNumberValue = !empty($schools) ? $schools['data']['fax_number'] : NULL;
        $titleValue = !empty($schools) ? $schools['data']['title'] : NULL;
        $firstNameValue = !empty($schools) ? $schools['data']['first_name'] : NULL;
        $lastNameValue = !empty($schools) ? $schools['data']['last_name'] : NULL;
        $genderValue = !empty($schools) ? $schools['data']['gender'] : NULL;
        $mobileNumberValue = !empty($schools) ? $schools['data']['mobile_number'] : NULL;
        $userEmailAddressValue = !empty($schools) ? $schools['data']['principal_email_address'] : NULL;
        $usernameValue = !empty($schools) ? $schools['data']['username'] : NULL;
        $passwordValue = !empty($schools) ? $schools['data']['password'] : NULL;
        $confirmPasswordValue = !empty($schools) ? $schools['data']['confirm_password'] : NULL;
        $contactPositionValue = !empty($schools) ? $schools['data']['contact_position'] : NULL;
        $contactNameValue = !empty($schools) ? $schools['data']['contact_name'] : NULL;
        $contactAddressOneValue = !empty($schools) ? $schools['data']['contact_address_one'] : NULL;
        $contactAddressTwoValue = !empty($schools) ? $schools['data']['contact_address_two'] : NULL;
        $contactAddressThreeValue = !empty($schools) ? $schools['data']['contact_address_three'] : NULL;
        $contactAddressFourValue = !empty($schools) ? $schools['data']['contact_address_four'] : NULL;
        $contacEmailAddressValue = !empty($schools) ? $schools['data']['contact_email_address'] : NULL;
        $contactTelephoneNumberValue = !empty($schools) ? $schools['data']['contact_telephone_number'] : NULL;
        $contactMobileNumberValue = !empty($schools) ? $schools['data']['contact_mobile_number'] : NULL;
        $contactFaxNumberValue = !empty($schools) ? $schools['data']['contact_fax_number'] : NULL;

        $provinceIdError = (!empty($schools) && array_key_exists('province_id', $schools['errors'])) ? $schools['errors']['province_id'] : NULL;
        $districtIdError = (!empty($schools) && array_key_exists('district_id', $schools['errors'])) ? $schools['errors']['district_id'] : NULL;
        $schoolNameError = (!empty($schools) && array_key_exists('school_name', $schools['errors'])) ? $schools['errors']['school_name'] : NULL;
        $addressOneError = (!empty($schools) && array_key_exists('address_one', $schools['errors'])) ? $schools['errors']['address_one'] : NULL;
        $emailAddressError = (!empty($schools) && array_key_exists('email_address', $schools['errors'])) ? $schools['errors']['email_address'] : NULL;
        $telephoneNumberError = (!empty($schools) && array_key_exists('telephone_number', $schools['errors'])) ? $schools['errors']['telephone_number'] : NULL;
        $titleError = (!empty($schools) && array_key_exists('title', $schools['errors'])) ? $schools['errors']['title'] : NULL;
        $firstNameError = (!empty($schools) && array_key_exists('first_name', $schools['errors'])) ? $schools['errors']['first_name'] : NULL;
        $lastNameError = (!empty($schools) && array_key_exists('last_name', $schools['errors'])) ? $schools['errors']['last_name'] : NULL;
        $genderError = (!empty($schools) && array_key_exists('gender', $schools['errors'])) ? $schools['errors']['gender'] : NULL;
        $userEmailAddressError = (!empty($schools) && array_key_exists('principal_email_address', $schools['errors'])) ? $schools['errors']['principal_email_address'] : NULL;
        $usernameError = (!empty($schools) && array_key_exists('username', $schools['errors'])) ? $schools['errors']['username'] : NULL;
        $passwordError = (!empty($schools) && array_key_exists('password', $schools['errors'])) ? $schools['errors']['password'] : NULL;
        $contactPositionError = (!empty($schools) && array_key_exists('contact_position', $schools['errors'])) ? $schools['errors']['contact_position'] : NULL;
        $contactNameError = (!empty($schools) && array_key_exists('contact_name', $schools['errors'])) ? $schools['errors']['contact_name'] : NULL;
        $contactAddressOneError = (!empty($schools) && array_key_exists('contact_address_one', $schools['errors'])) ? $schools['errors']['contact_address_one'] : NULL;
        $contactEmailAddressError = (!empty($schools) && array_key_exists('contact_email_address', $schools['errors'])) ? $schools['errors']['contact_email_address'] : NULL;
        $contactTelephoneNumberError = (!empty($schools) && array_key_exists('contact_telephone_number', $schools['errors'])) ? $schools['errors']['contact_telephone_number'] : NULL;

        // get data from the database.
        $provinces = $this->objDBprovinces->getAll();        

        // set up text elements.
        $addSchool = $this->objLanguage->languageText('mod_schools_addschooldetail', 'schools', 'TEXT: mod_schools_addschooldetail, not found');
        $schoolName = $this->objLanguage->languageText('mod_schools_schoolname', 'schools', 'TEXT: mod_schools_schoolname, not found');
        $province = $this->objLanguage->languageText('mod_schools_province', 'schools', 'TEXT: mod_schools_province, not found');
        $selectProvince = $this->objLanguage->languageText('mod_schools_selectprovince', 'schools', 'TEXT:mod_schools_selectprovince, not found');
        $district = $this->objLanguage->languageText('mod_schools_district', 'schools', 'TEXT: mod_schools_district, not found');
        $selectDistrict = $this->objLanguage->languageText('mod_schools_selectdistrict', 'schools', 'TEXT:mod_schools_selectdistrict, not found');
        $address = $this->objLanguage->languageText('word_address', 'system', 'WORD: word_address, niot found');
        $emailAddress = $this->objLanguage->languageText('phrase_emailaddress', 'system', 'PHRASE: phrase_eamiladdress, not found');
        $telephoneNumber = $this->objLanguage->languageText('phrase_telephonenumber', 'system', 'PHRASE: phrase_telephonenumber, not found');
        $faxNumber = $this->objLanguage->languageText('phrase_faxnumber', 'system', 'PHRASE: phrase_faxnumber');
        $principal = $this->objLanguage->languageText('mod_schools_principal', 'schools', 'TEXT: mod_schools_principalname, not found');
        $title = $this->objLanguage->languageText('word_title', 'system', 'WORD: word_title, not found');
        $selectTitle = $this->objLanguage->languageText('phrase_selecttitle', 'system', 'PHRASE: phrase_selecttitle, not found');
        $mr = $this->objLanguage->languageText('title_mr', 'system', 'TITLE: title_mr, not found');
        $miss = $this->objLanguage->languageText('title_miss', 'system', 'TITLE: title_miss, not found');
        $mrs = $this->objLanguage->languageText('title_mrs', 'system', 'TITLE: title_mrs, not found');
        $ms = $this->objLanguage->languageText('title_ms', 'system', 'TITLE: title_ms, not found');
        $dr = $this->objLanguage->languageText('title_dr', 'system', 'TITLE: title_dr, not found');
        $rev = $this->objLanguage->languageText('title_rev', 'system', 'TITLE: title_rev, not found');
        $prof = $this->objLanguage->languageText('title_prof', 'system', 'TITLE: title_prof, not found');
        $assocprof = $this->objLanguage->languageText('title_assocprof', 'system', 'TITLE: title_assocprof, not found');
        $firstName = $this->objLanguage->languageText('phrase_firstname', 'system', 'PHRASE: phrase_firstname, not found');
        $lastName = $this->objLanguage->languageText('phrase_lastname', 'system', 'PHRASE: phrase_lastname, not found');
        $gender = $this->objLanguage->languageText('word_gender', 'system', 'WORD: word_gender, not found');
        $male = $this->objLanguage->languageText('word_male', 'system', 'WORD: word_male, not found');
        $female = $this->objLanguage->languageText('word_female', 'system', 'WORD: word_female, not found');
        $username = $this->objLanguage->languageText('word_username', 'system', 'WORD: word_username, not found');
        $password = $this->objLanguage->languageText('word_password', 'system', 'WORD: word_password, not found');
        $confirmPassword = $this->objLanguage->languageText('phrase_confirmpassword', 'system', 'PHRASE: phrase_confirmpassword, not found');
        $mobileNumber = $this->objLanguage->languageText('phrase_mobilenumber', 'system', 'PHRASE: phrase_mobilenumber, not found');
        $contactPerson = $this->objLanguage->languageText('mod_schools_contactperson', 'schools', 'TEXT: mod_schools_contactperson, not found');
        $position = $this->objLanguage->languageText('mod_schools_position', 'schools', 'TEXT: mod_schools_position, not found');
        $fullName = $this->objLanguage->languageText('mod_schools_fullname', 'schools', 'TEXT: mod_schools_fullname, not found');
        $save = $this->objLanguage->languageText('word_save', 'system', 'WORD: word_save, not found');
        $cancel = $this->objLanguage->languageText('word_cancel', 'system', 'WORD: word_cancel, not found');
        $passwordNotAlike = $this->objLanguage->languageText('mod_schools_passwordsnotalike', 'schools', 'TEXT: mod_schools_passwordsnotalike, not found');
        $noDistricts = $this->objLanguage->languageText('mod_schools_nodistricts', 'schools', 'TEXT: mod_schools_nodistricts, not found');
        
        $arrayVars = array();
        $arrayVars['password_not_alike'] = $passwordNotAlike;
       
        // pass password error to javascript.
        $this->objSvars->varsToJs($arrayVars);

        // set up htmlelements.
        $objDrop = new dropdown('province_id');
        $objDrop->addOption('', $selectProvince);
        $objDrop->addFromDB($provinces, 'province_name', 'id');
        $objDrop->setSelected($provinceIdValue);
        $provinceDrop = $objDrop->show();
        
        if (!empty($provinceIdValue))
        {
            $districts = $this->objDBdistricts->getDistricts($provinceIdValue);

            if (!empty($districts))
            {
                // Set up htmlelements.
                $objDrop = new dropdown('district_id');
                $objDrop->addOption('', $selectDistrict);
                $objDrop->addFromDB($districts, 'district_name', 'id');
                $objDrop->setSelected($districtIdValue);
                $districtDrop = $objDrop->show();

                $objTable = new htmltable();
                $objTable->cellpadding = '4';
                $objTable->startRow();
                $objTable->addCell($district. ': ', '200px', '', '', '', '');
                $objTable->addCell($districtIdError . $districtDrop, '', '', '', '', '');
                $objTable->endRow();
                $districtTable = $objTable->show();

                $string = $districtTable;
            }
            else
            {
                $districtTable = '<span class="error"><b>' . $noDistricts . '</b></span>';
            }
        }
        else
        {
            $districtTable = NULL;
        }
        
        $objInput = new textinput('school_name', $schoolNameValue, '', '50');
        $schoolNameInput = $objInput->show();
        
        $objInput = new textinput('address_one', $addressOneValue, '', '50');
        $addressOneInput = $objInput->show();

        $objInput = new textinput('address_two', $addressTwoValue, '', '50');
        $addressTwoInput = $objInput->show();
        
        $objInput = new textinput('address_three', $addressThreeValue, '', '50');
        $addressThreeInput = $objInput->show();
        
        $objInput = new textinput('address_four', $addressFourValue, '', '50');
        $addressFourInput = $objInput->show();

        $objInput = new textinput('email_address', $emailAddressValue, '', '50');
        $emailAddressInput = $objInput->show();
        
        $objInput = new textinput('telephone_number', $telephoneNumberValue, '', '50');
        $telephoneNumberInput = $objInput->show();
        
        $objInput = new textinput('fax_number', $faxNumberValue, '', '50');
        $faxNumberInput = $objInput->show();

        $objDrop = new dropdown('title');
        $objDrop->addOption('', $selectTitle);
        $objDrop->addOption($mr, $mr);
        $objDrop->addOption($miss, $miss);
        $objDrop->addOption($mrs, $mrs);
        $objDrop->addOption($ms, $ms);
        $objDrop->addOption($dr, $dr);
        $objDrop->addOption($rev, $rev);
        $objDrop->addOption($prof, $prof);
        $objDrop->addOption($assocprof, $assocprof);
        $objDrop->setSelected($titleValue);
        $titleDrop = $objDrop->show();

        $objInput = new textinput('first_name', $firstNameValue, '', '50');
        $firstNameInput = $objInput->show();
        
        $objInput = new textinput('last_name', $lastNameValue, '', '50');
        $lastNameInput = $objInput->show();
        
        $objRadio = new radio('gender');
        $objRadio->addOption('M', $male);
        $objRadio->addOption('F', $female);
        $objRadio->setSelected($genderValue);
        $genderRadio = $objRadio->show();
        
        $objInput = new textinput('mobile_number', $mobileNumberValue, '', '50');
        $mobileNumberInput = $objInput->show();

        $objInput = new textinput('principal_email_address', $userEmailAddressValue, '', '50');
        $userEmailAddressInput = $objInput->show();

        $objInput = new textinput('username', $usernameValue, '', '50');
        $usernameInput = $objInput->show();
        
        $objInput = new textinput('password', $passwordValue, 'password', '50');
        $passwordInput = $objInput->show();
        
        $objInput = new textinput('confirm_password', $confirmPasswordValue, 'password', '50');
        $confirmPasswordInput = $objInput->show();
        
        $objInput = new textinput('contact_position', $contactPositionValue, '', '50');
        $contactPositionInput = $objInput->show();

        $objInput = new textinput('contact_name', $contactNameValue, '', '50');
        $contactNameInput = $objInput->show();

        $objInput = new textinput('contact_address_one', $contactAddressOneValue, '', '50');
        $contactAddressOneInput = $objInput->show();

        $objInput = new textinput('contact_address_two', $contactAddressTwoValue, '', '50');
        $contactAddressTwoInput = $objInput->show();

        $objInput = new textinput('contact_address_three', $contactAddressThreeValue, '', '50');
        $contactAddressThreeInput = $objInput->show();

        $objInput = new textinput('contact_address_four', $contactAddressFourValue, '', '50');
        $contactAddressFourInput = $objInput->show();

        $objInput = new textinput('contact_email_address', $contacEmailAddressValue, '', '50');
        $contactEmailAddressInput = $objInput->show();

        $objInput = new textinput('contact_telephone_number', $contactTelephoneNumberValue, '', '50');
        $contactTelephoneNumberInput = $objInput->show();

        $objInput = new textinput('contact_mobile_number', $contactMobileNumberValue, '', '50');
        $contactMobileNumberInput = $objInput->show();

        $objInput = new textinput('contact_fax_number', $contactFaxNumberValue, '', '50');
        $contactFaxNumberInput = $objInput->show();
        
        $objButton = new button('save', $save);
        $objButton->setToSubmit();
        $saveButton = $objButton->show();
        
        $objButton = new button('cancel', $cancel);
        $objButton->setToSubmit();
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($province . ': ', '200px', '', '', '', '');
        $objTable->addCell($provinceIdError . $provinceDrop, '', '', '', '', '');
        $objTable->endRow();
        $provinceTable = $objTable->show();

        $objLayer = new layer();
        $objLayer->id = 'username';
        $usernameLayer = $objLayer->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($title . ': ', '200px', '', '', '', '');
        $objTable->addCell($titleError . $titleDrop, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($firstName . ': ', '', '', '', '', '');
        $objTable->addCell($firstNameError . $firstNameInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($lastName . ': ', '', '', '', '', '');
        $objTable->addCell($lastNameError . $lastNameInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($gender . ': ', '', '', '', '', '');
        $objTable->addCell($genderError . $genderRadio, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($emailAddress . ': ', '', '', '', '', '');
        $objTable->addCell($userEmailAddressError . $userEmailAddressInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($mobileNumber . ': ', '', '', '', '', '');
        $objTable->addCell($mobileNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($username . ': ', '', '', '', '', '');
        $objTable->addCell($usernameError . $usernameLayer . $usernameInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($password . ': ', '', '', '', '', '');
        $objTable->addCell($passwordError . $passwordInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($confirmPassword . ': ', '', '', '', '', '');
        $objTable->addCell($confirmPasswordInput, '', '', '', '', '');
        $objTable->endRow();
        $principalTable = $objTable->show();
        
        $objFieldset = new fieldset();
        $objFieldset->legend = '<b>'.$principal.'</b>';
        $objFieldset->contents = $principalTable;
        $principalFieldset = $objFieldset->show();
        
        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($position . ': ', '200px', '', '', '', '');
        $objTable->addCell($contactPositionError . $contactPositionInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($fullName . ': ', '', '', '', '', '');
        $objTable->addCell($contactNameError . $contactNameInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($address . ': ', '', '', '', '', '');
        $objTable->addCell($contactAddressOneError . $contactAddressOneInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('', '', '', '', '', '');
        $objTable->addCell($contactAddressTwoInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('', '', '', '', '', '');
        $objTable->addCell($contactAddressThreeInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('', '', '', '', '', '');
        $objTable->addCell($contactAddressFourInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($emailAddress . ': ', '', '', '', '', '');
        $objTable->addCell($contactEmailAddressError . $contactEmailAddressInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($telephoneNumber . ': ', '', '', '', '', '');
        $objTable->addCell($contactTelephoneNumberError . $contactTelephoneNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($mobileNumber . ': ', '', '', '', '', '');
        $objTable->addCell($contactMobileNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($faxNumber . ': ', '', '', '', '', '');
        $objTable->addCell($contactFaxNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $contactTable = $objTable->show();
        
        $objFieldset = new fieldset();
        $objFieldset->legend = '<b>'.$contactPerson.'</b>';
        $objFieldset->contents = $contactTable;
        $contactFieldset = $objFieldset->show();
        
        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($schoolName . ': ', '200px', '', '', '', '');
        $objTable->addCell($schoolNameError . $schoolNameInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($address . ': ', '', '', '', '', '');
        $objTable->addCell($addressOneError . $addressOneInput, '', '', '', '', '');
        $objTable->endRow(); 
        $objTable->startRow();
        $objTable->addCell('', '', '', '', '', '');
        $objTable->addCell($addressTwoInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('', '', '', '', '', '');
        $objTable->addCell($addressThreeInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('', '', '', '', '', '');
        $objTable->addCell($addressFourInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($emailAddress . ': ', '', '', '', '', '');
        $objTable->addCell($emailAddressError . $emailAddressInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($telephoneNumber . ': ', '', '', '', '', '');
        $objTable->addCell($telephoneNumberError . $telephoneNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($faxNumber . ': ', '', '', '', '', '');
        $objTable->addCell($faxNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($principalFieldset, '', '', '', '', 'colspan="2"');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($contactFieldset, '', '', '', '', 'colspan="2"');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($saveButton . '&nbsp' . $cancelButton, '', '', '', '', 'colspan="2"');
        $objTable->endRow();
        $detailsTable = $objTable->show();
        
        $objLayer = new layer();
        $objLayer->id = 'province';
        $objLayer->str = $provinceTable;
        $provinceLayer = $objLayer->show();

        $objLayer = new layer();
        $objLayer->id = 'districts';
        $objLayer->str = $districtTable;
        $districtLayer = $objLayer->show();

        $objLayer = new layer();
        $objLayer->id = 'detail';
        $objLayer->str = $detailsTable;
        $detailsLayer = $objLayer->show();

        $objForm = new form('detail', $this->uri(array(
            'action' => 'validateAddDetails'
        )));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($provinceLayer);
        $objForm->addToForm($districtLayer);
        $objForm->addToForm($detailsLayer);
        $addForm = $objForm->show();

        $string = '<b>' . $addSchool . '</b><br />';
        $string .= $addForm;
        
        return $string;        
    }
    
    /**
     * Method to return the html for an ajax call for districts for a province
     * 
     * @access public
     * @return string The string to display 
     */
    public function ajaxDistricts()
    {
        // Set up text elements.
        $noDistricts = $this->objLanguage->languageText('mod_schools_nodistricts', 'schools', 'TEXT: mod_schools_nodistricts, not found');
        $district = $this->objLanguage->languageText('mod_schools_district', 'schools', 'TEXT: mod_schools_district, not found');
        $selectDistrict = $this->objLanguage->languageText('mod_schools_selectdistrict', 'schools', 'TEXT: mod_schools_selectdistrct, not found');
        
        // Get parameter.
        $id = $this->getParam('id', FALSE);
        
        // Get data
        $districts = $this->objDBdistricts->getDistricts($id);

        if (!empty($districts))
        {
            // Set up htmlelements.
            $objDrop = new dropdown('district_id');
            $objDrop->addOption('', $selectDistrict);
            $objDrop->addFromDB($districts, 'district_name', 'id');
            $districtDrop = $objDrop->show();
            
            $objTable = new htmltable();
            $objTable->cellpadding = '4';
            $objTable->startRow();
            $objTable->addCell($district. ': ', '200px', '', '', '', '');
            $objTable->addCell($districtDrop, '', '', '', '', '');
            $objTable->endRow();
            $districtTable = $objTable->show();
            
            $string = $districtTable;
        }
        else
        {
            $string = '<span class="error"><b>' . $noDistricts . '</b></span>';
        }
        
        echo $string;
        die();
    }

    /**
     * Method to return the html for an ajax call for check for unique username
     * 
     * @access public
     * @return string The string to display 
     */
    public function ajaxUsername()
    {
        // Set up text elements.
        $usernameExists = $this->objLanguage->languageText('mod_schools_usernameexists', 'schools', 'TEXT: mod_schools_usernameexists, not found');
        $invalidUsername = $this->objLanguage->languageText('mod_schools_invalidusername', 'schools', 'TEXT: mod_schools_invalidusername, not found');
        $usernameShort = $this->objLanguage->languageText('mod_schools_usernameshort', 'schools', 'TEXT: mod_schools_usernameshort, not found');
        
        // Get parameter.
        $username = $this->getParam('username', FALSE);
        
        if (strlen($username) >= 3)
        {
            if (preg_match('/[^0-9A-Za-z]/',$username) != 0)
            {
                $string = '<span class="error"><b>' . $invalidUsername . '</b></span>';
            }
            else
            {
                // Get data
                $users = $this->objUserAdmin->usernameAvailable($username);
                if ($users === TRUE)
                {
                    $string = '';
                }
                else
                {
                    $string = '<span class="error"><b>' . $usernameExists . '</b></span>';
                }
            }
        }
        else
        {
            $string = '<span class="error"><b>' . $usernameShort . '</b></span>';
        }

        echo $string;
        die();
    }
    
    /**
     *
     * Method to validate the schools details data
     * 
     * @access public
     * @param array $data The data to validate
     * @return  
     */
    public function validateAddDetails($data)
    {
        $errors = array();
        foreach ($data as $fieldname => $value)
        {
            if ($fieldname != 'province_id' && $fieldname != 'district_id' && $fieldname != 'title' && $fieldname != 'gender'
                && $fieldname != 'address_one' && $fieldname != 'address_two' && $fieldname != 'address_three'
                && $fieldname != 'address_four' && $fieldname != 'contact_address_one' && $fieldname != 'contact_address_two' 
                && $fieldname != 'contact_address_three' && $fieldname != 'contact_address_four' && $fieldname != 'password'
                && $fieldname != 'confirm_password' && $fieldname != 'contact_fax_number' && $fieldname != 'fax_number'
                && $fieldname != 'mobile_number' && $fieldname != 'contact_telephone_number' && $fieldname != 'contact_mobile_number')
            {
                if ($value == NULL)
                {
                    $name = explode('_', $fieldname);
                    $name = implode(' ', $name);
                    $array = array('fieldname' => $name);
                    $errorText = $this->objLanguage->code2Txt('mod_schools_error_1', 'schools', $array);
                    $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                }
                elseif ($fieldname == 'username')
                {
                    if (strlen($value) <= 2)
                    {
                        $errorText = $this->objLanguage->languageText('mod_schools_usernameshort', 'schools', 'TEXT: mod_schools_usernameshort, not found');
                        $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                    }
                }
                elseif ($fieldname == 'email_address' || $fieldname == 'principal_email_address' || $fieldname == 'contact_email_address')
                {
                    if (filter_var($value, FILTER_VALIDATE_EMAIL) == FALSE)
                    {
                        $errorText = $this->objLanguage->languageText('mod_schools_invalidemail', 'schools', 'TEXT: mod_schools_invalidemail, not found');
                        $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                    }
                }
            }
            elseif ($fieldname == 'title' || $fieldname == 'gender')
            {
                if ($value == NULL)
                {
                    $array = array('fieldname' => $fieldname);
                    $errorText = $this->objLanguage->code2Txt('mod_schools_error_2', 'schools', $array);
                    $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                }
            }
            elseif ($fieldname == 'address_one')
            {
                if ($data['address_one'] == NULL && $data['address_two'] == NULL
                    && $data['address_three'] == NULL && $data['address_three'] == NULL)
                {
                    $address = $this->objLanguage->languageText('word_address', 'system', 'WORD: word_address, not found');
                    $array = array('fieldname' => $address);
                    $errorText = $this->objLanguage->code2Txt('mod_schools_error_1', 'schools', $array);
                    $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                }
            }
            elseif ($fieldname == 'contact_address_one')
            {
                if ($data['contact_address_one'] == NULL && $data['contact_address_two'] == NULL
                    && $data['contact_address_three'] == NULL && $data['contact_address_three'] == NULL)
                {
                    $address = $this->objLanguage->languageText('word_address', 'system', 'WORD: word_address, not found');
                    $array = array('fieldname' => $address);
                    $errorText = $this->objLanguage->code2Txt('mod_schools_error_1', 'schools', $array);
                    $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                }
            }
            elseif ($fieldname == 'province_id')
            {
                if ($value == NULL)
                {
                    $province = $this->objLanguage->languageText('mod_schools_province', 'schools', 'TEXT: mod_schools_province, not found');
                    $array = array('fieldname' => $province);
                    $errorText = $this->objLanguage->code2Txt('mod_schools_error_2', 'schools', $array);
                    $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                }
                else
                {
                    if ($data['district_id'] == NULL)
                    {
                        $district = $this->objLanguage->languageText('mod_schools_district', 'schools', 'TEXT: mod_schools_province, not found');
                        $array = array('fieldname' => $district);
                        $errorText = $this->objLanguage->code2Txt('mod_schools_error_2', 'schools', $array);
                        $errors['district_id'] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                    }
                }
            }
            elseif ($fieldname == 'password')
            {
                if ($value == NULL && $data['confirm_password'] == NULL)
                {
                    $array = array('fieldname' => $fieldname);
                    $errorText = $this->objLanguage->code2Txt('mod_schools_error_1', 'schools', $array);
                    $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                }
                if ($value != $data['confirm_password'])
                {
                    $errorText = $this->objLanguage->languageText('mod_schools_passwordsnotalike','schools', $array);
                    $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                }
            } 
            elseif ($fieldname == 'contact_telephone_number')
            {
                if ($data['contact_telephone_number'] == NULL && $data['contact_mobile_number'] == NULL)
                {
                    $errorText = $this->objLanguage->languageText('mod_schools_contactnumbers', 'schools', 'TEXT: mod_schools_contactnumbers, not found');
                    $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                }
            }
            elseif ($fieldname == 'contact_mobile_number')
            {
                if ($data['contact_telephone_number'] == NULL && $data['contact_mobile_number'] == NULL)
                {
                    $errorText = $this->objLanguage->languageText('mod_schools_contactnumbers', 'schools', 'TEXT: mod_schools_contactnumbers, not found');
                    $errors['contact_telephone_number'] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                }
            }
        }
        $schools = array();
        $schools['data'] = $data;
        $schools['errors'] = $errors;
        $this->setSession('schools', $schools);
        if (empty($errors))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }        
    }
    
    /**
     *
     * Method to save the details on adding.
     * 
     * @access public
     * @param array $data The array of data to save
     * @return void 
     */
    public function saveAddDetails($data)
    {
        $principalDetails = array();
        $principalDetails['username'] = $data['username'];
        $principalDetails['password'] = $data['password'];
        $principalDetails['title'] = $data['title'];
        $principalDetails['first_name'] = $data['first_name'];
        $principalDetails['last_name'] = $data['last_name'];
        $principalDetails['user_email_address'] = $data['user_email_address'];
        $principalDetails['gender'] = $data['gender'];
        $principalDetails['mobile_number'] = $data['mobile_number'];
        $principalId = $this->saveAddPrincipalDetails($principalDetails);
        
        $details = array();
        $details['school_name'] = $data['school_name'];
        $details['principal_id'] = $principalId;
        $details['district_id'] = $data['district_id'];
        $details['address'] = implode('|', array($data['address_one'], $data['address_two'], $data['address_three'], $data['address_four']));
        $details['email_address'] = $data['email_address'];
        $details['telephone_number'] = $data['telephone_number'];
        $details['fax_number'] = $data['fax_number'];
        $details['created_by'] = $this->objUser->PKId();
        $details['date_created'] = date('Y-m-d H:i:s');
        $detailId = $this->objDBdetail->addSchool($details);
        
        $contactDetails = array();
        $contactDetails['school_id'] = $detailId;
        $contactDetails['position'] = $data['contact_position'];
        $contactDetails['name'] = $data['contact_name'];
        $contactDetails['address'] = implode('|', array($data['contact_address_one'], $data['contact_address_two'], $data['contact_address_three'], $data['contact_address_four']));
        $contactDetails['email_address'] = $data['contact_email_address'];
        $contactDetails['telephone_number'] = $data['contact_telephone_number'];
        $contactDetails['mobile_number'] = $data['contact_mobile_number'];
        $contactDetails['fax_number'] = $data['contact_fax_number'];
        $contactDetails['created_by'] = $this->objUser->PKId();
        $contactDetails['date_created'] = date('Y-m-d H:i:s');
        $contactId = $this->objDBcontacts->addContact($contactDetails);
    }
    
    /**
     *
     * Method to save the principal details
     * 
     * @access public
     * @param array $details The array of principal details
     * @return string 
     */
    public function saveAddPrincipalDetails($details)
    {
        $userId = $this->objUserAdmin->generateUserId();
        $id = $this->objUserAdmin->addUser($userId, $details['username'], $details['password'], 
            $details['title'], $details['first_name'], $details['last_name'], $details['principal_email_address'],
            $details['gender'], 'ZA', $details['mobile_number'], '', 'user', $accountstatus='1');
        $user = $this->objUserAdmin->getUserDetails($id);
        $puid = $user['puid'];
        
        $groupId = $this->objGroups->getId('Principals');
        $this->objGroups->addGroupUser($groupId, $puid);
        
        return $id;
    }
    
    /**
     *
     * Method to show edit details template
     * 
     * @access public
     * @param string $id The id of the school to edit
     * @return string $string The edit template string
     */
    public function showEditForm()
    {
        $id = $this->getParam('id');
        $detail = $this->objDBdetail->getDetail($id);
        $district = $this->objDBdistricts->getDistrict($detail['district_id']);

        $addressArray = explode('|', $detail['address']);
        
        // get session data for previously entered data on erorrs.
        $schools = $this->getSession('schools');

        $idValue = !empty($schools) ? $schools['data']['id'] : $detail['id'];
        $provinceIdValue = !empty($schools) ? $schools['data']['province_id'] : $district['province_id'];
        $districtIdValue = !empty($schools) ? $schools['data']['district_id'] : $detail['district_id'];
        $schoolNameValue = !empty($schools) ? $schools['data']['school_name'] : $detail['school_name'];
        $addressOneValue = !empty($schools) ? $schools['data']['address_one'] : $addressArray[0];
        $addressTwoValue = !empty($schools) ? $schools['data']['address_two'] : $addressArray[1];
        $addressThreeValue = !empty($schools) ? $schools['data']['address_three'] : $addressArray[2];
        $addressFourValue = !empty($schools) ? $schools['data']['address_four'] : $addressArray[3];
        $emailAddressValue = !empty($schools) ? $schools['data']['email_address'] : $detail['email_address'];
        $telephoneNumberValue = !empty($schools) ? $schools['data']['telephone_number'] : $detail['telephone_number'];
        $faxNumberValue = !empty($schools) ? $schools['data']['fax_number'] : $detail['fax_number'];

        $provinceIdError = (!empty($schools) && array_key_exists('province_id', $schools['errors'])) ? $schools['errors']['province_id'] : NULL;
        $districtIdError = (!empty($schools) && array_key_exists('district_id', $schools['errors'])) ? $schools['errors']['district_id'] : NULL;
        $schoolNameError = (!empty($schools) && array_key_exists('school_name', $schools['errors'])) ? $schools['errors']['school_name'] : NULL;
        $addressOneError = (!empty($schools) && array_key_exists('address_one', $schools['errors'])) ? $schools['errors']['address_one'] : NULL;
        $emailAddressError = (!empty($schools) && array_key_exists('email_address', $schools['errors'])) ? $schools['errors']['email_address'] : NULL;
        $telephoneNumberError = (!empty($schools) && array_key_exists('telephone_number', $schools['errors'])) ? $schools['errors']['telephone_number'] : NULL;

        // get data from the database.
        $provinces = $this->objDBprovinces->getAll();        

        // set up text elements.
        $editSchool = $this->objLanguage->languageText('mod_schools_editschooldetail', 'schools', 'TEXT: mod_schools_editschooldetail, not found');
        $schoolName = $this->objLanguage->languageText('mod_schools_schoolname', 'schools', 'TEXT: mod_schools_schoolname, not found');
        $province = $this->objLanguage->languageText('mod_schools_province', 'schools', 'TEXT: mod_schools_province, not found');
        $selectProvince = $this->objLanguage->languageText('mod_schools_selectprovince', 'schools', 'TEXT:mod_schools_selectprovince, not found');
        $district = $this->objLanguage->languageText('mod_schools_district', 'schools', 'TEXT: mod_schools_district, not found');
        $selectDistrict = $this->objLanguage->languageText('mod_schools_selectdistrict', 'schools', 'TEXT:mod_schools_selectdistrict, not found');
        $address = $this->objLanguage->languageText('word_address', 'system', 'WORD: word_address, niot found');
        $emailAddress = $this->objLanguage->languageText('phrase_emailaddress', 'system', 'PHRASE: phrase_eamiladdress, not found');
        $telephoneNumber = $this->objLanguage->languageText('phrase_telephonenumber', 'system', 'PHRASE: phrase_telephonenumber, not found');
        $faxNumber = $this->objLanguage->languageText('phrase_faxnumber', 'system', 'PHRASE: phrase_faxnumber');
        $save = $this->objLanguage->languageText('word_save', 'system', 'WORD: word_save, not found');
        $cancel = $this->objLanguage->languageText('word_cancel', 'system', 'WORD: word_cancel, not found');
        $noDistricts = $this->objLanguage->languageText('mod_schools_nodistricts', 'schools', 'TEXT: mod_schools_nodistricts, not found');
        
        // set up htmlelements.
        $objDrop = new dropdown('province_id');
        $objDrop->addOption('', $selectProvince);
        $objDrop->addFromDB($provinces, 'province_name', 'id');
        $objDrop->setSelected($provinceIdValue);
        $provinceDrop = $objDrop->show();
        
        if (!empty($provinceIdValue))
        {
            $districts = $this->objDBdistricts->getDistricts($provinceIdValue);

            if (!empty($districts))
            {
                // Set up htmlelements.
                $objDrop = new dropdown('district_id');
                $objDrop->addOption('', $selectDistrict);
                $objDrop->addFromDB($districts, 'district_name', 'id');
                $objDrop->setSelected($districtIdValue);
                $districtDrop = $objDrop->show();

                $objTable = new htmltable();
                $objTable->cellpadding = '4';
                $objTable->startRow();
                $objTable->addCell($district. ': ', '200px', '', '', '', '');
                $objTable->addCell($districtIdError . $districtDrop, '', '', '', '', '');
                $objTable->endRow();
                $districtTable = $objTable->show();

                $string = $districtTable;
            }
            else
            {
                $districtTable = '<span class="error"><b>' . $noDistricts . '</b></span>';
            }
        }
        else
        {
            $districtTable = NULL;
        }
        
        $objInput = new textinput('school_name', $schoolNameValue, '', '50');
        $schoolNameInput = $objInput->show();
        
        $objInput = new textinput('address_one', $addressOneValue, '', '50');
        $addressOneInput = $objInput->show();

        $objInput = new textinput('address_two', $addressTwoValue, '', '50');
        $addressTwoInput = $objInput->show();
        
        $objInput = new textinput('address_three', $addressThreeValue, '', '50');
        $addressThreeInput = $objInput->show();
        
        $objInput = new textinput('address_four', $addressFourValue, '', '50');
        $addressFourInput = $objInput->show();

        $objInput = new textinput('email_address', $emailAddressValue, '', '50');
        $emailAddressInput = $objInput->show();
        
        $objInput = new textinput('telephone_number', $telephoneNumberValue, '', '50');
        $telephoneNumberInput = $objInput->show();
        
        $objInput = new textinput('fax_number', $faxNumberValue, '', '50');
        $faxNumberInput = $objInput->show();

        $objInput = new textinput('id', $idValue, 'hidden', '50');
        $idInput = $objInput->show();

        $objButton = new button('save', $save);
        $objButton->setToSubmit();
        $saveButton = $objButton->show();
        
        $objButton = new button('cancel', $cancel);
        $objButton->setToSubmit();
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($province . ': ', '200px', '', '', '', '');
        $objTable->addCell($provinceIdError . $provinceDrop, '', '', '', '', '');
        $objTable->endRow();
        $provinceTable = $objTable->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($schoolName . ': ', '200px', '', '', '', '');
        $objTable->addCell($schoolNameError . $schoolNameInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($address . ': ', '', '', '', '', '');
        $objTable->addCell($addressOneError . $addressOneInput, '', '', '', '', '');
        $objTable->endRow(); 
        $objTable->startRow();
        $objTable->addCell('', '', '', '', '', '');
        $objTable->addCell($addressTwoInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('', '', '', '', '', '');
        $objTable->addCell($addressThreeInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell('', '', '', '', '', '');
        $objTable->addCell($addressFourInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($emailAddress . ': ', '', '', '', '', '');
        $objTable->addCell($emailAddressError . $emailAddressInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($telephoneNumber . ': ', '', '', '', '', '');
        $objTable->addCell($telephoneNumberError . $telephoneNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($faxNumber . ': ', '', '', '', '', '');
        $objTable->addCell($faxNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($idInput . $saveButton . '&nbsp' . $cancelButton, '', '', '', '', 'colspan="2"');
        $objTable->endRow();
        $detailsTable = $objTable->show();
        
        $objLayer = new layer();
        $objLayer->id = 'province';
        $objLayer->str = $provinceTable;
        $provinceLayer = $objLayer->show();

        $objLayer = new layer();
        $objLayer->id = 'districts';
        $objLayer->str = $districtTable;
        $districtLayer = $objLayer->show();

        $objLayer = new layer();
        $objLayer->id = 'detail';
        $objLayer->str = $detailsTable;
        $detailsLayer = $objLayer->show();

        $objForm = new form('detail', $this->uri(array(
            'action' => 'validateEditDetails'
        )));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($provinceLayer);
        $objForm->addToForm($districtLayer);
        $objForm->addToForm($detailsLayer);
        $editForm = $objForm->show();

        $string = '<b>' . $editSchool . '</b><br />';
        $string .= $editForm;
        
        return $string;        
    }

    /**
     *
     * Method to validate the schools details data
     * 
     * @access public
     * @param array $data The data to validate
     * @return  
     */
    public function validateEditDetails($data)
    {
        $errors = array();
        foreach ($data as $fieldname => $value)
        {
            if ($fieldname != 'province_id' && $fieldname != 'district_id' && $fieldname != 'address_one'
                && $fieldname != 'address_two' && $fieldname != 'address_three' && $fieldname != 'address_four'
                && $fieldname != 'fax_number' && $fieldname != 'id')
            {
                if ($value == NULL)
                {
                    $name = explode('_', $fieldname);
                    $name = implode(' ', $name);
                    $array = array('fieldname' => $name);
                    $errorText = $this->objLanguage->code2Txt('mod_schools_error_1', 'schools', $array);
                    $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                }
                elseif ($fieldname == 'email_address')
                {
                    if (filter_var($value, FILTER_VALIDATE_EMAIL) == FALSE)
                    {
                        $errorText = $this->objLanguage->languageText('mod_schools_invalidemail', 'schools', 'TEXT: mod_schools_invalidemail, not found');
                        $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                    }
                }
            }
            elseif ($fieldname == 'address_one')
            {
                if ($data['address_one'] == NULL && $data['address_two'] == NULL
                    && $data['address_three'] == NULL && $data['address_three'] == NULL)
                {
                    $address = $this->objLanguage->languageText('word_address', 'system', 'WORD: word_address, not found');
                    $array = array('fieldname' => $address);
                    $errorText = $this->objLanguage->code2Txt('mod_schools_error_1', 'schools', $array);
                    $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                }
            }
            elseif ($fieldname == 'province_id')
            {
                if ($value == NULL)
                {
                    $province = $this->objLanguage->languageText('mod_schools_province', 'schools', 'TEXT: mod_schools_province, not found');
                    $array = array('fieldname' => $province);
                    $errorText = $this->objLanguage->code2Txt('mod_schools_error_2', 'schools', $array);
                    $errors[$fieldname] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                }
                else
                {
                    if ($data['district_id'] == NULL)
                    {
                        $district = $this->objLanguage->languageText('mod_schools_district', 'schools', 'TEXT: mod_schools_province, not found');
                        $array = array('fieldname' => $district);
                        $errorText = $this->objLanguage->code2Txt('mod_schools_error_2', 'schools', $array);
                        $errors['district_id'] = '<div><span class="error"><b>' . ucfirst(strtolower($errorText)) . '</b></span></div>';
                    }
                }
            }
        }

        $schools = array();
        $schools['data'] = $data;
        $schools['errors'] = $errors;
        $this->setSession('schools', $schools);
        if (empty($errors))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }        
    }

    /**
     *
     * Method to save the details on editing.
     * 
     * @access public
     * @param array $data The array of data to save
     * @return void 
     */
    public function saveEditDetails($data)
    {
        $details = array();
        $details['school_name'] = $data['school_name'];
        $details['district_id'] = $data['district_id'];
        $details['address'] = implode('|', array($data['address_one'], $data['address_two'], $data['address_three'], $data['address_four']));
        $details['email_address'] = $data['email_address'];
        $details['telephone_number'] = $data['telephone_number'];
        $details['fax_number'] = $data['fax_number'];
        $details['modified_by'] = $this->objUser->PKId();
        $details['date_modified'] = date('Y-m-d H:i:s');
        $detailId = $this->objDBdetail->updateSchool($data['id'], $details);
    }
}
?>