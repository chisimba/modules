<?php

/**
 * creates the control panel
 *
 * @author davidwaf
 */
$this->loadClass('link', 'htmlelements');

class block_cpanel extends object {

    public $objLanguage;
    public $objConfig;

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objConfig = $this->getObject('altconfig', 'config');
    }

    function show() {
        $skinName = $this->objConfig->getdefaultSkin();
        $cPanelTable = $this->getObject("htmltable", "htmlelements");
        
        $cPanelTable->startRow();
       
        $cplink = new link($this->uri(array("action" => "viewthemes")));
        $cplink->link = '<img  src="skins/' . $skinName . '/images/product_theme.png" align="bottom"><br/>' . $this->objLanguage->languageText('mod_oer_productthemes', 'oer');
        $cPanelTable->addCell($cplink->show(), null, "top");
        
        $cplink = new link($this->uri(array("action" => "viewkeywords")));
        $cplink->link = '<img  src="skins/' . $skinName . '/images/keyword.png" align="bottom"><br/>' . $this->objLanguage->languageText('mod_oer_keywords', 'oer');
        $cPanelTable->addCell($cplink->show(), null, "top");
       
        $cPanelTable->endRow();
        
        
        return $cPanelTable->show();
    }

}

?>
