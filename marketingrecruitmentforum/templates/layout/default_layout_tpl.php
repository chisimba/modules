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
  *create link elements and add to the left column
  */
  $urltext = 'SLU Activity ';
  $content = 'Choose a specific activity';
  $caption = '';
  $url = $this->uri(array('action'=>'activitylist'));
  $this->objcreateactivity = & $this->newObject('mouseoverpopup','htmlelements');
  $this->objcreateactivity->mouseoverpopup($urltext,$content,$caption,$url); 
  
  $urltext = 'School List';
  $content = 'Capture details pertaining to a school';
  $caption = '';
  $url = $this->uri(array('action'=>'shoollist'));
  $this->objschoollist = & $this->newObject('mouseoverpopup','htmlelements');
  $this->objschoollist->mouseoverpopup($urltext,$content,$caption,$url);   

  $urltext = 'Student Cards';
  $content = 'Issue a student card';
  $caption = '';
  $url = $this->uri(array('action'=>'studentcard'));
  $this->objcreatestudcard = & $this->newObject('mouseoverpopup','htmlelements');
  $this->objcreatestudcard->mouseoverpopup($urltext,$content,$caption,$url);   

  $leftcolumn = '<br />' . $this->objcreateactivity->show() . '<br />' . $this->objschoollist->show() . '<br />' . $this->objcreatestudcard->show();
 /**
   *create an instance of the class defaultpageutils 
   *this defines the content of the left and right columns
   */
   $this->objpageutils  = & $this->getObject('defaultpageutils','marketingrecruitmentforum');
   $rightcolumn = $this->objpageutils->getQuickSearchBox(); 
  
     
        
 /**
   *add contents to layout columns
   */ 
   $cssLayout->setLeftColumnContent($leftcolumn);
   $cssLayout->setRightColumnContent($rightcolumn);
   $cssLayout->setMiddleColumnContent($this->getContent());
   
 /**
   *display the layout
   */
   echo  $cssLayout->show();
        
?>
