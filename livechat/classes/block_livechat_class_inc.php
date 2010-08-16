<?php

class block_livechat extends object {

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objAltConfig = $this->getObject('altconfig', 'config');

        $this->title = "Live Chat"; // $this->$objLanguage->languageText('mod_livechat_title', 'livechat', 'Live Chat');
    }

    function show() {
        $siteRoot = $this->objAltConfig->getsiteRoot();
        $objWasher = $this->getObject("washout", "utilities");
        $applet = "[IFRAME]".$siteRoot."?module=livechat|200|400[/IFRAME]";

        return $objWasher->parseText($applet);
    }

}
?>
