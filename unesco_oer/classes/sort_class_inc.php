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



public function SortDisp()

{
$content = '';
$content .= '
      <div class="sortBy">
'.

$this->objfilterlogic->Sort()

.'  </div>';



return $content;




}











}

















?>
