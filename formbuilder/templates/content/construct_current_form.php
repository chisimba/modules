<?php

/*! \file construct_current_form.php
 * \brief The template file is called by an action buildCurrentForm.
 * \brief This template file constructs a form for user submission.
 * \note No javascript is included to prevent library conflicts.
 * \section sec Template Code Explanation
 * - Construct the form will the a speicified form number.
*/

$objFormConstructor = $this->getObject('form_entity_handler','formbuilder');


echo ($objFormConstructor->buildForm($formNumber));
?>
