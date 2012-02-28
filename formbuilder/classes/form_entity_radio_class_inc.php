<?php

//Use Doxygen to extract comments into a technical reference manual.
//Download Doxygen from www.doxygen.org

/*!  \class form_entity_radio
 *
 *  \brief This class models all the content and functionality for the
 * radio form element.
 * \brief It provides functionality to insert new radio buttons, create them for the
 * WYSIWYG form editor and render them in the actual construction of the form.
 * It also allows you to delete radio buttons from forms.
 *  \brief This is a child class that belongs to the form entity heirarchy.
 *  \author Salman Noor
 *  \author CNS Intern
 *  \author School of Electrical Engineering, WITS Unversity
 *  \version 1.00
 *  \date    November 3, 2010
 */
include_once 'form_entity_handler_class_inc.php';

class form_entity_radio extends form_entity_handler {
    
    /*!
     * \brief Private data member that stores a radio button object for the WYSIWYG
     * form editor.
     */
    private $objRadio;

    /*!
     * \brief This data member stores the form element identifier or ID that can
     * be used anywhere in this class.
     */
    private $radioName;

    /*!
     * \brief This data member stores three possibilities. The radio button can be
     * put in a new line or a double space can be placed before it or
     * no spaces at all.
     */
    private $breakSpaceType;

    /*!
     * \brief This data member stores all the options with their values and labels
     * for the radio form element.
     */
    private $labelnOptionArray;

    /*!
     * \brief This data member stores whether or not an option is checked by default.
     */
    private $tempWYSIWYGBoolDefaultSelected;

    /*!
     * \brief This data member stores three possibilities. The radio button can be
     * put in a new line or a double space can be placed before it or
     * no spaces at all.
     */
    private $tempWYSIWYGLayoutOption;

    /*!
     * \brief Private data member from the class \ref dbformbuilder_radio_entity that stores all
     * the properties of this class in an usable object.
     * \note This object is used to add, get or delete radio form elements.
     */
    protected $objDBRadioEntity;

    /*!
     * \brief Standard constructor that loads classes for other modules and initializes
     * and instatiates private data members.
     * \note The radio class is from the htmlelements module
     * inside the chisimba core modules.
     */
    public function init() {
        $this->loadClass('radio', 'htmlelements');
        $this->breakSpaceType = NULL;
        $this->radioName = NULL;
        $this->labelnOptionArray = array();
        $this->objDBRadioEntity = $this->getObject('dbformbuilder_radio_entity', 'formbuilder');
        $this->tempWYSIWYGBoolDefaultSelected = FALSE;
    }

    /*!
     * \brief This member function initializes some of the private data members for the
     * radio object.
     * \parm elementName A string for the form element identifier.
     */
    public function createFormElement($elementName="") {
        $this->radioName = $elementName;
        $this->objRadio = new radio($elementName);
    }

    /*!
     * \brief This member function gets the radio form element name if the private
     * data member radioName is set already.
     * \note This member function is not used in this module.
     * \return A string.
     */
    public function getWYSIWYGRadioName() {
        return $this->radioName;
    }

    /*!
     * \brief This member function contructs the html content for the form that
     * allows you to insert the radio button parameters to insert
     * a radio button option form element.
     * \note This member function uses member functions
     * from the parent class \ref form_entity_handler to
     * construct this form.
     * \param formName A string.
     * \return A constructed radio button insert form.
     */
    public function getWYSIWYGRadioInsertForm($formName) {
        $WYSIWYGRadioInsertForm = "<b>Radio HTML ID and Name Menu</b>";
        $WYSIWYGRadioInsertForm.="<div id='radioIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> ";
        $WYSIWYGRadioInsertForm.=$this->buildInsertIdForm('radio', $formName, "70") . "";
        $WYSIWYGRadioInsertForm.="</div>";
        $WYSIWYGRadioInsertForm.= "<b>Radio Label Menu</b>";
        $WYSIWYGRadioInsertForm.="<div id='radioLabelContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> ";
        $WYSIWYGRadioInsertForm.= $this->insertFormLabelOptions("radio", "labelOrientation");
        $WYSIWYGRadioInsertForm.= "</div>";
        $WYSIWYGRadioInsertForm.="<b>Radio Option Layout Menu</b>";
        $WYSIWYGRadioInsertForm.="<div id='radioLayoutContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> ";
        $WYSIWYGRadioInsertForm.=$this->buildLayoutForm('radio option', $formName, "radio") . "";
        $WYSIWYGRadioInsertForm.="</div>";
        $WYSIWYGRadioInsertForm.="<b>Insert Radio Options Menu</b>";
        $WYSIWYGRadioInsertForm.="<div id='radioOptionAndValueContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> ";
        $WYSIWYGRadioInsertForm.=$this->insertOptionAndValueForm('radio', 0) . "";
        $WYSIWYGRadioInsertForm.="</div>";

        return $WYSIWYGRadioInsertForm;
    }

    /*!
     * \brief This member function gets the radio button name if it has already
     * been saved in the database.
     * \param radioFormName A string containing the form element indentifier.
     * \return A string.
     */
    protected function getRadioName($radioFormName) {
        $radioParameters = $this->objDBRadioEntity->listRadioParameters($radioFormName);
        return $radioName = $radioParameters["0"]['radioname'];
    }

    /*!
     * \brief This member function sets the break space type private data
     * member.
     * \param breakSpaceType A string. The radio button can be
     * put in a new line or a double space can be placed before it or
     * no spaces at all.
     */
    public function setBreakSpaceType($breakSpaceType) {
        $this->breakSpaceType = $breakSpaceType;
    }

    /*!
     * \brief This member function allows you to insert a radio button option in a form with
     * a form element identifier.
     * \brief Before a new radio button option gets inserted into the database,
     * duplicate entries are checked if there is another radio button option.
     * in this same form with the same form element identifier.
     * \param formElementName A string for the form element idenifier.
     * \param option A string for the label for the option.
     * \param value A string for the actual value of the option.
     * \param defaultSelected A boolean to determine whether or not is option
     * is checked by default.
     * \param breakSpace A string to store the break space or layout for the
     * checkbox. Three possibilities exist ie new line, no space or tab before the
     * radio button option.
     * \param formElementLabel A string for the actual label text for the entire drop down
     * list form element.
     * \param labelOrientation A string that stores whether the form element label gets
     * put on top, bottom, left or right of the radio button form element.
     * \return A boolean value on successful storage of the radio button form element.
     */
    public function insertOptionandValue($formElementName, $option, $value, $defaultSelected, $layoutOption, $formElementLabel, $labelOrientation) {

        if ($this->objDBRadioEntity->checkDuplicateRadioEntry($formElementName, $value) == TRUE) {
            $this->objDBRadioEntity->insertSingle($this->radioName, $option, $value, $defaultSelected, $layoutOption, $formElementLabel, $labelOrientation);
            $this->labelnOptionArray[$value] = $option;
            $this->tempWYSIWYGBoolDefaultSelected = $defaultSelected;
            $this->tempWYSIWYGLayoutOption = $layoutOption;

            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*!
     * \brief This member function deletes an existing radio button form element with
     * the form element identifier.
     * \param formElementName A string that contains the
     * form element identifier.
     * \note This member function is protected so it is
     * only called by the \ref form_entity_handler class. To
     * delete a radio button or any other form element call the
     * deleteExisitngFormElement member function which will
     * automatically call this member function.
     * \return A boolean value for a successful delete.
     */
    protected function deleteRadioEntity($formElementName) {
        $deleteSuccess = $this->objDBRadioEntity->deleteFormElement($formElementName);
        return $deleteSuccess;
    }

    /*!
     * \brief This member function constructs a radio form element for a the actual form
     * rendering from the database.
     * \param radioName A string that contains the
     * form element identifier.
     * \note The member function is only called by the
     * parent class member function buildForm to build a form.
     * \return A constructed radio object.
     */
    protected function constructRadioEntity($radioName) {
        $radioParameters = $this->objDBRadioEntity->listRadioParameters($radioName);
//$radioName = $radioParameters["radioname"]["0"];

$constructedRadio = "";
        foreach ($radioParameters as $thisradioParameter) {

            $radioName = $thisradioParameter["radioname"];
            $radioOptionLabel = $thisradioParameter["radiooptionlabel"];
            $radioOptionValue = $thisradioParameter["radiooptionvalue"];
            $defaultValue = $thisradioParameter["defaultvalue"];
            $breakspace = $thisradioParameter["breakspace"];
            $formElementLabel = $thisradioParameter["label"];
            $labelOrientation = $thisradioParameter["labelorientation"];

            $radioUnderConstruction = new radio($radioName);
            $radioUnderConstruction->addOption($radioOptionValue, $radioOptionLabel);
            if ($defaultValue == TRUE) {
                $radioUnderConstruction->setSelected($radioOptionValue);
            }

            $currentConstructedRadio = $this->getBreakSpaceType($breakspace) . $radioUnderConstruction->show();
            $constructedRadio .=$currentConstructedRadio;
        }

        if ($formElementLabel == NULL) {
            return "<div id='" . $radioName . "'>" . $constructedRadio . "</div>";
        } else {
            $radioLabel = new label($formElementLabel, $radioName);
            switch ($labelOrientation) {
                case 'top':
                    return "<div id='" . $radioName . "'><div class='radioLabelContainer' style='clear:both;'> " . $radioLabel->show() . "</div>"
                    . "<div class='radioContainer'style='clear:left;'> " . $constructedRadio . "</div></div>";
                    break;
                case 'bottom':
                    return "<div id='" . $radioName . "'><div class='radioContainer'style='clear:both;'> " . $constructedRadio . "</div>" .
                    "<div class='radioLabelContainer' style='clear:both;'> " . $radioLabel->show() . "</div></div>";
                    break;
                case 'left':
                    return "<div id='" . $radioName . "'><div style='clear:both;overflow:auto;'>" . "<div class='radioLabelContainer' style='float:left;clear:left;'> " . $radioLabel->show() . "</div>"
                    . "<div class='radioContainer'style='float:left; clear:right;'> " . $constructedRadio . "</div></div></div>";
                    break;
                case 'right':
                    return "<div id='" . $radioName . "'><div style='clear:both;overflow:auto;'>" . "<div class='radioContainer'style='float:left;clear:left;'> " . $constructedRadio . "</div>" .
                    "<div class='radioLabelContainer' style='float:left;clear:right;'> " . $radioLabel->show() . "</div></div></div>";
                    break;
            }
        }
    }

    /*!
     * \brief This member function constructs a radio for a the WYSIWYG form editor.
     * \note The member function uses the private data members
     * that are already initialized by the createFormElement and the insertOptionandValue member
     *  functions which should be always called first to create a form element in the database before
     * displaying it with this member function.
     * \return A constructed radio button form element.
     */
    private function buildWYSIWYGRadioEntity() {

        $radioParameters = $this->objDBRadioEntity->listRadioParameters($this->radioName);
        $constructedRadio = "";
        foreach ($radioParameters as $thisradioParameter) {
            $radioName = $thisradioParameter["radioname"];
            $radioOptionLabel = $thisradioParameter["radiooptionlabel"];
            $radioOptionValue = $thisradioParameter["radiooptionvalue"];
            $defaultValue = $thisradioParameter["defaultvalue"];
            $breakspace = $thisradioParameter["breakspace"];
            $formElementLabel = $thisradioParameter["label"];
            $labelOrientation = $thisradioParameter["labelorientation"];
            $this->objRadio = new radio($radioName);
            $this->objRadio->addOption($radioOptionValue, $radioOptionLabel);
            if ($defaultValue == TRUE) {
                $this->objRadio->setSelected($radioOptionValue);
            }



            $currentConstructedRadio = $this->getBreakSpaceType($breakspace) . $this->objRadio->show();
            $constructedRadio .=$currentConstructedRadio;
        }



        if ($formElementLabel == NULL) {
            return "<div id='" . $this->radioName . "'>" . $constructedRadio . "</div>";
        } else {
            $radioLabel = new label($formElementLabel, $this->radioName);
            switch ($labelOrientation) {
                case 'top':
                    return "<div id='" . $this->radioName . "'><div class='radioLabelContainer' style='clear:both;'> " . $radioLabel->show() . "</div>"
                    . "<div class='radioContainer'style='clear:left;'> " . $constructedRadio . "</div></div>";
                    break;
                case 'bottom':
                    return "<div id='" . $this->radioName . "'><div class='radioContainer'style='clear:both;'> " . $constructedRadio . "</div>" .
                    "<div class='radioLabelContainer' style='clear:both;'> " . $radioLabel->show() . "</div></div>";
                    break;
                case 'left':
                    return "<div id='" . $this->radioName . "'><div style='clear:both;overflow:auto;'>" . "<div class='radioLabelContainer' style='float:left;clear:left;'> " . $radioLabel->show() . "</div>"
                    . "<div class='radioContainer'style='float:left; clear:right;'> " . $constructedRadio . "</div></div></div>";
                    break;
                case 'right':
                    return "<div id='" . $this->radioName . "'><div style='clear:both;overflow:auto;'>" . "<div class='radioContainer'style='float:left;clear:left;'> " . $constructedRadio . "</div>" .
                    "<div class='radioLabelContainer' style='float:left;clear:right;'> " . $radioLabel->show() . "</div></div></div>";
                    break;
            }
        }
//dead code that creates a radio without the labels
//return $constructeddd;
//  foreach ($this->labelnOptionArray as $radioValue => $radioOptionLabel) {
//
//      $this->objRadio->addOption($radioValue,$radioOptionLabel);
//        if ($this->tempWYSIWYGBoolDefaultSelected==TRUE)
//  {
//      $this->objRadio->setSelected($radioValue);
//  }
//  }
//               return $this->getBreakSpaceType($this->tempWYSIWYGLayoutOption).$this->objRadio->show();
    }

    /*!
     * \brief This member function allows you to get a radio button for a the WYSIWYG form editor
     * that is already saved in the database.
     * \return A constructed radio button.
     */
    public function showWYSIWYGRadioEntity() {
        return $this->buildWYSIWYGRadioEntity();
    }

}

?>
