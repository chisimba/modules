<script language="javascript">

			var gAutoprint = true; // Flag for whether or not to automatically call the print function

			function printFriendly()
			{
			if (document.getElementById!= null)
			{
			var html = '<HTML>\n<HEAD>\n';

			if (document.getElementsByTagName!= null)
			{
			var headTags = document.getElementsByTagName("head");
			if (headTags.length > 0)
			html += headTags[0].innerHTML;
			}

			html += '\n</HE' + 'AD>\n<BODY>\n';

			var printPageElem = document.getElementById("printFocus");

			if (printPageElem!= null)
			{
			html += printPageElem.innerHTML;
			}
			else
			{
			alert("Could not find the printReady section in the HTML");
			return;
			}

			html += '\n</BO' + 'DY>\n</HT' + 'ML>';

			var printWin = window.open("","printFocus");
			printWin.document.open();
			printWin.document.write(html);
			printWin.document.close();
			if (gAutoprint)
			printWin.print();
			}
			else
			{
			alert("Sorry, the printer friendly feature works\nonly in javascript enabled browsers.");
			}
			}
</script>
<?php
//template to display report showing all students that qualify for entry
    /**
       *load all classes
       */
       $this->loadClass('datepicker','htmlelements');
       $this->loadClass('htmlheading','htmlelements');
       $this->loadClass('datepicker','htmlelements');
       
       $this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
       $this->objreport  = & $this->newObject('reportinfo','marketingrecruitmentforum');
       $this->objstudcard = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
       $this->objreport  = & $this->newObject('reportinfo','marketingrecruitmentforum');
       
       $total = $this->objstudcard->allstudq($where = 'where exemption = 1 AND sdcase = 0');
       $this->setSession('total',$total);
       $totcount  = 0;
       
      $tot = 0;
      for($i=0; $i< count($total); $i++){
      
          $tot  = $total[$i]->ENTRY;
      }
      $displaytot  = ':' . $tot;
      
/*------------------------------------------------------------------------------*/       
      /**
        *create form heading
        */
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_entryqualify1','marketingrecruitmentforum');
        
        $this->objheading =& $this->newObject('htmlheading','htmlelements');
        $this->objheading->type=3;
        $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_criteria22','marketingrecruitmentforum');
        
        $this->objtotal =& $this->newObject('htmlheading','htmlelements');
        $this->objtotal->type=3;
        $this->objtotal->str=$objLanguage->languageText('mod_marketingrecruitmentforum_entrytotal1','marketingrecruitmentforum') .$displaytot ;
        
        $this->objdate =& $this->newObject('htmlheading','htmlelements');
        $this->objdate->type=3;
        $this->objdate->str=$objLanguage->languageText('word_date1'). ':' .date('Y-m-d');
/*------------------------------------------------------------------------------*/
      /**
       *display all students that have relevant subject and exemption cases
       */             
      $results  =  $this->objreport->entryQualification();
/*------------------------------------------------------------------------------*/
      /**
       *create print button
       */
        $this->objPrint  = $this->newObject('button','htmlelements');
        $this->objPrint->name = 'Print_Report';
        $this->objPrint->setValue('Print Report'); 
        $this->objPrint->setOnclick('printFriendly();');
/*------------------------------------------------------------------------------*/
                   
     /**
       *Place elements in a form
       */
       $objForm = new form('reportSDval',$this->uri(array('action'=>'reportdropdown')));
       $objForm->displayType = 3;
       $objForm->addToForm($this->objMainheading->show() . '<br />' .$this->objheading->show().$this->objtotal->show().$this->objdate->show().'<br />' . $results.'<br/>');
       
       /**
       *display contents to screen
       */
       
       $frmElements = $objForm->show().'<br />';   
       $printView='<div id="printFocus">'.$frmElements.'</div>';
       echo $printView;
       echo  $this->objPrint->show(); 			
/*------------------------------------------------------------------------------*/
?>
