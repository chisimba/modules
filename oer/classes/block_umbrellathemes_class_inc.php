<?php

/**
 * Lists current product themes
 *
 * @author davidwaf
 */
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('link', 'htmlelements');
class block_umbrellathemes extends object {

    public $objLanguage;
    private $objDBThemeManager;

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objDBThemeManager = $this->getObject('dbumbrellathemes', 'oer');
    }

    function show() {
        return $this->createThemeListing();
    }

    function createThemeListing() {
        $objIcon = $this->newObject('geticon', 'htmlelements');
        $header = new htmlheading();
        $header->type = 4;
        $header->str = $this->objLanguage->languageText('mod_oer_umbrellathemes', 'oer');
        $themes = $this->objDBThemeManager->getUmbrellaThemes();
        $content = '<div id="umbrellatheme">';
        $content.='<ul id="umbrellatheme_ul">';
        foreach ($themes as $theme) {

            $editlink = new link($this->uri(array("action" => "editumbrellatheme", "id" => $theme['id'])));
            $objIcon->setIcon('edit');
            $editlink->link = $objIcon->show();

            $deletelink = new link($this->uri(array("action" => "confirmdeletetheme", "id" => $departmentid)));
            $objIcon->setIcon('delete');
            $deletelink->link = $objIcon->show();

            $content.='<li id="umbrellatheme_li">' . $theme['theme'] .$editlink->show().'&nbsp;'.$deletelink->show(). '</li>';
        }
        $content.='</ul>';
        $content.="</div>";
        return $header->show() . $content;
    }

}

?>
