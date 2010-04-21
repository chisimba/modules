<?php
class block_contexttools extends object{
    function init(){
      $this->objLanguage = $this->getObject ( 'language', 'language' );
      $this->title=$this->objLanguage->languageText('mod_contextcontent_toolstitle', 'contextcontent');
    }

    function show(){
      return "Context tools -DO NOT USE";
    }
}
?>
