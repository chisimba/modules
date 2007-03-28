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
      //create a template that shows all SD Cases of student info cards captured
      
      /**
       *load all classes
       */
       $this->loadClass('datepicker','htmlelements');
       $this->loadClass('htmlheading','htmlelements');
       $this->loadClass('datepicker','htmlelements');
       $this->loadClass('link','htmlelements');
       
       /**
        * language elements
        */                
       $this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
       $this->objreport  = & $this->newObject('reportinfo','marketingrecruitmentforum');
       $this->objstudcard = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
       $this->objreport  = & $this->newObject('reportinfo','marketingrecruitmentforum');
       
       $totresult = $this->objstudcard->allsdcases($where =  'where sdcase = 1 and exemption = 0');
       $totcount  = 0;

       for($i=0; $i< count($totresult); $i++){
          $totcount  = $totresult[$i]->SDRESULT;
       }
       $displaytot  = ':' . $totcount;
/*------------------------------------------------------------------------------*/       
      /**
        *create form heading
        */
        $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
        $this->objMainheading->type=1;
        $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_reportsd','marketingrecruitmentforum');
        
        $this->objTotheading =& $this->newObject('htmlheading','htmlelements');
        $this->objTotheading->type=3;
        $this->objTotheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_totalsd1','marketingrecruitmentforum') .$displaytot ;
        
        $this->objdate =& $this->newObject('htmlheading','htmlelements');
        $this->objdate->type=3;
        $this->objdate->str=$objLanguage->languageText('word_date1'). ':' .date('Y-m-d');
/*------------------------------------------------------------------------------*/ 
      /**
       *display all students that are sd cases
       */             
       $sdcases = $this->objreport->displaysdcases();
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
       $objForm->addToForm($this->objMainheading->show().'<br />' .$this->objTotheading->show().$this->objdate->show().'<br />'.$sdcases);
       
       /**
       *display contents to screen
       */
       
       $frmElements = $objForm->show().'<br />';   
       $printView='<div id="printFocus">'.$frmElements.'</div>';
       echo $printView;
       echo  $this->objPrint->show(); 			

/*------------------------------------------------------------------------------*/                    
?>
