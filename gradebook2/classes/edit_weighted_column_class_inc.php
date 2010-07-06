<?php
class edit_weighted_column extends object
{
    public function init()
    {
        // language object.
        $this->objLanguage = $this->getObject('language', 'language');
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
        //Load the table class
        $this->loadClass("htmltable", 'htmlelements'); 
    }
    private function buildForm()
    {
        //Load the form elements
        $this->loadElements();
        //Create the form
        $objForm = new form('weighted_column', $this->getFormAction());
        //Add Heading
        $objHeading = &$this->getObject('htmlheading', 'htmlelements');
        $objHeading->type = 1;
        $objHeading->str = $this->objLanguage->languageText("mod_gradebook2_addweightedcolumn","gradebook2");
        $objForm->addToForm($objHeading->show());
        //Create new table
        $objTable = new htmltable();
        $objTable->width = '100%';
        $objTable->attributes = " align='center' border='0'";
        $objTable->cellspacing = '5';
        //Add Heading: Column Information
        $objHeading = &$this->getObject('htmlheading', 'htmlelements');
        $objHeading->type = 3;
        $objHeading->str = "(1) ".$this->objLanguage->languageText("mod_gradebook2_columninfo","gradebook2");
        //Create new table row to contain heading: Column Information
        $objTable->startRow();
        $objTable->addCell($objHeading->show(), Null, 'top', 'left', '', 'colspan="2"');
        $objTable->endRow();
        //----------TEXT INPUT 1--------------
        //Create a new textinput for the column name
        $objColumname = new textinput('column_name');
        //Create a new label for the text labels
        $columnameLabel = new label($this->objLanguage->languageText("mod_gradebook2_columname","gradebook2"),"column_name");
        //Create new table row to contain the columname lable and textinput
        $objTable->startRow();
        $objTable->addCell($columnameLabel->show(), 180, 'top', 'left');
        $objTable->addCell($objColumname->show(), Null, 'top', 'left');
        $objTable->endRow();

        //----------TEXT INPUT 2--------------
        //Create a new textinput for the display name
        $objDisplayname = new textinput('display_name');
        //Create a new label for the text labels
        $displaynameLabel = new label($this->objLanguage->languageText("mod_gradebook2_displayname","gradebook2"),"display_name");
        //Create new table row to contain the displayname lable and textinput
        $objTable->startRow();
        $objTable->addCell($displaynameLabel->show(), 180, 'top', 'left');
        $objTable->addCell($objDisplayname->show(), Null, 'top', 'left');
        $objTable->endRow();
        //----------TEXTAREA--------------
        //Create a new textarea for the description text
        $objDescriptiontxt = new textarea('description');
        $descriptionLabel = new label($this->objLanguage->languageText("mod_gradebook2_description","gradebook2"),"description");
        //Create new table row to contain the description lable and textinput
        $objTable->startRow();
        $objTable->addCell($descriptionLabel->show(), 180, 'top', 'left');
        $objTable->addCell($objDescriptiontxt->show(), Null, 'top', 'left');
        $objTable->endRow();
        //----------DROP DOWN 1--------------
        //Create a new dropdown for the primary display
        $objPrimaryDisplay = new dropdown('primary_display');
        //Add percentage option
        $objPrimaryDisplay->addOption('%', $this->objLanguage->languageText("mod_gradebook2_percentage","gradebook2"));
        //Create a new label for the text labels
        $primarydisplayLabel = new label($this->objLanguage->languageText("mod_gradebook2_primarydisplay","gradebook2"),"primary_display");
        //Create new table row to contain the primarydisplay lable and textinput
        $objTable->startRow();
        $objTable->addCell($primarydisplayLabel->show(), 180, 'top', 'left');
        $objTable->addCell($objPrimaryDisplay->show(), Null, 'top', 'left');
        $objTable->endRow();
        //----------DROP DOWN 2--------------
        //Create a new dropdown for the secondary display
        $objSecondaryDisplay = new dropdown('secondary_display');
        //Add percentage option
        $objSecondaryDisplay->addOption('%', $this->objLanguage->languageText("mod_gradebook2_percentage","gradebook2"));
        //Create a new label for the text labels
        $secondarydisplayLabel = new label($this->objLanguage->languageText("mod_gradebook2_secondarydisplay","gradebook2"),"secondary_display");
        //Create new table row to contain the secondarydisplay lable and textinput
        $objTable->startRow();
        $objTable->addCell($secondarydisplayLabel->show(), 180, 'top', 'left');
        $objTable->addCell($objSecondaryDisplay->show(), Null, 'top', 'left');
        $objTable->endRow();
        //Add Heading: Dates
        $objHeading = &$this->getObject('htmlheading', 'htmlelements');
        $objHeading->type = 3;
        $objHeading->str = "(2) ".$this->objLanguage->languageText("mod_gradebook2_wordates","gradebook2");
        //Create new table row to contain heading: Dates
        $objTable->startRow();
        $objTable->addCell($objHeading->show(), Null, 'top', 'left', '', 'colspan="2"');
        $objTable->endRow();
        //----------DROP DOWN 3--------------
        //Create a new dropdown for the grading period
        $objGradingPeriod = new dropdown('grading_period');
        //Add Grading period option
        $objGradingPeriod->addOption('2008/2009', '2008/2009');
        //Create a new label for the text labels
        $gradingPeriodLabel = new label($this->objLanguage->languageText("mod_gradebook2_gradingperiod","gradebook2"),"grading_period");
        //Create new table row to contain the gradingperiod lable and textinput
        $objTable->startRow();
        $objTable->addCell($gradingPeriodLabel->show(), 180, 'top', 'left');
        $objTable->addCell($objGradingPeriod->show(), Null, 'top', 'left');
        $objTable->endRow();
        //Add Heading: Select Columns
        $objHeading = &$this->getObject('htmlheading', 'htmlelements');
        $objHeading->type = 3;
        $objHeading->str = "(3) ".$this->objLanguage->languageText("mod_gradebook2_selectcolumns","gradebook2");
        //Create new table row to contain heading: Select Columns
        $objTable->startRow();
        $objTable->addCell($objHeading->show(), Null, 'top', 'left', '', 'colspan="2"');
        $objTable->endRow();
        //----------RADIO BUTTON 1--------------
        //Create a new radio button for the weighted grade
        $objWeightedGrade = new radio('weighted_grade');
        //Add Grading period options
        $objWeightedGrade->addOption('TRUE', $this->objLanguage->languageText("mod_gradebook2_allgradecolumns","gradebook2"));
        $objWeightedGrade->addOption('FALSE', $this->objLanguage->languageText("mod_gradebook2_selectedcolumns","gradebook2"));
        //Create a new label for the text labels
        $weightedGradeLabel = new label($this->objLanguage->languageText("mod_gradebook2_includeweighted","gradebook2"),"weighted_grade");
        //Create new table row to contain the gradingperiod lable and textinput
        $objTable->startRow();
        $objTable->addCell($weightedGradeLabel->show(), 180, 'top', 'left');
        $objTable->addCell($objWeightedGrade->show(), Null, 'top', 'left');
        $objTable->endRow();
        //----------RADIO BUTTON 2--------------
        //Create a new radio button for the running total
        $objRunningTotal = new radio('running_total');
        //Add running total options
        $objRunningTotal->addOption('YES', $this->objLanguage->languageText("mod_gradebook2_wordyes","gradebook2"));
        $objRunningTotal->addOption('NO', $this->objLanguage->languageText("mod_gradebook2_wordno","gradebook2"));
        //Create a new label for the text labels
        $runningTotalLabel = new label($this->objLanguage->languageText("mod_gradebook2_calcrunningtotal","gradebook2"),"weighted_grade");
        //Create new table row to contain the runningtotal lable and textinput
        $objTable->startRow();
        $objTable->addCell($runningTotalLabel->show(), 180, 'top', 'left');
        $objTable->addCell($objRunningTotal->show().'<br /> '.$this->objLanguage->languageText("mod_gradebook2_runningtotal","gradebook2"), Null, 'top', 'left');
        $objTable->endRow();
        //Create new table row to contain the gradecenter description
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText("mod_gradebook2_gradecenterdesc","gradebook2"), Null, 'top', 'left', '', 'colspan="2"');
        $objTable->endRow();
        //Add Heading: Options
        $objHeading = &$this->getObject('htmlheading', 'htmlelements');
        $objHeading->type = 3;
        $objHeading->str = "(4) ".$this->objLanguage->languageText("mod_gradebook2_wordoptions","gradebook2");
        //Create new table row to contain heading: Select Columns
        $objTable->startRow();
        $objTable->addCell($objHeading->show(), Null, 'top', 'left', '', 'colspan="2"');
        $objTable->endRow();
        //Create new table to contain grade center options
        $objSubTable = new htmltable();
        $objSubTable->width = '100%';
        $objSubTable->attributes = " align='center' border='0'";
        $objSubTable->cellspacing = '5';
        //----------RADIO BUTTON 3--------------
        //Create a new radio button for the grade center calculations
        $objGradeCenterCalc = new radio('grade_center_calc');
        //Add grade center calculation options
        $objGradeCenterCalc->addOption('YES', $this->objLanguage->languageText("mod_gradebook2_wordyes","gradebook2"));
        $objGradeCenterCalc->addOption('NO', $this->objLanguage->languageText("mod_gradebook2_wordno","gradebook2"));
        //Create a new label for the text labels
        $gradeCenterCalcLabel = new label($this->objLanguage->languageText("mod_gradebook2_gradecentercalc","gradebook2"),"grade_center_calc");
        //Create new table row to contain the runningtotal lable and textinput
        $objSubTable->startRow();
        $objSubTable->addCell($gradeCenterCalcLabel->show(), '420', 'top', 'left');
        $objSubTable->addCell($objGradeCenterCalc->show(), Null, 'bottom', 'left');
        $objSubTable->endRow();
        //----------RADIO BUTTON 4--------------
        //Create a new radio button allowing Show/view in the grade center
        $objShowinGradeCenter = new radio('showin_grade_center');
        //Add show in grade center options
        $objShowinGradeCenter->addOption('YES', $this->objLanguage->languageText("mod_gradebook2_wordyes","gradebook2"));
        $objShowinGradeCenter->addOption('NO', $this->objLanguage->languageText("mod_gradebook2_wordno","gradebook2"));
        //Create a new label for the text labels
        $showinGradeCenterLabel = new label($this->objLanguage->languageText("mod_gradebook2_showingradecenter","gradebook2"),"showin_grade_center");
        //Create new table row to contain the show-in-grade-center lable and textinput
        $objSubTable->startRow();
        $objSubTable->addCell($showinGradeCenterLabel->show(), '420', 'top', 'left');
        $objSubTable->addCell($objShowinGradeCenter->show(), Null, 'top', 'left');
        $objSubTable->endRow();

        //----------RADIO BUTTON 5--------------
        //Create a new radio button to show statistics in grade center
        $objShowstatsGradeCenter = new radio('showstats_grade_center');
        //Add show statistics in grade center options
        $objShowstatsGradeCenter->addOption('YES', $this->objLanguage->languageText("mod_gradebook2_wordyes","gradebook2"));
        $objShowstatsGradeCenter->addOption('NO', $this->objLanguage->languageText("mod_gradebook2_wordno","gradebook2"));
        //Create a new label for the text labels
        $showstatsGradeCenterLabel = new label($this->objLanguage->languageText("mod_gradebook2_showstats","gradebook2"),"showstats_grade_center");
        //Create new table row to contain the show-stats-in-grade-center lable and textinput
        $objSubTable->startRow();
        $objSubTable->addCell($showstatsGradeCenterLabel->show(), '420', 'top', 'left');
        $objSubTable->addCell($objShowstatsGradeCenter->show(), Null, 'top', 'left');
        $objSubTable->endRow();
        //Create new table row to contain the sub table
        $objTable->startRow();
        $objTable->addCell($objSubTable->show(), Null, 'top', 'right', '', 'colspan="2"');
        $objTable->endRow();
        //Add Heading: Submit
        $objHeading = &$this->getObject('htmlheading', 'htmlelements');
        $objHeading->type = 3;
        $objHeading->str = "(5) ".$this->objLanguage->languageText("mod_gradebook2_submit","gradebook2");
        //Create new table row to contain heading: Select Columns
        $objTable->startRow();
        $objTable->addCell($objHeading->show(), Null, 'top', 'left', '', 'colspan="2"');
        $objTable->endRow();
        //----------SUBMIT BUTTON--------------
        //Create a button for submitting the form
        $objButton = new button('save');
        // Set the button type to submit
        $objButton->setToSubmit();
        // Use the language object to label button with the word save
        $objButton->setValue(' '.$this->objLanguage->languageText("mod_gradebook2_submit", "gradebook2").' ');
        //----------CANCEL BUTTON--------------
        $objCancel = &$this->getObject("link", "htmlelements");
        $objCancel->link($this->uri(array(
            'module' => 'gradebook2',
            'action' => 'home'
        )));
        //Create cancel button
        $objCancelBtn = new button('cancel');
        // Use the language object to label button with the word save
        $objCancelBtn->setValue(' '.$this->objLanguage->languageText("mod_gradebook2_wordcancel", "gradebook2").' ');
        $objCancel->link = $objCancelBtn->showSexy();

        //Create new table row to contain the submit/cancel instructions
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText("mod_gradebook2_submitorcancel", "gradebook2"), Null, 'top', 'left', '', 'colspan="2"');
        $objTable->endRow();
        //Create new table row to contain the submit and cancel buttons
        $objTable->startRow();
        $objTable->addCell($objCancel->show()."&nbsp;&nbsp;&nbsp;".$objButton->showSexy(), Null, 'top', 'right', '', 'colspan="2"');
        $objTable->endRow();
        //Add table to form
        $objForm->addToForm($objTable->show());        
        return $objForm->show();
    }
    private function getFormAction()
    {
        $action = $this->getParam("action", "addcolumn");
        if ($action == "editcolumn") {
            $formAction = $this->uri(array("action" => "updatecolumn"), "gradebook2");
        } else {
            $formAction = $this->uri(array("action" => "savenewcolumn"), "gradebook2");
        }
        return $formAction;
    }
    public function show()
    {
        return $this->buildForm();
    }
}
?>
