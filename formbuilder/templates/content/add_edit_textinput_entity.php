<?php

/*! \file add_edit_textinput_entity.php
 * \brief The template file is called by an AJAX function to insert a new text input
 * into the database and produce the html content for this form element in the div WYSIWYGTextInput
 * \section sec Explanation
 * - Request all the parameters from the post from the
Ajax function and store them into temporary variables.
 * - Create a new form element and insert these parameters into the database.
 * - If there was a successful insertion of the new form element then construct
 * this new form element in the div WYSIWYGTextInput so its content
 * can be passed back into WYSIWYG editor through jQuery.
*/

echo $formNumber = $this->getParam('formNumber');
echo $formElementName = $this->getParam('formElementName');
echo $textInputName = $this->getParam('textInputName');

echo $textInputValue = $this->getParam('textInputValue');
echo $textInputType = $this->getParam('textInputType');

echo $textInputSize = $this->getParam('textInputSize');

echo $maskedInputChoice= $this->getParam('maskedInputChoice');
echo $formElementLabel= $this->getParam('formElementLabel');
echo $formElementLabelLayout= $this->getParam('formElementLabelLayout');

$objTextInputEntity = $this->getObject('form_entity_textinput','formbuilder');
$objTextInputEntity->createFormElement($formElementName,$textInputName);
//echo "fefr   rgrg r g r";
//echo $objTextInputEntity->insertTextInputParameters($formElementName,$textInputName,$textInputValue,$textInputType,$textInputSize,$maskedInputChoice,$formElementLabel,$formElementLabelLayout)."<br>";
if ($objTextInputEntity->insertTextInputParameters($formElementName,$textInputName,$textInputValue,$textInputType,$textInputSize,$maskedInputChoice,$formElementLabel,$formElementLabelLayout) == TRUE)
{
    $postSuccessBoolean = 1;
}
 else
     {
    $postSuccessBoolean = 0;
}

?>
<div id="WYSIWYGTextInput">
    <?php
    if ($postSuccessBoolean == 1)
    {
  echo $objTextInputEntity->showWYSIWYGTextInputEntity();
    }
 else
     {
        echo $postSuccessBoolean;
    }
    ?>
</div>