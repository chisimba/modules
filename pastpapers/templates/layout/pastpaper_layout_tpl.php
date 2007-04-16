<?php
$this->_objDBContext = $this->getObject('dbcontext','context');
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
    
$instructions = $this->objLanguage->languageText('mod_pastpapers_instructions','pastpapers');
$contextCode = $this->_objDBContext->getContextCode();

if($contextCode){
$contextName = $this->_objDBContext->getTitle($contextCode);}
else {

$contextName = $this->objLanguage->languageText('mod_pastpapers_lobby','pastpapers');
}

$content = "";
$content .= $contextName."<br/><br/>";
$content .= $instructions;
$cssLayout->setLeftColumnContent($content);
$cssLayout->setMiddleColumnContent($head.$this->getContent());

echo $cssLayout->show();


?>