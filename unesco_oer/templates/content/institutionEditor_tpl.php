
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
        $this->loadClass('hiddeninput', 'htmlelements');
        $fieldsetErrors = $this->newObject('fieldset', 'htmlelements');
        $displayErrors = NULL;
        $reqiuredField = array();

        if ($formError) {
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
            $formData['thumbnail'] = $thumbnail;

            //Display the error messages
            $tableErrors = $this->newObject('htmltable', 'htmlelements');
            $tableErrors->cssClass = "moduleHeader";

            foreach ($errorMessage as $key => $message) {
                if (strlen($message) > 1) {
                    $tableErrors->startRow();
                    $required = '<span class="required_field"> * ' . $message . '</span>';
                    $requiredField[$key] = '<span class="required_field"> * ' . $message . ' </span>';
                    $tableErrors->addCell($required);
                    $tableErrors->endRow();
                }
            }

            $formErrorHeading = $title = $this->objLanguage->languageText('mod_unesco_oer_institution_form_error', 'unesco_oer');
            $fieldsetErrors->setLegend($formErrorHeading);
            $fieldsetErrors->addContent($tableErrors->show());
            $displayErrors = $fieldsetErrors->show() . '<br />';

//            $errorTable
            $formAction = $formAction;
        } else {

            $requiredField['name'] = '<span class="required_field"> * </span>';
            $requiredField['description'] = '<span class="required_field"> * </span>';
            $requiredField['type'] = '<span class="required_field"> * </span>';
            $requiredField['thumbnail'] = '<span class="required_field"> * </span>';
            $requiredField['keyword1'] = '<span class="required_field"> * </span>';
            $requiredField['address1'] = '<span class="required_field"> * </span>';
            $requiredField['zip'] = '<span class="required_field"> * </span>';
            $requiredField['city'] = '<span class="required_field"> * </span>';
            $requiredField['websiteLink'] = '<span class="required_field"> * </span>';

            //Check if an institution is being edited
            if (isset($institutionId)) {
                $institutionGUI->getInstitution($institutionId);
                $formData = $institutionGUI->showAllInstitutionData();
                $formAction = "editInstitutionSubmit";
            } else {
                $formData = array();
                $formAction = "createInstitutionSubmit";
            }
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
        ?>
        <div style="clear:both;"></div> 
        <div class="breadCrumb module"> 
            <div id='breadcrumb'>
                <ul><li class="first">Home</li>
                    <?php
                    $adminTools = $this->objLanguage->languageText('mod_unesco_oer_administrative_tools', 'unesco_oer');
                    $insLabel = $this->objLanguage->languageText('mod_unesco_oer_institution', 'unesco_oer');
                    echo "<li><a href='?module=unesco_oer&action=controlpanel' alt='$adminTools' title='$adminTools'>$adminTools</a></li>";
                    echo "<li class='last'>$insLabel</li>";
                    ?>
                </ul>
            </div>

        </div>

        <?php
        echo '<div id="institutionheading">';
        echo $header->show();
        echo '<br>';
        echo '</div>';

        // setup table and table headings with input fields
        $tableinstitutioninfo = $this->newObject('htmltable', 'htmlelements');
        $tableinstitutioninfo->cssClass = "moduleHeader";

        $this->_objAddDataUtil = $this->getObject('adddatautil');
        $this->objDbInstitutionType = $this->getObject('dbinstitutiontypes');

        //field for the name of the institution
        $name = $formData['name'];
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_name', 'unesco_oer') . $requiredField['name'];
        $this->_objAddDataUtil->addTextInputToTable(
                $title, 4, 'name', 0, $name, $tableinstitutioninfo
        );
        //$tableinstitutioninfo->addCell('*');
        //field for the description
        $description = $formData['description'];
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'description';
        $editor->height = '150px';
        $editor->width = '100%';
        $editor->setBasicToolBar($description);
        $editor->setContent($description);
        $tableinstitutioninfo->startRow();
        $tableinstitutioninfo->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer') . $requiredField['description']);
        $tableinstitutioninfo->endRow();
        $tableinstitutioninfo->startRow();
        $tableinstitutioninfo->addCell($editor->show());
        $tableinstitutioninfo->endRow();

        //field for the thumbnail
        $tableinstitutioninfo->startRow();
        $tableinstitutioninfo->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail', 'unesco_oer') . $requiredField['thumbnail']);
        $tableinstitutioninfo->endRow();
        $tableinstitutioninfo->startRow();
        $tester = new hiddeninput('thumbnail');
        $tester->value = $formData['thumbnail'];
//        $taster = '<input type="text" name="thumbnail" value=' . $formData['thumbnail'] . '>';
        $tableinstitutioninfo->addCell($tester->show());
        $tableinstitutioninfo->endRow();
        $tableinstitutioninfo->startRow();
//        $this->objThumbUploader->path($formData['thumbnail']);
        $tableinstitutioninfo->addCell($this->objThumbUploader->show());
        $tableinstitutioninfo->endRow();

        $tableinstitutioninfo->startRow();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_type', 'unesco_oer') . $requiredField['type'];
        $this->_objAddDataUtil->addTitleToRow($title, 4, $tableinstitutioninfo);
        $tableinstitutioninfo->endRow();

        $tableinstitutioninfo->startRow();
        $institutionTypes = $this->objDbInstitutionType->getInstitutionTypes();
//        $this->_objAddDataUtil->addDropDownToRow(
//                'type', $institutionTypes, $formData['type'], 'type', $tableinstitutioninfo, 'id'
//        );
        $objInstitutionTypesdd = new dropdown('type');
        $objInstitutionTypesdd->addFromDB($institutionTypes, 'type', 'id', $formData['type']);
        $tableinstitutioninfo->addCell($objInstitutionTypesdd->show());

        $tableinstitutioninfo->endRow();

        $tableinstitutioninfo->startRow();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_keyword1', 'unesco_oer') . $requiredField['keyword1'];
        $this->_objAddDataUtil->addTitleToRow($title, 4, $tableinstitutioninfo);
        $tableinstitutioninfo->endRow();


        //field for the keywords
        $tableinstitutioninfo->startRow();
        $keywords['keyword1'] = $formData['keyword1'];
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_keyword1', 'unesco_oer') . $requiredField['keyword1'];
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


        $fieldsetInstitutionInfo = $this->newObject('fieldset', 'htmlelements');
        $fieldsetInstitutionInfo->setLegend('Institution information');
        $fieldsetInstitutionInfo->addContent($tableinstitutioninfo->show());
        //echo $fieldsetInstitutionInfo->show();
        // setup table and table headings with input fields
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";
        $address['address1'] = $formData['address1'];
        $table->startRow();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_address1', 'unesco_oer') . $requiredField['address1'];
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

        $table->startRow();
        $zipTitle = $this->objLanguage->languageText('mod_unesco_oer_institution_zip', 'unesco_oer') . $requiredField['zip'];
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
        $countryTitle = $this->objLanguage->languageText('mod_unesco_oer_institution_country', 'unesco_oer') . $requiredField['country'];
        $this->_objAddDataUtil->addTitleToRow($countryTitle, 4, $table);
        $table->endRow();

        $table->startRow();
        $objCountries = &$this->getObject('languagecode', 'language');
        //$table->addCell($this->objLanguage->languageText('word_country', 'system'));
        if (!empty($formData['country'])) {
            $table->addCell($objCountries->countryAlpha($formData['country']));
        } else {
            $table->addCell($objCountries->countryAlpha());
        }
        $table->endRow();

        $table->startRow();
        $city = $formData['city'];
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_city', 'unesco_oer') . $requiredField['city'];
        $this->_objAddDataUtil->addTitleToRow($title, 4, $table);
        $table->endRow();
        $table->startRow();
        $this->_objAddDataUtil->addTextInputToRow(
                'city', 0, $city, $table
        );
        $table->endRow();

        $table->startRow();
        $title = $this->objLanguage->languageText('mod_unesco_oer_institution_websitelink', 'unesco_oer') . $requiredField['websiteLink'];
        $this->_objAddDataUtil->addTitleToRow($title, 4, $table);
        $table->endRow();

        $table->startRow();
        $websiteLink = $formData['websiteLink'];
        $this->_objAddDataUtil->addTextInputToRow(
                'websiteLink', 60, $websiteLink, $table
        );

        $table->endRow();

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
        $CancelLink = new link($this->uri(array('action' => "viewInstitutions")));
        $CancelLink->link = $buttonCancel->show();


        $buttons = '<div id="institutioncontrol">' . $buttonSubmit->show() . '&nbsp;' . $CancelLink->show() . '</div>';

        //createform, add fields to it and display
        $uri = $this->uri(array(
                    'action' => $formAction, 'institutionId' => $institutionId, 'prevAction' => $prevAction, 'productID' => $onestepid , 'groupid' => $groupid));
        $form_data = new form('add_institution_ui', $uri);
        $form_data->extra = 'enctype="multipart/form-data"';
        $form_data->addToForm($displayErrors . $fieldsetInstitutionInfo->show() . '<br />' . $fieldset2->show() . '<br />' . $buttons);
        echo $form_data->show();
        
        echo $onestepid . 'fffffffffff           ';
        echo $groupid;
        ?>
    </body>
</html>
