<?php
//echo '<pre>'.$buffer.'</pre>';
/*
* Template for main essay management page.
* @package essay
*/

/**************** Set Layout template ***************************/
$this->setLayoutTemplate('essay_layout_tpl.php');

$this->loadclass('htmltable','htmlelements');
$this->loadclass('link','htmlelements');

/**************** Display Page data *****************************/


echo $list;


if ($objUser->isCourseAdmin($this->contextcode)) {
    $link = new link ($this->uri(NULL, 'essayadmin'));
    $link->link = $this->objLanguage->languageText('mod_essayadmin_desc', 'essayadmin', 'Essay Management');

    echo $link->show();
}

?>