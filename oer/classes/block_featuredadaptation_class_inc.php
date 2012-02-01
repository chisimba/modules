<?php
/**
 * This displays featured adaptations
 *
 * @author pwando
 */
class block_featuredadaptation extends object {

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title= $this->objLanguage->languageText('mod_oer_featured', 'oer');
    }

    function show() {
        return "Featured adaptations";
    }
}
?>