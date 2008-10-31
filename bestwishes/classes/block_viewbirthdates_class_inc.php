<?php
/*
*@Author Emmanuel Natalis
*@University Computing Center
*@Dar es salaam university of Tanzania
*
*
*Happybithday block for viewing  users celebrating their birthdays today
*/
class block_viewbirthdates extends object
{
    public $objLanguage;
    public $blockContent;
    public $objDbbestwishes;
   function init()
   {
         /*
        *Initialising the language object
        */
        $this->objLanguage=$this->getObject('language','language');
           /*
           *
            *This is the title of the block
            *
            */
        $this->title=$this->objLanguage->languageText('mod_bestwishes_blocktitle','bestwishes');
     
        $this->objDbbestwishes=$this->getObject('dbbestwishes','bestwishes');
       
      }
      public function show()
      {
          /*
          *Returning the names of user names
          */
         return $this->objDbbestwishes->userFullname() ;
         }      
         
   }
?>