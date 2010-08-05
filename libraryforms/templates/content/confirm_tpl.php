<?php
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 *
 *libraryforms
 *
 * libraryforms allows students or distant user to request books online
 *
 * @category  Chisimba
 * @package   libraryforms
 * @author    Brenda Mayinga brendamayinga@ymail.com
 */

// create the Form
 //$objForm = new form('confirm_tpl', $this->getFormAction());

//Load form object
  $this->loadClass('form', 'htmlelements');

//Load the button object
  $this->loadClass('button', 'htmlelements');

//$objForm = new form('confirm_tpl', $this->getFormAction());

//   $this->loadElements();

echo '<p>'.$this->objLanguage->languageText('mod_libraryforms_commentsent', 'libraryforms', '').':</p>';

//---------Return Button--------------
        //Create a button for submitting the form
        $objButton = new button('Back');

        // Set the button type to submit
        $objButton->setToSubmit();

        // 
        $objButton->setValue(' '.$this->objLanguage->languageText("category_resource_five", "libraryforms").'back');
      //  $objForm->addToForm($objButton->show());
 
echo '<br /><br />';
?>
