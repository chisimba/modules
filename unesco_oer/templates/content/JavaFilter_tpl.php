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
$this->objDbproductthemes = $this->getobject('dbproductthemes', 'unesco_oer');
$this->objDbresourcetypes = $this->getobject('dbresourcetypes', 'unesco_oer');




//$adaptationstring = "parent_id is not null";
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


if ($PageNum == "undefined") {
    $PageNum = 1 ;
}

switch ($pagelayout) {

    case "1a" : {
            $adaptationstring = 'parent_id is null and deleted = 0';
            $view = "grid";
            break;
        }
    case "2a" : {
            $adaptationstring = 'parent_id is not null  and deleted = 0';
            $view = "grid";
            break;
        }
    case "1b" : {
            $adaptationstring = 'parent_id is null  and deleted = 0';
            $view = "list";
            break;
        }
    case "2b" : {
            $adaptationstring = 'parent_id is not null  and deleted = 0';
            $view = "list";
            break;
        }
}












if ($browsemapstring != null)
    $buildstring = $browsemapstring;
else
    $buildstring = $adaptationstring;







    $adaptations= $this->objDbProducts->getFilteredProducts($adaptationstring);
    $tempadap = array(); //convert to 1d array
    $i = 0;
    foreach ($adaptations as $adap ) {
        
        $tempadap[$i] = $adap['id'];
        $i++;
    }
    
    






if (!($AuthFilter == Null or $AuthFilter == 'All')) {

    $Auths = $this->objDbProducts->getFilteredProducts('creator = ' . "'$AuthFilter'");


    $TempAuth = array(); //convert to 1d array
    $i = 0;
    foreach ($Auths as $Auth) {
        
        $TempAuth[$i] = $Auth['id'];
        $i++;
    }
}
//   $buildstring .= ' and creator = ' . "'$AuthFilter'";





if (!($ThemeFilter == Null or $ThemeFilter == 'All')) {

    $Themes = $this->objDbproductthemes->getproductIDBythemeID($ThemeFilter);

    $TempTheme = array(); //convert to 1d array
    $i = 0;
    foreach ($Themes as $Theme) {
        
        $TempTheme[$i] = $Theme['product_id'];
        $i++;
    }
}






if (!($LangFilter == Null or $LangFilter == 'All')) {

    $ProdLangIDs = $this->objDbProducts->getFilteredProducts('language = ' . "'$LangFilter'");

    $Templang = array(); //convert to 1d array
    $i = 0;
    foreach ($ProdLangIDs as $ProdLangID) {
  
        $Templang[$i] = $ProdLangID['id'];
              $i++;
    }
}



$array_to_intersect = array($TempAuth, $TempTheme, $Templang,$tempadap);
$filter_empty_arrays = array_filter($array_to_intersect);

$total = count($filter_empty_arrays);

if ($total >= 2) {
    $result = call_user_func_array("array_intersect", $filter_empty_arrays);
} else if ($total = 1) {
    $temp = $filter_empty_arrays;
    

    $result = array(); //convert to 1d array
    $i = 0;
    foreach ($temp as $results) {
 
        $result = $results;
       
       $i++;
     
       
    }
     
    //   echo $i . "    tttttttttttt";
   

    //convert to 1d array
}
     //var_dump($result);  

if (($LangFilter == Null or $LangFilter == 'All'))
    if (($ThemeFilter == Null or $ThemeFilter == 'All'))
        if (($AuthFilter == Null or $AuthFilter == 'All')) {


            $temp = $this->objDbProducts->getFilteredProducts($adaptationstring);
            $result = array(); //convert to 1d array
            $i = 0;
            foreach ($temp as $temps) {
               
                $result[$i] = $temps['id'];
                 $i++;
                
                 
            }
         
        };


$products = array();

foreach ($result as $resultant) {


    array_push($products, $this->objDbProducts->getProductByID($resultant));
}


    


if ($sort == 'Alphabetical') {

    function cmp($a, $b) {
        return strcmp($a["title"], $b["title"]);
    }

    usort($products, "cmp");
}
//  $buildstring .= ' order by created_on';
//    else if ($sort == 'Alphabetical')
//         $buildstring .= ' order by title';
//   var_dump($products);



if (!($NumFilter == null or $NumFilter == 'All' ))  {
    $start = ($PageNum -1)* $NumFilter;
    $end = $start+ $NumFilter;
    if ($end > count($products)){
    $end = count($products);     
    }
}

else {

    $start = 0;
    $end = count($products) ;
}




echo $start;
echo $end;

echo $PageNum;
echo $NumFilter;
 








//  $result = array_intersect($TempAuth,$TempTheme,$Templang);
//   $buildstring .= ' and language = ' . "'$LangFilter'";
//$resource = $this->objDbresourcetypes->getResourceTypes();
//        if ($Model == 'on'){
//            
//           
//            
//            
//            
//        }
//            
//            
//            $buildstring .= ' resource_type = "Model" or';
//        if ($Handbook == 'on')
//            
//            
//            
//            $buildstring .= ' resource_type = "Handbook" or';
//        if ($Guide == 'on')
//            
//            
//            
//            $buildstring .= ' resource_type = "Guide" or';
//        if ($Manual == 'on')
//            
//            
//            $buildstring .= ' resource_type = "Manual" or';
//        if ($Besoractile == 'on')
//            
//            
//           
//            $buildstring .= ' resource_type = "Besoractile" or';
//
//      
































switch ($pagelayout) {

    case "1a" : {

            //Creates chisimba table
            $objTable = $this->getObject('htmltable', 'htmlelements');
            $objTable->cssClass = "gridListingTable";
            $objTable->width = NULL;


            // $products = $this->objDbProducts->getFilteredProducts($TotalEntries);

            $newRow = true;
            $count = 0;
            $noOfAdaptations = 0;

            //  echo $total;
            // foreach ($products as $product) {

            for ($i = $start; $i < ($end); $i++) {


                if ($products[$i]['parent_id'] == null) {
                    $count++;
                    $products[$i]['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($products[$i]['id']);

                    $languages = $this->objDbAvailableProductLanguages->getProductLanguage($products[$i]['id']);
                    $theProduct = $products[$i] + $languages;

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





            // echo $Themes[1]['product_id'];echo "<br>"
            //  echo $result[1];
            //  echo $Auths[0]['id'];
            //   echo $TotalEntries;
            //  var_dump($product);







            break;
        }
    case "2a" : {

            $objTable = $this->getObject('htmltable', 'htmlelements');





            $product = $this->objDbProducts->getFilteredProducts($TotalEntries);

            $newRow = true;
            $count = 0;

            foreach ($result as $results) {
                $count++;                       //populates table
                //Check if the creator is a group or an institution
                $prodID = " parent_id is null and id = '$results'";
                $product = $this->objDbProducts->getFilteredProducts("$prodID");

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
            //    $products = $this->objDbProducts->getFilteredProducts($TotalEntries);
            //Loop through the products and display each in it's own line
//             for ($i = $start; $i < ($end); $i++) { 
//                //Get number of adaptations
//                $products[$i]['noOfAdaptations'] = $this->objDbProducts->getNoOfAdaptations($products[$i]['id']);
//                $languages = $this->objDbAvailableProductLanguages->getProductLanguage($products[$i]['id']);
//                $theProduct = $products + $languages;

            echo $this->objProductUtil->populateListView($start, $end, $products);









            break;
        }
    case "2b" : {

            $newRow = true;
            $count = 0;

            $products = $this->objDbProducts->getFilteredProducts($TotalEntries);


            foreach ($products as $product) {
                if ($product['parent_id'] != '') {
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
                 //   echo $this->objProductUtil->populateAdaptedListView($product);
                }
            }







            break;
        }
}



$thumbnail = ' <div class="paginationDiv">
     <div class="paginationImage"><img src="skins/unesco_oer/images/icon-pagination.png" alt="Pagination" width="17" height="20"></div>';





$TotalPages = ceil(count($products) / $NumFilter);


if ($TotalPages > 0) {

    echo $thumbnail;
    for ($i = 1; $i <= $TotalPages; $i++) {

        $abLink = new link("javascript:void(0);");
        $abLink->extra = "onclick = javascript:ajaxFunction($i)";




        $abLink->link = $i;

        echo $abLink->show();
    }
};
?>
