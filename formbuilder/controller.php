<?php

class formbuilder extends controller {

    public $objlanguage;

    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = &$this->getObject('user', 'security');
    }

    public function dispatch() {
//Get action from query string and set default to view
        $action = $this->getParam('action', 'home');
//Convert the action into a method
        $method = $this->__getMethod($action);
//Return the template determined by the method resulting from action
        return $this->$method();
    }

    private function __validAction(& $action) {
        if (method_exists($this, "__" . $action)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function __getMethod(& $action) {
        if ($this->__validAction($action)) {
            return "__" . $action;
        } else {
            return "__actionError";
        }
    }

    private function __actionError() {
//Get action from query string
        $action = $this->getParam('action');
        $this->setVar('str', "<h3>"
                . $this->objLanguage->languageText("phrase_unrecognizedaction")
                . ": " . $action . "</h3>");
        return 'dump_tpl.php';
    }

    private function __graphtest() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
//      $this->setPageTemplate('ajax_template.php');
        return "graphtest.php";
    }

    private function __test() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        return "test1.php";
    }

    private function __newtest() {
        $objFlashGraphData = $this->newObject('flashgraphdata', 'utilities');
        $objFlashGraphData->graphType = 'bar';
        $objFlashGraphData->setupXAxisLabels(array('Jan', 'Feb', 'March'));
        $objFlashGraphData->setupYAxis('Rainy Days', NULL, NULL, 10, 5);
        $objFlashGraphData->addDataSet(array(4, 5, 4), '#3334AD', 50, 'bar', 'Cape Town');
        $objFlashGraphData->addDataSet(array(6, 7, 2), '#00ff00', 50, 'glassbar', 'Johannesburg');
        $objFlashGraphData->addDataSet(array(1, 4, 3), '#9900CC', 50, 'sketchbar', 'Durban');
        return $test = $objFlashGraphData->show();
    }

    private function __home() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        return 'home.php';
    }

    private function __addFormParameters() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "add_edit_form_parameters.php";
    }

    private function __editFormParameters() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "add_edit_form_parameters.php";
    }

    private function __updateExistingFormParameters() {
        $currentformNumber = $this->getParam('currentformNumber');
        $formLabel = $this->getParam('formLabel', NULL);
        $formEmail = $this->getParam('formEmail', NULL);
        $submissionOption = $this->getParam('formSubmissionRadio', NULL);
        $formDescription = $this->getParam('formCaption', NULL);

        $objDBUpdateFormParameters = $this->getObject('dbformbuilder_form_list', 'formbuilder');
        $objDBUpdateFormParameters->updateSingle($currentformNumber, $formLabel, $formDescription, $formEmail, $submissionOption);
        $this->setVar('JQUERY_VERSION', '1.4.2');
        return $this->nextAction("listAllForms");
    }

    private function __addNewFormParameters() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "add_new_form_parameters.php";
    }

    private function __buildCurrentForm() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $formNumber = $this->getParam("formNumber");
        $this->setVar('formNumber', $formNumber);
        return "construct_current_form.php";
    }

    private function __buildAReadOnlyForm() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "construct_a_read_only_form.php";
    }

    private function __listCurrentFormOptions() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "list_current_form_options.php";
    }

    private function __listCurrentFormGeneralandPublishingDetails() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "list_current_form_general_and_publish_details.php";
    }

    private function __listCurrentFormPublishingData() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "list_current_form_publishing_data.php";
    }

    private function __addEditFormPublishingData() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "add_edit_form_publishing_data.php";
    }

    private function __listAllForms() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        return "list_all_forms.php";
    }

    private function __moduleHelp() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        return "help_main.php";
    }

    private function __searchAllForms() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        return "list_all_forms.php";
    }

    private function __listAllFormsPaginated() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "list_all_forms_paginated.php";
    }

    private function __designWYSIWYGForm() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        return 'form_editor.php';
        return 'form_element_editor.php';
    }

    private function __updateWYSIWYGFormElementOrder() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "update_form_element_order.php";
    }

    private function __deleteForm() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "delete_form.php";
    }

    private function __deleteWYSIWYGFormElement() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "delete_form_element.php";
    }

    private function __deleteAllFormSubmissions() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "delete_form_submissions.php";
    }

    private function __createNewFormElement() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "create_new_form_element.php";
    }

    private function __insertFormElement() {
        $this->setPageTemplate('ajax_template.php');
        return "insert_form_element.php";
    }

    private function __addEditRadioEntity() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "add_edit_radio_entity.php";
    }

    private function __addEditCheckboxEntity() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "add_edit_checkbox_entity.php";
    }

    private function __addEditDropdownEntity() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "add_edit_dropdown_entity.php";
    }

    private function __addEditLabelEntity() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "add_edit_label_entity.php";
    }

    private function __addEditHTMLHeadingEntity() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "add_edit_HTMLheading_entity.php";
    }

    private function __addEditDatePickerEntity() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "add_edit_datepicker_entity.php";
    }

    private function __addEditTextInput() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "add_edit_textinput_entity.php";
    }

    private function __addEditButton() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "add_edit_button_entity.php";
    }

    private function __addEditTextArea() {
        $this->setPageTemplate('ajax_template.php');
        $this->setVar('JQUERY_VERSION', '1.4.2');
        return "add_edit_textarea_entity.php";
    }

    private function __addEditMultiSelectableDropdownEntity() {
        $this->setPageTemplate('ajax_template.php');
        $this->setVar('JQUERY_VERSION', '1.4.2');
        return "add_edit_multiselect_dropdown_entity.php";
    }

    private function __editWYSIWYGForm() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        return "form_editor.php";
        return "form_element_editor.php";
    }

    private function __saveSubmittedFormDataInDatabase() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $formNumber = $this->getParam('formNumber');
        $formElementNameList = $this->getParam('formElementNameList');
        $formElementTypeList = $this->getParam('formElementTypeList');
        $formElementValuesArray = array();

        $objDBFormSubmitResults = $this->getObject('dbformbuilder_submit_results', 'formbuilder');
        $submitNumber = $objDBFormSubmitResults->getNextSubmitNumber($formNumber);
        $formElementNameArray = explode(",", $formElementNameList);
        $formElementTypeArray = explode(",", $formElementTypeList);

        $lengthOfFormElementNameArray = count($formElementNameArray);
        $lengthOfFormElementTypeArray = count($formElementTypeArray);

        if ($lengthOfFormElementNameArray == $lengthOfFormElementTypeArray) {
            for ($i = 0; $i <= ($lengthOfFormElementNameArray - 1); $i++) {
//echo  $formElementNameArray[$i]."&nbsp;&nbsp;&nbsp;&nbsp;".$formElementTypeArray[$i]."<br>";
                $formElementType = $formElementTypeArray[$i];
                $formElementName = $formElementNameArray[$i];
                $formElementValue = $this->getParam($formElementName);
                switch ($formElementType) {
                    case 'checkbox';
                        if ($formElementValue == NULL) {
                            $formElementValue = "off";
                        }
                        $formElementValuesArray[] = $formElementValue;
//$objDBFormSubmitResults->insertSingle($formNumber,$submitNumber, $formElementType, $formElementName,$formElementValue);
                        break;
                    case 'datepicker';
                    case 'dropdown';
                    case 'multiselectable_dropdown';
                    case 'radio';
                    case 'text_area';
                    case 'text_input';
                        if ($formElementValue == NULL) {
                            $this->setErrorMessage("The " . $formElementType . " named " . $formElementName . " cannot be NULL. Please complete all form fields.", $formElementName);
// $this->putMessages();
                            $this->setVar('formNumber', $formNumber);
                            return "construct_current_form.php";
//  $this->nextAction("buildCurrentForm",array('formNumber'=>$formNumber));
                        }
                        $formElementValuesArray[] = $formElementValue;
// $objDBFormSubmitResults->insertSingle($formNumber,$submitNumber, $formElementType, $formElementName,$formElementValue);
                        break;
                    default;
                        $this->setErrorMessage("Internal Error. The form element type \"" . $formElementType . "\" is not registered to be stored in a database.", $formElementType);
                        break;
                }
            }
//saveFormSubmissionDataInDatabase($lengthOfFormElementNameArray,$formElementTypeArray,$formElementNameArray,$formElementValuesArray);
            for ($i = 0; $i <= ($lengthOfFormElementNameArray - 1); $i++) {
//echo  $formElementNameArray[$i]."&nbsp;&nbsp;&nbsp;&nbsp;".$formElementTypeArray[$i]."<br>";
                $formElementType = $formElementTypeArray[$i];
                $formElementName = $formElementNameArray[$i];
                $formElementValue = $formElementValuesArray[$i];



                $objDBFormSubmitResults->insertSingle($formNumber, $submitNumber, $formElementType, $formElementName, $formElementValue);
            }
        } else {
            $this->setErrorMessage("Internal Error. Number of form element types and form element names do not match.");
        }

        $this->setVar('formNumber', $formNumber);
        $this->setVarByRef('test', $test);
        return $this->nextAction("formSuccessfulSubmissionIntoDataBase", array('formNumber' => $formNumber, 'submitNumber' => $submitNumber));

    }

    private function __sendSubmittedFormDataViaEmail() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $formNumber = $this->getParam('formNumber');
        $formLabel = $this->getParam('formLabel');
        $formElementTypeList = $this->getParam('formElementTypeList');
        $formElementNameList = $this->getParam('formElementNameList');
        $formEmail = $this->getParam('formEmail');
        $userid = $this->objUser->userId();
        $nameOfSubmitter = $this->objUser->fullname($userid);
        $emailOfSubmitter = $this->objUser->email($userid);

        $objDBFormSubmitResults = $this->getObject('dbformbuilder_form_list', 'formbuilder');
        $submitTime = $objDBFormSubmitResults->getSubmitTime();

        $emailMessageContent = "<h1>Submission Results of Form Name: <b>" . $formLabel . "</b></h1>";
        $emailMessageContent .= "<b>Name of Person Submitting Form:  </b>" . $nameOfSubmitter . "<br>";
        $emailMessageContent .= "<b>Email Address of Person Submitting Form:  </b>" . $emailOfSubmitter . "<br>";
        $emailMessageContent .= "<b>Time of Submission:  </b>" . $submitTime . "<br>" . "<br>";
        $emailMessageContent .= "<h2>Results</h2>";


        $formElementNameArray = explode(",", $formElementNameList);
        $formElementTypeArray = explode(",", $formElementTypeList);

        $lengthOfFormElementNameArray = count($formElementNameArray);
        $lengthOfFormElementTypeArray = count($formElementTypeArray);

        if ($lengthOfFormElementNameArray == $lengthOfFormElementTypeArray) {
            for ($i = 0; $i <= ($lengthOfFormElementNameArray - 1); $i++) {
//echo  $formElementNameArray[$i]."&nbsp;&nbsp;&nbsp;&nbsp;".$formElementTypeArray[$i]."<br>";
                $formElementType = $formElementTypeArray[$i];
                $formElementName = $formElementNameArray[$i];
                $formElementValue = $this->getParam($formElementName);
                switch ($formElementType) {
                    case 'checkbox';
                        if ($formElementValue == NULL) {
                            $formElementValue = "off";
                        }
                        $formElementValuesArray[] = $formElementValue;
//$objDBFormSubmitResults->insertSingle($formNumber,$submitNumber, $formElementType, $formElementName,$formElementValue);
                        break;
                    case 'datepicker';
                    case 'dropdown';
                    case 'multiselectable_dropdown';
                    case 'radio';
                    case 'text_area';
                    case 'text_input';
                        if ($formElementValue == NULL) {
                            $this->setErrorMessage("The " . $formElementType . " named " . $formElementName . " cannot be NULL. Please complete all form fields.", $formElementName);
                            $this->setVar('formNumber', $formNumber);
                            return "construct_current_form.php";
                        }
                        $formElementValuesArray[] = $formElementValue;
                        break;
                    default;
                        $this->setErrorMessage("Internal Error. The form element type \"" . $formElementType . "\" is not registered to be stored in a database.", $formElementType);
                        break;
                }
            }
            for ($i = 0; $i <= ($lengthOfFormElementNameArray - 1); $i++) {
//echo  $formElementNameArray[$i]."&nbsp;&nbsp;&nbsp;&nbsp;".$formElementTypeArray[$i]."<br>";
                $formElementType = $formElementTypeArray[$i];
                $formElementName = $formElementNameArray[$i];
                $formElementValue = $formElementValuesArray[$i];
                $emailMessageContent .= "<b>" . $formElementName . " (Form Element Type : " . $formElementType . ") : </b>" . $formElementValue . "<br>";
//$objDBFormSubmitResults->insertSingle($formNumber,$submitNumber, $formElementType, $formElementName,$formElementValue);
            }
// $this->setErrorMessage("Form Successfully Submitted.");
        } else {
            $this->setErrorMessage("Internal Error. Number of form element types and form element names do not match.");
        }




        $objMailer = $this->getObject('email', 'mail');
        $objMailer->setValue('to', $formEmail);
        $objMailer->setValue('from', 'noreply@formbuilder.wits.ac.za');
        $objMailer->setValue('fromName', 'Wits CCMS Form Builder');
        $objMailer->setValue('subject', "Submission of Form By " . $nameOfSubmitter . " at Time " . $submitTime);
        $objMailer->setValue('body', $emailMessageContent);
//$objMailer->setValue('cc', '');
//$objMailer->setValue(bcc, '');
//$objMailer->attach('/var/www/app/config/config_inc.php', 'config_inc.php');
//$objMailer->attach('/var/www/app/index.php');
        if ($objMailer->send(true)) {
            $mailSuccess = "Success";
        } else {
            $mailSuccess = "Failiure";
        }
        if ($mailSuccess == "Success") {
            return $this->nextAction("formSuccessfulSubmissionToEmail", array('formNumber' => $formNumber, 'submitNumber' => $submitNumber, 'mailSuccess' => $mailSuccess));
        } else {
            return $this->nextAction("errorInSubmissionToEmail", array('mailSuccess' => $mailSuccess));
        }

    }

    private function __saveandsendSubmittedFormDataInDatabaseandViaEmail() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $formNumber = $this->getParam('formNumber');
        $formLabel = $this->getParam('formLabel');
        $formElementTypeList = $this->getParam('formElementTypeList');
        $formElementNameList = $this->getParam('formElementNameList');
        $formEmail = $this->getParam('formEmail');
        $userid = $this->objUser->userId();
        $nameOfSubmitter = $this->objUser->fullname($userid);
        $emailOfSubmitter = $this->objUser->email($userid);

        $objDBFormSubmitResults = $this->getObject('dbformbuilder_submit_results', 'formbuilder');
        $submitNumber = $objDBFormSubmitResults->getNextSubmitNumber($formNumber);

        $objDBFormList = $this->getObject('dbformbuilder_form_list', 'formbuilder');
        $submitTime = $objDBFormList->getSubmitTime();


        $emailMessageContent = "<h1>Submission Results of Form Name: <b>" . $formLabel . "</b></h1>";
        $emailMessageContent .= "<b>Name of Person Submitting Form:  </b>" . $nameOfSubmitter . "<br>";
        $emailMessageContent .= "<b>Email Address of Person Submitting Form:  </b>" . $emailOfSubmitter . "<br>";
        $emailMessageContent .= "<b>Time of Submission:  </b>" . $submitTime . "<br>" . "<br>";
        $emailMessageContent .= "<h2>Results</h2>";


        $formElementNameArray = explode(",", $formElementNameList);
        $formElementTypeArray = explode(",", $formElementTypeList);

        $lengthOfFormElementNameArray = count($formElementNameArray);
        $lengthOfFormElementTypeArray = count($formElementTypeArray);

        if ($lengthOfFormElementNameArray == $lengthOfFormElementTypeArray) {
            for ($i = 0; $i <= ($lengthOfFormElementNameArray - 1); $i++) {
//echo  $formElementNameArray[$i]."&nbsp;&nbsp;&nbsp;&nbsp;".$formElementTypeArray[$i]."<br>";
                $formElementType = $formElementTypeArray[$i];
                $formElementName = $formElementNameArray[$i];
                $formElementValue = $this->getParam($formElementName);
                switch ($formElementType) {
                    case 'checkbox';
                        if ($formElementValue == NULL) {
                            $formElementValue = "off";
                        }
                        $formElementValuesArray[] = $formElementValue;
//$objDBFormSubmitResults->insertSingle($formNumber,$submitNumber, $formElementType, $formElementName,$formElementValue);
                        break;
                    case 'datepicker';
                    case 'dropdown';
                    case 'multiselectable_dropdown';
                    case 'radio';
                    case 'text_area';
                    case 'text_input';
                        if ($formElementValue == NULL) {
                            $this->setErrorMessage("The " . $formElementType . " named " . $formElementName . " cannot be NULL. Please complete all form fields.", $formElementName);
                            $this->setVar('formNumber', $formNumber);
                            return "construct_current_form.php";
                        }
                        $formElementValuesArray[] = $formElementValue;
                        break;
                    default;
                        $this->setErrorMessage("Internal Error. The form element type \"" . $formElementType . "\" is not registered to be stored in a database.", $formElementType);
                        break;
                }
            }
            for ($i = 0; $i <= ($lengthOfFormElementNameArray - 1); $i++) {
//echo  $formElementNameArray[$i]."&nbsp;&nbsp;&nbsp;&nbsp;".$formElementTypeArray[$i]."<br>";
                $formElementType = $formElementTypeArray[$i];
                $formElementName = $formElementNameArray[$i];
                $formElementValue = $formElementValuesArray[$i];
                $emailMessageContent .= "<b>" . $formElementName . " (Form Element Type : " . $formElementType . ") : </b>" . $formElementValue . "<br>";
                $objDBFormSubmitResults->insertSingle($formNumber, $submitNumber, $formElementType, $formElementName, $formElementValue);
            }
// $this->setErrorMessage("Form Successfully Submitted.");
        } else {
            $this->setErrorMessage("Internal Error. Number of form element types and form element names do not match.");
        }




        $objMailer = $this->getObject('email', 'mail');
        $objMailer->setValue('to', $formEmail);
        $objMailer->setValue('from', 'noreply@formbuilder.wits.ac.za');
        $objMailer->setValue('fromName', 'Wits CCMS Form Builder');
        $objMailer->setValue('subject', "Submission of Form By " . $nameOfSubmitter . " at Time " . $submitTime);
        $objMailer->setValue('body', $emailMessageContent);
//$objMailer->setValue('cc', '');
//$objMailer->setValue(bcc, '');
//$objMailer->attach('/var/www/app/config/config_inc.php', 'config_inc.php');
//$objMailer->attach('/var/www/app/index.php');
        if ($objMailer->send(true)) {
            $mailSuccess = "Success";
        } else {
            $mailSuccess = "Failiure";
        }

        if ($mailSuccess == "Success") {
            return $this->nextAction("formSuccessfulSubmissionToEmailandDatabase", array('formNumber' => $formNumber, 'submitNumber' => $submitNumber, 'mailSuccess' => $mailSuccess));
        } else {
            return $this->nextAction("errorInSubmissionToEmailandDatabase", array('mailSuccess' => $mailSuccess));
        }
    }

    private function __formSuccessfulSubmissionIntoDataBase() {
        $formNumber = $this->getParam('formNumber');
        $submitNumber = $this->getParam('submitNumber');

        $this->setVar('formNumber', $formNumber);
        $this->setVar('submitNumber', $submitNumber);

        return "form_successful_submission.php";
    }

    private function __formSuccessfulSubmissionToEmail() {
        $mailSuccess = $this->getParam('mailSuccess');
        $formNumber = $this->getParam('formNumber');
        $submitNumber = $this->getParam('submitNumber');
        $this->setVar('formNumber', $formNumber);
        $this->setVar('submitNumber', $submitNumber);
        $this->setVar('mailSuccess', $mailSuccess);
        return "form_successful_submission.php";
    }

    private function __successfulSubmissionDivertToNextAction() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        return "form_successful_submission_diverter.php";
    }

    private function __errorInSubmissionToEmail() {
        $mailSuccess = $this->getParam('mailSuccess');
        $this->setVar('mailSuccess', $mailSuccess);
        return "form_successful_submission.php";
    }

    private function __formSuccessfulSubmissionToEmailandDatabase() {
        $mailandDatabaseSuccess = $this->getParam('mailSuccess');
        $formNumber = $this->getParam('formNumber');
        $submitNumber = $this->getParam('submitNumber');
        $this->setVar('formNumber', $formNumber);
        $this->setVar('submitNumber', $submitNumber);
        $this->setVar('mailandDatabaseSuccess', $mailandDatabaseSuccess);
        return "form_successful_submission.php";
    }

    private function __errorInSubmissionToEmailandDatabase() {
        $mailSuccess = $this->getParam('mailSuccess');

        $this->setVar('mailSuccess', $mailSuccess);

        return "form_successful_submission.php";
    }

    private function __viewSubmittedresults() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $formNumber = $this->getParam('formNumber');

        $this->setVar('formNumber', $formNumber);

        return "view_submitted_results.php";
    }

    private function __viewSubmitNumberResult() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
        $submitNumber = $this->getParam('submitNumber');

        $this->setVar('submitNumber', $submitNumber);
        return "view_selected_submit_result.php";
    }

    private function __getMorePaginatedSubmitResults() {
        $this->setVar('JQUERY_VERSION', '1.4.2');
        $this->setPageTemplate('ajax_template.php');
//            $paginationRequestNumber = $this->getParam('paginationRequestNumber');
//
//$this->setVar('paginationRequestNumber', $paginationRequestNumber);
        return "view_submitted_results_paginated.php";
    }

}
?>
