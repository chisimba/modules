<?php

/**
 * contains util methods for managing themes
 *
 * @author davidwaf
 */
class thememanager extends object {

    public $objDbThemes;
    public $objDbUmbrellaThemes;
    private $objLanguage;

    function init() {
        $this->objDbThemes = $this->getObject('dbthemes', 'oer');
        $this->objDbUmbrellaThemes = $this->getObject('dbumbrellathemes', 'oer');
        $this->objLanguage = $this->getObject('language', 'language');
    }

    function addNewUmbrellaTheme() {
        $errors = array();
        $title = $this->getParam('title');
        if ($title == '') {
            $errors[] = $this->objLanguage->languageText('mod_oer_title', 'oer');
        }
        if (count($errors) > 0) {
            $this->setVar('fieldsrequired', 'true');
            $this->setVar('errors', $errors);
            $this->setVar('title', $title);
            $this->setVar('mode', "fixup");

            return "addeditumbrellatheme_tpl.php";
        } else {

            $this->objDbThemes->addTheme($title);

            return "themes_tpl.php";
        }
    }

    function addNewTheme() {
        $errors = array();
        $title = $this->getParam('title');
        if ($title == '') {
            $errors[] = $this->objLanguage->languageText('mod_oer_title', 'oer');
        }
        $umbrellaTheme = $this->getParam("umbrellatheme");
        if (count($errors) > 0) {
            $this->setVar('fieldsrequired', 'true');
            $this->setVar('errors', $errors);
            $this->setVar('title', $title);
            $this->setVar('mode', "fixup");

            return "addedittheme_tpl.php";
        } else {

            $this->objDbThemes->addTheme($title,$umbrellaTheme);

            return "themes_tpl.php";
        }
    }

}

?>
