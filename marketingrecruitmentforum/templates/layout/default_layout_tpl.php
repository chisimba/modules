<?php
/**
 *create a template to define the basic form layout
 */
 
  /**
   *define the left column content
   *create an object of the leftcontent class that displays all links 
   */     
  $leftLayout =& $this->getObject('leftcontent', 'marketingrecruitmentforum');
  $leftcolumn   =  $leftLayout->leftColumnContent();
 
  /**
   *create an instance of the class defaultpageutils 
   *this defines the content of the left and right columns
   *adds the quicksearch box to the rightcolumn   
   */
   
   $this->objUtils  = & $this->getObject('defaultpageutils','semsutilities');
  //$rightcolumn = $this->objpageutils->getQuickSearchBox(); -- used within the defaultutils class therefore dont need to specify 
  
   $displaycontent = $this->objUtils->getDefaultLayout($leftcolumn,true,false);  
   //$displaycontent->css_class = 'div#wrapper';  
  echo  $displaycontent;
    
?>
