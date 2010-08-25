<?php

class form_entity_handler extends object
{
    protected  $objDBFormElements;

    public function init()
    {
        $this->objDBFormElements = $this->getObject('dbformbuilder_form_elements','formbuilder');
    }

    public function insertNewFormElement($formName,$formElementType,$formElementName)
    {

        if ($this->objDBFormElements->checkDuplicateFormElementName($formElementName,$formName))
      {
          $this->objDBFormElements->insertSingle(1,$formName,$formElementType,$formElementName);
           $postSuccess=1;
           return $postSuccess;
      }
      else
      {
         $postSuccess = 0;
          return $postSuccess;
      }
    }

    protected function getBreakSpaceType($breakSpaceType)
    {
        switch ($breakSpaceType)
        {
            case "tab":
                return " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                break;
            case "new line":
                return "<br>";
                break;
            case "normal":
                return "";
                break;
            default :
                return "";
                break;
        }
    }


}

?>
