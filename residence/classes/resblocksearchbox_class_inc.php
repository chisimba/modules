<?php
/* ----------- data class extends dbTable for tbl_guestbook------------*/

/**
* Model class for the table  
* Creates the right-Quick Search box.
*/
class resblocksearchbox extends object
{
    /**
    * var object Property to hold language object
    */
    var $objLanguage;
    
	function show(){
        // Get an Instance of the language object
        $this->objLanguage = &$this->getObject('language', 'language');
       // $objHighlightLabels = $this->getObject('highlightlabels', 'htmlelements');
      //  $objHighlightLabels->css = '<style type="text/css" title="text/css">
      //     .checked { color:red; font-weight:bold; cursor:pointer; cursor:hand;
      //     } </style>';
      //  echo $objHighlightLabels->show();
		$objForm = new form('testform');
		$objForm->setAction($this->uri(array('action'=>'ressearch'),$this->getParam('module')));
		$objForm->setDisplayType(2);


		$searchid= new radio('searchid',null,null,15);
  
        $surlabel="&nbsp;".$this->objLanguage->languageText('mod_studentenquiry_surname','studentenquiry');
        $stdlabel="&nbsp;".$this->objLanguage->languageText('mod_studentenquiry_stdnum','studentenquiry');
        $idlabel="&nbsp;".$this->objLanguage->languageText('mod_studentenquiry_idnum','studentenquiry');
        $searchid->addOption('1', $surlabel);
        $searchid->setBreakSpace('&nbsp; &nbsp;');
        $searchid->addOption('2', $stdlabel);
        $searchid->setBreakSpace('&nbsp; &nbsp;');
        $searchid->addOption('3', $idlabel);
        $searchid->setTableColumns(1);
        $searchid->setSelected('1');
        $srchlbl="<strong>" . "&nbsp;".$this->objLanguage->languageText('mod_studentenquiry_search','studentenquiry') . ":</strong> ";
        $search= new textinput('search',null,null,15);
        $search->setValue($this->getParam('search'));
       /* $dispcount = new radio('dispcount',null,null,15);
        $dispcount->setBreakSpace(' ');
        $dispcount->addOption('10', '&nbsp;10');
        $dispcount->addOption('25', '&nbsp;25');
        $dispcount->addOption('50', '&nbsp;50');
        $dispcount->setSelected('10');
        */
		$save= new button('save');
		$save->setToSubmit();
		$save->setValue('Search');

        $objForm->addToForm($srchlbl);
        $objForm->addToForm($searchid->showTable());
        $objForm->addToForm($search);
        
        $resppg=$this->objLanguage->languageText('mod_studentenquiry_resultsperpage','studentenquiry').":";
		$objForm->addToForm($resppg);
		//$objForm->addToForm($dispcount->showNormal());
		$objForm->addToForm($save);
  
		$objElement = new tabbedbox();
		$objElement->addTabLabel($this->objLanguage->languageText('mod_studentenquiry_quicksrch','studentenquiry'));
		$objElement->addBoxContent($objForm->show());	
		return $objElement->show();
	}

}
