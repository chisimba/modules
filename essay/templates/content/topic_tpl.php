<?php
//echo '<pre>'.$buffer.'</pre>';
if (!$objUser->isCourseAdmin($this->contextcode)) {
    echo $content;
} else {
    $this->loadclass('link','htmlelements');
    $link = new link ($this->uri(array(), 'essayadmin'));
    $link->link = $this->objLanguage->languageText('mod_essayadmin_name', 'essayadmin', 'Essay Management');
    echo $link->show();
}
?>