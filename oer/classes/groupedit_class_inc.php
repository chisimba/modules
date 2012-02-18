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
        $GLOBALS['kewl_entry_point_run']) {
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
class groupedit extends object {

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
    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        // Serialize language items to Javascript
        $arrayVars['status_success'] = "mod_oer_status_success";
        $arrayVars['status_fail'] = "mod_oer_status_fail";
        $objSerialize = $this->getObject('serializevars', 'oer');
        $objSerialize->serializetojs($arrayVars);
        $this->objThumbUploader = $this->getObject('thumbnailuploader');
        $this->objDbInstitution = $this->getObject('dbinstitution');
        // Load the jquery validate plugin
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/validate/jquery.validate.min.js', 'jquery'));
        // Load the helper Javascript.
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('groupedit.js', 'oer'));
        // Load all the required HTML classes from HTMLElements module.
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('fieldset', 'htmlelements');
        // Get Group details.
        $this->objDbGroups = $this->getObject('dbgroups');
        // Get edit or add mode from querystring.
        $this->mode = $this->getParam('mode', 'add');
    }

    public function buildGroupFormStep1($contextcode) {
        $dbGroup = $this->getObject("dbgroups", "oer");
        $group = $dbGroup->getGroupByContextCode($contextcode);
        $objContext = $this->getObject('dbcontext', 'context');
        $context = $objContext->getContext($contextcode);
        $action = "savegroupstep1";
        if ($group != null) {
            $action = "updategroupstep1";
        }
        // Create the form.
        $form = new form('groupFrom1', $this->uri(array("action" => $action)));

        // Create a table to hold the layout
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->width = '100%';
        $table->border = '0';
        $table->cellspacing = '0';
        $table->cellpadding = '2';

        
         if ($contextcode != null) {
            $hidId = new hiddeninput('contextcode');
            $hidId->cssId = "id";
            $hidId->value = $contextcode;
            $table->startRow();
            $table->addCell($hidId->show());
            $table->endRow();
        }
        
        
        // Group name.
        $name = new textinput('name');
        $name->size = 80;
        $name->cssClass = 'required';
        if ($context != null) {
            $name->value = $context['title'];
        } else {
            $name->value = NULL;
        }
        $table->startRow();
        $table->addCell(
                $this->objLanguage->languageText('mod_oer_group_name', 'oer'));
        $table->addCell($name->show());
        $table->endRow();

        // Group website.
        $website = new textinput('website');
        $website->size = 80;
        $website->cssClass = 'required';
        if ($group != null) {
            $website->value = $group['website'];
        } else {
            $website->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_website', 'oer')); // obj lang
        $table->addCell($website->show());
        $table->endRow();


        // Put it in a fieldset.
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = $this->objLanguage->languageText(
                'mod_oer_group_fieldset1', 'oer');
        $fieldset->contents = $table->show();
        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');

        $table = $this->newObject('htmltable', 'htmlelements');
        // Group description.
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'description';
        $editor->height = '150px';
        $editor->width = '85%';
        $editor->setBasicToolBar();
        if ($context != null) {
            $editor->setContent($context['about']);
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText(
                        'mod_oer_group_description', 'oer'));
        $table->addCell($editor->show());
        $table->endRow();

        // Put it in a fieldset.
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = $this->objLanguage->languageText(
                'mod_oer_group_fieldset5', 'oer');
        $fieldset->contents = $table->show();
        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');

        // Group contact details.
        $table = $this->newObject('htmltable', 'htmlelements');
        $email = new textinput('email');
        $email->size = 80;
        $email->cssClass = "required";
        if ($group != null) {
            $email->value = $group['email'];
        } else {
            $email->value = NULL;
        }
        $table->addCell($this->objLanguage->languageText(
                        'mod_oer_group_email', 'oer'));
        $table->addCell($email->show());
        $table->endRow();

        // Group address.
        $address = new textinput('address');
        $address->size = 80;
        $address->cssClass = 'required';
        if ($group != null) {
            $address->value = $group['address'];
        } else {
            $address->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText(
                        'mod_oer_group_address', 'oer'));
        $table->addCell($address->show());
        $table->endRow();

        // Group city.
        $city = new textinput('city');
        $city->size = 80;
        $city->cssClass = 'required';
        if ($group != null) {
            $city->value = $group['city'];
        } else {
            $city->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText(
                        'mod_oer_group_city', 'oer'));
        $table->addCell($city->show());
        $table->endRow();

        // Group state/province.
        $state = new textinput('state');
        $state->size = 80;
        $state->cssClass = 'required';
        if ($group != null) {
            $state->value = $group['state'];
        } else {
            $state->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText(
                        'mod_oer_group_state', 'oer')); // obj lang
        $table->addCell($state->show());
        $table->endRow();

        // Group postal code.
        $code = new textinput('postalcode');
        $code->size = 80;
        $code->cssClass = 'required';
        if ($group != null) {
            $code->value = $group['postalcode'];
        } else {
            $code->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText(
                        'mod_oer_group_postalcode', 'oer'));
        $table->addCell($code->show());
        $table->endRow();

        // Put it in a fieldset.
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = $this->objLanguage->languageText(
                'mod_oer_group_fieldset2', 'oer');
        $fieldset->contents = $table->show();
        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');

        $button = new button('saveGroupButton1', $this->objLanguage->languageText('mod_oer_group_save_button', 'oer'));
        $button->setToSubmit();
        $form->addToForm($button->show());



        // setup and show heading
        $header = new htmlheading();
        $header->type = 1;
        if ($context != null) {
            $header->str = $context['title'];
        } else {
            $header->str = $this->objLanguage->languageText(
                    'mod_oer_group_new', 'oer', "Creating a new group");
        }
        return$header->show() . $form->show();
    }

    public function buildGroupFormStep2() {
        // Group latitude.
        $table = $this->newObject('htmltable', 'htmlelements');
        $latitude = new textinput('loclat');
        $latitude->size = 38;
        if ($this->mode == 'edit') {
            $latitude->value = $this->loclat;
        } else {
            $latitude->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText(
                        'mod_oer_group_latitude', 'oer'));
        $table->addCell($latitude->show());
        $table->endRow();

        // Group longitude.
        $longitude = new textinput('loclong');
        $longitude->size = 38;
        if ($this->mode == 'edit') {
            $longitude->value = $this->loclong;
        } else {
            $longitude->value = NULL;
        }
        $table->startRow();
        $table->addCell($this->objLanguage->languageText(
                        'mod_oer_group_longitude', 'oer'));
        $table->addCell($longitude->show());
        $table->endRow();

        // Group country.
        $table->startRow();
        $objCountries = &$this->getObject('languagecode', 'language');
        $table->addCell($this->objLanguage->languageText(
                        'word_country', 'system'));
        if ($this->mode == 'edit') {
            $table->addCell($objCountries->countryAlpha($this->country));
        } else {
            $table->addCell($objCountries->countryAlpha());
        }
        $table->endRow();

        // Put it in a fieldset with a google map.
        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = $this->objLanguage->languageText('mod_oer_group_fieldset3', 'oer');
        $fieldset->contents = '<label>Address: </label><input id="address"  type="text"/> 
            ' . $this->objLanguage->languageText('mod_oer_group_locincorrect', 'oer') . '
            <div id="map_edit" style="width:600px; height:300px"></div><br/>'
                . $table->show();
        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');
    }

    /**
     * the third step when creating a group. This step presents thumbnail uploader
     * and a list of institutions that can be linked to the group
     * @param type $contextcode
     * @return type 
     */
    public function buildGroupFormStep3($contextcode) {
        $this->addStep4JS();
        $dbGroup = $this->getObject("dbgroups", "oer");
        $group = $dbGroup->getGroupByContextCode($contextcode);
        $objContext = $this->getObject('dbcontext', 'context');
        $action = "savegroupstep3";
        if ($group != null) {
            $action = "updategroupstep3";
        }
 
        $form = new form('groupFrom1', $this->uri(array("action" => $action)));

        $table = $this->newObject('htmltable', 'htmlelements');
        $user_current_membership = $this->objDbGroups->getGroupInstitutions(
                $this->getParam('id'));
        $currentMembership = array();

        $availablegroups = array();
        $groups = $this->objDbInstitution->getAllInstitutions();
        foreach ($groups as $group) {
            if (count($user_current_membership) > 0) { ///****** undefined
                foreach ($user_current_membership as $membership) {
                    if ($membership['institution_id'] != NULL) {
                        if (strcmp($group['id'], $membership['institution_id']) == 0) {
                            array_push($currentMembership, $group);
                        } else {
                            array_push($availablegroups, $group);
                        }
                    }
                }
            } else { /// TODO WHY IS NOT SHOWING ON EDIT ADMIN
                array_push($availablegroups, $group);
            }
        }

        $objSelectBox = $this->newObject('selectbox', 'htmlelements');
        $objSelectBox->create($form, 'leftList[]', 'Available Institutions', 'rightList[]', 'Chosen Institutions');
        $objSelectBox->insertLeftOptions(
                $availablegroups, 'id', 'name');
        $objSelectBox->insertRightOptions(
                $currentMembership, 'id', 'name');

        $tblLeft = $this->newObject('htmltable', 'htmlelements');
        $objSelectBox->selectBoxTable($tblLeft, $objSelectBox->objLeftList);
        //Construct tables for right selectboxes
        $tblRight = $this->newObject('htmltable', 'htmlelements');
        $objSelectBox->selectBoxTable($tblRight, $objSelectBox->objRightList);
        //Construct tables for selectboxes and headings
        $tblSelectBox = $this->newObject('htmltable', 'htmlelements');
        $tblSelectBox->width = '90%';
        $tblSelectBox->startRow();
        $tblSelectBox->addCell($objSelectBox->arrHeaders['hdrLeft'], '100pt');
        $tblSelectBox->addCell($objSelectBox->arrHeaders['hdrRight'], '100pt');
        $tblSelectBox->endRow();
        $tblSelectBox->startRow();
        $tblSelectBox->addCell($tblLeft->show(), '100pt');
        $tblSelectBox->addCell($tblRight->show(), '100pt');
        $tblSelectBox->endRow();

        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_oer_group_institution', 'oer'));
        $table->addCell($tblSelectBox->show());
        $table->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = $this->objLanguage->languageText('mod_oer_group_fieldset4', 'oer');
        $fieldset->contents = $table->show();
        $form->addToForm($fieldset->show());
        $form->addToForm('<br />');

        $button = new button('saveGroupButton', $this->objLanguage->languageText('mod_oer_group_save_button', 'oer'));
        $button->setToSubmit();
        $form->addToForm($button->show());
        $objAjaxUpload = $this->newObject('ajaxuploader', 'oer');

        return $objAjaxUpload->show($contextcode, 'uploadgroupthumbnail').$form->show();
    }

    /**
     * Creates side navigation links for moving in between forms when creating
     * a product 
     */
    function buildGroupStepsNav($contextcode, $step) {

        $header = new htmlheading();
        $header->type = 2;
        $header->cssClass = "build_group_steps_nav";
        $header->str = $this->objLanguage->languageText('mod_oer_jumpto', 'oer');

        $dbGroups = $this->getObject("dbgroups", "oer");
        $group = $dbGroups->getGroupByContextCode($contextcode);

        $thumbnail = '<img src="usrfiles/' . $group['thumbnail'] . '"  width="79" height="101" align="bottom"/>';
        if ($group['thumbnail'] == '') {
            $thumbnail = '<img src="skins/oer/images/product-cover-placeholder.jpg"  width="79" height="101" align="bottom"/>';
        }

        $viewGroupLink = new link($this->uri(array("action" => "viewgroup", "contextcode" => $contextcode)));
        $viewGroupLink->link = $thumbnail;
        $content = $viewGroupLink->show();
        $content .= $header->show();
        $content.='<ul id="nav-secondary">';

        $class = "";
        $link = new link($this->uri(array("action" => "editgroupstep1", "contextcode" => $contextcode)));
        $link->link = $this->objLanguage->languageText('mod_oer_step1', 'oer');

        if ($step == '1') {
            $class = "current";
        } else {
            $class = "";
        }
        $content.='<li class="' . $class . '">' . $link->show() . '</li>';


        $link = new link($this->uri(array("action" => "editgroupstep2", "contextcode" => $contextcode)));
        $link->link = $this->objLanguage->languageText('mod_oer_step2', 'oer');

        if ($step == '2') {
            $class = "current";
        } else {
            $class = "";
        }
        $content.='<li class="' . $class . '">' . $link->show() . '</li>';

        $link = new link($this->uri(array("action" => "editgroupstep3", "contextcode" => $contextcode)));
        $link->link = $this->objLanguage->languageText('mod_oer_step3', 'oer');

        if ($step == '3') {
            $class = "current";
        } else {
            $class = "";
        }
        $content.='<li class="' . $class . '">' . $link->show() . '</li>';

        $content.="</ul>";


        return $content;
    }

    /**
     * This includes necessary js for product 4 creation
     */
    function addStep4JS() {
        $this->appendArrayVar('headerParams', "

<script type=\"text/javascript\">
    //<![CDATA[

    function loadAjaxForm(fileid) {
        window.setTimeout('loadForm(\"'+fileid+'\");', 1000);
    }

    function loadForm(fileid) {
        var pars = \"module=oer&action=ajaxprocess&id=\"+fileid;
        new Ajax.Request('index.php',{
            method:'get',
            parameters: pars,
            onSuccess: function(transport){
                var response = transport.responseText || \"no response text\";
                $('updateform').innerHTML = response;
            },
            onFailure: function(transport){
                var response = transport.responseText || \"no response text\";
                //alert('Could not download module: '+response);
            }
        });
    }

    function processConversions() {
        window.setTimeout('doConversion();', 2000);
    }

    function doConversion() {

        var pars = \"module=oer&action=ajaxprocessconversions\";
        new Ajax.Request('index.php',{
            method:'get',
            parameters: pars,
            onSuccess: function(transport){
                var response = transport.responseText || \"no response text\";
                //alert(response);
            },
            onFailure: function(transport){
                var response = transport.responseText || \"no response text\";
                //alert('Could not download module: '+response);
            }
        });
    }
    //]]>
</script>            
");
    }

}

?>