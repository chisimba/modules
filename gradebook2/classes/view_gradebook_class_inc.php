<?php
class view_gradebook extends object
{
    public function init()
    {
        // language object.
        $this->objLanguage = $this->getObject('language', 'language');
    }
    private function loadElements()
    {
        //Load the link class
        $this->loadclass('link', 'htmlelements');
        //Load the altconfig class
        $objSysConfig = $this->getObject('altconfig', 'config');
        //Load DB Table weightedcolumn class
        $this->objWeightedColumn =& $this->getObject('dbgradebook2_weightedcolumn','gradebook2');
    }
    private function buildForm()
    {
        //Get params column id and status if edit
        $id = $this->getParam('id', NULL);
        $action = $this->getParam('action', NULL);
        //Get column values if action is edit
        if (!empty($id)) {
          $colVals = $this->objWeightedColumn->listSingle($id);
          $colVals = $colVals[0];
        }
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

        return $objTable->show();
    }
    private function getFormAction()
    {
        $action = $this->getParam("action", "addcolumn");
        $id = $this->getParam("id", Null);
        if ($action == "editcolumn") {
            $formAction = $this->uri(array("action" => "savecolumn", "id" => $id), "gradebook2");
        } else {
            $formAction = $this->uri(array("action" => "savecolumn"), "gradebook2");
        }
        return $formAction;
    }
    public function show()
    {
        return $this->buildForm();
    }
}
?>
