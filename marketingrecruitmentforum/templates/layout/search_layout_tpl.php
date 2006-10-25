<?php
//template for the search facilities layout

   $StudCardSLU = array();
   $StudCardSLU[] = array('params' => array("action" => "showsearchslu"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Student Card(SLU)');
   $StudCardSLU[] = array('params' => array("action" => "showsearchfac"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Student Card(Faculty)');
   $StudCardSLU[] = array('params' => array("action" => "showsearchactiities"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'List of Activities');
   $StudCardSLU[] = array('params' => array("action" => "showsearchschool"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'List of Schools');
   
    $leftColumn = array();
    $leftColumn[] = array('heading' => 'Search Facilities', 'links' => $StudCardSLU);
    
   /**
   *create an instance of the class defaultpageutils 
   *this defines the content of the left and right columns
   *adds the quicksearch box to the rightcolumn   
   */
   
   $this->objUtils  = & $this->getObject('defaultpageutils','semsutilities');
   $displaycontent = $this->objUtils->getDefaultLayout($leftColumn);  
  
  echo  $displaycontent
?>
