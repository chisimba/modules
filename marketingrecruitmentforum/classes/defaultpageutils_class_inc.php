<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* This object generates defult elements and page layouts used in sems modules
* @package 
* @category utilities
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Warren Windvogel
*/

class defaultpageutils extends object 
{
  /**
   * The User object
   *
   * @access private
   * @var object
   */
  protected $_objUser;
 
	public function init()
	{
		try {
      //Load Classes
      $this->loadClass('form', 'htmlelements');
      $this->loadClass('radio', 'htmlelements');
      $this->loadClass('textinput', 'htmlelements');
      $this->loadClass('button', 'htmlelements');
      $this->loadClass('tabbedbox', 'htmlelements');
      $this->loadClass('link', 'htmlelements');
      $this->loadClass('htmlheading', 'htmlelements');
			$this->_objUser = & $this->newObject('user', 'security');
			$this->objLanguage = &$this->newObject('language', 'language');
			$this->objModuleCatalogue = &$this->newObject('modules', 'modulecatalogue');
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
        }
		
	}

  /**
   * Method to return the quick search box
   *
   * @return string $quickSearchBox Generated html for quick search box
   * @access public
   * @author Warren Windvogel
   * @author Abdul Meyer
   *
   *     Remember to add the following to your register.conf file
   *     USES: word_introduction|Introduction|Introduction
   *     USES: word_surname|Surname|Surname
   *     USES: phrase_studentnumber|Student number|Student number
   *     USES: phrase_idnumber|ID number|ID number
   *     USES: word_search|Search|Search          
   *     USES: phrase_resultsperpage|Results per page|Results per page       
   *     USES: phrase_quicksearch|Quick search|Quick search              
   */
  public function getQuickSearchBox()
  {
      // Get an Instance of the language object
      
      //$objHighlightLabels = $this->getObject('highlightlabels', 'htmlelements');
      //$objHighlightLabels->css = '<style type="text/css" title="text/css">
      //.checked { color:red; font-weight:bold; cursor:pointer; cursor:hand;
      //} </style>';
      //echo $objHighlightLabels->show();
      
      $objForm = new form('testform');
		  $objForm->setAction($this->uri(array('action'=>'search'),$this->getParam('module')));
		  $objForm->setDisplayType(2);


		  $searchid= new radio('searchid',null,null,15);
      //  $searchid->cssClass = "checked";
      $surlabel="&nbsp;".$this->objLanguage->languageText('word_surname');
      $stdlabel="&nbsp;".$this->objLanguage->languageText('phrase_studentnumber');
      $idlabel="&nbsp;".$this->objLanguage->languageText('phrase_idnumber');
      $searchid->addOption('1', $surlabel);
      $searchid->setBreakSpace('&nbsp; &nbsp;');
      $searchid->addOption('2', $stdlabel);
      $searchid->setBreakSpace('&nbsp; &nbsp;');
      $searchid->addOption('3', $idlabel);
      $searchid->setTableColumns(1);
      $searchid->setSelected('1');
      $srchlbl="<h3>" . "&nbsp;".$this->objLanguage->languageText('word_search') . ":</h3> ";
      $search= new textinput('search',null,null,15);
      $search->setValue($this->getParam('search'));
      $dispcount = new radio('dispcount',null,null,15);
      //$dispcount->cssClass = "checked";
      $dispcount->setBreakSpace(' ');
      $dispcount->addOption('10', '&nbsp;10');
      $dispcount->addOption('25', '&nbsp;25');
      $dispcount->addOption('50', '&nbsp;50');
      $dispcount->setSelected('10');
        
		  $save= new button('save');
		  $save->setToSubmit();
		  $save->setValue('Search');

      $objForm->addToForm($srchlbl);
      $objForm->addToForm($searchid->showTable());
      $objForm->addToForm($search);
        
      $resppg=$this->objLanguage->languageText('phrase_resultsperpage').":";
		  $objForm->addToForm($resppg);
		  $objForm->addToForm($dispcount->showNormal());
		  $objForm->addToForm($save);
  
		  $objElement = new tabbedbox();
		  $objElement->addTabLabel($this->objLanguage->languageText('phrase_quicksearch'));
		  $objElement->addBoxContent($objForm->show());	
		  return $objElement->show();
  }
  /**
   * Method to return the default layout template with quick search box
   *
   * @param array $leftColmnContent An array of headings and links to be placed in the left side column
   * @return string $defaultLayout The generated html for default layout template
   * @access public
   * @author Warren Windvogel
   * @example :
   *     $firstHeadingLinks = array();
   *     $firstHeadingLinks[] = array('uri' => array("action" => "sumaction"), 'module' => 'modulelinkpointsto', 'linktext' => 'theTextToDisplay'); 
   *     $secondHeadingLinks = array();
   *     $secondHeadingLinks[] = array('uri' => array("action" => "anotheraction"), 'module' => 'modulelinkpointsto', 'linktext' => 'theTextToDisplay'); 
   *
   *     $leftColumn = array();
   *     $leftColumn[] = array('heading' => 'First Heading', 'links' => $firstHeadingLinks);
   *     $leftColumn[] = array('heading' => 'Second Heading', 'links' => $secondHeadingLinks);
   *
   *     $this->objUtils =& $this->newObject('defaultpageutils', 'studentenquiry');
   *     echo $this->objUtils->getDefaultLayout($leftColumn);
   *
   *     Remember to add the following to your register.conf file
   *     USES: word_introduction|Introduction|Introduction
   *     USES: word_surname|Surname|Surname
   *     USES: phrase_studentnumber|Student number|Student number
   *     USES: phrase_idnumber|ID number|ID number
   *     USES: word_search|Search|Search          
   *     USES: phrase_resultsperpage|Results per page|Results per page       
   *     USES: phrase_quicksearch|Quick search|Quick search              
   */
  public function getDefaultLayout($leftColmnContent)
  {
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
     $introLink->link($this->uri(array(NULL)));
     $introLink->link = $this->objLanguage->languageText('word_introduction');
     $left .= $introLink->show();
     //Add other headings and their links if any exist
     if(!empty($leftColmnContent)){
       $h->type = '5';
       foreach($leftColmnContent as $content){
          //Insert heading
          $h->str = $content['heading'];
          $left .= $h->show();
          //Build and insert each link
          if(!empty($content['links'])){
            foreach($content['links'] as $link){
               $leftLinks = new link();
               $leftLinks->link($this->uri($link['uri'], $link['module']));
               $leftLinks->link = $link['linktext'];
               $left .= $leftLinks->show();
            }
          }
       } 
     }  
     $cssLayout =& $this->newObject('csslayout', 'htmlelements');
     $cssLayout->setNumColumns(3);
     $cssLayout->setLeftColumnContent($left);
     $cssLayout->setRightColumnContent($this->getQuickSearchBox());
     $cssLayout->setMiddleColumnContent($this->objEngine->getContent());

     return $cssLayout->show();
  }
       
}
?>
