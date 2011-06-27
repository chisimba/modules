<?php

 $this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('filterdisplay', 'unesco_oer');





class javafilt extends object {
    
    
    public function init() {
        parent::init();
    }


    public function displayprods (){
        
        
       



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

$this->objDbproductthemes = $this->getobject('dbproductthemes', 'unesco_oer');
$this->objDbproductlanguages = $this->getobject('dbproductlanguages', 'unesco_oer');
$this->objDbresourcetypes = $this->getobject('dbresourcetypes', 'unesco_oer');
$this->objDbProducts = $this->getobject('dbproducts', 'unesco_oer');







    

    
    










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









if (!($LangFilter == Null or $LangFilter == 'All')) {

    $ProdLangIDs = $this->objDbProducts->getFilteredProducts('language = ' . "'$LangFilter'");

    $Templang = array(); //convert to 1d array
    $i = 0;
    foreach ($ProdLangIDs as $ProdLangID) {
        $i++;
        $Templang[$i] = $ProdLangID['id'];
    }
}



        $array_to_intersect = array($TempAuth, $TempTheme, $Templang);
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
                        
    //convert to 1d array
}


if (($LangFilter == Null or $LangFilter == 'All')) 
  if  (($ThemeFilter == Null or $ThemeFilter == 'All'))
     if    (($AuthFilter == Null or $AuthFilter == 'All')){
    
   
         $temp = $this->objDbProducts->getFilteredProducts("parent_id is null");
     
   
        
    

    $result = array(); //convert to 1d array
    $i = 0;
    foreach ($temp as $temps) {
        
        $result[$i] = $temps['id'];
         $i++;
         
  
    
    }
    
};

        
        
        
        
        
        $prodnumber =  count($result);
        
        
        
        
        
        
        

$content = ' </div>
                        <div class="blueNumberBackground">
                        <div class="iconOnBlueBackground"><img src="skins/unesco_oer/images/icon-filter.png" alt="filter"></div>
                        <div class="numberOffilteredProducts"> '.

                            
                          $prodnumber .
                           
                       '</div>
                        </div>';
        
        
        return $content;
        
        
        
        
    }
        
        
        
        
        
        
        
        
        
        
        
    
    
    
    
    
    
    
    
    
    
    
    
}



?>
