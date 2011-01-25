<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
// end security check

/**
 *
 * libraryforms
 *
 * libraryforms allows students or distant user to request books online
 *
 * @category  Chisimba
 * @package   libraryforms
 * @author    Brenda Mayinga brendamayinga@ymail.com
 */
//$objEditForm = $this->getObject('editform', 'libraryforms');
$objBookThesis = $this->getObject('bookthesis', 'libraryforms');
$objFeedbk = $this->getObject('feedbk', 'libraryforms');
$objILLperiodical = $this->getObject('illperiodical', 'libraryforms');
$tab = $this->newObject('tabbedbox', 'htmlelements');
$table = $this->newObject('htmltable', 'htmlelements');
$tabcontent = $this->newObject('tabcontent', 'htmlelements');
$objTable = $this->newObject('htmltable', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$objForm = new form('myform', $this->uri(array('action' => 'valform'), 'htmlelements'));
$this->objUser = $this->getObject('User', 'security');


$category = 'user';

echo '<br/><center>' . $objFeedbk->show() . '</center>';
?>

