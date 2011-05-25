<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class filterlogic extends object {

   public    $Filterinfo = array();







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
              $this->Filterinfo['page'] = $this->getParam('ThemeFilter');










    }

    public function ThemeFilter() {

       
       $filter = $this->setSession("Filter", $this->Filterinfo);

        $products = $this->objDbProducts->getProducts(0, 10);
        $filterTheme = new dropdown('ThemeFilter');
        $filterTheme->cssClass = "leftColumnSelectDropdown";
        $all = $this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer');
        $filterTheme->addoption($all);
        foreach ($products as $product) {

            $filterTheme->addOption($product['theme']);
        }
        $filterTheme->setSelected($ThemeFilter);


        $form = new form('ThemeFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile, 'MapEntries' => $MapEntries, 'Filterinfo' => $filter)));


        $filterTheme->addOnChange('javascript: sendThemeFilterform()');
        $form->addtoform($filterTheme->show());

       echo $this->Filterinfo['Guide'];




        return $form->show();
    }

    public function CheckBox() {





        $products = $this->objDbProducts->getProducts(0, 10);




        $form = new form('ProductType', $this->uri(array('action' => "FilterProducts", "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'MapEntries' => $MapEntries)));




        $button = new button('Search', $this->objLanguage->languageText('mod_unesco_oer_filter_search', 'unesco_oer'));
        $button->setToSubmit();

        $checkbox = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_model', 'unesco_oer'));
        $checkbox2 = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_handbook', 'unesco_oer'));
        $checkbox3 = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_guide', 'unesco_oer'));
        $checkbox4 = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_manual', 'unesco_oer'));
        $checkbox5 = new checkbox($this->objLanguage->languageText('mod_unesco_oer_filter_bestoractile', 'unesco_oer'));



        if ($Model == 'on')
            $checkbox->ischecked = true;

        if ($Handbook == 'on')
            $checkbox2->ischecked = true;

        if ($Guide == 'on')
            $checkbox3->ischecked = true;

        if ($Manual == 'on')
            $checkbox4->ischecked = true;

        if ($Besoractile == 'on')
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


        return $form->show();
    }

    public function FilterLanguage() {

        $products = $this->objDbProducts->getProducts(0, 10);
        $filterLang = new dropdown('LanguageFilter');
        $filterLang->cssClass = "leftColumnSelectDropdown";
        $filterLang->addoption($this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer'));
        foreach ($products as $product) {

            $filterLang->addOption($product['language']);
        }

        $filterLang->setSelected($LangFilter);

        $form = new form('LanguageFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile, 'MapEntries' => $MapEntries)));


        $uri = $this->uri(array('action' => 'LanguageFilter'));
        $filterLang->addOnChange('javascript: sendLanguageFilterform()');


        $form->addtoform($filterLang->show());


        return $form->show();
    }

    public function AuthFilter() {


        $products = $this->objDbProducts->getProducts(0, 10);
        $filterAuth = new dropdown('AuthorFilter');
        $filterAuth->cssClass = "leftColumnSelectDropdown";
        $filterAuth->addoption($this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer'));
        foreach ($products as $product) {

            $filterAuth->addOption($product['creator']);
        }

        $filterAuth->setSelected($AuthFilter);
        $form = new form('AuthorFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile, 'MapEntries' => $MapEntries)));


        $uri = $this->uri(array('action' => 'AuthorFilter'));
        $filterAuth->addOnChange('javascript: sendAuthorFilterform()');


        $form->addtoform($filterAuth->show());


        return $form->show();
    }

    public Function NumFilter() {

        $products = $this->objDbProducts->getProducts(0, 10);
        $filterNum = new dropdown('NumFilter');
        $filterNum->cssClass = "leftColumnSelectDropdown";


        $filterNum->addoption($this->objLanguage->languageText('mod_unesco_oer_filter_all', 'unesco_oer'));
        $filterNum->addOption('1');
        $filterNum->addOption('2');
        $filterNum->addOption('3');



        $filterNum->setSelected($NumFilter);
        $form = new form('NumFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile, 'MapEntries' => $MapEntries)));


        $uri = $this->uri(array('action' => 'NumFilter'));
        $filterNum->addOnChange('javascript: sendNumFilterform()');


        $form->addtoform($filterNum->show());


        return $form->show();
    }

    public function Reset() {


        $abLink = new link($this->uri(array("action" => 'FilterProducts', "adaptationstring" => 'parent_id is not null', "page" => '2a_tpl.php')));
        $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_reset', 'unesco_oer');


        return $abLink->show();
    }

    public function Sort(){

         $products = $this->objDbProducts->getProducts(0, 10);
                            $filterLang = new dropdown('SortFilter');
                             $filterLang->cssClass = "leftColumnSelectDropdown";

                            $filterLang->addoption('None');
                            $filterLang->addoption('Date');
                            $filterLang->addOption('Alphabetical');


                            $filterLang->setSelected($SortFilter);
                            $form = new form('SortFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile, 'MapEntries' => $MapEntries)));


                            $uri = $this->uri(array('action' => 'SortFilter'));
                            $filterLang->addOnChange('javascript: sendSortFilterform()');



                            $form->addtoform($this->objLanguage->languageText('mod_unesco_oer_sort_by', 'unesco_oer'));
                            $form->addtoform($filterLang->show());
                            return $form->show();







    }



    public function Search(){








                                    $button = new button('Search', 'GO');
                                    $button->setToSubmit();

                                    $textinput = new textinput('SearchInput');
                                    $textinput->cssClass = "searchInput";



                                    $filterSearch = new dropdown('SearchFilter');
                                    $filterSearch->cssClass = "searchDropDown";

                                    $filterSearch->addoption($this->objLanguage->languageText('mod_unesco_oer_search_title', 'unesco_oer'));
                                    $filterSearch->addoption($this->objLanguage->languageText('mod_unesco_oer_search_date', 'unesco_oer'));
                                    $filterSearch->addoption($this->objLanguage->languageText('mod_unesco_oer_search_creator', 'unesco_oer'));



                                    $form = new form('SearchField', $this->uri(array('action' => 'Search', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $Guide, 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile)));







                                    $form->addToForm($textinput->show());
                                    $form->addtoform($filterSearch->show());
                                    $form->addToForm($button->show());


                                    return $form->show();





    }








}
?>
