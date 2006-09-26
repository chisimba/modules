<?php
/**
 *create a template to define the basic form layout
 */
 
 /**
  *define the layout of the form
  *create an instance of the ccslayout class
  */
  $cssLayout =& $this->newObject('csslayout', 'htmlelements');
  $cssLayout->setNumColumns(3);

/**
 *create an instance of the class defaultpageutils 
 *this defines the content of the left and right columns
 */
 $this->objpageutils  = && $this->getObject('defaultpageutils','marketingrecruitmentforum');
 
 $rightcolumn = $this->objpageutils->getQuickSearchBox(); 
     
        
/**
 *add contents to layout columns
 */ 
  $cssLayout->setLeftColumnContent('to-be-completed' );
  $cssLayout->setRightColumnContent($rightcolumn));
  $cssLayout->setMiddleColumnContent($this->getContent());
   
/**
 *display the layout
 */
 
 echo  $cssLayout;
        
?>
