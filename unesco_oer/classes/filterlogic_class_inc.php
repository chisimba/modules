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
             










    }

   
    public function Sort(){

         $products = $this->objDbProducts->getProducts(0, 10);
                            $filterLang = new dropdown('SortFilter');
                             $filterLang->cssClass = "leftColumnSelectDropdown";

                            $filterLang->addoption('None');
                            $filterLang->addoption('Date');
                            $filterLang->addOption('Alphabetical');


                            $filterLang->setSelected($SortFilter);
                            $form = new form('SortFilter', $this->uri(array('action' => 'FilterProducts', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $this->Filterinfo['Guide'], 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile, 'MapEntries' => $MapEntries)));


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



                                    $form = new form('SearchField', $this->uri(array('action' => 'Search', "adaptationstring" => $adaptationstring, "page" => '2a_tpl.php', "TotalPages" => $TotalPages, "NumFilter" => $NumFilter, "PageNum" => $i, 'ThemeFilter' => $ThemeFilter, 'AuthorFilter' => $AuthFilter, 'LanguageFilter' => $LangFilter, 'SortFilter' => $SortFilter, 'Guide' => $this->Filterinfo['Guide'], 'Manual' => $Manual, 'Handbook' => $Handbook, 'Model' => $Model, 'Besoractile' => $Besoractile)));







                                    $form->addToForm($textinput->show());
                                    $form->addtoform($filterSearch->show());
                                    $form->addToForm($button->show());


                                    return $form->show();





    }




     




      /**
     * This function Builds the String to Send to the DBhandler and return the total number of entries according to the selected Filter
     * @param <type>$AuthFilter,$ThemeFilter,$LangFilter,$page,$sort,$TotalPages,$adaptationstring,$Model,$Handbook,$Guide,$Manual,$Besoractile
     * @return <type> $TotalEntries
     */
    public function FilterTotalProducts( $page, $sort, $TotalPages, $adaptationstring,$browsemapstring)
    {

        if ($browsemapstring != null)
        $buildstring = $browsemapstring;
        else
        $buildstring = $adaptationstring;


        if (!( $this->Filterinfo['AuthorFilter'] == Null or  $this->Filterinfo['AuthorFilter'] == 'All'))
            $buildstring .= ' and creator = ' .$this->Filterinfo['AuthorFilter'];

        if (!($this->Filterinfo['ThemeFilter'] == Null or $this->Filterinfo['ThemeFilter'] == 'All'))
            $buildstring .= ' and theme = ' . $this->Filterinfo['ThemeFilter'];

        if (!($this->Filterinfo['LanguageFilter'] == Null or $this->Filterinfo['LanguageFilter'] == 'All'))
            $buildstring .= ' and language = ' . $this->Filterinfo['LanguageFilter'];


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
    public function FilterAllProducts($NumFilter, $PageNum, $TotalEntries)
    {


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
<script>

    function sendThemeFilterform()
    {
        document.forms["ThemeFilter"].submit();
        
    }

    function sendLanguageFilterform()
    {
        document.forms["LanguageFilter"].submit();

    }function sendAuthorFilterform()
    {
        document.forms["AuthorFilter"].submit();
    }


    function sendSortFilterform()
    {
        document.forms["SortFilter"].submit();
    }

    function sendNumFilterform()
    {
        document.forms["NumFilter"].submit();
    }

</script>