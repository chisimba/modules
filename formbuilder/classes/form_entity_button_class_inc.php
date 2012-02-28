<?php

//Use Doxygen to extract comments into a technical reference manual.
//Download Doxygen from www.doxygen.org

/*!  \class form_entity_button
 *
 *  \brief This class models all the content and functionality for the
 * button form element.
 * \brief It provides functionality to insert new buttons, create them for the
 * WYSIWYG form editor and render them in the actual construction of the form.
 * It also allows you to delete buttons from forms.
 *  \brief This is a child class that belongs to the form entity heirarchy.
 *  \author Salman Noor
 *  \author CNS Intern
 *  \author School of Electrical Engineering, WITS Unversity
 *  \version 1.00
 *  \date    November 3, 2010
 */
include_once 'form_entity_handler_class_inc.php';

class form_entity_button extends form_entity_handler {

    /*!
     * \brief Private data member that stores a button object for the WYSIWYG
     * form editor.
     */
    private $objButton;

    /*!
     * \brief This data member stores the form element identifier or ID that can
     * be used anywhere in this class.
     */
    private $buttonFormName;

    /*!
     * \brief This data member stores the html name of the button.
     */
    private $buttonName;

    /*!
     * \brief This data member stores the label for the button
     */
    private $buttonLabel;

    /*!
     * \brief This data member stores a string to determine whether the button
     * object is a submit or reset button.
     */
    private $isSetOrResetChoice;

        /*!
     * \brief Private data member from the class \ref dbformbuilder_button_entity that stores all
     * the properties of this class in an usable object.
     * \note This object is used to add, get or delete button form elements.
     */
    private $objDBbuttonEntity;

    /*!
     * \brief Standard constructor that loads classes for other modules and initializes
     * and instatiates private data members.
     * \note The button class are from the htmlelements module
     * inside the chisimba core modules.
     */
    public function init() {
        $this->loadClass('button', 'htmlelements');
        $this->objDBbuttonEntity = $this->getObject('dbformbuilder_button_entity', 'formbuilder');
        $this->buttonFormName = NULL;
        $this->buttonName = NULL;
        $this->buttonLabel = NULL;
        $this->isSetOrResetChoice = NULL;
    }

    /*!
     * \brief This member function allows you to insert a new button in a form with
     * a form element identifier.
     * \brief Before a new button gets inserted into the database,
     * duplicate entries are checked if there is another button
     * with the same form element identifier.
     * \param buttonFormName A string for the form element identifier.
     * \param buttonName A string for the actual html name for the button.
     * \param buttonLabel A string.
     * \param isSetToResetOrSubmit A string. Two possibilties exist,either
     * submit or reset.
     * \return A boolean value on succesful storage of the button form element.
     */
    public function createFormElement($buttonFormName, $buttonName, $buttonLabel, $isSetToResetOrSubmit) {

        if ($this->objDBbuttonEntity->checkDuplicateButtonEntry($buttonFormName, $buttonName) == TRUE) {

            $this->buttonFormName = $buttonFormName;
            $this->buttonName = $buttonName;
            $this->buttonLabel = $buttonLabel;
            $this->isSetOrResetChoice = $isSetToResetOrSubmit;
            $this->objDBbuttonEntity->insertSingle($buttonFormName, $buttonName, $buttonLabel, $isSetToResetOrSubmit);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*!
     * \brief This member function gets the button name if the private
     * data member buttonName is set already.
     * \note This member function is not used in this module.
     * \return A string.
     */
    public function getWYSIWYGButtonName() {
        return $this->buttonName;
    }

    /*!
     * \brief This member function contructs the html content for the form that
     * allows you to insert the button parameters to insert
     * a button form element.
     * \note This member function uses member functions
     * from the parent class \ref form_entity_handler to
     * construct this form.
     * \param formName A string.
     * \return A constructed button insert form.
     */
    public function getWYSIWYGButtonInsertForm($formName) {
        $WYSIWYGButtonInsertForm = "<b>Button HTML ID and Name Menu</b>";
        $WYSIWYGButtonInsertForm.="<div id='labelNameAndIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> ";
        $WYSIWYGButtonInsertForm.= $this->buildInsertIdForm('button', $formName, "70") . "<br>";
        $WYSIWYGButtonInsertForm.= $this->buildInsertFormElementNameForm('button', "70") . "<br>";
        $WYSIWYGButtonInsertForm.="</div>";
        $WYSIWYGButtonInsertForm.="<b>Button Properties Menu</b>";
        $WYSIWYGButtonInsertForm.="<div id='labelNameAndIDContainer' class='ui-widget-content ui-corner-all'style='border:1px solid #CCCCCC;padding:10px 15px 10px 15px;margin:0px 0px 10px 0px;'> ";
        $WYSIWYGButtonInsertForm.= $this->insertButtonParametersForm() . "<br>";
        $WYSIWYGButtonInsertForm.="</div>";
        return $WYSIWYGButtonInsertForm;
    }

    /*!
     * \brief This member function deletes an existing button form element with
     * the form element identifier.
     * \param formElementName A string that contains the
     * form element identifier.
     * \note This member function is protected so it is
     * only called by the \ref form_entity_handler class. To
     * delete a button or any other form element call the
     * deleteExisitngFormElement member function which will
     * automatically call this member function.
     * \return A boolean value for a successful delete.
     */
    protected function deleteButtonEntity($formElementName) {
        $deleteSuccess = $this->objDBbuttonEntity->deleteFormElement($formElementName);
        return $deleteSuccess;
    }

    /*!
     * \brief This member function constructs a button for a the actual form
     * rendering from the database.
     * \param buttonFormName A string that contains the
     * form element identifier.
     * \note The member function is only called by the
     * parent class member function buildForm to build a form.
     * \return A constructed button.
     */
    protected function constructButtonEntity($buttonFormName) {

        $buttonParameters = $this->objDBbuttonEntity->listButtonParameters($buttonFormName);

$constructedButton="";
        foreach ($buttonParameters as $thisbuttonParameter) {

//$checkboxName = $thisCheckboxParameter["checkboxname"];
//$buttonFormName = $thisbuttonParameter["buttonformname"];
            $buttonName = $thisbuttonParameter["buttonname"];
            $buttonLabel = $thisbuttonParameter["buttonlabel"];
            $isSetToResetOrSubmit = $thisbuttonParameter["issettoresetorsubmit"];

            $buttonUnderConstuction = new button($buttonName);
            $buttonUnderConstuction->setValue($buttonLabel);
            if ($isSetToResetOrSubmit == "reset") {
                $buttonUnderConstuction->setToReset();
            } else {
                $buttonUnderConstuction->setToSubmit();
            }
            $currentConstructedButton = $buttonUnderConstuction->show();
            $constructedButton .=$currentConstructedButton;
        }

        return $constructedButton;
    }

    /*!
     * \brief This member function constructs a button for a the WYSIWYG form editor.
     * \note The member function uses the private data members
     * that are already initialized by the createFormElement member function which
     * should be always called first to create a form element in the database before
     * displaying it with this member function.
     * \return A constructed button.
     */
    private function buildWYSIWYGButtonEntity() {

        $this->objButton = new button($this->buttonName);
        $this->objButton->setValue($this->buttonLabel);


        if ($this->isSetOrResetChoice == "submit") {
            $this->objButton->setToSubmit();  //If you want to make the button a submit button
        } else {
            $this->objButton->setToReset();
        }
        return $this->objButton->show();
    }

    /*!
     * \brief This member function allows you to get a button for a the WYSIWYG form editor
     * that is already saved in the database.
     * \return A constructed button.
     */
    public function showWYSIWYGButtonEntity() {
        return $this->buildWYSIWYGButtonEntity();
    }

}

?>
