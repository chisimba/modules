<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
* This object generates data used to display search results for school information
* @package 
* @category sems
* @copyright 2005, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Colleen Tinker
*/

class searchschools extends object{ 
/**
 * Standard inti function
 * @param void
 * @return void
 */     
function init()
{
  	 try {
          $this->loadClass('dropdown', 'htmlelements');
          $this->loadClass('link', 'htmlelements');
          $this->loadClass('textinput', 'htmlelements');
          $this->objLanguage = &$this->newObject('language', 'language');
          $this->loadClass('htmltable','htmlelements');
          $this->objschool = & $this->newObject('dbschoollist','marketingrecruitmentforum');
  	 }catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
         	exit();
     }
}
/*------------------------------------------------------------------------------*/
/**
 * Method used to create a table to display school information
 * @param array obj $results,contains resulset of all school information
 * loop through contents of $results and create table rows
 * @return obj $myTable  
 */     
public  function  getAllschools(){
    $details = '';
    $records = '';
    $paging = '';  

       //$results = $this->objschool->getallsschools();
    $res = $this->objschool->getallsschools();
		$stdCount=count($res); 
    $startat = $this->getParam('startat', 1);
    $dispCount = $this->getParam('dispcount', 2);
    $page = $this->getParam('page', '1');

//if(is_array($stdinfo)){
  //    $cnt = count($stdinfo);
if ($stdCount > 0){
      //***start of pages***
     $links_code = "";
     $pageCount = $stdCount/$dispCount;

     $showlinks =& $this->getObject('htmlHeading','htmlelements');
     if ($pageCount != floor($pageCount)) {
         $pageCount = strtok(($pageCount+1), ".");
     }
     $startat = ($page - 1) * $dispCount;
     $endat = $startat + $dispCount;
     if ($endat > $stdCount){
        $endat = $stdCount;
     }
     $dispcountfield = new textinput("dispcount", $dispCount,  "hidden", NULL);
     $goButton = & $this->newObject('button','htmlelements');
   	 $cancelButton = & $this->newObject('button','htmlelements');
     $goButton = new button("submit",'Go');
     $goButton->setToSubmit();
	   $cancelButton = new button("cancel", 'Cancel');
     $cancelButton->setToSubmit();
     $dropdown =& $this->newObject("dropdown", "htmlelements");
	   $dropdown->name = 'page';
    	for ($i = 1; $i <= $pageCount; $i++){
    	    $dropdown->addOption($i,$i."&nbsp;&nbsp;&nbsp;");
            if ($i == $page){
                $dropdown->setSelected($i);
            }
    	}
      if ($page > 1){
          $prevLink = new link();
          $prevLink->href=$this->uri(array('action'=>'showsallearchslu','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'nextpgschoolsearch','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'nextpgschoolsearch', 'page'=>($page-1), 'dispcount'=>$dispCount)));
	$objPaging->addToForm('<center>'.'Page '.$dropdown->show().' of '.'<b>'.$pageCount.'</b>'.' '.$goButton->show()."&nbsp;&nbsp;&nbsp;&nbsp;"."<span class='menulink'><b>".$prev."&nbsp;&nbsp;".$next.'</b></span></center>');
    $paging = $objPaging->show();

    $records = array(
      'RANGE' => ($startat+1).'-'.$endat,
      'TOTAL' => $stdCount);


				
		$start=$startat+1;
     $viewpages = new link();
	
     for ($n=0; $n < $pageCount; $n++) {
         $stdCountR = ($n * $dispCount);
         $num = $n + 1;
         if ($num != $page){
            $viewpages->href=$this->uri(array('action'=>'nextpgschoolsearch','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
            $viewpages->link = "$num";
            $viewpages->style = "text-decoration:none";
            $links_code .= $viewpages->show();
         }else{
            $links_code .= "$num";
         }
         if ($num == $pageCount){
              // $links_code .= " ";
         }else if($n < $pageCount){
            $links_code .= " | ";
         }
     }
     $endl = $startat + $dispCount;

     if($stdCount<$dispCount){
	     $endl=$stdCount;
     }
	 if ($endl > $stdCount){
        $endl = $stdCount;
     }
     
     $viewp ="";
     $viewn ="";

     if ($startat > 1){
        $page = $this->getParam('pg');
        $pg = $page - 1;
        $stdCountR = $startat - $dispCount;

        $viewprev = new link();
        $viewprev->href=$this->uri(array('action'=>'nextpgschoolsearch','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'nextpgschoolsearch','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewnext->link = 'Next';
        $viewnext->style = "text-decoration:none";
        $viewn = "| ".$viewnext->show();
     }

     $Rectbl =" ";
     $Rectbl.="Results <b>$start - $endl</b> of <b>$stdCount</b>";
     $records = $Rectbl;
     $showlinks->str = "$viewp $links_code $viewn";
     $showlinks->align="center";

      if($stdCount < $dispCount){
          $pagelinks = "";
      }else{
          $pagelinks = $showlinks->show();
      }
             //***end of pagination***
		$results = $this->objschool->schoollimitdata($startat,$endat);

     //if($results){   
       $schoolname = $this->objLanguage->languageText('phrase_schoolname');
       $schoolname1 = ucfirst($schoolname);
           
       $oddEven = 'even';
       $myTable =& $this->newObject('htmltable', 'htmlelements');
       $myTable->cellspacing = '1';
       $myTable->cellpadding = '2';
       $myTable->border='0';
       $myTable->width = '100%';
       $myTable->css_class = 'highlightrows';
  
       $myTable->startHeaderRow();
       $myTable->addHeaderCell($schoolname1, null,'top','left','header');
       $myTable->endHeaderRow();
     
       $rowcount = '0';
  
//    foreach($results as $sessCard){
      for($i=0; $i< count($results); $i++){
       
       $myTable->startRow();
       (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
       $myTable->addCell($results[$i]->SCHOOLNAME,"15%", null, "left",$oddOrEven);
       $myTable->row_attributes = " class = \"$oddOrEven\"";
       $rowcount++;
       $myTable->endRow();
        
    }  
   }else{
    
                 $oddEven = 'even';
                 $myTable =& $this->newObject('htmltable', 'htmlelements');
                 $myTable->cellspacing = '1';
                 $myTable->cellpadding = '2';
                 $myTable->border='0';
                 $myTable->width = '30%';
                 $myTable->css_class = 'highlightrows';
                 $myTable->row_attributes = " class = \"$oddEven\"";
                 
                 $rowcount = 0;
                 
                 $myTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                 $myTable->addCell('NO RECORDS FOUND',"15%", null, "left","$oddOrEven");
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
    
    }  
	$content  =$myTable->show();
  $content = "<center>".$details.$records.$paging."</center> ".$content;
  return $content;
}
/*------------------------------------------------------------------------------*/
/**
 * Method used to create a table to display information about a specific school
 * @param array obj $schoolbyname,contains resulset of all school information
 * loop through contents of $results and create table rows
 * if condition true, then display a no records found msg
 * @return obj $myTable  
 */  
public function schoolbyname($schoolbyname){
    
         $this->loadClass('link', 'htmlelements');      
         $SchoolLink = new link($this->uri(array('action' => 'captureschool', 'module' => 'marketingrecruitmentforum', 'linktext' => 'Capture school details')));
         $SchoolLink->link = 'Capture school details';
         
         /**
           * define language item text
           */
                $schoolname = $this->objLanguage->languageText('phrase_schoolname');
                $schoolname1 = ucfirst($schoolname);
                $address = $this->objLanguage->languageText('phrase_schooladdress');
                $address1 = ucfirst($address);
                $telnumber = $this->objLanguage->languageText('phrase_telnumber');
                $telnumber1 = ucfirst($telnumber);
                $faxno = $this->objLanguage->languageText('phrase_faxnumber');
                $faxno1 = ucfirst($faxno);
                $emailaddy = $this->objLanguage->languageText('phrase_emailaddress1');
                $emailaddy1 = ucfirst($emailaddy);
                $principal = $this->objLanguage->languageText('word_principal');
                $principal1 = ucfirst($principal);
                $guidance = $this->objLanguage->languageText('mod_marketingrecruitmentforum_guidteach','marketingrecruitmentforum');
                                     
        
         if(!empty($schoolbyname)){
                $oddEven = 'even';
                $myTable =& $this->getObject('htmltable', 'htmlelements');
                $myTable->cellspacing = '1';
                $myTable->cellpadding = '2';
                $myTable->border='0';
                $myTable->width = '100%';
                $myTable->css_class = 'highlightrows';
              
                $myTable->startHeaderRow();
                //$myTable->addHeaderCell($schoolname1, null,'top','left','header');
                $myTable->addHeaderCell($address1, null,'top','left','header');
                $myTable->addHeaderCell($telnumber1, null,'top','left','header');
                $myTable->addHeaderCell($faxno1, null,'top','left','header');
                $myTable->addHeaderCell($emailaddy1, null,'top','left','header');
                $myTable->addHeaderCell($principal1, null,'top','left','header');
               // $myTable->addHeaderCell($principal1, null,'top','left','header');
                $myTable->addHeaderCell('Principal Email', null,'top','left','header');
                $myTable->addHeaderCell('Principal cell no.', null,'top','left','header');
                $myTable->addHeaderCell('Guidance Teacher', null,'top','left','header');
                $myTable->addHeaderCell('Teacher Email', null,'top','left','header');
                $myTable->addHeaderCell('Teacher cell no.', null,'top','left','header');
                $myTable->endHeaderRow();
    
                $rowcount = '0';
    
 //           foreach($schoolbyname as $sessCard){
              for($i=0; $i< count($schoolbyname); $i++){
                       
                         
               $myTable->startRow();
               (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
               //$myTable->addCell($schoolbyname[$i]->SCHOOLNAME,"25%", null, "left",$oddOrEven);
               $myTable->addCell($schoolbyname[$i]->SCHOOLADDRESS, "25%", null, "left",$oddOrEven);
               $myTable->addCell($schoolbyname[$i]->TELCODE.' '.$schoolbyname[$i]->TELNUMBER, "25%", null, "left",$oddOrEven);
               $myTable->addCell($schoolbyname[$i]->FAXCODE.' '.$schoolbyname[$i]->FAXNUMBER, "25%", null, "left",$oddOrEven);      
               $myTable->addCell($schoolbyname[$i]->EMAIL, "25%", null, "left",$oddOrEven);      
               $myTable->addCell($schoolbyname[$i]->PRINCIPAL, "25%", null, "left",$oddOrEven);
               $myTable->addCell($schoolbyname[$i]->PRINCIPALEMAIL, "25%", null, "left",$oddOrEven);  
               $myTable->addCell($schoolbyname[$i]->PRINCIPALCELLNO, "25%", null, "left",$oddOrEven);        
               $myTable->addCell($schoolbyname[$i]->GUIDANCETEACHER, "25%", null, "left",$oddOrEven);
               $myTable->addCell($schoolbyname[$i]->GUIDANCETEACHEMAIL, "25%", null, "left",$oddOrEven);
               $myTable->addCell($schoolbyname[$i]->GUIDANCETEACHCELLNO, "25%", null, "left",$oddOrEven);
               $myTable->row_attributes = " class = \"$oddOrEven\"";
               $rowcount++;            
               $myTable->endRow();           
                         
                          
           }  
            
        }else{
    
                 $oddEven = 'even';
                 $myTable =& $this->newObject('htmltable', 'htmlelements');
                 $myTable->cellspacing = '1';
                 $myTable->cellpadding = '2';
                 $myTable->border='0';
                 $myTable->width = '30%';
                 $myTable->css_class = 'highlightrows';
                 $myTable->row_attributes = " class = \"$oddEven\"";
                 
                 $rowcount = 0;
                 
                 $myTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                 $myTable->addCell('NO RECORDS FOUND',"15%", null, "left","$oddOrEven");
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
    
    }  
                 return $myTable->show();     
}      
/*------------------------------------------------------------------------------*/
/**
 * Method used to create a table to display all schools by area
 * @param array obj $results,contains resulset of all school information by specific area
 * loop through contents of $results and create table rows
 * @return obj $myTable  
 */    
public  function schoolbyarea(){
    $details = '';
    $records = '';
    $paging = '';  

    //     $results = $this->objschool->getschoolbyarea();
    $res = $this->objschool->getschoolbyarea();
		$stdCount=count($res); 
    $startat = $this->getParam('startat', 1);
    $dispCount = $this->getParam('dispcount', 2);
    $page = $this->getParam('page', '1');

//if(is_array($stdinfo)){
  //    $cnt = count($stdinfo);
if ($stdCount > 0){
      //***start of pages***
     $links_code = "";
     $pageCount = $stdCount/$dispCount;

     $showlinks =& $this->getObject('htmlHeading','htmlelements');
     if ($pageCount != floor($pageCount)) {
         $pageCount = strtok(($pageCount+1), ".");
     }
     $startat = ($page - 1) * $dispCount;
     $endat = $startat + $dispCount;
     if ($endat > $stdCount){
        $endat = $stdCount;
     }
     $dispcountfield = new textinput("dispcount", $dispCount,  "hidden", NULL);
     $goButton = & $this->newObject('button','htmlelements');
   	 $cancelButton = & $this->newObject('button','htmlelements');
     $goButton = new button("submit",'Go');
     $goButton->setToSubmit();
	   $cancelButton = new button("cancel", 'Cancel');
     $cancelButton->setToSubmit();
     $dropdown =& $this->newObject("dropdown", "htmlelements");
	   $dropdown->name = 'page';
    	for ($i = 1; $i <= $pageCount; $i++){
    	    $dropdown->addOption($i,$i."&nbsp;&nbsp;&nbsp;");
            if ($i == $page){
                $dropdown->setSelected($i);
            }
    	}
      if ($page > 1){
          $prevLink = new link();
          $prevLink->href=$this->uri(array('action'=>'nextpgschoolsearch','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'nextpgschoolsearch','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'nextpgschoolsearch', 'page'=>($page-1), 'dispcount'=>$dispCount)));
	$objPaging->addToForm('<center>'.'Page '.$dropdown->show().' of '.'<b>'.$pageCount.'</b>'.' '.$goButton->show()."&nbsp;&nbsp;&nbsp;&nbsp;"."<span class='menulink'><b>".$prev."&nbsp;&nbsp;".$next.'</b></span></center>');
    $paging = $objPaging->show();

    $records = array(
      'RANGE' => ($startat+1).'-'.$endat,
      'TOTAL' => $stdCount);


				
		$start=$startat+1;
     $viewpages = new link();
	
     for ($n=0; $n < $pageCount; $n++) {
         $stdCountR = ($n * $dispCount);
         $num = $n + 1;
         if ($num != $page){
            $viewpages->href=$this->uri(array('action'=>'nextpgschoolsearch','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
            $viewpages->link = "$num";
            $viewpages->style = "text-decoration:none";
            $links_code .= $viewpages->show();
         }else{
            $links_code .= "$num";
         }
         if ($num == $pageCount){
              // $links_code .= " ";
         }else if($n < $pageCount){
            $links_code .= " | ";
         }
     }
     $endl = $startat + $dispCount;

     if($stdCount<$dispCount){
	     $endl=$stdCount;
     }
	 if ($endl > $stdCount){
        $endl = $stdCount;
     }
     
     $viewp ="";
     $viewn ="";

     if ($startat > 1){
        $page = $this->getParam('pg');
        $pg = $page - 1;
        $stdCountR = $startat - $dispCount;

        $viewprev = new link();
        $viewprev->href=$this->uri(array('action'=>'nextpgschoolsearch','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'nextpgschoolsearch','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewnext->link = 'Next';
        $viewnext->style = "text-decoration:none";
        $viewn = "| ".$viewnext->show();
     }

     $Rectbl =" ";
     $Rectbl.="Results <b>$start - $endl</b> of <b>$stdCount</b>";
     $records = $Rectbl;
     $showlinks->str = "$viewp $links_code $viewn";
     $showlinks->align="center";

      if($stdCount < $dispCount){
          $pagelinks = "";
      }else{
          $pagelinks = $showlinks->show();
      }
             //***end of pagination***
		$results = $this->objschool->schoolarealimit($startat,$endat);
     //if($results){
         /**
           * define all lanuguage text elements
           */
         $schoolname = $this->objLanguage->languageText('phrase_schoolname');
         $schoolname1 = ucfirst($schoolname);
         $area = $this->objLanguage->languageText('word_area');
         $area1 = ucfirst($area);
         
                                 
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
  
         $myTable->startHeaderRow();
         $myTable->addHeaderCell($schoolname1, null,'top','left','header');
         $myTable->addHeaderCell($area1, null,'top','left','header');
         $myTable->endHeaderRow();
         
         $rowcount = '0';
  
//    foreach($results as $sessCard){
      for($i=0; $i< count($results); $i++){
     
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->SCHOOLNAME,"25%", null, "left",$oddOrEven);
         $myTable->addCell($results[$i]->SCHOOLAREA,"25%", null, "left",$oddOrEven);
         $myTable->row_attributes = " class = \"$oddOrEven\"";
         $rowcount++; 
         $myTable->endRow();
        
    }  
  }else{
    
                 $oddEven = 'even';
                 $myTable =& $this->newObject('htmltable', 'htmlelements');
                 $myTable->cellspacing = '1';
                 $myTable->cellpadding = '2';
                 $myTable->border='0';
                 $myTable->width = '30%';
                 $myTable->css_class = 'highlightrows';
                 $myTable->row_attributes = " class = \"$oddEven\"";
                 
                 $rowcount = 0;
                 
                 $myTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                 $myTable->addCell('NO RECORDS FOUND',"15%", null, "left","$oddOrEven");
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
    
    }   
	$content  =$myTable->show();
  $content = "<center>".$details.$records.$paging."</center> ".$content;
  return $content;
       
  
}	
/*------------------------------------------------------------------------------*/
/**
 * Method used to create a table to display all schools by province
 * @param array obj $results,contains resulset of all school information by specific province
 * loop through contents of $results and create table rows
 * @return obj $myTable  
 */    
public function activitybyprov(){
    $details = '';
    $records = '';
    $paging = '';  
    
    //$results = $this->objschool->getschoolbyprovince();
    $res = $this->objschool->getschoolbyprovince();
		$stdCount=count($res); 
    $startat = $this->getParam('startat', 1);
    $dispCount = $this->getParam('dispcount', 2);
    $page = $this->getParam('page', '1');

//if(is_array($stdinfo)){
  //    $cnt = count($stdinfo);
if ($stdCount > 0){
      //***start of pages***
     $links_code = "";
     $pageCount = $stdCount/$dispCount;

     $showlinks =& $this->getObject('htmlHeading','htmlelements');
     if ($pageCount != floor($pageCount)) {
         $pageCount = strtok(($pageCount+1), ".");
     }
     $startat = ($page - 1) * $dispCount;
     $endat = $startat + $dispCount;
     if ($endat > $stdCount){
        $endat = $stdCount;
     }
     $dispcountfield = new textinput("dispcount", $dispCount,  "hidden", NULL);
     $goButton = & $this->newObject('button','htmlelements');
   	 $cancelButton = & $this->newObject('button','htmlelements');
     $goButton = new button("submit",'Go');
     $goButton->setToSubmit();
	   $cancelButton = new button("cancel", 'Cancel');
     $cancelButton->setToSubmit();
     $dropdown =& $this->newObject("dropdown", "htmlelements");
	   $dropdown->name = 'page';
    	for ($i = 1; $i <= $pageCount; $i++){
    	    $dropdown->addOption($i,$i."&nbsp;&nbsp;&nbsp;");
            if ($i == $page){
                $dropdown->setSelected($i);
            }
    	}
      if ($page > 1){
          $prevLink = new link();
          $prevLink->href=$this->uri(array('action'=>'nextpgschoolsearch','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'nextpgschoolsearch','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'nextpgschoolsearch', 'page'=>($page-1), 'dispcount'=>$dispCount)));
	$objPaging->addToForm('<center>'.'Page '.$dropdown->show().' of '.'<b>'.$pageCount.'</b>'.' '.$goButton->show()."&nbsp;&nbsp;&nbsp;&nbsp;"."<span class='menulink'><b>".$prev."&nbsp;&nbsp;".$next.'</b></span></center>');
    $paging = $objPaging->show();

    $records = array(
      'RANGE' => ($startat+1).'-'.$endat,
      'TOTAL' => $stdCount);


				
		$start=$startat+1;
     $viewpages = new link();
	
     for ($n=0; $n < $pageCount; $n++) {
         $stdCountR = ($n * $dispCount);
         $num = $n + 1;
         if ($num != $page){
            $viewpages->href=$this->uri(array('action'=>'nextpgschoolsearch','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
            $viewpages->link = "$num";
            $viewpages->style = "text-decoration:none";
            $links_code .= $viewpages->show();
         }else{
            $links_code .= "$num";
         }
         if ($num == $pageCount){
              // $links_code .= " ";
         }else if($n < $pageCount){
            $links_code .= " | ";
         }
     }
     $endl = $startat + $dispCount;

     if($stdCount<$dispCount){
	     $endl=$stdCount;
     }
	 if ($endl > $stdCount){
        $endl = $stdCount;
     }
     
     $viewp ="";
     $viewn ="";

     if ($startat > 1){
        $page = $this->getParam('pg');
        $pg = $page - 1;
        $stdCountR = $startat - $dispCount;

        $viewprev = new link();
        $viewprev->href=$this->uri(array('action'=>'nextpgschoolsearch','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'nextpgschoolsearch','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewnext->link = 'Next';
        $viewnext->style = "text-decoration:none";
        $viewn = "| ".$viewnext->show();
     }

     $Rectbl =" ";
     $Rectbl.="Results <b>$start - $endl</b> of <b>$stdCount</b>";
     $records = $Rectbl;
     $showlinks->str = "$viewp $links_code $viewn";
     $showlinks->align="center";

      if($stdCount < $dispCount){
          $pagelinks = "";
      }else{
          $pagelinks = $showlinks->show();
      }
             //***end of pagination***
		$results = $this->objschool->schoolprovlimit($startat,$endat);  
       //if($results){
         /**
           * define all lanuguage text elements
           */
         $schoolname = $this->objLanguage->languageText('phrase_schoolname');
         $schoolname1 = ucfirst($schoolname);  
         $province = $this->objLanguage->languageText('word_province');
         $province1 = ucfirst($province);                       

         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';

         $myTable->startHeaderRow();
         $myTable->addHeaderCell($schoolname1, null,'top','left','header');
         $myTable->addHeaderCell($province1, null,'top','left','header');
         $myTable->endHeaderRow();
        
        
         $rowcount = '0';
  
//     foreach($results as $sessCard){
    for($i=0; $i< count($results); $i++){
     
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->SCHOOLNAME,"25%", null, "left",$oddOrEven);
         $myTable->addCell($results[$i]->SCHOOLPROV, "25%", null, "left",$oddOrEven);
         $myTable->row_attributes = " class = \"$oddOrEven\"";
         $rowcount++;
         $myTable->endRow();
        
    }  
  }else{
    
                 $oddEven = 'even';
                 $myTable =& $this->newObject('htmltable', 'htmlelements');
                 $myTable->cellspacing = '1';
                 $myTable->cellpadding = '2';
                 $myTable->border='0';
                 $myTable->width = '30%';
                 $myTable->css_class = 'highlightrows';
                 $myTable->row_attributes = " class = \"$oddEven\"";
                 
                 $rowcount = 0;
                 
                 $myTable->startRow();
                (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                 $myTable->addCell('NO RECORDS FOUND',"15%", null, "left","$oddOrEven");
                 $myTable->row_attributes = " class = \"$oddOrEven\"";
                 $rowcount++;
                 $myTable->endRow();
    
    }    
	$content  =$myTable->show();
  $content = "<center>".$details.$records.$paging."</center> ".$content;
  return $content;
  
}
/*------------------------------------------------------------------------------*/
/**
 * Method used to create a table to display school information, if school exist within the db... else display a link that allows user to capture details for the school selected
 * @param array obj $schoolbyname,contains resulset of all school information 
 * loop through contents of $schoolbyname and create table rows
 * @return obj $myTable  
 */
public function schoolexisting($schoolbyname){
        /**
          * define all language items
          */
   
         $schoolname = $this->objLanguage->languageText('phrase_schoolname');
         $schoolname1 = ucfirst($schoolname);
         $address = $this->objLanguage->languageText('phrase_schooladdress');
         $address1 = ucfirst($address);
         $area = $this->objLanguage->languageText('word_area');
         $area1 = ucfirst($area);
         $province = $this->objLanguage->languageText('word_province');
         $province1 = ucfirst($province); 
         $telnumber = $this->objLanguage->languageText('phrase_telnumber');
         $telnumber1 = ucfirst($telnumber);
         $faxno = $this->objLanguage->languageText('phrase_faxnumber');
         $faxno1 = ucfirst($faxno);
         $emailaddy = $this->objLanguage->languageText('phrase_emailaddress');
         $emailaddy1 = ucfirst($emailaddy);
         $principal = $this->objLanguage->languageText('phrase_principal');
         $principal1 = ucfirst($principal);
         $guidance = $this->objLanguage->languageText('mod_marketingrecruitmentforum_guidteach','marketingrecruitmentforum');
         $principalEmailAddy  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_principalemailaddy','marketingrecruitmentforum');
         $prinicipalCellNo  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_principalcellno','marketingrecruitmentforum');
         $guidanceteachemail  = $this->objLanguage->languageText('mod_marketingrecruitmentforum_guidanceteachemail','marketingrecruitmentforum');
         $guidanceteachcellno1 = $this->objLanguage->languageText('mod_marketingrecruitmentforum_guidanceteachcellno1','marketingrecruitmentforum');
                                         
         $this->loadClass('link', 'htmlelements');      
         $SchoolLink = new link($this->uri(array('action' => 'captureschool', 'module' => 'marketingrecruitmentforum', 'linktext' => 'Capture school details')));
         $SchoolLink->link = 'Capture School Details';
         
         $Link = new link($this->uri(array('action' => 'showschooloutput', 'module' => 'marketingrecruitmentforum', 'linktext' => 'Edit school details')));
         $Link->link = 'Edit school details';
         
         $objLanguage =& $this->getObject('language', 'language');
         $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
         $this->objMainheading->type=3;
         $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_schooldet','marketingrecruitmentforum');
         
         $name  = $this->getSession('nameschool');
        
         if(!empty($schoolbyname)){

                for($i=0; $i< count($schoolbyname); $i++){
                    $rowcount = 0;
                    $oddOrEven = '';
                    $myTable =& $this->getObject('htmltable', 'htmlelements');
                    $myTable->cellspacing = '1';
                    $myTable->cellpadding = '2';
                    $myTable->border='0';
                    $myTable->width = '50%';
                    
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($this->objMainheading->show(),"15%", null, "left",$oddOrEven);
                    $myTable->addCell('<b>'. $name . '</b>',"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
              
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell("","15%", null, "left",$oddOrEven);
                    $myTable->addCell("","15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell("","15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell("","15%", null, "left",$oddOrEven);
                    $myTable->addCell("","15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($schoolname1,"15%", null, "left",$oddOrEven);
                    $myTable->addCell($schoolbyname[$i]->SCHOOLNAME,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($address1,"15%", null, "left",$oddOrEven);
                    $myTable->addCell($schoolbyname[$i]->SCHOOLADDRESS,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($area1,"15%", null, "left",$oddOrEven);
                    $myTable->addCell($schoolbyname[$i]->SCHOOLAREA,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($province1,"15%", null, "left",$oddOrEven);
                    $myTable->addCell($schoolbyname[$i]->SCHOOLPROV,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
                    //'TELCODE','FAXCODE','PRINCIPALEMAIL','PRINCIPALCELLNO',,'GUIDANCETEACHEMAIL','GUIDANCETEACHCELLNO'
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($telnumber1,"15%", null, "left",$oddOrEven);
                    $myTable->addCell($schoolbyname[$i]->TELCODE.' '.$schoolbyname[$i]->TELNUMBER,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($faxno1,"15%", null, "left",$oddOrEven);
                    $myTable->addCell($schoolbyname[$i]->FAXCODE.' '.$schoolbyname[$i]->FAXNUMBER,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++; 
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($emailaddy1,"15%", null, "left",$oddOrEven);
                    $myTable->addCell($schoolbyname[$i]->EMAIL,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\""; 
                    $rowcount++;
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($principal1,"15%", null, "left",$oddOrEven);
                    $myTable->addCell($schoolbyname[$i]->PRINCIPAL,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
 
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($principalEmailAddy,"15%", null, "left",$oddOrEven);
                    $myTable->addCell($schoolbyname[$i]->PRINCIPALEMAIL,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($prinicipalCellNo,"15%", null, "left",$oddOrEven);
                    $myTable->addCell($schoolbyname[$i]->PRINCIPALCELLNO,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($guidance,"15%", null, "left",$oddOrEven);
                    $myTable->addCell($schoolbyname[$i]->GUIDANCETEACHER,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\""; 
                    $rowcount++;
                    $myTable->endRow();
                    
                                                            
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($guidanceteachemail,"15%", null, "left",$oddOrEven);
                    $myTable->addCell($schoolbyname[$i]->GUIDANCETEACHEMAIL,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
                    
                                                            
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($guidanceteachcellno1,"15%", null, "left",$oddOrEven);
                    $myTable->addCell($schoolbyname[$i]->GUIDANCETEACHCELLNO,"15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
                    
                    $myTable->startRow();
                    (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
                    $myTable->addCell($Link->show(),"15%", null, "left",$oddOrEven);
                    $myTable->addCell("","15%", null, "left",$oddOrEven);
                    $myTable->row_attributes = " class = \"$oddOrEven\"";
                    $rowcount++;
                    $myTable->endRow();
                
                }  
            
          }else{
               
                   $oddEven = 'even';
                   $myTable =& $this->newObject('htmltable', 'htmlelements');
                   $myTable->cellspacing = '1';
                   $myTable->cellpadding = '2';
                   $myTable->border='0';
                   $myTable->width = '100%';
                  
                   $myTable->startRow();
                   $myTable->addCell('<b>' . "NO RECORDS FOUND" . ' '.'FOR '. ' ' . '<u>'.$name.'</u>'.'</b>' . '<br />');
                   $myTable->endRow();
                   
                   $myTable->startRow();
                   $myTable->addCell($SchoolLink->show());
                   $myTable->endRow(); 
        }
        
                   return $myTable->show();     
}      
/*------------------------------------------------------------------------------*/
}//end of class  
?>
