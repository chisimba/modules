<?php
  /**
   *create a template for service expenses
   */     
/****************************************************************************************************************************************/   
   /**
    *load all classes
    */
   $this->objnextlink  = & $this->newObject('mouseoverpopup','htmlelements');     
    $this->loadClass('tabbedbox', 'htmlelements');
    
/****************************************************************************************************************************************/    
   /**
    *create all language items
    */       
   $serviceinputs = $this->objLanguage->languageText('mod_onlinveinvoice_serviceinputs','onlineinvoice');
   $strserviceinfo  = strtoupper($serviceinputs);
   $nextCategory  = $this->objLanguage->languageText('phrase_nomovetonextcategory');
   $nextcatcontent = $this->objLanguage->languageText('mod_onlineinvoice_nextcaption','onlineinvoice');
/****************************************************************************************************************************************/
   
   $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
   $this->objMainheading->type=1;
   $this->objMainheading->str=$objLanguage->languageText('mod_onlineinvoice_webbasedinvoicingsystem','onlineinvoice');
/****************************************************************************************************************************************/
   
   
   /**
    *create all link elements
    */
    
    $urltext = 'YES - Go to service';
    $content = 'Complete any service expenses';
    $caption = '';
    $url = $this->uri(array('action'=>'displayserviceinfo'));
    $this->objnextlink->mouseoverpopup($urltext,$content,$caption,$url);
    
    $urltext = $nextCategory;
    $content = $nextcatcontent;
    $caption = '';
    $url = $this->uri(array('action'=>'null'));
    $this->objnextcat  = & $this->newObject('mouseoverpopup','htmlelements');
    $this->objnextcat->mouseoverpopup($urltext,$content,$caption,$url);

/****************************************************************************************************************************************/    
    /**
     *create tabbed box
     */
     
     $objcreateinvtab = new tabbedbox();
     $objcreateinvtab->addTabLabel('Service Information');
     $objcreateinvtab->addBoxContent('<br>'  . $strserviceinfo . '<br />'.  '<br />' . $this->objnextlink->show(). ' ' .$this->objnextcat->show(). '<br />');
/****************************************************************************************************************************************/              
    /**
     *display screen output
     */
     echo  "<div align=\"center\">" . $this->objMainheading->show() . "</div>";
     echo '<br />';
     echo $objcreateinvtab->show();
     //echo '<br />' . $this->objnextlink->show();
     
                                   
   
   
?>
