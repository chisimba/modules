<?php

  /**
   *create a template to view outputs / add another travel expense
   */     

  $this->loadClass('htmlheading', 'htmlelements');
  $this->loadClass('tabbedbox','htmlelements');
  $this->loadClass('mouseoverpopup','htmlelements');
  
  
  /**
   *create all language items
   */
       
  $travelsheet  = ucfirst($this->objLanguage->languageText('mod_onlineinvoice_travellabel','onlineinvoice'));
  $viewoutput = ucfirst($this->objLanguage->languageText('mod_onlineinvoice_viewoutput','onlineinvoice'));
  $addtev = ucfirst($this->objLanguage->languageText('mod_onlineinvoice_addtev','onlineinvoice'));
  $tooltipcontent = ucfirst($this->objLanguage->languageText('mod_onlineinvoice_tip','onlineinvoice'));
  $addtip = ucfirst($this->objLanguage->languageText('mod_onlineinvoice_addtip','onlineinvoice'));
  
  
  $strheading  = strtoupper($travelsheet);
  $objtravelsheet  = new htmlHeading();
  $objtravelsheet->type  = 1;
  $objtravelsheet->str = $strheading;
     
  $urltext = $viewoutput;
  $content = $tooltipcontent;
  $caption = '';
  $url = $this->uri(array('action'=>'viewtraveloutput'));
  $this->objcreatelink  = & $this->newObject('mouseoverpopup','htmlelements');
  $this->objcreatelink->mouseoverpopup($urltext,$content,$caption,$url);
  
  $urltext = $addtev;
  $content = $addtip;
  $caption = '';
  $url = $this->uri(array('action'=>'addanothertev'));
  $this->objlink  = & $this->newObject('mouseoverpopup','htmlelements');
  $this->objlink->mouseoverpopup($urltext,$content,$caption,$url);
     
       
  $objtabbedinfo = new tabbedbox();
  //$objtabbedinfo->addTabLabel('');
  $objtabbedinfo->addBoxContent("<div align=\"center\">" . '<br />'. $this->objcreatelink->show() .  $this->objlink->show() .'<br />'."</div>");
  
  echo "<div align=\"center\">" . $objtravelsheet->show() . "</div>";
  echo "<div align=\"left\">" . $objtabbedinfo->show() . "</div>";
  
     
?>
