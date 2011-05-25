<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class filterdisplay extends object {

    public function init() {


        $this->objLanguage = $this->getObject("language", "language");
        $this->objDbProducts = $this->getobject('dbproducts', 'unesco_oer');
        $this->objfilterlogic = $this->getobject('filterlogic', 'unesco_oer');
     
    }

    function SideFilter($adaptationstring) {






$content = '';
  $content .= '  <div class="blueBackground blueBackgroundCheckBoxText">'.

                    $this->objfilterlogic->CheckBox()
                        




                     

                 .'   </div>


                    <br> 


                   

        

 <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-theme.png" alt="Theme" class="modulesImages"> ' .
                $this->objLanguage->languageText('mod_unesco_oer_theme', 'unesco_oer') .
                ' </div>
                    <div class="blueBackground">' . $this->objfilterlogic->ThemeFilter()
                . '  </div>
                    <br>


                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-languages.png" alt="Language" class="modulesImages">'.
                        
                         $this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer').'
                        

                    </div>
                    <div class="blueBackground">' .
                        
                       $this->objfilterlogic->FilterLanguage()

                                                                                          
               . '</div>
                    <br>
                    
                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-author.png" alt="Author" class="modulesImages">'.
                        
                         $this->objLanguage->languageText('mod_unesco_oer_author', 'unesco_oer')
                        

                   .' </div>
                    <div class="blueBackground">'.

                      
                       $this->objfilterlogic->AuthFilter()



                   .' </div>
                    <br>
                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-items-per-page.png" alt="Items per page" class="modulesImages"> '.
                       
                         $this->objLanguage->languageText('mod_unesco_oer_items_per_page', 'unesco_oer')
                       .'
                    </div>
                    <div class="blueBackground"> '.


                       
                        $this->objfilterlogic->Numfilter()


.'
                                                          
                    </div>
                    <br><br>
                    <div class="blueBackground rightAlign">
                        <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
                        <a href="#" class="resetLink"> '.
                           
                          $this->objfilterlogic->Reset()

.'
                        </a>
                    </div>
                    <div class="filterheader">


                        <?php ?>

                    </div>
                    <div class="rssFeed">
                        <img src="skins/unesco_oer/images/small-icon-rss-feed.png" alt="RSS Feed" width="18" height="18" class="imgFloatRight">
                        <div class="feedLinkDiv"><a href="#" class="rssFeedLink">RSS Feed</a></div>
                    </div>
           ';



        return $content;
    }

    public function FilterTotalProducts($AuthFilter, $ThemeFilter, $LangFilter, $page, $sort, $TotalPages, $adaptationstring, $Model, $Handbook, $Guide, $Manual, $Besoractile, $browsemapstring) {

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








        return $TotalEntries;
    }

    /**
     * This functionTakes the Filtered string and Returns the Products according to the pagination filter seleted.
     * @param <type>$NumFilter,$PageNum,$TotalEntries
     * @return <type> $Buildstring
     */
    public function FilterAllProducts($NumFilter, $PageNum, $TotalEntries) {


        if ((!($NumFilter == null or $NumFilter == 'All')) & $PageNum == null) {
            $start = 0;
            $end = $start + $NumFilter;
            $TotalEntries .= ' LIMIT ' . $start . ',' . $end;
        } else if (!($NumFilter == null or $NumFilter == 'All')) {

            $temp = $NumFilter * $PageNum - 1;
            $start = $temp - $NumFilter + 1;
            $end = $NumFilter;
            $TotalEntries .= ' LIMIT ' . $start . ',' . $end;
        }

        $Buildstring = $TotalEntries;


        return $Buildstring;
    }

}
?>
