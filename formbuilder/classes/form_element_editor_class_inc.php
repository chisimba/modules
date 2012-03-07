<?php

//Use Doxygen to extract comments into a technical reference manual.
//Download Doxygen from www.doxygen.org

/*!  \class form_element_editor
 *
 *  \brief The class models all of the objects and forms used inside the WYSIWYG form
 * editor page
 *  \brief It basically is an interface class for all the ajax functions used in
 * the form_editor.php template file. This class reduces a significant amount
 * of javascript and php code when creating these objects.
 *  \author Salman Noor
 *  \author CNS Intern
 *  \author School of Electrical Engineering, WITS Unversity
 *  \version 1.00
 *  \date    November 3, 2010
 * \note Some objects such as WYSIWYG header and toolbar are just simply called
 * by PHP in the form_editor.php template file because they are core objects inside
 * that template file that do not change. Other object such as the form element
 * constructors are called through by AJAX beacuase they are temporarily needed.
 * \note The class is composed of all classes inside the form entity heirarchy.
 * \warning There are two main arguments used inside this class. They are formNumber
 * and formName. Make sure that these arguments are correct and for the right form.
 */
class form_element_editor extends object {

    /*!
     * \brief Private data member from the class \ref dbformbuilder_form_list that stores all
     * the properties of this class in an usable object.
     * \note This class is not part of the form entity hierarchy.
     * \note This object is used to get some of the metadata of forms to be
     * displayed WYSIWYG Editor Header.
     */
    private $objDBFormMetaDataList;

    /*!
     * \brief Private data member from the class \ref form_element_inserter that stores all
     * the properties of this class in an usable object.
     * \note This class is not part of the form entity hierarchy.
     * \note This object models the form element inserter drop down that
     * goes inside the WYSiWYG editor toolbar.
     */
    private $formElementDropDown;

    /*!
     * \brief Private data member from the class \ref form_entity_checkbox that stores all
     * the properties of this class in an usable object.
     */
    private $checkboxConstructor;

    /*!
     * \brief Private data member from the class \ref form_entity_button that stores all
     * the properties of this class in an usable object.
     */
    private $buttonConstructor;

    /*!
     * \brief Private data member from the class \ref form_entity_datepicker that stores all
     * the properties of this class in an usable object.
     */
    private $datePickerConstructor;

    /*!
     * \brief Private data member from the class \ref form_entity_htmlheading that stores all
     * the properties of this class in an usable object.
     */
    private $HTMLHeadingConstructor;

    /*!
     * \brief Private data member from the class \ref form_entity_label that stores all
     * the properties of this class in an usable object.
     */
    private $labelConstructor;

    /*!
     * \brief Private data member from the class \ref form_entity_radio that stores all
     * the properties of this class in an usable object.
     */
    private $radioConstructor;

    /*!
     * \brief Private data member from the class \ref form_entity_textinput that stores all
     * the properties of this class in an usable object.
     */
    private $textinputConstructor;

    /*!
     * \brief Private data member from the class \ref form_entity_textarea that stores all
     * the properties of this class in an usable object.
     */
    private $textareaConstructor;

    /*!
     * \brief Private data member from the class \ref form_entity_dropdown that stores all
     * the properties of this class in an usable object.
     */
    private $dropdownConstructor;

    /*!
     * \brief Private data member from the class \ref form_entity_multiselect_dropdown that stores all
     * the properties of this class in an usable object.
     */
    private $multiselectDropDownConstructor;

    /*!
     * \brief Standard constructor that sets up all the private data members
     *  of this class.
     */
    public function init() {
        $this->objDBFormMetaDataList = $this->getObject('dbformbuilder_form_list', 'formbuilder');

        $this->formElementDropDown = $this->getObject('form_element_inserter', 'formbuilder');

        $this->checkboxConstructor = $this->getObject('form_entity_checkbox', 'formbuilder');
        $this->buttonConstructor = $this->getObject('form_entity_button', 'formbuilder');
        $this->datePickerConstructor = $this->getObject('form_entity_datepicker', 'formbuilder');
        $this->HTMLHeadingConstructor = $this->getObject('form_entity_htmlheading', 'formbuilder');
        $this->labelConstructor = $this->getObject('form_entity_label', 'formbuilder');
        $this->radioConstructor = $this->getObject('form_entity_radio', 'formbuilder');
        $this->textinputConstructor = $this->getObject('form_entity_textinput', 'formbuilder');
        $this->textareaConstructor = $this->getObject('form_entity_textarea', 'formbuilder');
        $this->dropdownConstructor = $this->getObject('form_entity_dropdown', 'formbuilder');
        $this->multiselectDropDownConstructor = $this->getObject('form_entity_multiselect_dropdown', 'formbuilder');
    }

    /*!
     * \brief This member function sets up the header that gets displayed on top
     * of the form_editor.php template.
     * \brief It get some form metadata from the \ref dbformbuilder_form_list
     * class and spits it out in the header.
     * \param formNumber An integer. Make sure you insert the right form
     * number or it is spit out wrong metadata information.
     * \return A constructed header object to be displayed on top of the
     * form_editor.php template.
     */
    private function buildWYSIWYGEditorHeader($formNumber) {
        $WYSIWYGEditorHeaderUnderConstruction = "<div class='ui-widget-content'style='border:0px solid #CCCCCC;padding:0px 20px 0px 20px; margin: 0px 10px 10px 10px; '>";
        $WYSIWYGEditorHeaderUnderConstruction .= "<h2>WYSIWYG Form Editor</h2>";
        $formMetaDataList = $this->objDBFormMetaDataList->getFormMetaData($formNumber);
        $formLabel = $formMetaDataList["0"]['label'];
        $WYSIWYGEditorHeaderUnderConstruction .= "<b>Form Number: </b>" . $formNumber . "<br>";
        $WYSIWYGEditorHeaderUnderConstruction .= "<b>Form Title: </b>" . $formLabel . "<br><br>";
        $WYSIWYGEditorHeaderUnderConstruction .= "</div>";
        return $WYSIWYGEditorHeaderUnderConstruction;
    }

    /*!
     * \brief This member function sets up the editor toolbar that gets displayed under the
     * header of the form_editor.php template.
     * \brief Some text, the form element inserter drop down and two buttons delete and
     * rearrange form elements get concatenated into a large span.
     * \return A constructed toolbar that gets displayed on top of the
     * form_editor.php template.
     */
    private function buildWYSIWYGToolBar() {
        $toolBarUnderConstrunction = '<span id="toolbar" class="ui-state-default ui-corner-all"style="border:1px solid #CCCCCC;padding:30px 15px 15px 15px; margin: 50px 50px 50px 50px; ">';
        $toolBarUnderConstrunction .= "<span style='padding-bottom:155px;'><b >WYSIWYG Form Editor Toolbar</b>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        $toolBarUnderConstrunction .= "Insert A:   ";
        $toolBarUnderConstrunction .=$this->formElementDropDown->showFormElementInserterDropDown() . "
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
        $toolBarUnderConstrunction .= "Rearrange Form Elements : <span style='margin:35px' id='sortFormElementsToggleContainer'><input style='margin:25px' type='checkbox' id='sortFormElementsButton' /><label for='sortFormElementsButton'>Toggle</label></span>
&nbsp;&nbsp;&nbsp;Delete Form Elements : <input type='checkbox' id='deleteFormElementsButton' /><label for='deleteFormElementsButton'>Toggle</label>
&nbsp;&nbsp;&nbsp;<button class='finishDesigningForm' id='toolBarDoneButton'>Done</button>";
        $toolBarUnderConstrunction .= '</span>';
        return $toolBarUnderConstrunction;
    }

    /*!
     * \brief This member function is the interface for the private member function
     * buildWYSIWYGEditorHeader.
     * \param formNumber An integer. Make sure you insert the right form
     * number or it is spit out wrong metadata information.
     * \return A constructed header object to be displayed on top of the
     * form_editor.php template.
     */
    public function showWYSIWYGEditorHeader($formNumber) {
        return $this->buildWYSIWYGEditorHeader($formNumber);
    }

    /*!
     * \brief This member function is the interface for the private member function
     * buildWYSIWYGToolbar.
     * \return A constructed toolbar that gets displayed on top of the
     * form_editor.php template.
     */
    public function showWYSIWYGToolBar() {
        return $this->buildWYSIWYGToolBar();
    }

    /*!
     * \brief This member function is the interface for AJAX functions to call a
     * member function inside the \ref form_entity_label class.
     * \param formNumber An integer. Make sure you insert the right form
     * number or the form element will be constructed for the wrong form.
     * \return A constructed form to insert a label form element in a
     * jQuery modal window inside form_editor.php template.
     */
    public function showInsertLabelForm($formName) {
        return $this->labelConstructor->getWYSIWYGLabelInsertForm($formName);
    }

    /*!
     * \brief This member function is the interface for AJAX functions to call a
     * member function inside the \ref form_entity_htmlheading class.
     * \param formNumber An integer. Make sure you insert the right form
     * number or the form element will be constructed for the wrong form.
     * \return A constructed form to insert a html heading in a
     * jQuery modal window inside form_editor.php template.
     */
    public function showInsertHTMLHeadingForm($formName) {
        return $this->HTMLHeadingConstructor->getWYSIWYGHTMLHeadingInsertForm($formName);
    }

    /*!
     * \brief This member function is the interface for AJAX functions to call a
     * member function inside the \ref form_entity_textinput class.
     * \param formNumber An integer. Make sure you insert the right form
     * number or the form element will be constructed for the wrong form.
     * \return A constructed form to insert a text input in a
     * jQuery modal window inside form_editor.php template.
     */
    public function showInsertTextInputForm($formName) {
        return $this->textinputConstructor->getWYSIWYGTextInputInsertForm($formName);
    }

    /*!
     * \brief This member function is the interface for AJAX functions to call a
     * member function inside the \ref form_entity_textarea class.
     * \param formNumber An integer. Make sure you insert the right form
     * number or the form element will be constructed for the wrong form.
     * \return A constructed form to insert a text area in a
     * jQuery modal window inside form_editor.php template.
     */
    public function showInsertTextAreaForm($formName) {
        return $this->textareaConstructor->getWYSIWYGTextAreaInsertForm($formName);
    }

    /*!
     * \brief This member function is the interface for AJAX functions to call a
     * member function inside the \ref form_entity_datepicker class.
     * \param formNumber An integer. Make sure you insert the right form
     * number or the form element will be constructed for the wrong form.
     * \return A constructed form to insert a date picker object in a
     * jQuery modal window inside form_editor.php template.
     */
    public function showInsertDatePickerForm($formName) {
        return $this->datePickerConstructor->getWYSIWYGDatePickerInsertForm($formName);
    }

    /*!
     * \brief This member function is the interface for AJAX functions to call a
     * member function inside the \ref form_entity_checkbox class.
     * \param formNumber An integer. Make sure you insert the right form
     * number or the form element will be constructed for the wrong form.
     * \return A constructed form to insert a check box in a
     * jQuery modal window inside form_editor.php template.
     */
    public function showInsertCheckboxForm($formName) {
        return $this->checkboxConstructor->getWYSIWYGCheckBoxInsertForm($formName);
    }

    /*!
     * \brief This member function is the interface for AJAX functions to call a
     * member function inside the \ref form_entity_radio class.
     * \param formNumber An integer. Make sure you insert the right form
     * number or the form element will be constructed for the wrong form.
     * \return A constructed form to insert a radio button in a
     * jQuery modal window inside form_editor.php template.
     */
    public function showInsertRadioForm($formName) {
        return $this->radioConstructor->getWYSIWYGRadioInsertForm($formName);
    }

    /*!
     * \brief This member function is the interface for AJAX functions to call a
     * member function inside the \ref form_entity_dropdown class.
     * \param formNumber An integer. Make sure you insert the right form
     * number or the form element will be constructed for the wrong form.
     * \return A constructed form to insert a drop down list in a
     * jQuery modal window inside form_editor.php template.
     */
    public function showInsertDropDownForm($formName) {
        return $this->dropdownConstructor->getWYSIWYGDropDownInsertForm($formName);
    }

    /*!
     * \brief This member function is the interface for AJAX functions to call a
     * member function inside the \ref form_entity_multiselect_dropdown class.
     * \param formNumber An integer. Make sure you insert the right form
     * number or the form element will be constructed for the wrong form.
     * \return A constructed form to insert a multiselectable drop down list in a
     * jQuery modal window inside form_editor.php template.
     */
    public function showInsertMSDropDownForm($formName) {
        return $this->multiselectDropDownConstructor->getWYSIWYGMSDropDownInsertForm($formName);
    }

    /*!
     * \brief This member function is the interface for AJAX functions to call a
     * member function inside the \ref form_entity_button class.
     * \param formNumber An integer. Make sure you insert the right form
     * number or the form element will be constructed for the wrong form.
     * \return A constructed form to insert a button in a
     * jQuery modal window inside form_editor.php template.
     */
    public function showInsertButtonForm($formName) {
        return $this->buttonConstructor->getWYSIWYGButtonInsertForm($formName);
    }

}

?>
