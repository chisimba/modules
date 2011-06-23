<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('filterdisplay', 'unesco_oer');




//$adaptationstring = "relation is not null";
$ThemeFilter = $this->getParam('theme');
$PageNum = $this->getParam('id');
$AuthFilter = $this->getParam('auth');
$LangFilter = $this->getParam('lang');
$sort = $this->getParam('sort');
$Model = $this->getParam('model');
$Handbook = $this->getParam('handbook');
$Manual = $this->getParam('manual');
$Besoractile = $this->getParam('bestprac');
$Guide = $this->GetParam('guide');
$NumFilter = $this->getParam('numperpage');

$pagelayout = $this->getParam('adaptation');

switch ($pagelayout){
    
    case "1a" : {
     $adaptationstring = 'relation is null';
     $view = "grid";
     break;
        
    }
    case "2a" : {
     $adaptationstring = 'relation is not null';
     $view = "grid";
      break;  
    }
    case "1b" : {
     $adaptationstring = 'relation is null';
     $view = "list";
     break;   
    }
    case "2b" : {
     $adaptationstring = 'relation is not null';
     $view = "list";
      break;  
    }
    
}
     
    










   if ($browsemapstring != null)
            $buildstring = $browsemapstring;
        else
            $buildstring = $adaptationstring;

//
//        if (!($AuthFilter == Null or $AuthFilter == 'All'))
//            $buildstring .= ' and creator = ' . "'$AuthFilter'";
//
//        if (!($ThemeFilter == Null or $ThemeFilter == 'All'))
//            $buildstring .= ' and theme = ' . "'$ThemeFilter'";
//
//        if (!($LangFilter == Null or $LangFilter == 'All'))
//            $buildstring .= ' and language = ' . "'$LangFilter'";

//
//        if (($Model == 'on') or ($Handbook == 'on') or ($Guide == 'on') or ($Manual == 'on') or ($Besoractile == 'on'))
//            $buildstring .= ' and (';
//
//        if ($Model == 'on')
//            $buildstring .= ' resource_type = "Model" or';
//        if ($Handbook == 'on')
//            $buildstring .= ' resource_type = "Handbook" or';
//        if ($Guide == 'on')
//            $buildstring .= ' resource_type = "Guide" or';
//        if ($Manual == 'on')
//            $buildstring .= ' resource_type = "Manual" or';
//        if ($Besoractile == 'on')
//            $buildstring .= ' resource_type = "Besoractile" or';
//
//        $length = strlen($buildstring);
//
//        if (($Model == 'on') or ($Handbook == 'on') or ($Guide == 'on') or ($Manual == 'on') or ($Besoractile == 'on')) {
//            $buildstring = substr($buildstring, 0, ($length - 2));
//
//            $buildstring .= ')';
//        }
//


        if ($sort == 'Date Added')
            $buildstring .= ' order by created_on';
        else if ($sort == 'Alphabetical')
            $buildstring .= ' order by title';

        $TotalEntries = $buildstring;






     










   if ((!($NumFilter == null or $NumFilter == 'All' )) & $PageNum == 'undefined') {
            $start = 0;
            $end = $start + $NumFilter;
            $TotalEntries .= ' LIMIT ' . $start . ',' . $end;
        } else if (!($NumFilter == null or $NumFilter == 'All')) {

            $temp = $NumFilter * $PageNum - 1;
            $start = $temp - $NumFilter + 1;
            $end = $NumFilter;
            $TotalEntries .= ' LIMIT ' . $start . ',' . $end;
        }

       







switch ($pagelayout){
    
    case "1a" : {
   
      //Creates chisimba table
                                        $objTable = $this->getObject('htmltable', 'htmlelements');
                                        $objTable->cssClass = "gridListingTable";
                                        $objTable->width = NULL;


                                        $products = $this->objDbProducts->getFilteredProducts($TotalEntries);

                                        $newRow = true;
                                        $count = 0;
                                        $noOfAdaptations = 0;

                                        foreach ($products as $product) {               //populates table
                                            if ($product['relation'] == null) {
                                                $count++;
                                                $product['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($product['id']);

                                                $languages = $this->objDbAvailableProductLanguages->getProductLanguage($product['id']);
                                                $theProduct = $product + $languages;

                                                if ($newRow) {
                                                    $objTable->startRow();
                                                    $objTable->addCell($this->objProductUtil->populateGridView($theProduct, $noOfAdaptations));
                                                    $newRow = false;
                                                } else {
                                                    $objTable->addCell($this->objProductUtil->populateGridView($theProduct, $noOfAdaptations));
                                                }
                                            }

                                            if ($count == 3) {
                                                $newRow = true;
                                                $objTable->endRow();
                                                $count = 0;
                                            }
                                        }
                                        echo $objTable->show();     
        
        
        
        
        
        
        
        
        
     break;
        
    }
    case "2a" : {
   
         $objTable = $this->getObject('htmltable', 'htmlelements');





                                $products = $this->objDbProducts->getFilteredProducts($TotalEntries);
                                echo $$TotalEntries;
                                $newRow = true;
                                $count = 0;

                                foreach ($products as $product) {
                                    $count++;                       //populates table
                                    //Check if the creator is a group or an institution

                                    if ($this->objDbGroups->isGroup($product['creator'])) {
                                        $thumbnail = $this->objDbGroups->getGroupThumbnail($product['creator']);
                                        $product['group_thumbnail'] = $thumbnail['thumbnail'];
                                        $product['institution_thumbnail'] = NULL;
                                        //$product['country'] = 'Not Available';
                                        $product['country'] = $this->objDbGroups->getGroupCountry($product['creator']);
                                        $product['type'] = 'Not Available';
                                    } else {
                                        $thumbnail = $this->objDbInstitution->getInstitutionThumbnail($product['creator']);
                                        $product['group_thumbnail'] = NULL;
                                        //$product['country'] = 'Not Available';


                                        $product['country'] = $this->objDbInstitution->getInstitutionCountry($product['creator']);
                                        //$product['type'] = 'Not Available';

                                        $institutionTypeID = $this->objDbInstitution->findInstitutionTypeID($product['creator']);
                                        //   $product['type'] = $this->objDbInstitutionTypes->getTypeName($institutionTypeID);

                                        $product['institution_thumbnail'] = $thumbnail['thumbnail'];
                                    }

                                    if ($newRow) {
                                        $objTable->startRow();
                                        $objTable->addCell($this->objProductUtil->populateAdaptedGridView($product));
                                        $newRow = false;
                                    } else {
                                        $objTable->addCell($this->objProductUtil->populateAdaptedGridView($product));
                                    }


                                    //Display 3 products per row
                                    if ($count == 3) {
                                        $newRow = true;
                                        $count = 0;
                                        $objTable->endRow();
                                    }
                                }

                               echo $objTable->show();
        
        
        
        
        
        
      break;  
    }
    case "1b" : {
   
                   $objTable = $this->getObject('htmltable', 'htmlelements');
                                        $products = $this->objDbProducts->getFilteredProducts($TotalEntries);

                                        //Loop through the products and display each in it's own line
                                        foreach ($products as $product) {
                                            //Get number of adaptations
                                            $product['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($product['id']);
                                            $languages = $this->objDbAvailableProductLanguages->getProductLanguage($product['id']);
                                            $theProduct = $product + $languages;

                                            echo $this->objProductUtil->populateListView($theProduct);
                                        }
        
        
        
        
        
        
        
        
     break;   
    }
    case "2b" : {
    
               $newRow = true;
                                            $count = 0;

                                            $products = $this->objDbProducts->getFilteredProducts($TotalEntries);


                                            foreach ($products as $product) {
                                                if ($product['relation'] != '') {
                                                    $product['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($product['id']);

                                                    //Get The adapters details
                                                    if ($this->objDbGroups->isGroup($product['creator'])) {
                                                        $thumbnail = $this->objDbGroups->getGroupThumbnail($product['creator']);
                                                        $product['group_thumbnail'] = $thumbnail['thumbnail'];
                                                        $product['institution_thumbnail'] = NULL;
                                                        $product['country'] = 'Not Available';
                                                        $product['type'] = 'Not Available';
                                                    } else {
                                                        $thumbnail = $this->objDbInstitution->getInstitutionThumbnail($product['creator']);
                                                        $product['group_thumbnail'] = NULL;
                                                        $product['country'] = 'Not Available';
                                                        $product['type'] = 'Not Available';
                                                        $product['institution_thumbnail'] = $thumbnail['thumbnail'];
                                                    }
                                                    echo $this->objProductUtil->populateAdaptedListView($product);
                                                }
                                            }
        
        
        
        
        
        
        
      break;  
    }
    
}

 

 $thumbnail =' <div class="paginationDiv">
     <div class="paginationImage"><img src="skins/unesco_oer/images/icon-pagination.png" alt="Pagination" width="17" height="20"></div>';
 

       

                        $TotalRecords = $this->objDbProducts->getTotalEntries($buildstring);
                        $TotalPages = ceil($TotalRecords / $NumFilter);


                        if ($TotalPages > 0) {

                         echo   $thumbnail;
                            for ($i = 1; $i <= $TotalPages; $i++) {

                                $abLink = new link("javascript:void(0);");
                                 $abLink->extra = "onclick = javascript:ajaxFunction($i)";
                                 
                                  


                                $abLink->link = $i;

                                     echo    $abLink->show();

                            }
                        };







?>
