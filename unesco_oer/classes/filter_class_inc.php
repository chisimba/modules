<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


        

class filtermanager extends object {





   public function init()
   {
     
   
  $this->objLanguage = $this->getObject("language", "language");
    echo "test";
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
  $this->loadClass('form', 'htmlelements');

}






    function ThemeFilter($form, $objDbProducts)



    {
      


       
        $products = $objDbProducts->getProducts(0, 10);
                        $filterTheme = new dropdown('ThemeFilter');
                         $filterTheme->cssClass = "leftColumnSelectDropdown";
                      //  $all = $this->objLanguage->languageText('mod_unesco_oer_filter_All', 'unesco_oer');
                        $filterTheme->addoption($all);
                        foreach ($products as $product) {

                            $filterTheme->addOption($product['theme']);
                        }
                        $filterTheme->setSelected($ThemeFilter);

                       


             
                        $filterTheme->addOnChange('javascript: sendThemeFilterform()');
                     $form->addtoform($filterTheme->show());

                          echo $form->show();
                     







    }







public function FilterTotalProducts($AuthFilter, $ThemeFilter, $LangFilter, $page, $sort, $TotalPages, $adaptationstring, $Model, $Handbook, $Guide, $Manual, $Besoractile,$browsemapstring)
    {

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
