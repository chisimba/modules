<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */




class search extends Object{

    public function init()
   {

 $this->objLanguage = $this->getObject("language", "language");
        $this->objDbProducts = $this->getobject('dbproducts', 'unesco_oer');
        $this->objfilterlogic = $this->getobject('filterlogic', 'unesco_oer');

}



   function Searchdisp($page, $SortFilter, $TotalPages, $adaptationstring, $browsemapstring,$NumFilter, $PageNum){

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

















}
















?>
