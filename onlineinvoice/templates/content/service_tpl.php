<?php
  /**
   *create a template for service expenses
   */     
   
   /**
    *create all language items
    */       
   $serviceinputs = $this->objLanguage->languageText('mod_onlinveinvoice_serviceinputs','onlineinvoice');
   $strserviceinfo  = strtoupper($serviceinputs);
   
   /**
    *load all classes
    */
   $this->objnextlink  = & $this->newObject('mouseoverpopup','htmlelements');     
    $this->loadClass('tabbedbox', 'htmlelements');
   /**
    *create all link elements
    */
    
    $urltext = 'YES - Go to service';
    $content = 'Complete any service expenses';
    $caption = '';
    $url = $this->uri(array('action'=>'NULL'));
    $this->objnextlink->mouseoverpopup($urltext,$content,$caption,$url);
    
    /**
     *create tabbed box
     */
     
     $objcreateinvtab = new tabbedbox();
     $objcreateinvtab->addTabLabel('Service Information');
     $objcreateinvtab->addBoxContent('<br>'  . $strserviceinfo . '<br />'.  '<br />' . $this->objnextlink->show() . '<br />');         
    /**
     *display screen output
     */
     
     echo $objcreateinvtab->show();
     //echo '<br />' . $this->objnextlink->show();
     
                                   
   
   
?>
