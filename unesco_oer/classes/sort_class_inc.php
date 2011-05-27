<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



class sort extends object {



public function init()
   {

 $this->objLanguage = $this->getObject("language", "language");
        $this->objDbProducts = $this->getobject('dbproducts', 'unesco_oer');
        $this->objfilterlogic = $this->getobject('filterlogic', 'unesco_oer');
   
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











}

















?>
