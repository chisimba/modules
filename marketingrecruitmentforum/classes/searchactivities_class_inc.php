<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}

/**
* This object generates data used to display search results for student card (SLU) 
* @package 
* @category sems
* @copyright 2005, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Colleen Tinker
*/

class searchactivities extends object{ 

/**
  * Standard init function
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
      $this->objactivity = & $this->getObject('dbsluactivities','marketingrecruitmentforum');
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
	}
/*------------------------------------------------------------------------------*/
/**
 * Method used to create a table to display all sluactivity data
 * @param string $result,contains resulset of sluactiities by calling the function getallsluactivity() in dbsluactivities class
 * loop through contents of $result and create table rows
 * @return obj $myTable  
 */            
public  function  getAllactivities(){
        $details = '';
    $records = '';
    $paging = '';  
 
    //$results = $this->objactivity->getallsluactivity();
   	$res = $this->objactivity->getallsluactivity();
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
          $prevLink->href=$this->uri(array('action'=>'showstudschoolactivity','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'showstudschoolactivity','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'showstudschoolactivity', 'page'=>($page-1), 'dispcount'=>$dispCount)));
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
            $viewpages->href=$this->uri(array('action'=>'showstudschoolactivity','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
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
        $viewprev->href=$this->uri(array('action'=>'showstudschoolactivity','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'showstudschoolactivity','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
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
		$results = $this->objactivity->activitydetails($startat,$endat);
    //if($results){    
        /**
          * Create all language item text
          */                    
        $date1 = $this->objLanguage->languageText('word_date1');
        $activity = $this->objLanguage->languageText('word_activity');
        $activity1 = ucfirst($activity); 
        $schoolname = $this->objLanguage->languageText('phrase_schoolname');
        $schoolname1 = ucfirst($schoolname);
        $area = $this->objLanguage->languageText('word_area');
        $area1 = ucfirst($area);
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
        $myTable->addHeaderCell($date1, null,'top','left','header');
        $myTable->addHeaderCell($activity1, null,'top','left','header');
        $myTable->addHeaderCell($schoolname1, null,'top','left','header');
        $myTable->addHeaderCell($area1, null,'top','left','header');
        $myTable->addHeaderCell($province1, null,'top','left','header');
        $myTable->endHeaderRow();
     
        $rowcount = '0';
  
//    foreach($results as $sessCard){
      for($i=0; $i< count($results); $i++){
       
       
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->ACTIVITYDATE,"15%", null, "left",$oddOrEven);
         $myTable->addCell($results[$i]->ACTIVITY, "15%", null, "left",$oddOrEven);
         $myTable->addCell($results[$i]->SCHOOLNAME,"15%", null, "left",$oddOrEven);
         $myTable->addCell($results[$i]->AREA,"15%", null, "left",$oddOrEven);
         $myTable->addCell($results[$i]->PROVINCE,"15%", null, "left",$oddOrEven);
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
 * Method used to create a table to display sluactivities between two specific dates
 * @param $activitydate, array obj passed to function, containing all activities between two selected dates 
 * loop through contents of $result and create table rows
 * @return obj $myTable  
 */
public  function getactivdate($activitydate){
        
    if(isset($activitydate)){
           /**
            * Create all language item text
            */                    
             $date = $this->objLanguage->languageText('word_date1');
             $activity = $this->objLanguage->languageText('word_activity');
             $activity1 = ucfirst($activity);
      
             $oddEven = 'even';
             $myTable =& $this->newObject('htmltable', 'htmlelements');
             $myTable->cellspacing = '1';
             $myTable->cellpadding = '2';
             $myTable->border='0';
             $myTable->width = '100%';
             $myTable->css_class = 'highlightrows';
             
          
      
             $myTable->startHeaderRow();
             $myTable->addHeaderCell($date, null,'top','left','header');
             $myTable->addHeaderCell($activity1, null,'top','left','header');
             $myTable->endHeaderRow();
            
            
             $rowcount = '0';
  
      //   foreach($activitydate as $sessCard){
       for($i=0; $i< count($activitydate); $i++){
            
             $myTable->startRow();
             (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
             $myTable->addCell($activitydate[$i]->ACTIVITYDATE,"15%", null, "left",$oddOrEven);
             $myTable->addCell($activitydate[$i]->ACTIVITY, "15%", null, "left",$oddOrEven);
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
  // }    
                     
}      
/*------------------------------------------------------------------------------*/
/**
 * Method used to create a table to display all activities by type
 * @param string $result,contains resulset of activitity types by calling the function getactivitytype() in dbsluactivities class
 * loop through contents of $result and create table rows
 * @return obj $myTable  
 */       
public  function activitytype(){
        $details = '';
    $records = '';
    $paging = '';  

//    $results = $this->objactivity->getactivitytype();
  	$res = $this->objactivity->getactivitytype();
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
          $prevLink->href=$this->uri(array('action'=>'showstudschoolactivity','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'showstudschoolactivity','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'showstudschoolactivity', 'page'=>($page-1), 'dispcount'=>$dispCount)));
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
            $viewpages->href=$this->uri(array('action'=>'showstudschoolactivity','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
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
        $viewprev->href=$this->uri(array('action'=>'showstudschoolactivity','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'showstudschoolactivity','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
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
		$results = $this->objactivity->activtypelimit($startat,$endat);

         
    //if($results){
         $activity = $this->objLanguage->languageText('word_activity');
         $activity1 = ucfirst($activity);
      
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
         $myTable->css_class = 'highlightrows';
      
         $myTable->startHeaderRow();
         $myTable->addHeaderCell($activity1, null,'top','left','header');
         $myTable->endHeaderRow();
       
         $rowcount = '0';
  
    //foreach($results as $sessCard){
    for($i=0; $i< count($results); $i++){
       
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->ACTIVITY,"15%", null, "left",$oddOrEven);
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
    return $content;  }	
/*------------------------------------------------------------------------------*/
/**
 * Method used to create a table to display all activities by province
 * @param string $result,contains resulset of activities by province by calling the function getactivityprovince() in dbsluactivities class
 * loop through contents of $result and create table rows
 * @return obj $myTable  
 */    
public function activitybyprov(){
        $details = '';
    $records = '';
    $paging = '';  

    //$results = $this->objactivity->getactivityprovince();
    $res = $this->objactivity->getactivityprovince();
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
          $prevLink->href=$this->uri(array('action'=>'showstudschoolactivity','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'showstudschoolactivity','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'showstudschoolactivity', 'page'=>($page-1), 'dispcount'=>$dispCount)));
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
            $viewpages->href=$this->uri(array('action'=>'showstudschoolactivity','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
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
        $viewprev->href=$this->uri(array('action'=>'showstudschoolactivity','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'showstudschoolactivity','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
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
		$results = $this->objactivity->activprovincedata($startat,$endat);

        /**
         * create all language item text
         */  
    //if($results){                
        $activity = $this->objLanguage->languageText('word_activity');
        $activity1 = ucfirst($activity);
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
        $myTable->addHeaderCell($activity1, null,'top','left','header');
        $myTable->addHeaderCell($province1, null,'top','left','header');
        $myTable->endHeaderRow();
        
        
        $rowcount = '0';
  
    //foreach($results as $sessCard){
    for($i=0; $i< count($results); $i++){
       
       $myTable->startRow();
       (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
       $myTable->addCell($results[$i]->ACTIVITY,"15%", null, "left",$oddOrEven);
       $myTable->addCell($results[$i]->PROVINCE, "15%", null, "left",$oddOrEven);
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
 * Method used to create a table to display all activities by area
 * @param string $result,contains resulset of activities by area by calling the function getactivityarea() in dbsluactivities class
 * loop through contents of $result and create table rows
 * @return obj $myTable  
 */   
public function activitybyarea(){
         $details = '';
    $records = '';
    $paging = '';  
   
    //$results = $this->objactivity->getactivityarea();
  	$res = $this->objactivity->getactivityarea();
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
          $prevLink->href=$this->uri(array('action'=>'showstudschoolactivity','page'=>($page-1), 'dispcount'=>$dispCount));
          $prevLink->link = 'Pervious';
          $prevLink->style = "text-decoration:none";
          $prev = $prevLink->show();
      }else{
          $prev = 'Pervious';
      }

      if ($page < $pageCount){
          $nextLink = new link();
          $nextLink->href=$this->uri(array('action'=>'showstudschoolactivity','page'=>($page+1), 'dispcount'=>$dispCount));
          $nextLink->link = 'Next';
          $nextLink->style = "text-decoration:none";
          $next = $nextLink->show();
      }else{
          $next = 'Next';
      }
    $objPaging = new form('pagingform');
    $objPaging->setAction($this->uri(array('action'=>'showstudschoolactivity', 'page'=>($page-1), 'dispcount'=>$dispCount)));
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
            $viewpages->href=$this->uri(array('action'=>'showstudschoolactivity','startat'=>$stdCountR,'pg'=>$num, 'dispcount'=>$dispCount));
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
        $viewprev->href=$this->uri(array('action'=>'showstudschoolactivity','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
        $viewprev->link = 'Pervious';
		
        $viewprev->style = "text-decoration:none";
        $viewp = $viewprev->show()." |";
     }
     $vntest = $stdCount - $dispCount;
     if ($startat <= $vntest){
        $pg = $page + 1;
        $stdCountR = $startat + $dispCount;

        $viewnext = new link();
        $viewnext->href=$this->uri(array('action'=>'showstudschoolactivity','startat'=>$stdCountR,'pg'=>$pg, 'dispcount'=>$dispCount));
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
		$results = $this->objactivity->activareaedata($startat,$endat);

  // if($results){      
         /**
          * create all language item tect
          */
          $activity = $this->objLanguage->languageText('word_activity');
          $activity1 = ucfirst($activity);
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
         $myTable->addHeaderCell($activity1, null,'top','left','header');
         $myTable->addHeaderCell($area1, null,'top','left','header');
         $myTable->endHeaderRow();
        
         $rowcount = '0';
  
    //foreach($results as $sessCard){
    for($i=0; $i< count($results); $i++){
     
       
         $myTable->startRow();
         (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
         $myTable->addCell($results[$i]->ACTIVITY, "15%", null, "left",$oddOrEven);
         $myTable->addCell($results[$i]->AREA,"15%", null, "left",$oddOrEven);
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
 * Method used to create a table to display all activities by school
 * @param string $result,contains resulset of activities by school by calling the function activitybyschool() in dbsluactivities class
 * loop through contents of $result and create table rows
 * @return obj $myTable  
 */  

public   function activitybyschool($activschool = NULL){
      
    if(isset($activschool)){
    
         $activity = $this->objLanguage->languageText('word_activity');
         $activity1 = ucfirst($activity);
         $schoolname = $this->objLanguage->languageText('phrase_schoolname');
         $schoolname1 = ucfirst($schoolname);
              
         $oddEven = 'even';
         $myTable =& $this->newObject('htmltable', 'htmlelements');
         $myTable->cellspacing = '1';
         $myTable->cellpadding = '2';
         $myTable->border='0';
         $myTable->width = '100%';
      
  
         $myTable->startHeaderRow();
         $myTable->addHeaderCell($activity1, null,'top','left','header');
         $myTable->addHeaderCell($schoolname1, null,'top','left','header');
         $myTable->endHeaderRow();
        
         $rowcount = '0';
  
    //    foreach($activschool as $sessCard){
          for($i=0; $i< count($activschool); $i++){
         
             $myTable->startRow();
             (($rowcount % 2) == 0)? $oddOrEven = 'even' : $oddOrEven = 'odd';
             $myTable->addCell($activschool[$i]->ACTIVITY, "15%", null, "left",$oddOrEven);
             $myTable->addCell($activschool[$i]->SCHOOLNAME, "15%", null, "left",$oddOrEven);
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
}//end of class  
?>
