
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // set up html elements
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('adddatautil', 'unesco_oer');

        //get parent if any
        //$product = $this->_objDbProducts->getProductByID($productID);
        // setup and show heading
        $header = new htmlHeading();
        $header->str = $this->objLanguage->
                        languageText('mod_unesco_oer_add_data_newInstitution', 'unesco_oer');
        $header->type = 2;
        echo $header->show();

        // setup table and table headings with input fields
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";

        $this->_objAddDataUtil = $this->getObject('adddatautil');
        $this->objDbInstitutionType = $this->getObject('dbinstitutiontypes');

        //Stores the data from the array
        //Obtains the data from an array that is passed  to it from the institution gui class
        $formData = array();

        //field for the name of the institution
        $formData['name'] = 'Vito ra kona';
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_name', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                $formData['name'],
                0,
                $formData['name'],
                $table
        );


        //field for the description
        $fieldName = 'description';
        $formData['description'] = 'description';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $formData['description'];
        $editor->height = '150px';
        $editor->width = '70%';
        $editor->setBasicToolBar();
        $editor->setContent($formData['description']);
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($editor->show());
        $table->endRow();

        //field for the institution type
        $fieldName = 'institution_type';
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_type', 'unesco_oer');
        $institutionTypes = $this->objDbInstitutionType->getInstitutionTypes();
        $this->_objAddDataUtil->addDropDownToTable(
                $title,
                4,
                $fieldName,
                $institutionTypes,
                $fieldName,
                'type',
                $table,
                'id'
        );

        //field for the Country where institution is located
        $this->objDbCountries = $this->getObject('dbcountries');
        $fieldName = 'country';
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_country', 'unesco_oer');
        $country = $this->objDbCountries->getAllCountries();
        $this->_objAddDataUtil->addDropDownToTable(
                $title,
                4,
                $fieldName,
                $country,
                $fieldName,
                'countryname',
                $table,
                'id'
        );


        //field for address1
        $fieldName = 'address1';
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address1', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                $fieldName,
                0,
                $fieldName,
                $table
        );

        //field for address2
        $fieldName = 'address2';
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address2', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                $fieldName,
                0,
                $fieldName,
                $table
        );

        //field for address3
        $fieldName = 'address3';
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address3', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                $fieldName,
                0,
                $fieldName,
                $table
        );

        //field for zip code
        $fieldName = 'zip';
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_zip', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                $fieldName,
                0,
                $fieldName,
                $table
        );

        //field for the city
        $fieldName = 'city';
        $title = $this->objLanguage->languageText('mod_unesco_oer_title_alternative', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                $fieldName,
                0,
                $fieldName,
                $table
        );

        //field for the institutions website link
        $fieldName = 'websitelink';
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_city', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                $fieldName,
                0,
                $fieldName,
                $table
        );

        //field for the keywords
        $fieldName = 'keyword1';
        $title = $this->objLanguage->languageText('mod_unesco_oer_title_alternative', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                $fieldName,
                0,
                $fieldName,
                $table
        );

        //field for the institutions keywords
        $fieldName = 'keyword2';
        $title = $this->objLanguage->languageText('mod_unesco_oer_title_alternative', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title,
                4,
                $fieldName,
                0,
                $fieldName,
                $table
        );

        $this->objDbGroups = $this->getObject('dbgroups');

        //field for the linking groups to institution
        //Add functionality to add as many groups as one wants
        $fieldName = 'groups';
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_linked_groups', 'unesco_oer');
        $groups = $this->objDbGroups->getGroups();
        $this->_objAddDataUtil->addDropDownToTable(
                $title,
                4,
                $fieldName,
                $groups,
                $fieldName,
                'name',
                $table,
                'id'
        );

        //field for the thumbnail
        $this->objThumbUploader = $this->getObject('thumbnailuploader');
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail', 'unesco_oer'));
        $table->addCell($this->objThumbUploader->show());
        $table->endRow();

        // setup button for submission
        $buttonSubmit = new button('submit', $this->objLanguage->
                                languageText('mod_unesco_oer_product_upload_button', 'unesco_oer'));
        $buttonSubmit->setToSubmit();

        // setup button for cancellation
        $buttonCancel = new button('submit', $this->objLanguage->
                                languageText('mod_unesco_oer_product_cancel_button', 'unesco_oer'));
        $buttonCancel->setToSubmit();


        //createform, add fields to it and display
        $uri = $this->uri(array(
                    'action' => "savetest",
                    'parentID' => $productID,
                    'prevAction' => $prevAction,
                    'isNewProduct' => $isNewProduct));
        $form_data = new form('add_products_ui', $uri);
        $form_data->extra = 'enctype="multipart/form-data"';
        $form_data->addToForm($table->show() . '<br />' . $buttonSubmit->show() . $buttonCancel->show());
        echo $form_data->show();
        ?>
    </body>
</html>
