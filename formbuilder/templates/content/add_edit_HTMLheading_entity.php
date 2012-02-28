<?php
/* ! \file add_edit_HTMLheading_entity.php
 * \brief The template file is called by an AJAX function to insert a new html heading
 * into the database and produce the html content for this form element in the div WYSIWYGHTMLHeading
 * \section sec Explanation
 * - Request all the parameters from the post from the
  Ajax function and store them into temporary variables.
 * - Create a new form element and insert these parameters into the database.
 * - If there was a succesful insertion of the new form element then construct
 * this new form element in the div WYSIWYGHTMLHeading so its content
 * can be passed back into WYSIWYG editor through jQuery.
 */

$HTMLHeadingValue = $this->getParam('HTMLHeadingValue');
$formElementName = $this->getParam('formElementName');
$fontSize = $this->getParam('fontSize');
$textAlignment = $this->getParam('textAlignment');


$objHTMLHeadingEntity = $this->getObject('form_entity_htmlheading', 'formbuilder');

if ($objHTMLHeadingEntity->createFormElement($formElementName, $HTMLHeadingValue, $fontSize, $textAlignment) == TRUE) {
    $postSuccessBoolean = 1;
} else {
    $postSuccessBoolean = 0;
}
?>

<div id="WYSIWYGHTMLHeading">
    <?php
    if ($postSuccessBoolean == 1) {
        echo $objHTMLHeadingEntity->showWYSIWYGHTMLHeadingEntity();
    } else {
        echo $postSuccessBoolean;
    }
    ?>
</div>
