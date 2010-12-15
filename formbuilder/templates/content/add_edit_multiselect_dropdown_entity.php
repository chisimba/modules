<?php

/*! \file add_edit_multiselect_dropdown_entity.php
 * \brief The template file is called by an AJAX function to insert a new ms drop down
 * into the database and produce the html content for this form element in the div WYSIWYGMSDropdown
 * \section sec Explanation
 * - Request all the parameters from the post from the
Ajax function and store them into temporary variables.
 * - Create a new form element and insert these parameters into the database.
 * - If there was a successful insertion of the new form element then construct
 * this new form element in the div WYSIWYGMSDropdown so its content
 * can be passed back into WYSIWYG editor through jQuery.
*/

echo $optionValue = $this->getParam('optionValue');
echo $optionLabel = $this->getParam('optionLabel');
echo $formElementName = $this->getParam('formElementName');

echo $defaultSelected = $this->getParam('defaultSelected');
echo $menuSize = $this->getParam('menuSize');
echo $formElementLabel = $this->getParam('formElementLabel');
echo $formElementLabelLayout = $this->getParam('formElementLabelLayout');
if ($defaultSelected == "on") {
    $defaultSelected = true;
} else {
    $defaultSelected = false;
}

$objMSDDEntity = $this->getObject('form_entity_multiselect_dropdown', 'formbuilder');
$objMSDDEntity->createFormElement($formElementName);

if ($objMSDDEntity->insertOptionandValue($formElementName, $optionLabel, $optionValue, $defaultSelected, $menuSize,$formElementLabel,$formElementLabelLayout) == TRUE) {
    $postSuccessBoolean = 1;
} else {
    $postSuccessBoolean = 0;
}
?>

<div id="WYSIWYGMSDropdown">
<?php
if ($postSuccessBoolean == 1) {
    echo $objMSDDEntity->showWYSIWYGMultiSelectDropdownEntity();
} else {
    echo $postSuccessBoolean;
}
?>
</div>
