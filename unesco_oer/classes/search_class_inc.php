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



   function Searchdisp(){

       $content ='';
       $content .='

       <div class="searchInputBoxDiv"> '.


$this->objfilterlogic->Search()





                                .'
                                </div>';


return $content;






   }

















}
















?>
