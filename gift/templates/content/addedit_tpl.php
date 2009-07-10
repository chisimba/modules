<?php
/**
 * Variables:
 *    $param         - gets the parameter to check whether it's an add or edit form
 *    $this->objGift - declared in controller.php in init function used to get the giftops object
 *
 * Funcions:
 *    createForm()   - a function in giftops_class_inc.php used to create the form with empty fields
 *    getForm()      - a function in giftops_class_inc.php used to create the form and retrieve information from the database
 */

if($param) {
    $this->objGift->getForm($this->id);
}
else {
    $this->objGift->createForm();
}
?>
