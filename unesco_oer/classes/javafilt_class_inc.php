<?php

class javafilt extends object {

    public function init() {
        parent::init();



        $this->loadClass('link', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('filterdisplay', 'unesco_oer');
        $this->objDbproductthemes = $this->getobject('dbproductthemes', 'unesco_oer');
        $this->objDbresourcetypes = $this->getobject('dbresourcetypes', 'unesco_oer');
        $this->objDbProducts = $this->getobject('dbproducts', 'unesco_oer');
        $this->objDbresourcetypes = $this->getobject('dbresourcetypes', 'unesco_oer');
        $this->_institutionGUI = $this->getObject('institutiongui', 'unesco_oer');
        $this->objLanguage = $this->getObject("language", "language");
        $this->objDbRegions = $this->getObject('dbregions', 'unesco_oer');

        $this->objDbAvailableProductLanguages = $this->getObject("dbavailableproductlanguages", "unesco_oer");
        $this->objUser = $this->getObject("user", "security");
        $this->objbookmarkmanager = $this->getObject('bookmarkmanager', 'unesco_oer');
        $this->objProductUtil = $this->getObject('productutil', 'unesco_oer');
        $this->objDbGroups = $this->getObject('dbgroups', 'unesco_oer');
        $this->objDbInstitution = $this->getObject('dbinstitution', 'unesco_oer');
         $this->objDbInstitutionTypes = $this->getObject('dbinstitutiontypes', 'unesco_oer');
    }

    public function displayprods() {






        $ThemeFilter = $this->getParam('theme');
        $adaptationstring = $this->getParam('id');
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
        $prod = $this->getParam('ProdID');
        $browsecheck = $this->getParam('browsecheck');
        $institutionFilter = $this->getParam('inst');
        $regionFilter = $this->getParam('Reg');
         $CountryFilter = $this->getParam('Country');
         $institutionid = $this->getParam('institutionid');




//   if ($browsemapstring != null)
//            $buildstring = $browsemapstring;
//        else
//            $buildstring = $adaptationstring;
//
//        if (!($AuthFilter == Null or $AuthFilter == 'All'))
//            $buildstring .= ' and creator = ' . "'$AuthFilter'";
//
//       if (!($ThemeFilter == Null or $ThemeFilter == 'All'))
//            $buildstring .= ' and theme = ' . "'$ThemeFilter'";
//
//       if (!($LangFilter == Null or $LangFilter == 'All'))
//           $buildstring .= ' and language = ' . "'$LangFilter'";
//
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
//
//        if ($sort == 'Date Added')
//            $buildstring .= ' order by created_on';
//        else if ($sort == 'Alphabetical')
//            $buildstring .= ' order by title';
//
//        $TotalEntries = $buildstring;
//        
//   $objDbProducts = $this->getObject('dbproducts');
//        
//         $TotalRecords = $objDbProducts->getTotalEntries($buildstring);


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

   
        if ($browsecheck == '1') {

            $adaptations = $this->objDbGroups->getGroupProductadaptation($prod);
        } else if ($pagelayout == '3b')
            $adaptations = $this->objDbProducts->getadapted($prod);
       else if ($institutionid != 'undefined') {
               $adaptations= $this->objDbInstitution->getProductIdbyInstid($institutionid); 
         
        }else{
            $adaptations = $this->objDbProducts->getFilteredProducts($adaptationstring); 
       
            
        }


 //echo 'hhhhhhhhhhhhhhhhhhhh' .$institutionid;

        $tempadap = array(); //convert to 1d array
        $i = 0;
        foreach ($adaptations as $adap) {
            if (($browsecheck == '1') || ($institutionid != 'undefined')) {
                $tempadap[$i] = $adap['product_id'];
            }
            else 
                $tempadap[$i] = $adap['id'];
            $i++;
        }
//var_dump($adaptations);

        //  var_dump($tempadap);   






        if (!($AuthFilter == Null or $AuthFilter == 'All')) {

            $Auths = $this->objDbProducts->getFilteredProducts('creator = ' . "'$AuthFilter'");


            $TempAuth = array(); //convert to 1d array
            $i = 0;
            foreach ($Auths as $Auth) {
                $i++;
                $TempAuth[$i] = $Auth['id'];
            }
        }
//   $buildstring .= ' and creator = ' . "'$AuthFilter'";





        if (!($ThemeFilter == Null or $ThemeFilter == 'All')) {

            $Themes = $this->objDbproductthemes->getproductIDBythemeID($ThemeFilter);

            $TempTheme = array(); //convert to 1d array
            $i = 0;
            foreach ($Themes as $Theme) {
                $i++;
                $TempTheme[$i] = $Theme['product_id'];
            }
        }
        
          if (!($regionFilter == Null or $regionFilter == 'All')) {
               
            $regionid = $this->objDbRegions->getRegionid($regionFilter);     
            $prodids = $this->objDbRegions->getProdbyid($regionid[0]['id']);
            $TempRegion = array(); //convert to 1d array
            $i = 0;
            foreach ($prodids as $prodid) {

                $TempRegion[$i] = $prodid['product_id'];
                $i++;
            }
        }

        
         if (!($CountryFilter == Null or $CountryFilter == 'All')) {

            $Countrys = $this->objDbProducts->getProductidbycountry($CountryFilter);

            $TempCountry = array(); //convert to 1d array
            $i = 0;
            foreach ($Countrys as $Country) {

                $TempCountry[$i] = $Country['product_id'];
                $i++;
            }
        }
        
        
        
        
           if (!($institutionFilter == Null or $institutionFilter == 'All')) {

           
               
               $Instypeid = $this->objDbInstitutionTypes->findTypeID($institutionFilter);
               $Instids = $this->objDbInstitution->getInstitutionIdbyType($Instypeid);
            
         //     $content .= $Instids[0]['id'];
              
            $TempInst = array(); //convert to 1d array
            $i = 0;
            foreach ($Instids as  $Instid) {
             
             

                $prodids = $this->objDbInstitution->getProductIdbyInstid($Instid['id']);
                
                foreach ($prodids as  $prodid) {
                       $i++;
                 
                       $TempInst[$i] = $prodid['product_id'];
                }
                 
//           foreach ($TempInst as $temp){
//               $content .= $temp;
//               
//           }
               // 
            }
        }




        if (!($LangFilter == Null or $LangFilter == 'All')) {

            $ProdLangIDs = $this->objDbProducts->getFilteredProducts('language = ' . "'$LangFilter'");

            $Templang = array(); //convert to 1d array
            $i = 0;
            foreach ($ProdLangIDs as $ProdLangID) {
                $i++;
                $Templang[$i] = $ProdLangID['id'];
            }
        }



        $array_to_intersect = array($TempAuth, $TempTheme, $Templang, $tempadap,$TempInst,$TempRegion,$TempCountry);
        $filter_empty_arrays = array_filter($array_to_intersect);

        $total = count($filter_empty_arrays);

        if ($total >= 2) {
            $result = call_user_func_array("array_intersect", $filter_empty_arrays);
        } else if ($total == 1) {
//            $temp = $filter_empty_arrays;
//
//
//            $result = array(); //convert to 1d array
//            $i = 0;
//            foreach ($temp as $results) {
//
//                $result = $results;
//
//                $i++;
//            }
            $result = null;
            //convert to 1d array
        }


        if (($LangFilter == Null or $LangFilter == 'All'))
            if (($ThemeFilter == Null or $ThemeFilter == 'All'))
                if (($AuthFilter == Null or $AuthFilter == 'All'))
                     if (($institutionFilter == Null or $institutionFilter == 'All'))
                           if (($regionFilter == Null or $regionFilter == 'All'))
                                if (($CountryFilter == Null or $CountryFilter == 'All')){


                    if ($browsecheck == '1') {

                        $temp = $this->objDbGroups->getGroupProductadaptation($prod);
                    } else if ($pagelayout == '3b')
                        $temp = $this->objDbProducts->getadapted($prod);
                    else if ($institutionid != 'undefined')  {
               $temp= $this->objDbInstitution->getProductIdbyInstid($institutionid);
         
        }else{
            $temp = $this->objDbProducts->getFilteredProducts($adaptationstring); 

            
        }


                    $result = array(); //convert to 1d array
                    $i = 0;
                    foreach ($temp as $temps) {
                     if (($browsecheck == '1') || ($institutionid != 'undefined')) {

                            $result[$i] = $temps['product_id'];
                        }
                        else
                            $result[$i] = $temps['id'];

                        $i++;
                    }
                };







        $prodnumber = count($result);

        //   $content = $this->objProductUtil->navigation();
        $content .= ' <div class="moduleHeader">';

        $content.= $this->objLanguage->languageText('mod_unesco_oer_product_description', 'unesco_oer');
        $content .= '                     </div>
                        <div class="blueNumberBackground">
                        <div class="iconOnBlueBackground"><img src="skins/unesco_oer/images/icon-filter.png" alt="filter"></div>
                        <div class="numberOffilteredProducts"> ' .
                $prodnumber .
                '</div>
                        </div>';


        return $content;
    }

    public function replaceprods() {




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
        $institutionFilter = $this->getParam('inst');
        $regionFilter = $this->getParam('Reg');
        $CountryFilter = $this->getParam('Country');
        $pagelayout = $this->getParam('adaptation');
        $prod = $this->getParam('ProdID');
        $browsecheck = $this->getParam('browsecheck');
        $institutionid = $this->getParam('institutionid');



        if ($PageNum == "undefined") {
            $PageNum = 1;
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

        if ($browsecheck == '1') {

            $adaptations = $this->objDbGroups->getGroupProductadaptation($prod);
        } else if ($pagelayout == '3b')
            $adaptations = $this->objDbProducts->getadapted($prod);
       else  if ($institutionid != 'undefined'){
               $adaptations= $this->objDbInstitution->getProductIdbyInstid($institutionid);
         
        }else{
            $adaptations = $this->objDbProducts->getFilteredProducts($adaptationstring); 
   
            
        }




        $tempadap = array(); //convert to 1d array
        $i = 0;
        foreach ($adaptations as $adap) {
            if (($browsecheck == '1') || ($institutionid != 'undefined')){
                $tempadap[$i] = $adap['product_id'];
            }
            else 
                $tempadap[$i] = $adap['id'];
            $i++;
        }

//var_dump($adaptations);

         // var_dump($tempadap);   


        if (!($AuthFilter == Null or $AuthFilter == 'All')) {

            $Auths = $this->objDbProducts->getFilteredProducts('creator = ' . "'$AuthFilter'");


            $TempAuth = array(); //convert to 1d array
            $i = 0;
            foreach ($Auths as $Auth) {

                $TempAuth[$i] = $Auth['id'];
                $i++;
            }
        }
        
        
          if (!($CountryFilter == Null or $CountryFilter == 'All')) {

            $Countrys = $this->objDbProducts->getProductidbycountry($CountryFilter);

            $TempCountry = array(); //convert to 1d array
            $i = 0;
            foreach ($Countrys as $Country) {

                $TempCountry[$i] = $Country['product_id'];
                $i++;
            }
        }
        
           if (!($regionFilter == Null or $regionFilter == 'All')) {
               
            $regionid = $this->objDbRegions->getRegionid($regionFilter);     
            $prodids = $this->objDbRegions->getProdbyid($regionid[0]['id']);
            $TempRegion = array(); //convert to 1d array
            $i = 0;
            foreach ($prodids as $prodid) {

                $TempRegion[$i] = $prodid['product_id'];
                $i++;
            }
        }



         if (!($institutionFilter == Null or $institutionFilter == 'All')) {

   
            $Instypeid = $this->objDbInstitutionTypes->findTypeID($institutionFilter);
            $Instids = $this->objDbInstitution->getInstitutionIdbyType($Instypeid);

            $TempInst = array(); //convert to 1d array
            $i = 0;
            foreach ($Instids as  $Instid) {
            
                $prodids = $this->objDbInstitution->getProductIdbyInstid($Instid['id']);
                
                foreach ($prodids as  $prodid) {
                     
                 
                       $TempInst[$i] = $prodid['product_id'];
                         $i++;
                }
      // 
            }
        }
        
        
        



        if (!($ThemeFilter == Null or $ThemeFilter == 'All')) {

            $Themes = $this->objDbproductthemes->getproductIDBythemeID($ThemeFilter);

            $TempTheme = array(); //convert to 1d array
            $i = 0;
            foreach ($Themes as $Theme) {

                $TempTheme[$i] = $Theme['product_id'];
                $i++;
               // echo $Theme['product_id'];
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



        $array_to_intersect = array($TempAuth, $TempTheme, $Templang, $tempadap,$TempInst,$TempRegion,$TempCountry);
        $filter_empty_arrays = array_filter($array_to_intersect);

        $total = count($filter_empty_arrays);

        if ($total >= 2) {
            $result = call_user_func_array("array_intersect", $filter_empty_arrays);
        } else if ($total == 1) {
//            $temp = $filter_empty_arrays;
//     
//   
//            $result = array(); //convert to 1d array
//            $i = 0;
//            foreach ($temp as $results) {
//
//                $result = $results;
//
//                $i++;
//            }
//                 var_dump($temp);  
//               echo $i . "    tttttttttttt";
            $result = null;
            //convert to 1d array
        }


        if (($LangFilter == Null or $LangFilter == 'All'))
            if (($ThemeFilter == Null or $ThemeFilter == 'All'))
                if (($AuthFilter == Null or $AuthFilter == 'All'))
                      if (($institutionFilter == Null or $institutionFilter == 'All'))
                          if (($regionFilter == Null or $regionFilter == 'All'))
                               if (($CountryFilter == Null or $CountryFilter == 'All')){


                    if ($browsecheck == '1') {

                        $temp = $this->objDbGroups->getGroupProductadaptation($prod);
                    } else if ($pagelayout == '3b')
                        $temp = $this->objDbProducts->getadapted($prod);
                      else if ($institutionid != 'undefined') {
               $temp= $this->objDbInstitution->getProductIdbyInstid($institutionid);
         
        }else{
            $temp = $this->objDbProducts->getFilteredProducts($adaptationstring); 
  
            
        }


                    $result = array(); //convert to 1d array
                    $i = 0;
                    foreach ($temp as $temps) {
                     if (($browsecheck == '1') || ($institutionid != 'undefined')) {

                            $result[$i] = $temps['product_id'];
                        }
                        else
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



        if (!($NumFilter == null or $NumFilter == 'All' )) {
            $start = ($PageNum - 1) * $NumFilter;
            $end = $start + $NumFilter;
            if ($end > count($products)) {
                $end = count($products);
            }
        } else {

            $start = 0;
            $end = count($products);
        }



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

//              //Creates chisimba table
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





                    //   $product = $this->objDbProducts->getFilteredProducts($TotalEntries);

                    $newRow = true;
                    $count = 0;

                    for ($i = $start; $i < ($end); $i++) {
                        $count++;                       //populates table
                        //Check if the creator is a group or an institution

                        $objProduct = $this->getObject('product');
                        if ($browsecheck == '1') {
                            $objProduct->loadProduct($products[$i]['id']);
                        }
                        else
                            $objProduct->loadProduct($products[$i]);

//                                    if ($this->objDbGroups->isGroup($product['creator'])) {
//                                        $thumbnail = $this->objDbGroups->getGroupThumbnail($product['creator']);
//                                        $product['group_thumbnail'] = $thumbnail['thumbnail'];
//                                        $product['institution_thumbnail'] = NULL;
//                                        //$product['country'] = 'Not Available';
//                                        $product['country'] = $this->objDbGroups->getGroupCountry($product['creator']);
//                                        $product['type'] = 'Not Available';
//                                    } else {
//                                        $thumbnail = $this->objDbInstitution->getInstitutionThumbnail($product['creator']);
//                                        $product['group_thumbnail'] = NULL;
//                                        //$product['country'] = 'Not Available';
//
//
//                                        $product['country'] = $this->objDbInstitution->getInstitutionCountry($product['creator']);
//                                        //$product['type'] = 'Not Available';
//
//                                        $institutionTypeID = $this->objDbInstitution->findInstitutionTypeID($product['creator']);
//                                        //   $product['type'] = $this->objDbInstitutionTypes->getTypeName($institutionTypeID);
//
//                                        $product['institution_thumbnail'] = $thumbnail['thumbnail'];
//                                    }

                        if ($newRow) {
                            $objTable->startRow();
                            $objTable->addCell($this->objProductUtil->populateAdaptedGridView($objProduct));
                            $newRow = false;
                        } else {
                            $objTable->addCell($this->objProductUtil->populateAdaptedGridView($objProduct));
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

                    //     $products = $this->objDbProducts->getFilteredProducts($TotalEntries);


                    for ($i = $start; $i < ($end); $i++) {
                        $count++;                       //populates table
                        //Check if the creator is a group or an institution

                        $objProduct = $this->getObject('product');
                        if ($browsecheck == '1') {
                            $objProduct->loadProduct($products[$i]['id']);
                        }
                        else
                            $objProduct->loadProduct($products[$i]);

                        echo $this->objProductUtil->populateAdaptedListView($objProduct);
                    }







                    break;
                }


            case "3b" : {




                    $form = new form("compareprods", $this->uri(array('action' => 'CompareProducts')));

                    $objTable = $this->getObject('htmltable', 'htmlelements');
                    $objTable->cssClass = "threeAListingTable";
                    $objTable->width = NULL;

                    $newRow = true;
                    $count = 0;

                    for ($i = $start; $i < ($end); $i++) {
                        $count++;


                        $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $productID, 'nextAction' => 'ViewProduct', 'cancelAction' => 'ViewProduct', 'cancelParams' => "id=$productID"));
                        $adaptLink = new link($uri);
                        $adaptLink->cssClass = "adaptationLinks";
                        $linkText = $this->objLanguage->languageText('mod_unesco_oer_product_new_adaptation', 'unesco_oer');
                        $adaptLink->link = $linkText;
                        $groupid = $this->objDbProducts->getAdaptationDataByProductID($products[$i]['id']);
                        $grouptitle = $this->objDbGroups->getGroupName($groupid['group_id']);
                        $thumbnail = $this->objDbGroups->getThumbnail($groupid['group_id']);

                        $abLink = new link($this->uri(array("action" => '11a', 'id' => $groupid['group_id'], "page" => '10a_tpl.php')));
                        $abLink->link = "<img src='" . $thumbnail . "'  width='45' height='49' >";
                        $abLink->cssClass = "smallAdaptationImageGrid";

                        $checkbox = new checkbox('selectedusers[]', $products[$i]['id']);
                        $checkbox->value = $products[$i]['id'];
                        $checkbox->cssId = 'user_' . $products[$i]['id'];

                        $content = '
                            
                            <div class="adaptedByDiv3a">Adapted by:</div>
                            <div class="gridSmallImageAdaptation" >
                            	' . $abLink->show() . '
                                <span class="greyListingHeading">
                            
                                    
                                    
                                </span>
                  			</div>
                            <div class="gridAdaptationLinksDiv">' .
                                $grouptitle
                                . '
                    
             	
                            </div>
                            <div class="">
                            	<div class="product3aViewDiv">
                                    <div class="imgFloatRight">
                                      <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18">
                                    </div>
                                    <div class="listingAdaptationLinkDiv">
                                        <a href="#" class="adaptationLinks">';

                        if ($this->objUser->isLoggedIn()) {
                            $content .= $adaptLink->show();
                        };


                        $content.= '</a>
                                    </div>
                           	  </div>
                                
                       		  <div class="product3aViewDiv">
                                    <div class="imgFloatRight">
                                    	<img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18">
                                </div>
                                    <div class="listingAdaptationLinkDiv">
                                    <a href="#" class="bookmarkLinks">bookmark</a>
                                 	</div>
                                </div>
                                 <div class="product3aViewDiv">
                                    <div class="imgFloatRight">' .
                                $checkbox->show() . '
                  
                          
                                    
                    
                                   <div class="listingAdaptationLinkDiv">
                                    <a href="#" class="bookmarkLinks">Compare</a>
                                 	</div>
                              
                                
                                
                          
               
                
                ';

                        if ($newRow) {
                            $objTable->startRow();
                            $objTable->addCell($content);
                            $newRow = false;
                        } else {
                            $objTable->addCell($content);
                        }


                        if ($count == 3) {
                            $newRow = true;
                            $objTable->endRow();
                            $count = 0;
                        }
                    }

                    $form->addToForm($objTable->show());
                    echo $form->show();










                    break;
                }
                
            case "4" :   {       for ($i = $start; $i < ($end); $i++) {
                                $product =  $this->getObject('product','unesco_oer');
                                $productobject = $product->loadProduct($products[$i]['id']);
                                
                                $productID = $products[$i]['id'];
                                

                                $uri = $this->uri(array('action' => 'adaptProduct', 'productID' => $productID, 'nextAction' => 'ViewProduct', 'cancelAction' => 'ViewProduct', 'cancelParams' => "id=$productID"));
                                $adaptLink = new link($uri);
                                $adaptLink->cssClass = "adaptationLinks";
                                $linkText = $this->objLanguage->languageText('mod_unesco_oer_product_new_adaptation', 'unesco_oer');
                                $adaptLink->link = $linkText;
                                
                                $bookmarkText = $this->objLanguage->languageText('mod_unesco_oer_bookmark', 'unesco_oer');
                                $adaptedText = $this->objLanguage->languageText('mod_unesco_oer_adapted_in', 'unesco_oer');
                                
                                $bookmark =  $this->objProductUtil->populatebookmark($productID,'smallLisitngIcons');

                            
                                $productData = '
                                <div class="listAdaptations">
                                <div class="floaLeftDiv">
                                    <img width="45" height="49" alt="Adaptation placeholder" src="'.$product->getThumbnailPath().'">
                                </div>
                                <div class="rightColumInnerDiv">
                                <div class="blueListingHeading">'.$product->getTitle().'</div>
                                '.$adaptedText.' <a href="#" class="productAdaptationGridViewLinks">English</a>
                                <br>
                                <div class="listingAdaptationsLinkAndIcon">
                                    <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18" class="smallLisitngIcons">
                                <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">'.$adaptLink->show().'</a></div>
                                </div>

                             
                               
                                </div>
                                </div>
                                </div>';
                            
                             echo $productData;
                            }    
                
                
                
            }
                
                
                
                
                
                
                
                
                
        }



        $thumbnail = ' <div class="paginationDiv">
     <div class="paginationImage"><img src="skins/unesco_oer/images/icon-pagination.png" alt="Pagination" width="17" height="20"></div>';





        $TotalPages = ceil(count($products) / $NumFilter);


        if ($TotalPages > 0) {

            echo $thumbnail;
            for ($i = 1; $i <= $TotalPages; $i++) {

                $abLink = new link("javascript:void(0);");
                $abLink->extra = "onclick = javascript:ajaxFunction($i,'$prod',$browsecheck,'$institutionid')";




                $abLink->link = $i;

                echo $abLink->show();
                
            }
        };
    }

}

?>