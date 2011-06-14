
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        //Display errors
        error_reporting(E_ALL);
        ini_set('display_errors', 'Off');

        $institutionGUI = $this->getObject('institutiongui', 'unesco_oer');

        // set up html elements
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('adddatautil', 'unesco_oer');




        //Check if an institution is being edited
        if (isset($institutionId)) {
            $institutionGUI->getInstitution($institutionId);
            $formData = $institutionGUI->showAllInstitutionData();
            $formAction = "editInstitutionSubmit";
        } else {
            $formData = array();
            $formAction = "createInstitutionSubmit";
        }

        // setup and show heading
        $header = new htmlHeading();
        //Check if an institution is being edited
        if (isset($institutionId)) {
            $header->str = $this->objLanguage->
                            languageText('mod_unesco_oer_institution_Update_heading', 'unesco_oer');
        } else {
            $header->str = $this->objLanguage->
                            languageText('mod_unesco_oer_add_data_newInstitution', 'unesco_oer');
        }
        $header->type = 2;
        echo $header->show();

        // setup table and table headings with input fields
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";

        $this->_objAddDataUtil = $this->getObject('adddatautil');
        $this->objDbInstitutionType = $this->getObject('dbinstitutiontypes');

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->legend = $this->objLanguage->languageText('phrase_accountdetails', 'userregistration', 'Account Details');
        $fieldset->contents = $table->show();

        //field for the name of the institution
        $name = $institutionGUI->showInstitutionName();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_name', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                'name',
                0,
                $name,
                $table
        );

        //field for the description
        $description = $institutionGUI->showInstitutionDescription();
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'description';
        $editor->height = '150px';
        $editor->width = '70%';
        $editor->setBasicToolBar($description);
        $editor->setContent($institutionGUI->showInstitutionDescription());
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->endRow();
        $table->startRow();
        $table->addCell($editor->show());
        $table->endRow();

//        $fieldset = $this->newObject('fieldset', 'htmlelements');
//        $fieldset->setLegend('Institution information');
//        $fieldset->addContent($table->show());
        //echo $fieldset->show();

        //field for the institution type
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_type', 'unesco_oer');
        $institutionTypes = $this->objDbInstitutionType->getInstitutionTypes();
        $this->_objAddDataUtil->addDropDownToTable(
                $title,
                4,
                'type',
                $institutionTypes,
                $institutionGUI->showInstitutionTypeId(),
                'type',
                $table,
                'id'
        );

        //field for the Country where institution is located
        $this->objDbCountries = $this->getObject('dbcountries');
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_country', 'unesco_oer');
        $countryList = $this->objDbCountries->getAllCountries();
        $this->_objAddDataUtil->addDropDownToTable(
                $title,
                4,
                'country',
                $countryList,
                $institutionGUI->showInstitutionCountryId(),
                'countryname',
                $table,
                'id'
        );


        //field for address1
        $address = $institutionGUI->showInstitutionAddress();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address1', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                'address1',
                0,
                $address['address1'],
                $table
        );

//field for address2
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address2', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                'address2',
                0,
                $address['address2'],
                $table
        );

//field for address3
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address3', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                'address3',
                0,
                $address['address3'],
                $table
        );

//field for zip code
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_zip', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                'zip',
                0,
                $institutionGUI->showInstitutionZip(),
                $table
        );

//field for the city
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_city', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                'city',
                0,
                $institutionGUI->showInstitutionCity(),
                $table
        );

//field for the institutions website link

        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_websitelink', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                'websiteLink',
                0,
                $institutionGUI->showInstitutionWebsiteLink(),
                $table
        );

//field for the keywords
        $keywords = $institutionGUI->showInstitutionKeywords();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_keyword1', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                'keyword1',
                0,
                $keywords['keyword1'],
                $table
        );

//field for the institutions keywords
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_keyword2', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                'keyword2',
                0,
                $keywords['keyword2'],
                $table
        );

//field for the thumbnail
//field for the thumbnail
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail', 'unesco_oer') . '<font color="#FF2222">* ' . $this->validationArray['thumbnail']['message'] . '</font>');
        $table->endRow();
        $table->startRow();
        $table->addCell($this->objThumbUploader->show());
        $table->endRow();

// setup button for submission
        $buttonSubmit = new button('submit', $this->objLanguage->
                                languageText('mod_unesco_oer_add_data_newInstitutionBtn', 'unesco_oer'));
        $buttonSubmit->setToSubmit();

// setup button for cancellation
        $buttonCancel = new button('submit', $this->objLanguage->
                                languageText('mod_unesco_oer_product_cancel_button', 'unesco_oer'));
        $buttonCancel->setToSubmit();

        //createform, add fields to it and display
        $uri = $this->uri(array(
                    'action' => $formAction, 'institutionId' => $institutionId));
        $form_data = new form('add_institution_ui', $uri);
        $form_data->extra = 'enctype="multipart/form-data"';
        $form_data->addToForm($table->show() . '<br />' . $buttonSubmit->show() . $buttonCancel->show());
        echo $form_data->show();
        ?>
    </body>
</html>
