
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
        $institutionmanager = $this->getObject('institutionmanager', 'unesco_oer');

        // set up html elements
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('adddatautil', 'unesco_oer');

        if ($formError == TRUE) {
            //If the user has entered invalid data
            $formData['name'] = $name;
            $formData['description'] = $description;
            $formData['type'] = $type;
            $formData['country'] = $country;
            $formData['address1'] = $address1;
            $formData['address2'] = $address2;
            $formData['address3'] = $address3;
            $formData['zip'] = $zip;
            $formData['city'] = $city;
            $formData['websiteLink'] = $websiteLink;
            $formData['keyword1'] = $keyword1;
            $formData['keyword2'] = $keyword2;
        }
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
        echo '<br>';

        // setup table and table headings with input fields
        $tableinstitutioninfo = $this->newObject('htmltable', 'htmlelements');
        $tableinstitutioninfo->cssClass = "moduleHeader";

        $this->_objAddDataUtil = $this->getObject('adddatautil');
        $this->objDbInstitutionType = $this->getObject('dbinstitutiontypes');

        //field for the name of the institution
        $name = $formData['name'];
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_name', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToTable(
                $title, 4, 'name', 0, $name, $tableinstitutioninfo
        );

        //field for the description
        $description = $formData['description'];
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'description';
        $editor->height = '150px';
        $editor->width = '100%';
        $editor->setBasicToolBar($description);
        $editor->setContent($description);
        $tableinstitutioninfo->startRow();
        $tableinstitutioninfo->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $tableinstitutioninfo->endRow();
        $tableinstitutioninfo->startRow();
        $tableinstitutioninfo->addCell($editor->show());
        $tableinstitutioninfo->endRow();

        //field for the thumbnail
        $tableinstitutioninfo->startRow();
        $tableinstitutioninfo->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail', 'unesco_oer') . '<font color="#FF2222">* ' . $this->validationArray['thumbnail']['message'] . '</font>');
        $tableinstitutioninfo->endRow();
        $tableinstitutioninfo->startRow();
        $tableinstitutioninfo->addCell($this->objThumbUploader->show());
        $tableinstitutioninfo->endRow();

        $tableinstitutioninfo->startRow();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_type', 'unesco_oer');
        $this->_objAddDataUtil->addTitleToRow($title, 4, $tableinstitutioninfo);
        $tableinstitutioninfo->endRow();

        $tableinstitutioninfo->startRow();
        $institutionTypes = $this->objDbInstitutionType->getInstitutionTypes();
        $this->_objAddDataUtil->addDropDownToRow(
                'type', $institutionTypes, $institutionGUI->showInstitutionTypeId(), 'type', $tableinstitutioninfo, 'id'
        );

        $tableinstitutioninfo->endRow();

        $tableinstitutioninfo->startRow();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_keyword1', 'unesco_oer');
        $this->_objAddDataUtil->addTitleToRow($title, 4, $tableinstitutioninfo);
        $tableinstitutioninfo->endRow();


        //field for the keywords
        $tableinstitutioninfo->startRow();
        $keywords['keyword1'] = $formData['keyword1'];
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_keyword1', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToRow(
                'keyword1', 0, $keywords['keyword1'], $tableinstitutioninfo
        );
        $tableinstitutioninfo->endRow();

        $tableinstitutioninfo->startRow();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_keyword2', 'unesco_oer');
        $this->_objAddDataUtil->addTitleToRow($title, 4, $tableinstitutioninfo);
        $tableinstitutioninfo->endRow();
        
               //field for the institutions keywords
        $keywords['keyword2'] = $formData['keyword2'];
        $tableinstitutioninfo->startRow();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_keyword2', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToRow(
                'keyword2', 0, $keywords['keyword2'], $tableinstitutioninfo
        );
        $tableinstitutioninfo->endRow();
        // $tableinstitutioninfo->startRow();
//        $this->_objAddDataUtil->addDropDownToTable(
//                $title,
//                4,
//                'type',
//                $institutionTypes,
//                $institutionGUI->showInstitutionTypeId(),
//                'type',
//                $tableinstitutioninfo,
//                'id'
//        );
//
//        //field for the keywords
//        $keywords = $institutionGUI->showInstitutionKeywords();
//        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_keyword1', 'unesco_oer');
//        $this->_objAddDataUtil->addTextInputToRow(
//                'keyword1',
//                0,
//                $keywords['keyword1'],
//                $tableinstitutioninfo
//        );
//
////field for the institutions keywords
//        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_keyword2', 'unesco_oer');
//        $this->_objAddDataUtil->addTextInputToTable(
//                $title,
//                4,
//                'keyword2',
//                0,
//                $keywords['keyword2'],
//                $tableinstitutioninfo
//        );
//        $tableinstitutioninfo->endRow();

        $fieldsetInstitutionInfo = $this->newObject('fieldset', 'htmlelements');
        $fieldsetInstitutionInfo->setLegend('Institution information');
        $fieldsetInstitutionInfo->addContent($tableinstitutioninfo->show());
        //echo $fieldsetInstitutionInfo->show();
        // setup table and table headings with input fields
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";
        $address['address1'] = $formData['address1'];
        $table->startRow();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address1', 'unesco_oer');
        $table->addCell($title);
        $table->endRow();

        $table->startRow();
        //field for address1
        $this->_objAddDataUtil->addTextInputToRow(
                'address1', 60, $address['address1'], $table
        );
        $table->endRow();

        $table->startRow();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address2', 'unesco_oer');
        $table->addCell($title);
        $table->endRow();

        $table->startRow();
        $address['address2'] = $formData['address2'];
        //field for address2
        $this->_objAddDataUtil->addTextInputToRow(
                'address2', 60, $address['address2'], $table
        );
        $table->endRow();

        $table->startRow();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address3', 'unesco_oer');
        $table->addCell($title);
        $table->endRow();


        $table->startRow();
        $address['address3'] = $formData['address3'];
        //field for address3
        $this->_objAddDataUtil->addTextInputToRow(
                'address3', 60, $address['address3'], $table
        );
        $table->endRow();
        /*
          $table->startRow();
          $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address1', 'unesco_oer');
          $this->_objAddDataUtil->addTitleToRow($title, 4, $table);
          $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address2', 'unesco_oer');
          $this->_objAddDataUtil->addTitleToRow($title, 4, $table);
          $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address3', 'unesco_oer');
          $this->_objAddDataUtil->addTitleToRow($title, 4, $table);
          $table->endRow();



          $table->startRow();
          //field for the Country where institution is located
          //field for address1
          $address = $institutionGUI->showInstitutionAddress();
          //$title = $this->objLanguage->languageText('mod_unesco_oer_institution_address1', 'unesco_oer');
          $this->_objAddDataUtil->addTextInputToRow(
          'address1', 0, $address['address1'], $table
          );

          //field for address2
          //        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address2', 'unesco_oer');
          $this->_objAddDataUtil->addTextInputToRow(
          'address2', 0, $address['address2'], $table
          );

          //field for address3
          //        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address3', 'unesco_oer');
          $this->_objAddDataUtil->addTextInputToRow(
          'address3', 0, $address['address3'], $table
          );
          $table->endRow(); */


        $table->startRow();
        $zipTitle = $this->objLanguage->languageText('mod_unesco_oer_institution_zip', 'unesco_oer');
        $this->_objAddDataUtil->addTitleToRow($zipTitle, 4, $table);
        $table->endRow();
        $table->startRow();
        $zip = $formData['zip'];
        //field for zip code
        $this->_objAddDataUtil->addTextInputToRow(
                'zip', 5, $zip, $table
        );
        $table->endRow();

        //Field for the country
        $table->startRow();
        $countryTitle = $this->objLanguage->languageText('mod_unesco_oer_institution_country', 'unesco_oer');
        $this->_objAddDataUtil->addTitleToRow($countryTitle, 4, $table);
        $table->endRow();
        $table->startRow();
        $this->objDbCountries = $this->getObject('dbcountries');
//        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_country', 'unesco_oer');
        $countryList = $this->objDbCountries->getAllCountries();
        $this->_objAddDataUtil->addDropDownToRow(
                'country', $countryList, $institutionGUI->showInstitutionCountryId(), 'countryname', $table, 'id'
        );
        $table->endRow();

        //Field for city
        $table->startRow();
        $city = $formData['city'];
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_city', 'unesco_oer');
        $this->_objAddDataUtil->addTitleToRow($title, 4, $table);
        $table->endRow();
        $table->startRow();
        $this->_objAddDataUtil->addTextInputToRow(
                'city', 0, $city, $table
        );
        $table->endRow();

        $table->startRow();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_websitelink', 'unesco_oer');
        $this->_objAddDataUtil->addTitleToRow($title, 4, $table);
        $table->endRow();

        $table->startRow();
        $websiteLink = $formData['websiteLink'];
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_websitelink', 'unesco_oer');
        $this->_objAddDataUtil->addTextInputToRow(
                'websiteLink', 60, $websiteLink, $table
        );

        $table->endRow();

//        //field for the Country where institution is located
//        $this->objDbCountries = $this->getObject('dbcountries');
//        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_country', 'unesco_oer');
//        $countryList = $this->objDbCountries->getAllCountries();
//        $this->_objAddDataUtil->addDropDownToTable(
//                $title,
//                4,
//                'country',
//                $countryList,
//                $institutionGUI->showInstitutionCountryId(),
//                'countryname',
//                $table,
//                'id'
//        );
//
//        //field for address1
//        $address = $institutionGUI->showInstitutionAddress();
//        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address1', 'unesco_oer');
//        $this->_objAddDataUtil->addTextInputToTable(
//                $title,
//                4,
//                'address1',
//                0,
//                $address['address1'],
//                $table
//        );
//
////field for address2
//        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address2', 'unesco_oer');
//        $this->_objAddDataUtil->addTextInputToTable(
//                $title,
//                4,
//                'address2',
//                0,
//                $address['address2'],
//                $table
//        );
//
////field for address3
//        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address3', 'unesco_oer');
//        $this->_objAddDataUtil->addTextInputToTable(
//                $title,
//                4,
//                'address3',
//                0,
//                $address['address3'],
//                $table
//        );
//
////field for zip code
//        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_zip', 'unesco_oer');
//        $this->_objAddDataUtil->addTextInputToTable(
//                $title,
//                4,
//                'zip',
//                0,
//                $institutionGUI->showInstitutionZip(),
//                $table
//        );
//
////field for the city
//        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_city', 'unesco_oer');
//        $this->_objAddDataUtil->addTextInputToTable(
//                $title,
//                4,
//                'city',
//                0,
//                $institutionGUI->showInstitutionCity(),
//                $table
//        );
//
////field for the institutions website link
//
//        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_websitelink', 'unesco_oer');
//        $this->_objAddDataUtil->addTextInputToTable(
//                $title,
//                4,
//                'websiteLink',
//                0,
//                $institutionGUI->showInstitutionWebsiteLink(),
//                $table
//        );

        $fieldset2 = $this->newObject('fieldset', 'htmlelements');
        $fieldset2->setLegend('Contact Details');
        $fieldset2->addContent($table->show());
        //echo $fieldset2->show();
// setup button for submission
        $buttonSubmit = new button('submit', $this->objLanguage->
                                languageText('mod_unesco_oer_add_data_newInstitutionBtn', 'unesco_oer'));
        $buttonSubmit->setToSubmit();

// setup button for cancellation
        $buttonCancel = new button('submit', $this->objLanguage->
                                languageText('mod_unesco_oer_product_cancel_button', 'unesco_oer'));
        $buttonCancel->setToSubmit();


        $buttons = '<div id="institutioncontrol">' . $buttonSubmit->show() . $buttonCancel->show() . '</div>';

        //createform, add fields to it and display
        $uri = $this->uri(array(
                    'action' => $formAction, 'institutionId' => $institutionId, 'prevAction' => $prevAction));
        $form_data = new form('add_institution_ui', $uri);
        $form_data->extra = 'enctype="multipart/form-data"';
        $form_data->addToForm($fieldsetInstitutionInfo->show() . '<br />' . $fieldset2->show() . '<br />' . $buttons);
        echo $form_data->show();
        ?>
    </body>
</html>
