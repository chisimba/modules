<?php
/**
 *create a template to define the basic form layout
 */
 
  /**
   *define the left column content
   *create an object of the leftcontent class that displays all links 
   */     
  $leftLayout = & $this->getObject('leftcontent', 'marketingrecruitmentforum');
  $leftcolumn   =  $leftLayout->leftColumnContent();
  $this->objModuleCatalogue = &$this->newObject('modules', 'modulecatalogue');
  $this->loadClass('link', 'htmlelements');
  $this->loadClass('htmlheading', 'htmlelements');
   /**
   *create an instance of the class defaultpageutils 
   *this defines the content of the left and right columns
   *adds the quicksearch box to the rightcolumn   
   */
  // $this->objUtils =& $this->newObject('defaultpageutils', 'studentenquiry');
  // echo $this->objUtils->getDefaultLayout($leftColumn,false,false);
 
     
    //Var to store left column content
     $left = "";
     //Add module name as heading
     $moduleInfo = $this->objModuleCatalogue->getModuleInfo($this->getParam('module'));
     $h = new htmlheading();
     $h->type = '3';
     $h->str = $moduleInfo['name'];
     $left .= $h->show();
     //Add introduction link below heading
     $introLink = new link();
     $introLink->link($this->uri(array(NULL), $this->getParam('module')));
     $introLink->link = $this->objLanguage->languageText('word_introduction');
     $left .= '<br />'.$introLink->show() . '<br />' . '<br />';
     //Add other headings and their links if any exist
     if(!empty($leftcolumn)){
       $h->type = '5';
       foreach($leftcolumn as $content){
          //Insert heading
          $h->str = $content['heading'];
          $left .= $h->show();
          //Build and insert each link
          if(!empty($content['links'])){
            $leftLinks = new link();
            foreach($content['links'] as $link){
               $leftLinks->link($this->uri($link['params'], $link['module']));
               $leftLinks->link = $link['linktext'];
               $left .= $leftLinks->show().'<br />';
            }
          }
       }
     }
     $cssLayout =& $this->newObject('csslayout', 'htmlelements');
     $cssLayout->setNumColumns(2);
     $cssLayout->setLeftColumnContent($left);
     $cssLayout->setMiddleColumnContent($this->getContent());
     echo $cssLayout->show();  
     
?>
