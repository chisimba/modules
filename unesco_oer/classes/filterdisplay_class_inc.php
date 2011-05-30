<?php
error_reporting(E_ALL);
ini_set('display_errors', 'off');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class filterdisplay extends object {

    public $Filterinfo  = array();

    public function init() {


        $this->objLanguage = $this->getObject("language", "language");
        $this->objDbProducts = $this->getobject('dbproducts', 'unesco_oer');
         $this->loadClass('link', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
     

        $this->Filterinfo['ThemeFilter'] = $this->getParam('ThemeFilter');
        $this->Filterinfo['Model'] = $this->getParam('Model');
        $this->Filterinfo['Handbook'] = $this->getParam('Handbook');
        $this->Filterinfo['Guide'] = $this->getParam('Guide');
        $this->Filterinfo['Manual'] = $this->getParam('Manual');
        $this->Filterinfo['Besoractile'] = $this->getParam('Besoractile');
        $this->Filterinfo['AuthorFilter'] = $this->getParam('AuthorFilter');
        $this->Filterinfo['LanguageFilter'] = $this->getParam('LanguageFilter');
        $this->Filterinfo['NumFilter'] = $this->getParam('NumFilter');
        
 
    }

    function SideFilter($page, $SortFilter, $TotalPages, $adaptationstring, $browsemapstring,$NumFilter, $PageNum) {





        $form = new form('temporary', $this->uri(array('action' => "FilterProducts", "adaptationstring" => $adaptationstring, "page" => $page, "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'SortFilter' => $SortFilter, 'MapEntries' => $MapEntries)));


            $form->addToForm(' 
                <div class="leftColumnDiv">
                    <div class="moduleHeader">');
                           
                           $form->addToForm($this->objLanguage->languageText('mod_unesco_oer_product_description', 'unesco_oer'));








     $form->addToForm('</div>
                        <div class="blueNumberBackground">
                        <div class="iconOnBlueBackground"><img src="skins/unesco_oer/images/icon-filter.png" alt="filter"></div>
                        <div class="numberOffilteredProducts"> ');

                             $TotalRecords = $this->objDbProducts->getTotalEntries('relation is not null');
                           $form ->addToForm($TotalRecords);
                         $form->addToForm('</div>
                        </div>
                        <div class="moduleSubHeader">Product matches filter criteria</div>
                        <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-type.png" alt="Type of product" class="modulesImages"> ');

                        $form->addToForm($this->objLanguage->languageText('mod_unesco_oer_product_type', 'unesco_oer'));


                     

        

        $form->addToForm('  </div>
                              <div class="blueBackground blueBackgroundCheckBoxText">');

        $products = $this->objDbProducts->getProducts(0, 10);




        //  $form = new form('ProductType', $this->uri(array('action' => "FilterProducts", "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'SortFilter' => $SortFilter, 'MapEntries' => $MapEntries)));




        $button = new button('Search', $this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));
        $button->setToSubmit();

        $checkbox = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_model', 'unesco_oer'));
        $checkbox2 = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_handbook', 'unesco_oer'));
        $checkbox3 = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_guide', 'unesco_oer'));
        $checkbox4 = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_manual', 'unesco_oer'));
        $checkbox5 = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_bestoractile', 'unesco_oer'));



        if ($this->Filterinfo['Model'] == 'on')
            $checkbox->ischecked = true;

        if ($this->Filterinfo['Handbook'] == 'on')
            $checkbox2->ischecked = true;

        if ($this->Filterinfo['Guide'] == 'on')
            $checkbox3->ischecked = true;

        if ($this->Filterinfo['Manual'] == 'on')
            $checkbox4->ischecked = true;

        if ($this->Filterinfo['Besoractile'] == 'on')
            $checkbox5->ischecked = true;


        $form->addToForm($checkbox->show());
        $form->addToForm('Model<br>');
        $form->addToForm($checkbox2->show());
        $form->addToForm('Handbook<br>');
        $form->addToForm($checkbox3->show());
        $form->addToForm('Guide<br>');
        $form->addToForm($checkbox4->show());
        $form->addToForm('Manual<br>');
        $form->addToForm($checkbox5->show());
        $form->addToForm('Best Practices<br>');
        $form->addToForm($button->show());








        $form->addToForm('</div>


                    <br> 

 <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-theme.png" alt="Theme" class="modulesImages"> ');
        $form->addToForm($this->objLanguage->languageText('mod_unesco_oer_theme', 'unesco_oer'));
        $form->addToForm(' </div>
                    <div class="blueBackground">');

        $products = $this->objDbProducts->getProducts(0, 10);
        $filterTheme = new dropdown('ThemeFilter');
        $filterTheme->cssClass = "leftColumnSelectDropdown";
        $all = $this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer');
        $filterTheme->addoption($all);
        foreach ($products as $product) {

            $filterTheme->addOption($product['theme']);
        }
        $filterTheme->setSelected($this->Filterinfo['ThemeFilter']);

        $temp = serialize($this->Filterinfo);
        // $form = newform('ThemeFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'SortFilter' => $SortFilter, 'MapEntries' => $MapEntries,'inf' =>$temp)));


        $filterTheme->addOnChange('javascript: sendThemeFilterform()');
        $form->addtoform($filterTheme->show());









        $form->addtoform('  </div>
                    <br>


                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-languages.png" alt="Language" class="modulesImages">');

        $form->addtoform($this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer'));


        $form->addtoform('</div>
                    <div class="blueBackground">');

        $products = $this->objDbProducts->getProducts(0, 10);
        $filterLang = new dropdown('LanguageFilter');
        $filterLang->cssClass = "leftColumnSelectDropdown";
        $filterLang->addoption($this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer'));
        foreach ($products as $product) {

            $filterLang->addOption($product['language']);
        }

        $filterLang->setSelected($this->Filterinfo['LanguageFilter']);

        //  $form = new form('LanguageFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'SortFilter' => $SortFilter, 'MapEntries' => $MapEntries)));


        $uri = $this->uri(array('action' => 'LanguageFilter'));
        $filterLang->addOnChange('javascript: sendLanguageFilterform()');


        $form->addtoform($filterLang->show());












        $form->addtoform('</div>
                    <br>
                    
                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-author.png" alt="Author" class="modulesImages">');

        $form->addtoform($this->objLanguage->languageText('mod_unesco_oer_author', 'unesco_oer'));


        $form->addtoform(' </div>
                    <div class="blueBackground">');
        $products = $this->objDbProducts->getProducts(0, 10);
        $filterAuth = new dropdown('AuthorFilter');
        $filterAuth->cssClass = "leftColumnSelectDropdown";
        $filterAuth->addoption($this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer'));
        foreach ($products as $product) {

            $filterAuth->addOption($product['creator']);
        }

        $filterAuth->setSelected($this->Filterinfo['AuthorFilter']);
        //   $form = new form('AuthorFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $this->Filterinfo['Guide'], 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile, 'MapEntries' => $MapEntries)));


        $uri = $this->uri(array('action' => 'AuthorFilter'));
        $filterAuth->addOnChange('javascript: sendAuthorFilterform()');


        $form->addtoform($filterAuth->show());











        $form->addtoform(' </div>
                    <br>
                    <div class="moduleHeader"><img src="skins/unesco_oer/images/icon-filter-items-per-page.png" alt="Items per page" class="modulesImages"> ');

        $form->addtoform($this->objLanguage->languageText('mod_unesco_oer_items_per_page', 'unesco_oer'));

        $form->addtoform('  </div>
                    <div class="blueBackground"> ');



        $products = $this->objDbProducts->getProducts(0, 10);
        $filterNum = new dropdown('NumFilter');
        $filterNum->cssClass = "leftColumnSelectDropdown";


        $filterNum->addoption($this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer'));
        $filterNum->addOption('1');
        $filterNum->addOption('2');
        $filterNum->addOption('3');



        $filterNum->setSelected($this->Filterinfo['NumFilter']);
        //   $form = new form('NumFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $this->Filterinfo['Guide'], 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile, 'MapEntries' => $MapEntries)));


     //   $uri = $this->uri(array('action' => 'NumFilter'));
       // $filterNum->addOnChange('javascript: sendNumFilterform()');


        $form->addtoform($filterNum->show());





        $form->addtoform('      </div>
                    <br><br>
                    <div class="blueBackground rightAlign">
                        <img src="skins/unesco_oer/images/button-reset.png" alt="Reset" width="17" height="17" class="imgFloatLeft">
                        <a href="#" class="resetLink"> ');


        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'parent_id is not null', "page" => '2a_tpl.php')));
        $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reset', 'unesco_oer');
        $form->addtoform($abLink->show());

        $form->addtoform(' </a>
                    </div>
                    <div class="filterheader">


                        <?php ?>

                    </div>
                    <div class="rssFeed">
                        <img src="skins/unesco_oer/images/small-icon-rss-feed.png" alt="RSS Feed" width="18" height="18" class="imgFloatRight">
                        <div class="feedLinkDiv"><a href="#" class="rssFeedLink">RSS Feed</a></div>
                    </div>
           ');



        return $form->show();
    }


    public function Pagination($page, $SortFilter, $TotalPages, $adaptationstring, $browsemapstring,$NumFilter,$PageNum,$pageinfo){



 $thumbnail ='
     <div class="paginationImage"><img src="skins/unesco_oer/images/icon-pagination.png" alt="Pagination" width="17" height="20"></div>';
 

       

                        $TotalRecords = $this->objDbProducts->getTotalEntries('relation is not null');
                        $TotalPages = ceil($TotalRecords / $NumFilter);


                        if ($TotalPages > 0) {

                         echo   $thumbnail;
                            for ($i = 1; $i <= $TotalPages; $i++) {

                                $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'SortFilter' => $SortFilter, 'MapEntries' => $MapEntries, 'pageinfo' => $pageinfo)));
                                $abLink->link = $i;
                                     echo    $abLink->show();

                            }
                        };
  
        

    }






    public function SortDisp($page, $SortFilter, $TotalPages, $adaptationstring, $browsemapstring,$NumFilter, $PageNum)

{

      $form = new form('SortFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'SortFilter' => $SortFilter, 'MapEntries' => $MapEntries)));

  $form->addToForm('<div class="sortBy">');


 $products = $this->objDbProducts->getProducts(0, 10);
                            $filterLang = new dropdown('SortFilter');
                             $filterLang->cssClass = "leftColumnSelectDropdown";

                            $filterLang->addoption('None');
                            $filterLang->addoption('Date');
                            $filterLang->addOption('Alphabetical');


                            $filterLang->setSelected($SortFilter);



                            $uri = $this->uri(array('action' => 'SortFilter'));
                            $filterLang->addOnChange('javascript: sendSortFilterform()');



                            $form->addtoform($this->objLanguage->languageText('mod_unesco_oer_sort_by', 'unesco_oer'));
                            $form->addtoform($filterLang->show());





$form->addToForm('  </div>');



return $form->show();




}


function Search($page, $SortFilter, $TotalPages, $adaptationstring, $browsemapstring,$NumFilter, $PageNum){

        $form = new form('SearchField', $this->uri(array('action' => 'Search', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'SortFilter' => $SortFilter)));



    $form->addToForm('   <div class="searchInputBoxDiv"> ');


   $button = new button('Search', 'GO');
                                    $button->setToSubmit();

                                    $textinput = new textinput('SearchInput');
                                    $textinput->cssClass = "searchInput";



                                    $filterSearch = new dropdown('SearchFilter');
                                    $filterSearch->cssClass = "searchDropDown";

                                    $filterSearch->addoption($this->objLanguage->languageText('mod_unesco_oer_search_title', 'unesco_oer'));
                                    $filterSearch->addoption($this->objLanguage->languageText('mod_unesco_oer_search_date', 'unesco_oer'));
                                    $filterSearch->addoption($this->objLanguage->languageText('mod_unesco_oer_search_creator', 'unesco_oer'));











                                    $form->addToForm($textinput->show());
                                    $form->addtoform($filterSearch->show());
                                    $form->addToForm($button->show());











                                $form->addToForm('
                                </div>');


                    return $form->show();



     }

















   public function FilterTotalProducts( $page, $sort, $TotalPages, $adaptationstring,$browsemapstring)
    {



        if ($browsemapstring != null)
        $buildstring = $browsemapstring;
        else
        $buildstring = $adaptationstring;


 $Auth  = $this->Filterinfo['AuthorFilter'];
 $Theme =  $this->Filterinfo['ThemeFilter'];
  $Lang = $this->Filterinfo['LanguageFilter'];


        if (!( $this->Filterinfo['AuthorFilter'] == Null or  $this->Filterinfo['AuthorFilter'] == 'All'))
            $buildstring .= ' and creator = ' ."'$Auth'";

        if (!($this->Filterinfo['ThemeFilter'] == Null or $this->Filterinfo['ThemeFilter'] == 'All'))
            $buildstring .= ' and theme = ' . "'$Theme'";

        if (!($this->Filterinfo['LanguageFilter'] == Null or $this->Filterinfo['LanguageFilter'] == 'All'))
            $buildstring .= ' and language = ' . "'$lang'";


        if (( $this->Filterinfo['Model'] == 'on') or ($this->Filterinfo['Handbook'] == 'on') or ($this->Filterinfo['Guide'] == 'on') or ($this->Filterinfo['Manual'] == 'on') or ($this->Filterinfo['Besoractile'] == 'on'))
            $buildstring .= ' and (';

        if ( $this->Filterinfo['Model'] == 'on')
            $buildstring .= ' resource_type = "Model" or';
        if ($this->Filterinfo['Handbook'] == 'on')
            $buildstring .= ' resource_type = "Handbook" or';
        if ($this->Filterinfo['Guide'] == 'on')
            $buildstring .= ' resource_type = "Guide" or';
        if ($this->Filterinfo['Manual'] == 'on')
            $buildstring .= ' resource_type = "Manual" or';
        if ($this->Filterinfo['Besoractile'] == 'on')
            $buildstring .= ' resource_type = "Besoractile" or';

        $length = strlen($buildstring);

        if (( $this->Filterinfo['Model'] == 'on') or ($this->Filterinfo['Handbook'] == 'on') or ($this->Filterinfo['Guide'] == 'on') or ($this->Filterinfo['Manual'] == 'on') or ($this->Filterinfo['Besoractile'] == 'on')) {
            $buildstring = substr($buildstring, 0, ($length - 2));

            $buildstring .= ')';
        }



        if ($sort == 'Date')
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
