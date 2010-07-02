<?php
class edit_weighted_column extends object
{
    public function init()
    {
    }
    private function loadElements()
    {
        //Load the form class 
        $this->loadClass('form','htmlelements');
        //Load the textinput class 
        $this->loadClass('textinput','htmlelements');
        //Load the label class
        $this->loadClass('label', 'htmlelements');
        //Load the textarea class
        $this->loadClass('textarea','htmlelements');
        //Load the radio button class
        $this->loadClass('radio', 'htmlelements'); 
        //Load the dropdown button class
        $this->loadClass('dropdown', 'htmlelements'); 
        //Load the button class
        $this->loadClass('button', 'htmlelements'); 
    }
    private function buildForm()
    {
        //Load the form elements
        $this->loadElements();
        //Create the form
        $objForm = new form('weighted_column', $this->getFormAction());
        //Set Display type to show label on the left
        $objForm->setDisplayType(2);
        //----------TEXT INPUT 1--------------
        //Create a new textinput for the column name
        $objColumname = new textinput('column_name');
        //Create a new label for the text labels
        $columnameLabel = new label($this->objLanguage->languageText("mod_gradebook2_columname","gradebook2"),"column_name");
        $objForm->addToForm($objColumname->show() . "<br />");
        $objForm->addToForm($columnameLabel->show());
        //----------TEXT INPUT 2--------------
        //Create a new textinput for the display name
        $objDisplayname = new textinput('display_name');
        //Create a new label for the text labels
        $displaynameLabel = new label($this->objLanguage->languageText("mod_gradebook2_displayname","gradebook2"),"display_name");
        $objForm->addToForm($objDisplayname->show() . "<br />");
        $objForm->addToForm($displaynameLabel->show());
        //----------TEXTAREA--------------
        //Create a new textarea for the description text
        $objDescriptiontxt = new textarea('description');
        $descriptionLabel = new label($this->objLanguage->languageText("mod_gradebook2_description","gradebook2"),"description");
        $objForm->addToForm($descriptionLabel->show() . "<br />");
        $objForm->addToForm($objDescriptiontxt->show() . "<br />");
        //----------DROP DOWN 1--------------
        //Create a new dropdown for the primary display
        $objPrimaryDisplay = new dropdown('primary_display');
        //Add percentage option
        $objPrimaryDisplay->addOption('%', $this->objLanguage->languageText("mod_gradebook2_percentage","gradebook2"));
        //Create a new label for the text labels
        $primarydisplayLabel = new label($this->objLanguage->languageText("mod_gradebook2_primarydisplay","gradebook2"),"primary_display");
        $objForm->addToForm($objPrimaryDisplay->show() . "<br />");
        $objForm->addToForm($primarydisplayLabel->show());
        //----------DROP DOWN 2--------------
        //Create a new dropdown for the secondary display
        $objSecondaryDisplay = new dropdown('secondary_display');
        //Add percentage option
        $objSecondaryDisplay->addOption('%', $this->objLanguage->languageText("mod_gradebook2_percentage","gradebook2"));
        //Create a new label for the text labels
        $secondarydisplayLabel = new label($this->objLanguage->languageText("mod_gradebook2_secondarydisplay","gradebook2"),"secondary_display");
        $objForm->addToForm($objSecondaryDisplay->show() . "<br />");
        $objForm->addToForm($secondarydisplayLabel->show());
        //----------DROP DOWN 3--------------
        //Create a new dropdown for the grading period
        $objGradingPeriod = new dropdown('grading_period');
        //Add Grading period option
        $objGradingPeriod->addOption('2008/2009', '2008/2009');
        //Create a new label for the text labels
        $gradingPeriodLabel = new label($this->objLanguage->languageText("mod_gradebook2_gradingperiod","gradebook2"),"grading_period"));
        $objForm->addToForm($objGradingPeriod->show() . "<br />");
        $objForm->addToForm($gradingPeriodLabel->show());
        //----------RADIO BUTTON 1--------------
        //Create a new radio button for the weighted grade
        $objWeightedGrade = new radio('weighted_grade');
        //Add Grading period options
        $objWeightedGrade->addOption('TRUE', $this->objLanguage->languageText("mod_gradebook2_allgradecolumns","gradebook2"));
        $objWeightedGrade->addOption('FALSE', $this->objLanguage->languageText("mod_gradebook2_selectedcolumns","gradebook2"));
        //Create a new label for the text labels
        $weightedGradeLabel = new label($this->objLanguage->languageText("mod_gradebook2_includeweighted","gradebook2"),"weighted_grade");
        $objForm->addToForm($objWeightedGrade->show() . "<br />");
        $objForm->addToForm($weightedGradeLabel->show());
        //----------RADIO BUTTON 2--------------
        //Create a new radio button for the running total
        $objRunningTotal = new radio('running_total');
        //Add running total options
        $objRunningTotal->addOption('YES', $this->objLanguage->languageText("mod_gradebook2_wordyes","gradebook2"));
        $objRunningTotal->addOption('NO', $this->objLanguage->languageText("mod_gradebook2_wordno","gradebook2"));
        //Create a new label for the text labels
        $runningTotalLabel = new label($this->objLanguage->languageText("mod_gradebook2_includeweighted","gradebook2"),"weighted_grade");
        $objForm->addToForm($objRunningTotal->show() . "<br />");
        $objForm->addToForm($runningTotalLabel->show());
        $objForm->addToForm($this->objLanguage->languageText("mod_gradebook2_runningtotal","gradebook2"));
        //----------SUBMIT BUTTON--------------
        //Create a button for submitting the form
        $objButton = new button('save');
        // Set the button type to submit
        $objButton->setToSubmit();
        // Use the language object to label button 
        // with the word save
        $objButton->setValue(' '.$this->objLanguage->languageText("mod_gradebook2_submit", "gradebook2").' ');
        $objForm->addToForm($objButton->show());
        
        return $objForm->show();
    }
    private function getFormAction()
    {
    }
    public function show()
    {
    }
}
?>
