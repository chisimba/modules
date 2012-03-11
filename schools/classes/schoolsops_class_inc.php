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
            $this->objTab = $this->newObject('tabber', 'htmlelements');
            
            // Load db classes,
            $this->objDBprovinces = $this->getObject('dbschools_provinces', 'schools');
            $this->objDBdistricts = $this->getObject('dbschools_districts', 'schools');
            $this->objDBcontacts = $this->getObject('dbschools_contacts', 'schools');
            $this->objDBschools = $this->getObject('dbschools_schools', 'schools');
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }

    /**
     *
     * Method to generate an error string for display
     * 
     * @access private
     * @param string $errorText The error string
     * @return string $string The formated error string
     */
    private function error($errorText)
    {
        $error = $this->objLanguage->languageText('word_error', 'system', 'WORD: word_error, not found');
        
        $this->objIcon->title = $error;
        $this->objIcon->alt = $error;
        $this->objIcon->setIcon('exclamation', 'gif');
        $errorIcon = $this->objIcon->show();
        
        $string = '<span style="color: red">' . $errorIcon . '&nbsp;<b>' . $errorText . '</b></span>';
        return $string;
    }

    /**
     * Method to generate the html for the find schools template
     *
     * @access public
     * @return string $string The html string to be sent to the template
     */
    public function findSchool()
    {
        $this->appendArrayVar('headerParams',
            $this->getJavaScriptFile('plugins/ui/js/jquery-ui-1.8.7.custom.min.js',
            'jquery'));
        $cssUri = $this->getResourceUri('plugins/ui/css/ui-lightness/jquery-ui-1.8.7.custom.css',
            'jquery');
        $this->appendArrayVar('headerParams', 
            "<link href='$cssUri' rel='stylesheet' type='text/css'/>");

        $data = $this->objDBschools->autocompleteDetails();
 
        // pass array to javascript.
        if ($data)
        {
            $this->objSvars->arrayFromPhpToJs('schools', $data, TRUE);
        }
        
        // set up language elements.
        $selectLabel = $this->objLanguage->languageText('word_select', 'system', 'WORD: word_select, not found');
        $schoolLabel = $this->objLanguage->languageText('mod_schools_school', 'schools', 'TEXT: mod_schools_school, not found');
        $schoolNameLabel = $this->objLanguage->languageText('mod_schools_schoolname', 'schools', 'TEXT: mod_schools_schoolname, not found');
        $addSchoolLabel = $this->objLanguage->languageText('mod_schools_addschool', 'schools', 'TEXT: mod_schools_addchool, not found');
        $noSchoolsLabel = $this->objLanguage->languageText('mod_schools_noschools', 'schools', 'TEXT: mod_schools_noschools, not found');
        $addLabel = $this->objLanguage->languageText('word_add', 'system', 'WORD: word_add, not found');
        
        $string = '';
        
        if ($data)
        {
            // set up htmlelements.
            $objInput = new textinput('schools', '', '', '50');
            $schoolInput = $objInput->show();

            $objInput = new textinput('sid', '', 'hidden', '50');
            $schoolInputId = $objInput->show();

            $objButton = new button('select', $selectLabel);
            $objButton->setToSubmit();
            $selectButton = $objButton->show();

            $objTable = new htmltable();
            $objTable->cellpadding = '4';
            $objTable->startRow();
            $objTable->addCell($schoolNameLabel, '200px', '', '', '');
            $objTable->addCell($schoolInput . $schoolInputId, '', '', '', '');
            $objTable->endRow();
            $objTable->startRow();
            $objTable->addCell($selectButton, '', '', '', '');
            $objTable->endRow();
            $schoolTable = $objTable->show();

            $objForm = new form('detail', $this->uri(array(
                'action' => 'showschool'
            )));
            $objForm->extra = ' enctype="multipart/form-data"';
            $objForm->addToForm($schoolTable);
            $addForm = $objForm->show();

            $this->objIcon->title = $addLabel;
            $this->objIcon->alt = $addLabel;
            $this->objIcon->setIcon('add', 'png');
            $addIcon = $this->objIcon->show();

            $objLink = new link($this->uri(array('action' => 'addeditschool', 'mode' => 'add')));
            $objLink->link = $addIcon . '&nbsp;' . $addSchoolLabel;
            $addLink = $objLink->show();

            $objFieldset = new fieldset();
            $objFieldset->legend = '<b>' . $schoolLabel . '</b>';
            $objFieldset->contents = $addForm;
            $schoolFieldset = $objFieldset->show();
            
            $string .= $schoolFieldset . '<br />' . $addLink;
        }
        else
        {
            $error = $this->error($noSchoolsLabel);
            
            $this->objIcon->title = $addLabel;
            $this->objIcon->alt = $addLabel;
            $this->objIcon->setIcon('add', 'png');
            $addIcon = $this->objIcon->show();

            $objLink = new link($this->uri(array('action' => 'addeditschool', 'mode' => 'add')));
            $objLink->link = $addIcon . '&nbsp;' . $addSchoolLabel;
            $addLink = $objLink->show();
            
            $string .= $error . '<br />' . $addLink;            
        }
        return $string;
    }
    
    /**
     * Method to generate the html for the add and edit school form template
     *
     * @access public
     * @param strint $mode The mode of the action form request
     * @return string $string The html string to be sent to the template
     */
    public function addEditSchool($mode)
    {
        if ($mode == 'add')
        {
            $provinceDropValue = NULL;
            $districtDropValue = NULL;
            $nameInputValue = NULL;
            $addressOneInputValue = NULL;
            $addressTwoInputValue = NULL;
            $addressThreeInputValue = NULL;
            $addressFourInputValue = NULL;
            $emailAddressInputValue = NULL;
            $telephoneNumberInputValue = NULL;
            $faxNumberInputValue = NULL;

            $idInput = NULL;
        }
        else
        {
            $sid = $this->getParam('sid');
            $schoolArray = $this->objDBschools->getSchool($sid);
            $districtArray = $this->objDBdistricts->getDistrict($schoolArray['district_id']);

            $idInputValue = $sid;
            $provinceDropValue = $districtArray['province_id'];
            $districtDropValue = $districtArray['id'];
            $nameInputValue = $schoolArray['name'];
            $addressArray = explode('|', $schoolArray['address']);
            $addressOneInputValue = $addressArray[0];
            $addressTwoInputValue = $addressArray[1];
            $addressThreeInputValue = $addressArray[2];
            $addressFourInputValue = $addressArray[3];
            $emailAddressInputValue = $schoolArray['email_address'];
            $telephoneNumberInputValue = $schoolArray['telephone_number'];
            $faxNumberInputValue = $schoolArray['fax_number'];

            $objInput = new textinput('sid', $idInputValue, 'hidden', '50');
            $idInput = $objInput->show();
        }
        
        $errorArray = $this->getSession('schools');
        
        $provinceDropValue = !empty($errorArray) ? $errorArray['data']['province_id'] : $provinceDropValue;
        $districtDropValue = !empty($errorArray) ? $errorArray['data']['district_id'] : $districtDropValue;
        $nameInputValue = !empty($errorArray) ? $errorArray['data']['name'] : $nameInputValue;
        $addressOneInputValue = !empty($errorArray) ? $errorArray['data']['address_one'] : $addressOneInputValue;
        $addressTwoInputValue = !empty($errorArray) ? $errorArray['data']['address_two'] : $addressTwoInputValue;
        $addressThreeInputValue = !empty($errorArray) ? $errorArray['data']['address_three'] : $addressThreeInputValue;
        $addressFourInputValue = !empty($errorArray) ? $errorArray['data']['address_four'] : $addressFourInputValue;
        $emailAddressInputValue = !empty($errorArray) ? $errorArray['data']['email_address'] : $emailAddressInputValue;
        $telephoneNumberInputValue = !empty($errorArray) ? $errorArray['data']['telephone_number'] : $telephoneNumberInputValue;
        $faxNumberInputValue = !empty($errorArray) ? $errorArray['data']['fax_number'] : $faxNumberInputValue;

        $provinceDropError = (!empty($errorArray) && array_key_exists('province_id', $errorArray['errors'])) ? $errorArray['errors']['province_id'] : NULL;
        $districtDropError = (!empty($errorArray) && array_key_exists('district_id', $errorArray['errors'])) ? $errorArray['errors']['district_id'] : NULL;
        $nameInputError = (!empty($errorArray) && array_key_exists('name', $errorArray['errors'])) ? $errorArray['errors']['name'] : NULL;
        $addressOneInputError = (!empty($errorArray) && array_key_exists('address_one', $errorArray['errors'])) ? $errorArray['errors']['address_one'] : NULL;
        $emailAddressInputError = (!empty($errorArray) && array_key_exists('email_address', $errorArray['errors'])) ? $errorArray['errors']['email_address'] : NULL;
        $telephoneNumberInputError = (!empty($errorArray) && array_key_exists('telephone_number', $errorArray['errors'])) ? $errorArray['errors']['telephone_number'] : NULL;
        
        $saveLabel = $this->objLanguage->languageText('word_save', 'system', 'WORD: word_save, not found');
        $cancelLabel = $this->objLanguage->languageText('word_cancel', 'system', 'WORD: word_cancel, not found');
        $provinceLabel = $this->objLanguage->languageText('mod_schools_province', 'schools', 'TEXT: mod_schools_province, not found');
        $selectProvinceLabel = $this->objLanguage->languageText('mod_schools_selectprovince', 'schools', 'TEXT:mod_schools_selectprovince, not found');
        $districtLabel = $this->objLanguage->languageText('mod_schools_district', 'schools', 'TEXT: mod_schools_district, not found');
        $selectDistrictLabel = $this->objLanguage->languageText('mod_schools_selectdistrict', 'schools', 'TEXT:mod_schools_selectdistrict, not found');
        $schoolNameLabel = $this->objLanguage->languageText('mod_schools_schoolname', 'schools', 'TEXT: mod_schools_schoolname, not found');
        $addressLabel = $this->objLanguage->languageText('word_address', 'system', 'WORD: word_address, not found');
        $emailAddressLabel = $this->objLanguage->languageText('phrase_emailaddress', 'system', 'phrase_emailaddress, not found');
        $telephoneNumberLabel = $this->objLanguage->languageText('phrase_telephonenumber', 'system', 'phrase_telephonenumber, not found');
        $faxNumberLabel = $this->objLanguage->languageText('phrase_faxnumber', 'system', 'phrase_faxnumber, not found');
        $noDistrictsLabel = $this->objLanguage->languageText('mod_schools_nodistricts', 'schools', 'TEXT: mod_schools_nodistricts, not found');

        $provincesArray = $this->objDBprovinces->getAll();
        
        // set up htmlelements.
        $objDrop = new dropdown('province_id');
        $objDrop->addOption('', $selectProvinceLabel);
        $objDrop->addFromDB($provincesArray, 'name', 'id');
        $objDrop->setSelected($provinceDropValue);
        $provinceDrop = $objDrop->show();
        
        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($provinceLabel . ': ', '200px', '', '', '', '');
        $objTable->addCell($provinceDropError . $provinceDrop, '', '', '', '', '');
        $objTable->endRow();
        $provinceTable = $objTable->show();

        if (!empty($provinceDropValue))
        {
            $districtArray = $this->objDBdistricts->getDistrictsForProvince($provinceDropValue);

            if (!empty($districtArray))
            {
                // Set up htmlelements.
                $objDrop = new dropdown('district_id');
                $objDrop->addOption('', $selectDistrictLabel);
                $objDrop->addFromDB($districtArray, 'name', 'id');
                $objDrop->setSelected($districtDropValue);
                $districtDrop = $objDrop->show();

                $objTable = new htmltable();
                $objTable->cellpadding = '4';
                $objTable->startRow();
                $objTable->addCell($districtLabel . ': ', '200px', '', '', '', '');
                $objTable->addCell($districtDropError . $districtDrop, '', '', '', '', '');
                $objTable->endRow();
                $districtTable = $objTable->show();

                $string = $districtTable;
            }
            else
            {
                $error = $this->error($noDistrictsLabel);

                $objTable = new htmltable();
                $objTable->cellpadding = '4';
                $objTable->startRow();
                $objTable->addCell($districtLabel . ': ', '200px', '', '', '', '');
                $objTable->addCell($error, '', '', '', '', '');
                $objTable->endRow();
                $districtTable = $objTable->show();            
            }
        }
        else
        {
            $districtTable = NULL;
        }

        $objLayer = new layer();
        $objLayer->id = 'province';
        $objLayer->str = $provinceTable;
        $provinceLayer = $objLayer->show();

        $objLayer = new layer();
        $objLayer->id = 'district';
        $objLayer->str = $districtTable;
        $districtLayer = $objLayer->show();

        $objInput = new textinput('name', $nameInputValue, '', '50');
        $nameInput = $objInput->show();
        
        $objInput = new textinput('address_one', $addressOneInputValue, '', '50');
        $addressOneInput = $objInput->show();

        $objInput = new textinput('address_two', $addressTwoInputValue, '', '50');
        $addressTwoInput = $objInput->show();
        
        $objInput = new textinput('address_three', $addressThreeInputValue, '', '50');
        $addressThreeInput = $objInput->show();
        
        $objInput = new textinput('address_four', $addressFourInputValue, '', '50');
        $addressFourInput = $objInput->show();

        $objInput = new textinput('email_address', $emailAddressInputValue, '', '50');
        $emailAddressInput = $objInput->show();
        
        $objInput = new textinput('telephone_number', $telephoneNumberInputValue, '', '50');
        $telephoneNumberInput = $objInput->show();
        
        $objInput = new textinput('fax_number', $faxNumberInputValue, '', '50');
        $faxNumberInput = $objInput->show();

        $objButton = new button('save', $saveLabel);
        $objButton->setToSubmit();
        $saveButton = $objButton->show();

        $objButton = new button('cancel', $cancelLabel);
        $objButton->setToSubmit();
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($schoolNameLabel . ': ', '200px', '', '', '', '');
        $objTable->addCell($nameInputError . $nameInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($addressLabel . ': ', '', '', '', '', '');
        $objTable->addCell($addressOneInputError . $addressOneInput, '', '', '', '', '');
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
        $objTable->addCell($emailAddressLabel . ': ', '', '', '', '', '');
        $objTable->addCell($emailAddressInputError . $emailAddressInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($telephoneNumberLabel . ': ', '', '', '', '', '');
        $objTable->addCell($telephoneNumberInputError . $telephoneNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($faxNumberLabel . ': ', '', '', '', '', '');
        $objTable->addCell($faxNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($idInput . $saveButton . '&nbsp;' . $cancelButton, '', '', '', '', 'colspan="2"');
        $objTable->endRow();
        $schoolTable = $objTable->show();
        
        $objLayer = new layer();
        $objLayer->id = 'school';
        $objLayer->str = $schoolTable;
        $schoolLayer = $objLayer->show();

        $objForm = new form('school', $this->uri(array(
            'action' => 'validateschool',
            'mode' => $mode,
        )));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($provinceLayer);
        $objForm->addToForm($districtLayer);
        $objForm->addToForm($schoolLayer);
        $saveForm = $objForm->show();
        
        $string = $saveForm;
        
        return $string;
    }

    /**
     * Method to return the html for an ajax call for districts for a province
     * 
     * @access public
     * @return VOID
     */
    public function ajaxGetDistricts()
    {
        // Set up text elements.
        $noDistrictsLabel = $this->objLanguage->languageText('mod_schools_nodistricts', 'schools', 'TEXT: mod_schools_nodistricts, not found');
        $districtLabel = $this->objLanguage->languageText('mod_schools_district', 'schools', 'TEXT: mod_schools_district, not found');
        $selectDistrictLabel = $this->objLanguage->languageText('mod_schools_selectdistrict', 'schools', 'TEXT: mod_schools_selectdistrct, not found');
        
        // Get parameter.
        $pid = $this->getParam('pid', FALSE);
        
        // Get data
        $districtArray = $this->objDBdistricts->getDistrictsForProvince($pid);

        if (!empty($districtArray))
        {
            // Set up htmlelements.
            $objDrop = new dropdown('district_id');
            $objDrop->addOption('', $selectDistrictLabel);
            $objDrop->addFromDB($districtArray, 'name', 'id');
            $districtDrop = $objDrop->show();
            
            $objTable = new htmltable();
            $objTable->cellpadding = '4';
            $objTable->startRow();
            $objTable->addCell($districtLabel . ': ', '200px', '', '', '', '');
            $objTable->addCell($districtDrop, '', '', '', '', '');
            $objTable->endRow();
            $districtTable = $objTable->show();
            
            $string = $districtTable;
        }
        else
        {
            $error = $this->error($noDistrictsLabel);

            $objTable = new htmltable();
            $objTable->cellpadding = '4';
            $objTable->startRow();
            $objTable->addCell($districtLabel . ': ', '200px', '', '', '', '');
            $objTable->addCell($error, '', '', '', '', '');
            $objTable->endRow();
            $districtTable = $objTable->show();
            
            $string = $districtTable;
        }
        
        echo $string;
        die();
    }

    /**
     *
     * Method to validate the input of the add school form 
     * 
     * @access public
     * @param array $data The data to validate
     * @return boolean TRUE on validation succes | FALSE on failure
     */
    public function validateSchool($data)
    {
        $errors = array();
        foreach ($data as $fieldname => $value)
        {
            if ($fieldname != 'sid' && $fieldname != 'province_id' && $fieldname != 'district_id'
                && $fieldname != 'address_one' && $fieldname != 'address_two' && $fieldname != 'address_three'
                && $fieldname != 'address_four' && $fieldname != 'fax_number')
            {
                if ($value == NULL)
                {
                    $name = explode('_', $fieldname);
                    $name = implode(' ', $name);
                    $array = array('fieldname' => $name);
                    $errorText = $this->objLanguage->code2Txt('mod_schools_error_1', 'schools', $array);
                    $errors[$fieldname] = '<div>' . $this->error(ucfirst(strtolower($errorText))) . '</div>';
                }
                elseif ($fieldname == 'email_address')
                {
                    if (filter_var($value, FILTER_VALIDATE_EMAIL) == FALSE)
                    {
                        $errorText = $this->objLanguage->languageText('mod_schools_invalidemail', 'schools', 'TEXT: mod_schools_invalidemail, not found');
                        $errors[$fieldname] = '<div>' . $this->error(ucfirst(strtolower($errorText))) . '</div>';
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
                    $errors[$fieldname] = '<div>' . $this->error(ucfirst(strtolower($errorText))) . '</div>';
                }
            }
            elseif ($fieldname == 'province_id')
            {
                if ($value == NULL)
                {
                    $province = $this->objLanguage->languageText('mod_schools_province', 'schools', 'TEXT: mod_schools_province, not found');
                    $array = array('fieldname' => $province);
                    $errorText = $this->objLanguage->code2Txt('mod_schools_error_2', 'schools', $array);
                    $errors[$fieldname] = '<div>' . $this->error(ucfirst(strtolower($errorText))) . '</div>';
                }
                else
                {
                    if ($data['district_id'] == NULL)
                    {
                        $district = $this->objLanguage->languageText('mod_schools_district', 'schools', 'TEXT: mod_schools_province, not found');
                        $array = array('fieldname' => $district);
                        $errorText = $this->objLanguage->code2Txt('mod_schools_error_2', 'schools', $array);
                        $errors['district_id'] = '<div>' . $this->error(ucfirst(strtolower($errorText))) . '</div>';
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
     * Method tho save the school data on adding a school
     * 
     * @access public
     * @param array $data The data array to be saved
     * @return string $sid The id of the school 
     */
    public function insertSchool($data)
    {
        $data['address'] = implode('|', array($data['address_one'], $data['address_two'], $data['address_three'], $data['address_four']));
        $data['created_by'] = $this->objUser->PKId();
        $data['date_created'] = date('Y-m-d H:i:s');
        unset($data['sid']);
        unset($data['address_one']);
        unset($data['address_two']);
        unset($data['address_three']);
        unset($data['address_four']);
        unset($data['province_id']);

        $sid = $this->objDBschools->insertSchool($data);
        
        return $sid;
    }

    /**
     *
     * Method tho save the school data on editing a school
     * 
     * @access public
     * @param array $data The data array to be saved
     * @return string $sid The id of the school 
     */
    public function updateSchool($data)
    {
        $sid = $data['sid'];
        $data['address'] = implode('|', array($data['address_one'], $data['address_two'], $data['address_three'], $data['address_four']));
        $data['modified_by'] = $this->objUser->PKId();
        $data['date_modified'] = date('Y-m-d H:i:s');
        unset($data['sid']);
        unset($data['address_one']);
        unset($data['address_two']);
        unset($data['address_three']);
        unset($data['address_four']);
        unset($data['province_id']);

        $sid = $this->objDBschools->updateSchool($sid, $data);
        
        return $sid;
    }

    /**
     * Method to generate the html for the show schools template
     *
     * @access public
     * @return string $string The html string to be sent to the template
     */
    public function showSchool()
    {
        $schoolLabel = $this->objLanguage->languageText('mod_schools_school', 'schools', 'TEXT: mod_schools_school, not found');               
        $principalLabel = $this->objLanguage->languageText('mod_schools_principal', 'schools', 'TEXT: mod_schools_principal, not found');               
        $contactsLabel = $this->objLanguage->languageText('mod_schools_contacts', 'schools', 'TEXT: mod_schools_contacts, not found');               
        $schoolNameLabel = $this->objLanguage->languageText('mod_schools_schoolname', 'schools', 'TEXT: mod_schools_schoolname, not found');
        $provinceLabel = $this->objLanguage->languageText('mod_schools_province', 'schools', 'TEXT: mod_schools_province, not found');
        $districtLabel = $this->objLanguage->languageText('mod_schools_district', 'schools', 'TEXT: mod_schools_district, not found');
        $addressLabel = $this->objLanguage->languageText('word_address', 'system', 'WORD: word_address, niot found');
        $emailAddressLabel = $this->objLanguage->languageText('phrase_emailaddress', 'system', 'PHRASE: phrase_eamiladdress, not found');
        $telephoneNumberLabel = $this->objLanguage->languageText('phrase_telephonenumber', 'system', 'PHRASE: phrase_telephonenumber, not found');
        $faxNumberLabel = $this->objLanguage->languageText('phrase_faxnumber', 'system', 'PHRASE: phrase_faxnumber');
        $editSchoolLabel = $this->objLanguage->languageText('mod_schools_editschool', 'schools', 'TEXT: mod_schools_editschool, not found');
        $deleteSchoolLabel = $this->objLanguage->languageText('mod_schools_deleteschool', 'schools', 'TEXT: mod_schools_deleteschool, not found');
        $noPrincipalLabel = $this->objLanguage->languageText('mod_schools_noprincipal', 'schools', 'TEXT: mod_schools_noprincipal, not found');
        $addPrincipalLabel = $this->objLanguage->languageText('mod_schools_addprincipal', 'schools', 'TEXT: mod_schools_addprincipal, not found');
        $titleLabel = $this->objLanguage->languageText('word_title', 'system', 'WORD: word_title, not found');
        $firstNameLabel = $this->objLanguage->languageText('phrase_firstname', 'system', 'PHRASE: phrase_firstname, not found');
        $lastNameLabel = $this->objLanguage->languageText('phrase_lastname', 'system', 'PHRASE: phrase_lastname, not found');
        $genderLabel = $this->objLanguage->languageText('word_gender', 'system', 'WORD: word_gender, not found');
        $maleLabel = $this->objLanguage->languageText('word_male', 'system', 'WORD: word_male, not found');
        $femaleLabel = $this->objLanguage->languageText('word_female', 'system', 'WORD: word_female, not found');
        $mobileNumberLabel = $this->objLanguage->languageText('phrase_mobilenumber', 'system', 'PHRASE: phrase_mobilenumber, not found');
        $fullNameLabel = $this->objLanguage->languageText('mod_schools_fullname', 'schools', 'TEXT: mod_schools_fullname, not found');
        $noContactsLabel = $this->objLanguage->languageText('mod_schools_nocontacts', 'schools', 'TEXT: mod_schools_nocontacts, not found');
        $addContactLabel = $this->objLanguage->languageText('mod_schools_addcontact', 'schools', 'TEXT: mod_schools_addcontact, not found');
        $addLabel = $this->objLanguage->languageText('word_add', 'system', 'WORD: word_add, not found');
        $editLabel = $this->objLanguage->languageText('word_edit', 'system', 'WORD: word_edit, not found');
        $deleteLabel = $this->objLanguage->languageText('word_delete', 'system', 'WORD: word_delete, not found');
        $editContactLabel = $this->objLanguage->languageText('mod_schools_editcontact', 'schools', 'TEXT: mod_schools_editcontact, not found');
        $deleteContactLabel = $this->objLanguage->languageText('mod_schools_deletecontact', 'schools', 'TEXT: mod_schools_deletecontact, not found');
        $deleteConfirmLabel = $this->objLanguage->languageText('mod_schools_deleteconfirm', 'schools', 'TEXT: mod_schools_deleteconfirm, not found');

        $sid = $this->getParam('sid');
        
        $schoolArray = $this->objDBschools->getSchool($sid);
        $array = explode('|', $schoolArray['address']);
        $addressArray = array();
        foreach($array as $line)
        {
            if (!empty($line))
            {
                $addressArray[] = $line;
            }
        }
        $addressString = implode(',<br />', $addressArray);
        $districtArray = $this->objDBdistricts->getDistrict($schoolArray['district_id']);
        $provinceArray = $this->objDBprovinces->getProvince($districtArray['province_id']);
        $principalArray = $this->objUserAdmin->getUserDetails($schoolArray['principal_id']);
        $contactArray = $this->objDBcontacts->getContacts($sid);        
        
        $this->objIcon->setIcon('delete', 'png');
        $this->objIcon->title = $deleteLabel;
        $this->objIcon->alt = $deleteLabel;
        $icon = $this->objIcon->show() . '&nbsp;' . $deleteSchoolLabel;

        $location = $this->uri(array('action' => 'deleteschool', 'sid' => $sid));

        $this->objConfirm->setConfirm($icon, $location, $deleteConfirmLabel);
        $deleteSchoolIcon = $this->objConfirm->show();

        $this->objIcon->title = $editLabel;
        $this->objIcon->alt = $editLabel;
        $this->objIcon->setIcon('edit', 'png');
        $editIcon = $this->objIcon->show();

        $objLink = new link($this->uri(array('action' => 'addeditschool', 'sid' => $sid, 'mode' => 'edit')));
        $objLink->link = $editIcon . '&nbsp;' . $editSchoolLabel;
        $editSchoolLink = $objLink->show();
            
        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($provinceLabel . ': ', '200px', '', '', '', '');
        $objTable->addCell($provinceArray['name'], '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($districtLabel . ': ', '200px', '', '', '', '');
        $objTable->addCell($districtArray['name'], '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($schoolNameLabel . ': ', '200px', '', '', '', '');
        $objTable->addCell($schoolArray['name'], '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($addressLabel . ': ', '', '', '', '', '');
        $objTable->addCell($addressString, '', '', '', '', '');
        $objTable->endRow(); 
        $objTable->startRow();
        $objTable->addCell($emailAddressLabel . ': ', '', '', '', '', '');
        $objTable->addCell($schoolArray['email_address'], '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($telephoneNumberLabel . ': ', '', '', '', '', '');
        $objTable->addCell($schoolArray['telephone_number'], '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($faxNumberLabel . ': ', '', '', '', '', '');
        $objTable->addCell($schoolArray['fax_number'], '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($editSchoolLink . '&nbsp;&nbsp;' . $deleteSchoolIcon, '', '', '', '', 'colspan="2"');
        $objTable->endRow();
        $schoolTable = $objTable->show();

        $schoolTab = array(
            'name' => $schoolLabel,
            'content' => $schoolTable,
        );

        if (!empty($principalArray))
        {
            $sex = $principalArray['sex'] == 'M' ? $maleLabel : $femaleLabel;

            $objTable = new htmltable();
            $objTable->cellpadding = '4';
            $objTable->startRow();
            $objTable->addCell($titleLabel . ': ', '200px', '', '', '', '');
            $objTable->addCell($principalArray['title'], '', '', '', '', '');
            $objTable->endRow();
            $objTable->startRow();
            $objTable->addCell($firstNameLabel . ': ', '', '', '', '', '');
            $objTable->addCell($principalArray['firstname'], '', '', '', '', '');
            $objTable->endRow();
            $objTable->startRow();
            $objTable->addCell($lastNameLabel . ': ', '', '', '', '', '');
            $objTable->addCell($principalArray['surname'], '', '', '', '', '');
            $objTable->endRow();
            $objTable->startRow();
            $objTable->addCell($genderLabel . ': ', '', '', '', '', '');
            $objTable->addCell($sex, '', '', '', '', '');
            $objTable->endRow();
            $objTable->startRow();
            $objTable->addCell($emailAddressLabel . ': ', '', '', '', '', '');
            $objTable->addCell($principalArray['emailaddress'], '', '', '', '', '');
            $objTable->endRow();
            $objTable->startRow();
            $objTable->addCell($mobileNumberLabel . ': ', '', '', '', '', '');
            $objTable->addCell($principalArray['cellnumber'], '', '', '', '', '');
            $objTable->endRow();
            $principalTable = $objTable->show();
            
            $principalString = $principalTable;
        }
        else
        {
            $principalString = $this->error($noPrincipalLabel);

            $this->objIcon->title = $addLabel;
            $this->objIcon->alt = $addLabel;
            $this->objIcon->setIcon('user_plus', 'png');
            $addIcon = $this->objIcon->show();

            $objLink = new link($this->uri(array('action' => 'manage', 'type' => 'c', 'sid' => $sid, 'mode' => 'add')));
            $objLink->link = $addIcon . '&nbsp;' . $addPrincipalLabel;
            $addLink = $objLink->show();

            $principalString .= '<br />' . $addLink;       
        }
        
        $principalTab = array(
            'name' => $principalLabel,
            'content' => $principalString,
        );
        
        $contactString = '';
        if (!empty($contactArray))
        {
            foreach ($contactArray as $key => $contact)
            {
                $array = explode('|', $contact['address']);
                $addressArray = array();
                foreach($array as $line)
                {
                    if (!empty($line))
                    {
                        $addressArray[] = $line;
                    }
                }
                $addressString = implode(',<br />', $addressArray);

                $this->objIcon->setIcon('user_minus', 'png');
                $this->objIcon->title = $deleteLabel;
                $this->objIcon->alt = $deleteLabel;
                $icon = $this->objIcon->show() . '&nbsp;' . $deleteContactLabel;

                $location = $this->uri(array('action' => 'deletecontact', 'cid' => $contact['id'], 'sid' => $sid));

                $this->objConfirm->setConfirm($icon, $location, $deleteConfirmLabel);
                $deleteContactIcon = $this->objConfirm->show();

                $this->objIcon->title = $editLabel;
                $this->objIcon->alt = $editLabel;
                $this->objIcon->setIcon('user_pencil', 'png');
                $editIcon = $this->objIcon->show();

                $objLink = new link($this->uri(array('action' => 'contacts', 'cid' => $contact['id'], 'sid' => $sid, 'mode' => 'edit')));
                $objLink->link = $editIcon . '&nbsp;' . $editContactLabel;
                $editContactLink = $objLink->show();
            
                $objTable = new htmltable();
                $objTable->cellpadding = '4';
                $objTable->startRow();
                $objTable->addCell($fullNameLabel . ': ', '200px', '', '', '', '');
                $objTable->addCell($contact['name'], '', '', '', '', '');
                $objTable->endRow();
                $objTable->startRow();
                $objTable->addCell($addressLabel . ': ', '', '', '', '', '');
                $objTable->addCell($addressString, '', '', '', '', '');
                $objTable->endRow();
                $objTable->startRow();
                $objTable->addCell($emailAddressLabel . ': ', '', '', '', '', '');
                $objTable->addCell($contact['email_address'], '', '', '', '', '');
                $objTable->endRow();
                $objTable->startRow();
                $objTable->addCell($telephoneNumberLabel . ': ', '', '', '', '', '');
                $objTable->addCell($contact['telephone_number'], '', '', '', '', '');
                $objTable->endRow();
                $objTable->startRow();
                $objTable->addCell($mobileNumberLabel . ': ', '', '', '', '', '');
                $objTable->addCell($contact['mobile_number'], '', '', '', '', '');
                $objTable->endRow();
                $objTable->startRow();
                $objTable->addCell($faxNumberLabel . ': ', '', '', '', '', '');
                $objTable->addCell($contact['fax_number'], '', '', '', '', '');
                $objTable->endRow();
                $contactTable = $objTable->show();

                $objFieldset = new fieldset();
                $objFieldset->legend = '<b>' . $contact['position'] . '</b>';
                $objFieldset->contents = $contactTable . '<br />' . $editContactLink . '&nbsp;&nbsp;' . $deleteContactIcon;
                $contactFieldset = $objFieldset->show();
        
                $contactString .= $contactFieldset . '<br />';
            }

            $this->objIcon->title = $addLabel;
            $this->objIcon->alt = $addLabel;
            $this->objIcon->setIcon('user_plus', 'png');
            $addIcon = $this->objIcon->show();

            $objLink = new link($this->uri(array('action' => 'contacts', 'mode' => 'add', 'sid' => $sid)));
            $objLink->link = $addIcon . '&nbsp;' . $addContactLabel;
            $addLink = $objLink->show();
            
            $contactString .= $addLink;
        }
        else
        {
            $contactString = $this->error($noContactsLabel);

            $this->objIcon->title = $addLabel;
            $this->objIcon->alt = $addLabel;
            $this->objIcon->setIcon('user_plus', 'png');
            $addIcon = $this->objIcon->show();

            $objLink = new link($this->uri(array('action' => 'contacts', 'mode' => 'add', 'sid' => $sid)));
            $objLink->link = $addIcon . '&nbsp;' . $addContactLabel;
            $addLink = $objLink->show();

            $contactString .= '<br />' . $addLink;       
        }

        $contactsTab = array(
            'name' => $contactsLabel,
            'content' => $contactString,
        );

        $this->objTab->init();
        $this->objTab->tabId = 'schools_tab';
        $this->objTab->setSelected = $this->getParam('tab', 0);
        $this->objTab->addTab($schoolTab);
        $this->objTab->addTab($principalTab);
        $this->objTab->addTab($contactsTab);
        
        return $this->objTab->show();
    }
    
    /**
     *
     * Method to delete a school record
     * 
     * @access public
     * @return boolean TRUE on success | FALSE on failure 
     */
    public function deleteSchool()
    {
        $sid = $this->getParam('sid');
        $this->objDBschools->deleteSchool($sid);
        $this->objDBcontacts->deleteSchoolContacts($sid);
        
        return TRUE;
    }

    /**
     * Method to generate the html for the add school form template
     *
     * @access public
     * @return string $string The html string to be sent to the template
     */
    public function editSchool()
    {
        $errorArray = $this->getSession('schools');
        
        $provinceDropValue = !empty($errorArray) ? $errorArray['data']['province_id'] : NULL;
        $districtDropValue = !empty($errorArray) ? $errorArray['data']['district_id'] : NULL;
        $nameInputValue = !empty($errorArray) ? $errorArray['data']['name'] : NULL;
        $addressOneInputValue = !empty($errorArray) ? $errorArray['data']['address_one'] : NULL;
        $addressTwoInputValue = !empty($errorArray) ? $errorArray['data']['address_two'] : NULL;
        $addressThreeInputValue = !empty($errorArray) ? $errorArray['data']['address_three'] : NULL;
        $addressFourInputValue = !empty($errorArray) ? $errorArray['data']['address_four'] : NULL;
        $emailAddressInputValue = !empty($errorArray) ? $errorArray['data']['email_address'] : NULL;
        $telephoneNumberInputValue = !empty($errorArray) ? $errorArray['data']['telephone_number'] : NULL;
        $faxNumberInputValue = !empty($errorArray) ? $errorArray['data']['fax_number'] : NULL;

        $provinceDropError = (!empty($errorArray) && array_key_exists('province_id', $errorArray['errors'])) ? $errorArray['errors']['province_id'] : NULL;
        $districtDropError = (!empty($errorArray) && array_key_exists('district_id', $errorArray['errors'])) ? $errorArray['errors']['district_id'] : NULL;
        $nameInputError = (!empty($errorArray) && array_key_exists('school_name', $errorArray['errors'])) ? $errorArray['errors']['school_name'] : NULL;
        $addressOneInputError = (!empty($errorArray) && array_key_exists('address_one', $errorArray['errors'])) ? $errorArray['errors']['address_one'] : NULL;
        $emailAddressInputError = (!empty($errorArray) && array_key_exists('email_address', $errorArray['errors'])) ? $errorArray['errors']['email_address'] : NULL;
        $telephoneNumberInputError = (!empty($errorArray) && array_key_exists('telephone_number', $errorArray['errors'])) ? $errorArray['errors']['telephone_number'] : NULL;
        
        $saveLabel = $this->objLanguage->languageText('word_save', 'system', 'WORD: word_save, not found');
        $cancelLabel = $this->objLanguage->languageText('word_cancel', 'system', 'WORD: word_cancel, not found');
        $provinceLabel = $this->objLanguage->languageText('mod_schools_province', 'schools', 'TEXT: mod_schools_province, not found');
        $selectProvinceLabel = $this->objLanguage->languageText('mod_schools_selectprovince', 'schools', 'TEXT:mod_schools_selectprovince, not found');
        $districtLabel = $this->objLanguage->languageText('mod_schools_district', 'schools', 'TEXT: mod_schools_district, not found');
        $selectDistrictLabel = $this->objLanguage->languageText('mod_schools_selectdistrict', 'schools', 'TEXT:mod_schools_selectdistrict, not found');
        $schoolNameLabel = $this->objLanguage->languageText('mod_schools_schoolname', 'schools', 'TEXT: mod_schools_schoolname, not found');
        $addressLabel = $this->objLanguage->languageText('word_address', 'system', 'WORD: word_address, not found');
        $emailAddressLabel = $this->objLanguage->languageText('phrase_emailaddress', 'system', 'phrase_emailaddress, not found');
        $telephoneNumberLabel = $this->objLanguage->languageText('phrase_telephonenumber', 'system', 'phrase_telephonenumber, not found');
        $faxNumberLabel = $this->objLanguage->languageText('phrase_faxnumber', 'system', 'phrase_faxnumber, not found');
        $noDistrictsLabel = $this->objLanguage->languageText('mod_schools_nodistricts', 'schools', 'TEXT: mod_schools_nodistricts, not found');

        $provinceArray = $this->objDBprovinces->getAll();
        
        // set up htmlelements.
        $objDrop = new dropdown('province_id');
        $objDrop->addOption('', $selectProvinceLabel);
        $objDrop->addFromDB($provinceArray, 'name', 'id');
        $objDrop->setSelected($provinceDropValue);
        $provinceDrop = $objDrop->show();
        
        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($provinceLabel . ': ', '200px', '', '', '', '');
        $objTable->addCell($provinceDropError . $provinceDrop, '', '', '', '', '');
        $objTable->endRow();
        $provinceTable = $objTable->show();

        if (!empty($provinceDropValue))
        {
            $districtArray = $this->objDBdistricts->getDistrictsForProvince($provinceDropValue);

            if (!empty($districtArray))
            {
                // Set up htmlelements.
                $objDrop = new dropdown('district_id');
                $objDrop->addOption('', $selectDistrictLabel);
                $objDrop->addFromDB($districtArray, 'name', 'id');
                $objDrop->setSelected($districtDropValue);
                $districtDrop = $objDrop->show();

                $objTable = new htmltable();
                $objTable->cellpadding = '4';
                $objTable->startRow();
                $objTable->addCell($districtLabel . ': ', '200px', '', '', '', '');
                $objTable->addCell($districtDropError . $districtDrop, '', '', '', '', '');
                $objTable->endRow();
                $districtTable = $objTable->show();

                $string = $districtTable;
            }
            else
            {
                $error = $this->error($noDistrictsLabel);

                $objTable = new htmltable();
                $objTable->cellpadding = '4';
                $objTable->startRow();
                $objTable->addCell($districtLabel . ': ', '200px', '', '', '', '');
                $objTable->addCell($error, '', '', '', '', '');
                $objTable->endRow();
                $districtTable = $objTable->show();            
            }
        }
        else
        {
            $districtTable = NULL;
        }

        $objLayer = new layer();
        $objLayer->id = 'province';
        $objLayer->str = $provinceTable;
        $provinceLayer = $objLayer->show();

        $objLayer = new layer();
        $objLayer->id = 'district';
        $objLayer->str = $districtTable;
        $districtLayer = $objLayer->show();

        $objInput = new textinput('name', $nameInputValue, '', '50');
        $nameInput = $objInput->show();
        
        $objInput = new textinput('address_one', $addressOneInputValue, '', '50');
        $addressOneInput = $objInput->show();

        $objInput = new textinput('address_two', $addressTwoInputValue, '', '50');
        $addressTwoInput = $objInput->show();
        
        $objInput = new textinput('address_three', $addressThreeInputValue, '', '50');
        $addressThreeInput = $objInput->show();
        
        $objInput = new textinput('address_four', $addressFourInputValue, '', '50');
        $addressFourInput = $objInput->show();

        $objInput = new textinput('email_address', $emailAddressInputValue, '', '50');
        $emailAddressInput = $objInput->show();
        
        $objInput = new textinput('telephone_number', $telephoneNumberInputValue, '', '50');
        $telephoneNumberInput = $objInput->show();
        
        $objInput = new textinput('fax_number', $faxNumberInputValue, '', '50');
        $faxNumberInput = $objInput->show();

        $objButton = new button('save', $saveLabel);
        $objButton->setToSubmit();
        $saveButton = $objButton->show();

        $objButton = new button('cancel', $cancelLabel);
        $objButton->setToSubmit();
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($schoolNameLabel . ': ', '200px', '', '', '', '');
        $objTable->addCell($nameInputError . $nameInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($addressLabel . ': ', '', '', '', '', '');
        $objTable->addCell($addressOneInputError . $addressOneInput, '', '', '', '', '');
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
        $objTable->addCell($emailAddressLabel . ': ', '', '', '', '', '');
        $objTable->addCell($emailAddressInputError . $emailAddressInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($telephoneNumberLabel . ': ', '', '', '', '', '');
        $objTable->addCell($telephoneNumberInputError . $telephoneNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($faxNumberLabel . ': ', '', '', '', '', '');
        $objTable->addCell($faxNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($saveButton . '&nbsp;' . $cancelButton, '', '', '', '', 'colspan="2"');
        $objTable->endRow();
        $schoolTable = $objTable->show();
        
        $objLayer = new layer();
        $objLayer->id = 'school';
        $objLayer->str = $schoolTable;
        $schoolLayer = $objLayer->show();

        $objForm = new form('add_school', $this->uri(array(
            'action' => 'validateschool'
        )));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($provinceLayer);
        $objForm->addToForm($districtLayer);
        $objForm->addToForm($schoolLayer);
        $saveForm = $objForm->show();
        
        $string = $saveForm;
        
        return $string;
    }

    /**
     * Method to generate the html for the add and edit school form template
     *
     * @access public
     * @param string $mode The mode of the action form request
     * @return string $string The html string to be sent to the template
     */
    public function contacts($mode)
    {
        if ($mode == 'add')
        {
            $positionInputValue = NULL;
            $nameInputValue = NULL;
            $addressOneInputValue = NULL;
            $addressTwoInputValue = NULL;
            $addressThreeInputValue = NULL;
            $addressFourInputValue = NULL;
            $emailAddressInputValue = NULL;
            $telephoneNumberInputValue = NULL;
            $faxNumberInputValue = NULL;
            $mobileNumberInputValue = NULL;

            $sidInputValue = $this->getParam('sid');
            $cidInput = NULL;
        }
        else
        {
            $sid = $this->getParam('sid');
            $cid = $this->getParam('cid');
            $contactArray = $this->objDBcontacts->getContact($cid);

            $sidInputValue = $sid;
            $cidInputValue = $cid;
            $positionInputValue = $contactArray['position'];
            $nameInputValue = $contactArray['name'];
            $addressArray = explode('|', $contactArray['address']);
            $addressOneInputValue = $addressArray[0];
            $addressTwoInputValue = $addressArray[1];
            $addressThreeInputValue = $addressArray[2];
            $addressFourInputValue = $addressArray[3];
            $emailAddressInputValue = $contactArray['email_address'];
            $telephoneNumberInputValue = $contactArray['telephone_number'];
            $faxNumberInputValue = $contactArray['fax_number'];
            $mobileNumberInputValue = $contactArray['mobile_number'];

            $objInput = new textinput('cid', $cidInputValue, 'hidden', '50');
            $cidInput = $objInput->show();
        }
        
        $errorArray = $this->getSession('schools');
        
        $positionInputValue = !empty($errorArray) ? $errorArray['data']['position'] : $positionInputValue;
        $nameInputValue = !empty($errorArray) ? $errorArray['data']['name'] : $nameInputValue;
        $addressOneInputValue = !empty($errorArray) ? $errorArray['data']['address_one'] : $addressOneInputValue;
        $addressTwoInputValue = !empty($errorArray) ? $errorArray['data']['address_two'] : $addressTwoInputValue;
        $addressThreeInputValue = !empty($errorArray) ? $errorArray['data']['address_three'] : $addressThreeInputValue;
        $addressFourInputValue = !empty($errorArray) ? $errorArray['data']['address_four'] : $addressFourInputValue;
        $emailAddressInputValue = !empty($errorArray) ? $errorArray['data']['email_address'] : $emailAddressInputValue;
        $telephoneNumberInputValue = !empty($errorArray) ? $errorArray['data']['telephone_number'] : $telephoneNumberInputValue;
        $faxNumberInputValue = !empty($errorArray) ? $errorArray['data']['fax_number'] : $faxNumberInputValue;
        $mobileNumberInputValue = !empty($errorArray) ? $errorArray['data']['mobile_number'] : $mobileNumberInputValue;

        $positionInputError = (!empty($errorArray) && array_key_exists('name', $errorArray['errors'])) ? $errorArray['errors']['position'] : NULL;
        $nameInputError = (!empty($errorArray) && array_key_exists('name', $errorArray['errors'])) ? $errorArray['errors']['name'] : NULL;
        $addressOneInputError = (!empty($errorArray) && array_key_exists('address_one', $errorArray['errors'])) ? $errorArray['errors']['address_one'] : NULL;
        $emailAddressInputError = (!empty($errorArray) && array_key_exists('email_address', $errorArray['errors'])) ? $errorArray['errors']['email_address'] : NULL;
        $telephoneNumberInputError = (!empty($errorArray) && array_key_exists('telephone_number', $errorArray['errors'])) ? $errorArray['errors']['telephone_number'] : NULL;
        
        $positionLabel = $this->objLanguage->languageText('mod_schools_position', 'schools', 'TEXT: mod_schools_position, not found');
        $fullNameLabel = $this->objLanguage->languageText('mod_schools_fullname', 'schools', 'TEXT: mod_schools_fullname, not found');
        $addressLabel = $this->objLanguage->languageText('word_address', 'system', 'WORD: word_address, niot found');
        $emailAddressLabel = $this->objLanguage->languageText('phrase_emailaddress', 'system', 'PHRASE: phrase_eamiladdress, not found');
        $telephoneNumberLabel = $this->objLanguage->languageText('phrase_telephonenumber', 'system', 'PHRASE: phrase_telephonenumber, not found');
        $faxNumberLabel = $this->objLanguage->languageText('phrase_faxnumber', 'system', 'PHRASE: phrase_faxnumber');
        $mobileNumberLabel = $this->objLanguage->languageText('phrase_mobilenumber', 'system', 'PHRASE: phrase_mobilenumber, not found');
        $saveLabel = $this->objLanguage->languageText('word_save', 'system', 'WORD: word_save, not found');
        $cancelLabel = $this->objLanguage->languageText('word_cancel', 'system', 'WORD: word_cancel, not found');

        $objInput = new textinput('position', $positionInputValue, '', '50');
        $positionInput = $objInput->show();

        $objInput = new textinput('name', $nameInputValue, '', '50');
        $nameInput = $objInput->show();

        $objInput = new textinput('address_one', $addressOneInputValue, '', '50');
        $addressOneInput = $objInput->show();

        $objInput = new textinput('address_two', $addressTwoInputValue, '', '50');
        $addressTwoInput = $objInput->show();

        $objInput = new textinput('address_three', $addressThreeInputValue, '', '50');
        $addressThreeInput = $objInput->show();

        $objInput = new textinput('address_four', $addressFourInputValue, '', '50');
        $addressFourInput = $objInput->show();

        $objInput = new textinput('email_address', $emailAddressInputValue, '', '50');
        $emailAddressInput = $objInput->show();

        $objInput = new textinput('telephone_number', $telephoneNumberInputValue, '', '50');
        $telephoneNumberInput = $objInput->show();

        $objInput = new textinput('mobile_number', $mobileNumberInputValue, '', '50');
        $mobileNumberInput = $objInput->show();

        $objInput = new textinput('fax_number', $faxNumberInputValue, '', '50');
        $faxNumberInput = $objInput->show();

        $objInput = new textinput('sid', $sidInputValue, 'hidden', '50');
        $sidInput = $objInput->show();

        $objButton = new button('save', $saveLabel);
        $objButton->setId('save_contact');
        $objButton->setToSubmit();
        $saveButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->setId('cancel_contact');
        $objButton->setToSubmit();
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($positionLabel . ': ', '200px', '', '', '', '');
        $objTable->addCell($positionInputError . $positionInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($fullNameLabel . ': ', '', '', '', '', '');
        $objTable->addCell($nameInputError . $nameInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($addressLabel . ': ', '', '', '', '', '');
        $objTable->addCell($addressOneInputError . $addressOneInput, '', '', '', '', '');
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
        $objTable->addCell($emailAddressLabel . ': ', '', '', '', '', '');
        $objTable->addCell($emailAddressInputError . $emailAddressInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($telephoneNumberLabel . ': ', '', '', '', '', '');
        $objTable->addCell($telephoneNumberInputError . $telephoneNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($mobileNumberLabel . ': ', '', '', '', '', '');
        $objTable->addCell($mobileNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($faxNumberLabel . ': ', '', '', '', '', '');
        $objTable->addCell($faxNumberInput, '', '', '', '', '');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($cidInput . $sidInput . $saveButton . '&nbsp' . $cancelButton, '', '', '', '', '');
        $objTable->endRow();
        $contactTable = $objTable->show();

        $objForm = new form('contact', $this->uri(array(
            'action' => 'validatecontact',
            'mode' => $mode,
        )));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($contactTable);
        $addForm = $objForm->show();
        
        return $addForm;
    }

    /**
     *
     * Method to validate the contact data
     * 
     * @access public
     * @param array $data The data to validate
     * @return  
     */
    public function validateContact($data)
    {
        $errors = array();
        foreach ($data as $fieldname => $value)
        {
            if ($fieldname != 'cid' && $fieldname != 'sid' && $fieldname != 'address_one'
                && $fieldname != 'address_two' && $fieldname != 'address_three'
                && $fieldname != 'address_four' && $fieldname != 'fax_number' 
                && $fieldname != 'telephone_number' && $fieldname != 'mobile_number')
            {
                if ($value == NULL)
                {
                    $name = explode('_', $fieldname);
                    $name = implode(' ', $name);
                    $array = array('fieldname' => $name);
                    $errorText = $this->objLanguage->code2Txt('mod_schools_error_1', 'schools', $array);
                    $errors[$fieldname] = '<div>' . $this->error(ucfirst(strtolower($errorText))) . '</div>';
                }
                elseif ($fieldname == 'email_address')
                {
                    if (filter_var($value, FILTER_VALIDATE_EMAIL) == FALSE)
                    {
                        $errorText = $this->objLanguage->languageText('mod_schools_invalidemail', 'schools', 'TEXT: mod_schools_invalidemail, not found');
                        $errors[$fieldname] = '<div>' . $this->error(ucfirst(strtolower($errorText))) . '</div>';
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
                    $errors[$fieldname] = '<div>' . $this->error(ucfirst(strtolower($errorText))) . '</div>';
                }
            }
            elseif ($fieldname == 'telephone_number')
            {
                if ($data['telephone_number'] == NULL && $data['mobile_number'] == NULL)
                {
                    $errorText = $this->objLanguage->languageText('mod_schools_contactnumbers', 'schools', 'TEXT: mod_schools_contactnumbers, not found');
                    $errors[$fieldname] = '<div>' . $this->error(ucfirst(strtolower($errorText))) . '</div>';
                }
            }
            elseif ($fieldname == 'mobile_number')
            {
                if ($data['telephone_number'] == NULL && $data['mobile_number'] == NULL)
                {
                    $errorText = $this->objLanguage->languageText('mod_schools_contactnumbers', 'schools', 'TEXT: mod_schools_contactnumbers, not found');
                    $errors['contact_telephone_number'] = '<div>' . $this->error(ucfirst(strtolower($errorText))) . '</div>';
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
     * Method tho save the contact data on adding a contact
     * 
     * @access public
     * @param array $data The data array to be saved
     * @return string $sid The id of the contact
     */
    public function insertContact($data)
    {
        $data['address'] = implode('|', array($data['address_one'], $data['address_two'], $data['address_three'], $data['address_four']));
        $data['created_by'] = $this->objUser->PKId();
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['school_id'] = $data['sid'];
        unset($data['cid']);
        unset($data['sid']);
        unset($data['address_one']);
        unset($data['address_two']);
        unset($data['address_three']);
        unset($data['address_four']);

        $cid = $this->objDBcontacts->insertContact($data);
        
        return $cid;
    }
    
    /**
     *
     * Method to delete a contact record
     * 
     * @access public
     * @return boolean TRUE on success | FALSE on failure 
     */
    public function deleteContact()
    {
        $cid = $this->getParam('cid');
        $this->objDBcontacts->deleteContact($cid);
        
        return TRUE;
    }
    
    /**
     *
     * Method tho save the contact data on editing a contact
     * 
     * @access public
     * @param array $data The data array to be saved
     * @return string $sid The id of the contact
     */
    public function updateContact($data)
    {
        $cid = $data['cid'];
        $data['address'] = implode('|', array($data['address_one'], $data['address_two'], $data['address_three'], $data['address_four']));
        $data['modified_by'] = $this->objUser->PKId();
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['school_id'] = $data['sid'];
        unset($data['sid']);
        unset($data['cid']);
        unset($data['address_one']);
        unset($data['address_two']);
        unset($data['address_three']);
        unset($data['address_four']);

        $cid = $this->objDBcontacts->updateContact($cid, $data);
        
        return $cid;
    }
    
    /**
     *
     * Method to show the left block to manage schools components
     * 
     * @access public 
     * @return string The string to display in the block 
     */
    public function showManage()
    {
        $manageSchools = $this->objLanguage->languageText('mod_schools_manageschools', 'schools', 'TEXT: mod_schools_manageschools, not found');
        $manageDistricts = $this->objLanguage->languageText('mod_schools_managedistricts', 'schools', 'TEXT: mod_schools_managedistricts, not found');
        
        $this->objIcon->title = $manageSchools;
        $this->objIcon->alt = $manageSchools;
        $this->objIcon->setIcon('house_two', 'png');
        $manageIcon = $this->objIcon->show();
        
        $objLink = new link($this->uri(array('action' => 'manage', 'type' => 's')));
        $objLink->link = $manageIcon . '&nbsp' . $manageSchools;
        $schoolsLink = $objLink->show();

        $this->objIcon->title = $manageDistricts;
        $this->objIcon->alt = $manageDistricts;
        $this->objIcon->setIcon('map', 'png');
        $manageIcon = $this->objIcon->show();
        
        $objLink = new link($this->uri(array('action' => 'manage', 'type' => 'd')));
        $objLink->link = $manageIcon . '&nbsp' . $manageDistricts;
        $districtLink = $objLink->show();

        $objLayer = new layer();
        $objLayer->id = 'manage';
        $objLayer->str = $schoolsLink . '<br /><br />' . $districtLink;
        $manageLayer = $objLayer->show();

        $objFieldset = new fieldset();
        $objFieldset->contents = $manageLayer;
        
        return $objFieldset->show();
    }

    /**
     *
     * Method to show the manage schools districts template
     * 
     * @access puclic
     * @return string The template stricng
     */
    public function manageDistricts()
    {
        // set up language elements.
        $provinceLabel = $this->objLanguage->languageText('mod_schools_province', 'schools', 'TEXT: mod_schools_province, not found');
        $selectProvinceLabel = $this->objLanguage->languageText('mod_schools_selectprovince', 'schools', 'TEXT: mod_schools_selectprovince, not found');
        $districtNameLabel = $this->objLanguage->languageText('mod_schools_districtname', 'schools', 'TEXT: mod_schools_districtname, not found');
        
        $noName = $this->objLanguage->code2Txt('mod_schools_error_1', 'schools', array('fieldname' => $districtNameLabel));
        
        $arrayVars = array();
        $arrayVars['no_district'] = $noName;

        // pass password error to javascript.
        $this->objSvars->varsToJs($arrayVars);

        $pid = $this->getParam('pid');
        
        // get data from the database.
        $provincesArray = $this->objDBprovinces->getAll();        

        // set up htmlelements.
        $objDrop = new dropdown('province');
        $objDrop->addOption('', $selectProvinceLabel);
        $objDrop->addFromDB($provincesArray, 'name', 'id');
        $objDrop->setSelected($pid);
        $provinceDrop = $objDrop->show();

        $objFieldset = new fieldset();
        $objFieldset->legend = '<b>' . $provinceLabel . '</b>';
        $objFieldset->contents = $provinceDrop;
        $provinceFieldset = $objFieldset->show();

        $str = '';
        if ($pid != NULL)
        {
            $str = $this->ajaxManageDistricts(FALSE);
        }
 
        $objLayer = new layer();
        $objLayer->id = 'district';
        $objLayer->str = $str;
        $districtLayer = $objLayer->show();

        $objLayer = new layer();
        $objLayer->id = 'manage';
        $objLayer->str = $provinceFieldset . '<br />' . $districtLayer ;
        $manageLayer = $objLayer->show();
        
        return $manageLayer;
    }

    /**
     *
     * Method to display the ajax call on province change for managing districts
     * 
     * @access public
     * @param boolean $isAjax TRUE if this is called via ajax | FALSE if not
     * @return void 
     */
    public function ajaxManageDistricts($isAjax = TRUE)
    {
        $districtNameLabel = $this->objLanguage->languageText('mod_schools_districtname', 'schools', 'TEXT: mod_schools_districtname, not found');
        $noDistrictsLabel = $this->objLanguage->languageText('mod_schools_nodistricts', 'schools', 'TEXT: mod_schools_nodistricts, not found');
        $addDistrictLabel = $this->objLanguage->languageText('mod_schools_adddistrict', 'schools', 'TEXT: mod_schools_adddistrict, not found');
        $editDistrictLabel = $this->objLanguage->languageText('mod_schools_editdistrict', 'schools', 'TEXT: mod_schools_editdistrict, not found');
        $deleteDistrictLabel = $this->objLanguage->languageText('mod_schools_deletedistrict', 'schools', 'TEXT: mod_schools_deletedistrict, not found');
        $deleteConfirmLabel = $this->objLanguage->languageText('mod_schools_deleteconfirm', 'schools', 'TEXT: mod_schools_deleteconfirm, not found');
        $districtsLabel = $this->objLanguage->languageText('mod_schools_districts', 'schools', 'TEXT: mod_schools_districts, not found');
        $addLabel = $this->objLanguage->languageText('word_add', 'system', 'WORD: word_add, not found');
        $editLabel = $this->objLanguage->languageText('word_edit', 'system', 'WORD: word_edit, not found');
        $deleteLabel = $this->objLanguage->languageText('word_delete', 'system', 'WORD: word_delete, not found');        

        $pid = $this->getParam('pid');
        
        $districtArray = $this->objDBdistricts->getDistrictsForProvince($pid);
        
        $this->objIcon->title = $addLabel;
        $this->objIcon->alt = $addLabel;
        $this->objIcon->setIcon('map_add', 'png');
        $addIcon = $this->objIcon->show();

        $addLink = '<a href="#" id="adddistrict">' . $addIcon . '&nbsp' . $addDistrictLabel . '</a>';
            
        $objLayer = new layer();
        $objLayer->id = 'adddistrictdiv';
        $addLayer = $objLayer->show();
        $str = $addLayer;

        if (empty($districtArray))
        {
            $str .= $this->error($noDistrictsLabel);
            $str .= '<br />' . $addLink . '<br />';
        }
        else
        {
            $str .= $addLink . '<br />';
            
            $objTable = new htmltable();
            $objTable->cellpadding = '4';
            $objTable->startRow();
            $objTable->addCell('<b>' . $districtNameLabel . '</b>', '', '', '', '');
            $objTable->addCell('<b>' . $editLabel . '</b>', '', '', '', '');
            $objTable->addCell('<b>' . $deleteLabel . '</b>', '', '', '', '');
            $objTable->endRow();
            foreach ($districtArray as $key => $value)
            {
                $this->objIcon->setIcon('map_delete', 'png');
                $this->objIcon->title = $deleteDistrictLabel;
                $this->objIcon->alt = $deleteDistrictLabel;
                $icon = $this->objIcon->show();

                $location = $this->uri(array('action' => 'deletedistrict', 'id' => $value['id'], 'pid' => $value['province_id']));

                $this->objConfirm->setConfirm($icon, $location, $deleteConfirmLabel);
                $deleteIcon = $this->objConfirm->show();

                $this->objIcon->title = $editDistrictLabel;
                $this->objIcon->alt = $editDistrictLabel;
                $this->objIcon->setIcon('map_edit', 'png');
                $editIcon = '<a href="#" id="editdistrict" class="' . $value['id'] . '">' . $this->objIcon->show() . '</a>';

                $objTable->startRow();
                $objTable->addCell($value['name'], '', '', '', '');
                $objTable->addCell($editIcon, '', '', '', '');
                $objTable->addCell($deleteIcon, '', '', '', '');
                $objTable->endRow();
            }
            $districtTable = $objTable->show();   
            $str .= $districtTable;
        }
                
        $objFieldset = new fieldset();
        $objFieldset->legend = '<b>' . $districtsLabel . '</b>';
        $objFieldset->contents = $str;
        $districtFieldset = $objFieldset->show();
        
        if ($isAjax)
        {           
            echo $districtFieldset;
            die();
        }
        else
        {
            return $districtFieldset;
        }
    }    

    /**
     *
     * Method to display the add district form
     * 
     * @access public
     * @return VOID 
     */
    public function ajaxAddEditDistrict()
    {
        $id = $this->getParam('id');
        $pid = $this->getParam('pid');
        $districtNameValue = NULL;
        if (!empty($id))
        {
            $districtArray = $this->objDBdistricts->getDistrict($id);
            $districtNameValue = $districtArray['name'];
            $pid = $districtArray['province_id'];
        }
        
        $districtNameLabel = $this->objLanguage->languageText('mod_schools_districtname', 'schools', 'TEXT: mod_schools_districtname, not found');
        $saveLabel = $this->objLanguage->languageText('word_save', 'system', 'WORD: word_save, not found');
        $cancelLabel = $this->objLanguage->languageText('word_cancel', 'system', 'WORD: word_cancel, not found');

        $objInput = new textinput('name', $districtNameValue, '', '50');
        $districtInput = $objInput->show();
  
        $objInput = new textinput('province_id', $pid, 'hidden', '50');
        $provinceInput = $objInput->show();

        $objInput = new textinput('id', $id, 'hidden', '50');
        $idInput = $objInput->show();

        $objButton = new button('save', $saveLabel);
        $objButton->setId('save_district');
        $objButton->setToSubmit();
        $saveButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->setId('cancel_district');
        $cancelButton = $objButton->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';
        $objTable->startRow();
        $objTable->addCell($districtNameLabel, '200px', '', '', 'colspan="7"');
        $objTable->addCell($districtInput, '', '', '', 'colspan="7"');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($idInput . $provinceInput . $saveButton . '&nbsp' . $cancelButton, '', '', '', 'colspan="7"');
        $objTable->endRow();
        $addTable = $objTable->show();

        $objForm = new form('district', $this->uri(array(
            'action' => 'district'
        )));
        $objForm->extra = ' enctype="multipart/form-data"';
        $objForm->addToForm($addTable);
        $addForm = $objForm->show();
        
        echo $addForm;
        die();
    }
}
?>