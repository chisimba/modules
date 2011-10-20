<?php

error_reporting(E_ALL);
ini_set('display_errors', 'off');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class filterdisplay extends object {

    public $Filterinfo = array();

    public function init() {


        $this->objLanguage = $this->getObject("language", "language");
        $this->objDbProducts = $this->getobject('dbproducts', 'unesco_oer');
          $this->objDbInstitutionTypes = $this->getobject('dbinstitutiontypes', 'unesco_oer');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->objDbproductthemes = $this->getobject('dbproductthemes', 'unesco_oer');
        $this->objProductUtil = $this->getObject('productutil', 'unesco_oer');
        $this->objDbRegions = $this->getObject('dbregions', 'unesco_oer');
        $this->objDbReporting = $this->getObject('dbreporting', 'unesco_oer');

        $this->Filterinfo['ThemeFilter'] = $this->getParam('ThemeFilter');
        $this->Filterinfo['Model'] = $this->getParam('Model');
        $this->Filterinfo['Handbook'] = $this->getParam('Handbook');
        $this->Filterinfo['Guide'] = $this->getParam('Guide');
        $this->Filterinfo['Manual'] = $this->getParam('Manual');
        $this->Filterinfo['Besoractile'] = $this->getParam('Besoractile');
        $this->Filterinfo['AuthorFilter'] = $this->getParam('AuthorFilter');
        $this->Filterinfo['LanguageFilter'] = $this->getParam('LanguageFilter');
        $this->Filterinfo['NumFilter'] = $this->getParam('NumFilter');
         $this->Filterinfo['InstitutionFilter'] = $this->getParam('InstitutionFilter');
          $this->Filterinfo['RegionFilter'] = $this->getParam('RegionFilter');
           $this->Filterinfo['CountryFilter'] = $this->getParam('CountryFilter');
           
    }

    function SideFilter($page, $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum) {
        $uri = $this->uri(array('action' => 'createCommentSubmit', 'id' => $productID, 'pageName' => 'home'));
        $form = new form('temporary', $this->uri(array('action' => "FilterProducts", "adaptationstring" => $adaptationstring, "page" => $page, "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'SortFilter' => $SortFilter, 'MapEntries' => $MapEntries)));
        $form->addToForm('<div id="filternumDiv">
            <div class="moduleHeader blueText">
                            ');
        $form->addToForm($this->objLanguage->languageText('mod_unesco_oer_product_description', 'unesco_oer'));
        $form->addToForm('</div>'); //close module header div


        $form->addToForm('
                        <div class="blueNumberBackground">
                        <div class="iconOnBlueBackground"><img src="skins/unesco_oer/images/icon-filter.png" alt="filter"></div>
                        <div class="numberOffilteredProducts"> 
                        ');


        if (is_string($adaptationstring)) {
            $TotalRecords = $this->objDbProducts->getTotalEntries($adaptationstring);
        } else {
            $TotalRecords = count($adaptationstring);
        }
        $form->addToForm($TotalRecords);
        $form->addToForm('</div> '); //number if filter products div
        $form->addToForm(' </div>');//blue number bg
         $form->addToForm(' </div>');



        $form->addToForm('<div class="moduleHeader darkBlueText">');
        $form->addToForm("Product matches filter criteria<br/>");
        $form->addToForm('<img src="skins/unesco_oer/images/icon-filter-type.png" alt="Type of product" class="modulesImages">');
        $form->addToForm($this->objLanguage->languageText('mod_unesco_oer_product_type', 'unesco_oer'));
        $form->addToForm(' </div>'); //module subheader

        $form->addToForm('<div class="blueBackground blueBackgroundCheckBoxText">');
        $products = $this->objDbProducts->getProducts(0, 10);
        $checkbox = new checkbox('Model');
        $checkbox2 = new checkbox('Handbook');
        $checkbox3 = new checkbox('Guide');
        $checkbox4 = new checkbox('Manual');
        $checkbox5 = new checkbox('BestPractices');

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
        $form->addToForm($this->objLanguage->languageText('mod_unesco_oer_model', 'unesco_oer') . '<br>');
        $form->addToForm($checkbox2->show());
        $form->addToForm($this->objLanguage->languageText('mod_unesco_oer_handbook', 'unesco_oer') . '<br>');
        $form->addToForm($checkbox3->show());
        $form->addToForm($this->objLanguage->languageText('mod_unesco_oer_guide', 'unesco_oer') . 'Guide<br>');
        $form->addToForm($checkbox4->show());
        $form->addToForm($this->objLanguage->languageText('mod_unesco_oer_manual', 'unesco_oer') . 'Manual<br>');
        $form->addToForm($checkbox5->show());
        $form->addToForm($this->objLanguage->languageText('mod_unesco_oer_best_practices', 'unesco_oer') . '<br>');

        $form->addToForm('</div>'); //blueBackground blueBackgroundCheckBoxText

        $form->addToForm('<br>');

        $form->addToForm('<div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-theme.png" alt="Theme" class="modulesImages"> ');
        $form->addToForm($this->objLanguage->languageText('mod_unesco_oer_theme', 'unesco_oer'));
        $form->addToForm(' </div>');


        //types
        $form->addToForm('<div class="blueBackground">');
        $products = $this->objDbproductthemes->getProductThemes();
        $filterTheme = new dropdown('ThemeFilter');
        $filterTheme->cssClass = "leftColumnSelectDropdown";
        $all = $this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer');
        $filterTheme->addoption($all);


        foreach ($products as $product) {

            $filterTheme->addOption($product['id'], $product['theme']);
        }
        $filterTheme->setSelected($this->Filterinfo['ThemeFilter']);

        // $form = newform('ThemeFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'SortFilter' => $SortFilter, 'MapEntries' => $MapEntries,'inf' =>$temp)));
        //     $filterTheme->addOnChange('javascript:ajaxFunction()');
        $form->addtoform($filterTheme->show());
        $form->addtoform('</div>'); //bluebackground
        $form->addToForm('<br>');
        $form->addToForm('<div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-languages.png" alt="Language" class="modulesImages">');
        $form->addtoform($this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer'));
        $form->addtoform('</div>'); //
        //languages
        $form->addToForm('<div class="blueBackground">');
        $products = $this->objLanguage->getLangs();
        $filterLang = new dropdown('LanguageFilter');
        $filterLang->cssClass = "leftColumnSelectDropdown";
        $filterLang->addoption($this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer'));
        foreach ($products as $id=>$product) {

            $filterLang->addOption($id, $product);
        }

        $filterLang->setSelected($this->Filterinfo['LanguageFilter']);
        $form->addtoform($filterLang->show());
        $form->addtoform('</div> <br>');


        //authors
        $form->addToForm('<div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-author.png" alt="Author" class="modulesImages">');
        $form->addtoform($this->objLanguage->languageText('mod_unesco_oer_author', 'unesco_oer'));
        $form->addtoform(' </div>');


        $form->addToForm('<div class="blueBackground">');
        $products = $this->objDbProducts->getProducts(0, 10);
        $filterAuth = new dropdown('AuthorFilter');
        $filterAuth->cssClass = "leftColumnSelectDropdown";
        $filterAuth->addoption($this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer'));
        foreach ($products as $product) {

            $filterAuth->addOption($product['creator']);
        }

        $filterAuth->setSelected($this->Filterinfo['AuthorFilter']);
        $form->addtoform($filterAuth->show());
        $form->addtoform(' </div><br>');
        
        
        
        $form->addToForm('<div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-author.png" alt="Author" class="modulesImages">');
        $form->addtoform($this->objLanguage->languageText('mod_unesco_oer_institution', 'unesco_oer'));
        $form->addtoform(' </div>');


        $form->addToForm('<div class="blueBackground">');
        $products = $this->objDbInstitutionTypes->getInstitutionTypes();
        $filterinstitution = new dropdown('InstitutionFilter');
        $filterinstitution->cssClass = "leftColumnSelectDropdown";
        $filterinstitution->addoption($this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer'));
        foreach ($products as $product) {

            $filterinstitution->addOption($product['type']);
        }

        $filterinstitution->setSelected($this->Filterinfo['InstitutionFilter']);
        $form->addtoform($filterinstitution->show());
        $form->addtoform(' </div><br>');
        
        
        
         $form->addToForm('<div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-author.png" alt="Author" class="modulesImages">');
        $form->addtoform($this->objLanguage->languageText('mod_unesco_oer_region', 'unesco_oer'));
        $form->addtoform(' </div>');


        $form->addToForm('<div class="blueBackground">');
        $products = $this->objDbRegions->getRegions();
        $filterRegion = new dropdown('RegionFilter');
        $filterRegion->cssClass = "leftColumnSelectDropdown";
        $filterRegion->addoption($this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer'));
        foreach ($products as $product) {

            $filterRegion->addOption($product['region']);
        }

        $filterRegion->setSelected($this->Filterinfo['RegionFilter']);
        $form->addtoform($filterRegion->show());
        $form->addtoform(' </div><br>');
        
        
        
        
        
         $form->addToForm('<div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-author.png" alt="Author" class="modulesImages">');
        $form->addtoform($this->objLanguage->languageText('mod_unesco_oer_Country', 'unesco_oer'));
        $form->addtoform(' </div>');


        $form->addToForm('<div class="blueBackground">');
        $products = $this->objDbProducts->getProductLanguages();
        
        $filterRegion = new dropdown('CountryFilter');
        $filterRegion->cssClass = "leftColumnSelectDropdown";
        $filterRegion->addoption($this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer'));
        foreach ($products as $product) {
            $countryname = $this->objDbReporting->getCountryName($product['country_code']);
            $filterRegion->addOption($product['country_code'],$countryname);
        }

        $filterRegion->setSelected($this->Filterinfo['CountryFilter']);
        $form->addtoform($filterRegion->show());
        $form->addtoform(' </div><br>');
        
        
        
        
        
        
        
        
        //items per page
        $form->addToForm('<div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-items-per-page.png" alt="Items per page" class="modulesImages"> ');
        $form->addtoform($this->objLanguage->languageText('mod_unesco_oer_items_per_page', 'unesco_oer'));
        $form->addtoform(' </div>');
        $form->addToForm('<div class="blueBackground"> ');
        $products = $this->objDbProducts->getProducts(0, 10);
        $filterNum = new dropdown('NumFilter');
        $filterNum->cssClass = "leftColumnSelectDropdown";
        $filterNum->addoption($this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer'));
        $filterNum->addOption('1');
        $filterNum->addOption('2');
        $filterNum->addOption('3');
        $filterNum->setSelected($this->Filterinfo['NumFilter']);
        $form->addtoform($filterNum->show());

       $form->addToForm('</div>');



        return $form->show();
        
    }

    public function Pagination($page, $SortFilter, $TotalPages, $NumFilter, $PageNum, $products) {



        $thumbnail = '
     <div class="paginationImage"><img src="skins/unesco_oer/images/icon-pagination.png" alt="Pagination" width="17" height="20"></div>';




        //      $TotalRecords = $this->objDbProducts->getTotalEntries($adaptationstring);
        //   $TotalPages = ceil($TotalRecords / $NumFilter);
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
    }

    public function SortDisp($page, $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum) {

        $form = new form('SortFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'SortFilter' => $SortFilter, 'MapEntries' => $MapEntries)));

        $form->addToForm('<div class="sortBy">');


        $products = $this->objDbProducts->getProducts(0, 10);
        $filtersort = new dropdown('SortFilter');
        $filterLang->cssClass = "leftColumnSelectDropdown";

        $filtersort->addoption('None');
        //   $filtersort->addoption('Date');
        $filtersort->addOption('Alphabetical');


        $filtersort->setSelected($SortFilter);




        //  $filtersort->addOnChange('javascript: sendSortFilterform()');



        $form->addtoform($this->objLanguage->languageText('mod_unesco_oer_sort_by', 'unesco_oer'));
        $form->addtoform($filtersort->show());





        $form->addToForm('  </div>');



        return $form->show();
    }

    function Search($page, $SortFilter, $TotalPages, $adaptationstring, $browsemapstring, $NumFilter, $PageNum) {

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

    public function FilterTotalProducts($page, $sort, $TotalPages, $adaptationstring, $browsemapstring) {



        if ($browsemapstring != null)
            $buildstring = $browsemapstring;
        else
            $buildstring = $adaptationstring;


        $Auth = $this->Filterinfo['AuthorFilter'];
        $Theme = $this->Filterinfo['ThemeFilter'];
        $Language = $this->Filterinfo['LanguageFilter'];


        if (!( $this->Filterinfo['AuthorFilter'] == Null or $this->Filterinfo['AuthorFilter'] == 'All'))
            $buildstring .= ' and creator = ' . "'$Auth'";

        if (!($this->Filterinfo['ThemeFilter'] == Null or $this->Filterinfo['ThemeFilter'] == 'All'))
            $buildstring .= ' and theme = ' . "'$Theme'";

        if (!($this->Filterinfo['LanguageFilter'] == Null or $this->Filterinfo['LanguageFilter'] == 'All'))
            $buildstring .= ' and language = ' . "'$Language'";


        if (( $this->Filterinfo['Model'] == 'on') or ($this->Filterinfo['Handbook'] == 'on') or ($this->Filterinfo['Guide'] == 'on') or ($this->Filterinfo['Manual'] == 'on') or ($this->Filterinfo['Besoractile'] == 'on'))
            $buildstring .= ' and (';

        if ($this->Filterinfo['Model'] == 'on')
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