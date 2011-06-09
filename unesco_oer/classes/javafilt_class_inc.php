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






    

    
    










   if ($browsemapstring != null)
            $buildstring = $browsemapstring;
        else
            $buildstring = $adaptationstring;


        if (!($AuthFilter == Null or $AuthFilter == 'All'))
            $buildstring .= ' and creator = ' . "'$AuthFilter'";

        if (!($ThemeFilter == Null or $ThemeFilter == 'All'))
            $buildstring .= ' and theme = ' . "'$ThemeFilter'";

        if (!($LangFilter == Null or $LangFilter == 'All'))
            $buildstring .= ' and language = ' . "'$LangFilter'";


        if (($Model == 'on') or ($Handbook == 'on') or ($Guide == 'on') or ($Manual == 'on') or ($Besoractile == 'on'))
            $buildstring .= ' and (';

        if ($Model == 'on')
            $buildstring .= ' resource_type = "Model" or';
        if ($Handbook == 'on')
            $buildstring .= ' resource_type = "Handbook" or';
        if ($Guide == 'on')
            $buildstring .= ' resource_type = "Guide" or';
        if ($Manual == 'on')
            $buildstring .= ' resource_type = "Manual" or';
        if ($Besoractile == 'on')
            $buildstring .= ' resource_type = "Besoractile" or';

        $length = strlen($buildstring);

        if (($Model == 'on') or ($Handbook == 'on') or ($Guide == 'on') or ($Manual == 'on') or ($Besoractile == 'on')) {
            $buildstring = substr($buildstring, 0, ($length - 2));

            $buildstring .= ')';
        }



        if ($sort == 'Date Added')
            $buildstring .= ' order by created_on';
        else if ($sort == 'Alphabetical')
            $buildstring .= ' order by title';

        $TotalEntries = $buildstring;
        
        $objDbProducts = $this->getObject('dbproducts');
        
         $TotalRecords = $objDbProducts->getTotalEntries($buildstring);

$content = ' </div>
                        <div class="blueNumberBackground">
                        <div class="iconOnBlueBackground"><img src="skins/unesco_oer/images/icon-filter.png" alt="filter"></div>
                        <div class="numberOffilteredProducts"> '.

                            
                           $TotalRecords.
                           
                       '</div>
                        </div>';
        
        
        return $content;
        
        
        
        
    }
        
        
        
        
        
        
        
        
        
        
        
    
    
    
    
    
    
    
    
    
    
    
    
}



?>
