<?php

//Use Doxygen to extract comments into a technical reference manual.
//Download Doxygen from www.doxygen.org

/*!  \class form_entity_checkbox
 *
 *  \brief This class models all the content and functionality for the
 * checkbox form element.
 * \brief It provides functionality to insert new checkboxes, create them for the
 * WYSIWYG form editor and render them in the actual construction of the form.
 * It also allows you to delete checkboxes from forms.
 *  \brief This is a child class that belongs to the form entity heirarchy.
 *  \author Salman Noor
 *  \author CNS Intern
 *  \author School of Electrical Engineering, WITS Unversity
 *  \version 1.00
 *  \date    November 3, 2010
 */
include_once 'form_entity_handler_class_inc.php';

class form_entity_checkbox extends form_entity_handler {
    
    private $formNumber;
    /*!
     * \brief This data member stores the form element identifier or ID that can
     * be used anywhere in this class.
     */
    private $checkboxName;

    /*!
     * \brief This data member stores the html name of the checkbox.
     */
    private $checkboxValue;

    /*!
     * \brief This data member stores the label for the checkbox
     */
    private $checkboxLabel;

    /*!
     * \brief This data member stores a boolean whether or not the checkbox is
     * checked by default.
     */
    private $isCheckedBoolean;

    /*!
     * \brief This data member stores three possibilities. The checkbox can be
     * put in a new line or a double space can be placed before it or
     * no spaces at all.
     */
    private $checkboxLayoutOption;

    /*!
     * \brief Private data member that stores a checkbox object for the WYSIWYG
     * form editor.
     */
    protected $objcheckBoxEntity;

    /*!
     * \brief Private data member from the class \ref dbformbuilder_checkbox_entity that stores all
     * the properties of this class in an usable object.
     * \note This object is used to add, get or delete checkbox form elements.
     */
    private $objDBcheckboxEntity;

    /*!
     * \brief Standard constructor that loads classes for other modules and initializes
     * and instatiates private data members.
     * \note The checkbox and label classes are from the htmlelements module
     * inside the chisimba core modules.
     */
    public function init() {
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->objDBcheckboxEntity = $this->getObject('dbformbuilder_checkbox_entity', 'formbuilder');
    }

    /*!
     * \brief This member function allows you to insert a new checkbox in a form with
     * a form element identifier.
     * \brief Before a new checkbox gets inserted into the database,
     * duplicate entries are checked if there is another checkbox
     * with the same form element identifier.
     * \param checkboxName A string for the form element identifier.
     * \param checkboxValue A string for the actual html name for the checkbox.
     * \param checkboxLabel A string.
     * \param isChecked A boolean to check whether or not the checkbox is checked
     * by default.
     * \param breakSpace A string to store the break space or layout for the
     * checkbox. Three possibilities exist ie new line, no space or tab before the
     * check box.
     * \param formElementLabel A string that stores the label for whole form element.
     * \param labelLayout A string that stores whether the form element label gets
     * put on top, bottom, left or right of the checkbox.
     * \return A boolean value on successful storage of the checkbox form element.
     */
    public function createFormElement($formNumber,$checkboxName, $checkboxValue, $checkboxLabel, $isChecked, $breakSpace, $formElementLabel, $labelLayout) {

        if ($this->objDBcheckboxEntity->checkDuplicateCheckboxEntry($formNumber,$checkboxName, $checkboxValue) == TRUE) {
            $this->formNumber = $formNumber;
            $this->checkboxValue = $checkboxValue;
            $this->checkboxName = $checkboxName;
            $this->checkboxLabel = $checkboxLabel;
            $this->isCheckedBoolean = $isChecked;
            $this->checkboxLayoutOption = $breakSpace;

            $this->objDBcheckboxEntity->insertSingle($formNumber,$checkboxName, $checkboxValue, $checkboxLabel, $isChecked, $breakSpace, $formElementLabel, $labelLayout);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*!
     * \brief This member function gets the checkbox form element name if the private
     * data member checkboxName is set already.
     * \note This member function is not used in this module.
     * \return A string.
     */
    public function getWYSIWYGCheckboxName() {
        return $this->checkboxName;
    }

    /*!
     * \brief This member function gets the checkbox name if it has already
     * been saved in the database.
     * \param checkboxFormName A string containing the form element indentifier.
     * \return A string.
     */
    protected function getCheckboxName($formNumber,$checkboxFormName) {
        $checkboxParameters = $this->objDBcheckboxEntity->listCheckboxParameters($formNumber,$checkboxFormName);
        $checkboxNameArray = array();
        foreach ($checkboxParameters as $thisCheckboxParameter) {
            $checkboxValue = $thisCheckboxParameter["checkboxvalue"];
            $checkboxNameArray[] = $checkboxValue;
        }
        return $checkboxNameArray;
    }

    /*!
     * \brief This member function contructs the html content for the form that
     * allows you to insert the checkbox option parameters to insert
     * a checkbox form element.
     * \note This member function uses member functions
     * from the parent class \ref form_entity_handler to
     * construct this form.
     * \param formName A string.
     * \return A constructed checkbox insert form.
     */
    public function getWYSIWYGCheckBoxInsertForm($formName) {
        $WYSIWYGCheckBoxInsertForm = "<b>Check Box HTML ID and Name Menu</b>";
        $WYSIWYGCheckBoxInsertForm.="<div id='checkboxIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> ";
        $WYSIWYGCheckBoxInsertForm.= $this->buildInsertIdForm('checkbox', $formName, "70") . "";
        $WYSIWYGCheckBoxInsertForm.="</div>";
        $WYSIWYGCheckBoxInsertForm.= "<b>Check Box Label Menu</b>";
        $WYSIWYGCheckBoxInsertForm.="<div id='checkboxLabelContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> ";
        $WYSIWYGCheckBoxInsertForm.= $this->insertFormLabelOptions("checkbox", "labelOrientation");
        $WYSIWYGCheckBoxInsertForm.= "</div>";
        $WYSIWYGCheckBoxInsertForm.= "<b>Check Box Option Layout Menu</b>";
        $WYSIWYGCheckBoxInsertForm.= "<div id='checkboxLayoutContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> ";
        $WYSIWYGCheckBoxInsertForm.= $this->buildLayoutForm('checkbox', $formName, "checkbox") . "";
        $WYSIWYGCheckBoxInsertForm.= "</div>";
        $WYSIWYGCheckBoxInsertForm.="<b>Insert Check Box Options Menu</b>";
        $WYSIWYGCheckBoxInsertForm.="<div id='checkboxOptionAndValueContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> ";
        $WYSIWYGCheckBoxInsertForm.= $this->insertOptionAndValueForm('checkbox', 0) . "";
        $WYSIWYGCheckBoxInsertForm.= "</div>";

        return $WYSIWYGCheckBoxInsertForm;
    }

    /*!
     * \brief This member function deletes an existing checkbox form element with
     * the form element identifier.
     * \param formElementName A string that contains the
     * form element identifier.
     * \note This member function is protected so it is
     * only called by the \ref form_entity_handler class. To
     * delete a checkbox or any other form element call the
     * deleteExisitngFormElement member function which will
     * automatically call this member function.
     * \return A boolean value for a successful delete.
     */
    protected function deleteCheckBoxEntity($formNumber,$formElementName) {
        $deleteSuccess = $this->objDBcheckboxEntity->deleteFormElement($formNumber,$formElementName);
        return $deleteSuccess;
    }

    /*!
     * \brief This member function constructs a checkbox for a the actual form
     * rendering from the database.
     * \param checkboxName A string that contains the
     * form element identifier.
     * \note The member function is only called by the
     * parent class member function buildForm to build a form.
     * \return A constructed checkbox.
     */
    protected function constructCheckBoxEntity($checkboxName,$formNumber) {
        $checkboxParameters = $this->objDBcheckboxEntity->listCheckboxParameters($formNumber,$checkboxName);
        $constructedCheckbox = "";
        foreach ($checkboxParameters as $thisCheckboxParameter) {

//$checkboxName = $thisCheckboxParameter["checkboxname"];
            $checkboxValue = $thisCheckboxParameter["checkboxvalue"];
            $checkboxLabel = $thisCheckboxParameter["checkboxlabel"];
            $isChecked = $thisCheckboxParameter["ischecked"];
            $breakspace = $thisCheckboxParameter["breakspace"];
            $checkBoxLabel = $thisCheckboxParameter["label"];
            $labelOrientation = $thisCheckboxParameter["labelorientation"];

            $checkboxUnderConstruction = new checkbox($checkboxValue, $checkboxLabel, $isChecked);
           // $labelUnderConstruction = new label($checkboxLabel, $checkboxValue);
            $currentConstructedCheckbox = $this->getBreakSpaceType($breakspace) . $checkboxUnderConstruction->show() ;
            $constructedCheckbox .=$currentConstructedCheckbox;
        }
        if ($checkBoxLabel == NULL) {
            return "<div id='" . $checkboxName . "'>" . $constructedCheckbox . "</div>";
        } else {
            $checkboxLabel = new label($checkBoxLabel, $checkboxValue);
            switch ($labelOrientation) {
                case 'top':
                    return "<div id='" . $checkboxName . "'><div class='checkboxLabelContainer' style='clear:both;'> " . $checkboxLabel->show() . "</div>"
                    . "<div class='checkboxContainer'style='clear:left;'> " . $constructedCheckbox . "</div></div>";
                    break;
                case 'bottom':
                    return "<div id='" . $checkboxName . "'><div class='checkboxContainer'style='clear:both;'> " . $constructedCheckbox . "</div>" .
                    "<div class='checkboxLabelContainer' style='clear:both;'> " . $checkboxLabel->show() . "</div></div>";
                    break;
                case 'left':
                    return "<div id='" . $checkboxName . "'><div style='clear:both;overflow:auto;'>" . "<div class='checkboxLabelContainer' style='float:left;clear:left;'> " . $checkboxLabel->show() . "</div>"
                    . "<div class='checkboxContainer'style='float:left; clear:right;'> " . $constructedCheckbox . "</div></div></div>";
                    break;
                case 'right':
                    return "<div id='" . $checkboxName . "'><div style='clear:both;overflow:auto;'>" . "<div class='checkboxContainer'style='float:left;clear:left;'> " . $constructedCheckbox . "</div>" .
                    "<div class='checkboxLabelContainer' style='float:left;clear:right;'> " . $checkboxLabel->show() . "</div></div></div>";
                    break;
            }
        }
    }

    /*!
     * \brief This member function constructs a checkbox for a the WYSIWYG form editor.
     * \note The member function uses the private data members
     * that are already initialized by the createFormElement member function which
     * should be always called first to create a form element in the database before
     * displaying it with this member function.
     * \return A constructed checkbox form element.
     */
    private function buildWYSIWYGCheckboxEntity() {

        $checkboxParameters = $this->objDBcheckboxEntity->listCheckboxParameters($this->formNumber,$this->checkboxName);
        $constructedCheckbox = "";
        foreach ($checkboxParameters as $thisCheckboxParameter) {
//Store the values of the array in variables
//$checkboxName = $thisCheckboxParameter["checkboxname"];
            $checkboxValue = $thisCheckboxParameter["checkboxvalue"];
            $checkboxLabel = $thisCheckboxParameter["checkboxlabel"];
            $isChecked = $thisCheckboxParameter["ischecked"];
            $breakspace = $thisCheckboxParameter["breakspace"];
            $checkBoxLabel = $thisCheckboxParameter["label"];
            $labelOrientation = $thisCheckboxParameter["labelorientation"];

            $checkboxUnderConstruction = new checkbox($checkboxValue, $checkboxLabel, $isChecked);
            //$labelUnderConstruction = new label($checkboxLabel, $checkboxValue);
            $currentConstructedCheckbox = $this->getBreakSpaceType($breakspace) . $checkboxUnderConstruction->show();
            $constructedCheckbox .=$currentConstructedCheckbox;
        }

        if ($checkBoxLabel == NULL) {
            return "<div id='" . $this->checkboxName . "'>" . $constructedCheckbox . "</div>";
        } else {
            $checkboxLabel = new label($checkBoxLabel, $checkboxValue);
            switch ($labelOrientation) {
                case 'top':
                    return "<div id='" . $this->checkboxName . "'><div class='checkboxLabelContainer' style='clear:both;'> " . $checkboxLabel->show() . "</div>"
                    . "<div class='checkboxContainer'style='clear:left;'> " . $constructedCheckbox . "</div></div>";
                    break;
                case 'bottom':
                    return "<div id='" . $this->checkboxName . "'><div class='checkboxContainer'style='clear:both;'> " . $constructedCheckbox . "</div>" .
                    "<div class='checkboxLabelContainer' style='clear:both;'> " . $checkboxLabel->show() . "</div></div>";
                    break;
                case 'left':
                    return "<div id='" . $this->checkboxName . "'><div style='clear:both;overflow:auto;'>" . "<div class='checkboxLabelContainer' style='float:left;clear:left;'> " . $checkboxLabel->show() . "</div>"
                    . "<div class='checkboxContainer'style='float:left; clear:right;'> " . $constructedCheckbox . "</div></div></div>";
                    break;
                case 'right':
                    return "<div id='" . $this->checkboxName . "'><div style='clear:both;overflow:auto;'>" . "<div class='checkboxContainer'style='float:left;clear:left;'> " . $constructedCheckbox . "</div>" .
                    "<div class='checkboxLabelContainer' style='float:left;clear:right;'> " . $checkboxLabel->show() . "</div></div></div>";
                    break;
            }
        }
    }

    /*!
     * \brief This member function allows you to get a checkbox for a the WYSIWYG form editor
     * that is already saved in the database.
     * \return A constructed checkbox.
     */
    public function showWYSIWYGCheckboxEntity() {
        return $this->buildWYSIWYGCheckboxEntity();
    }

}

?>
