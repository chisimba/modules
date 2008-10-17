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
    public $ObjLanguage;
    public $blockContent;
   function nit()
   {
         /*
        *Initialising the language object
        */
        $this->ObjLanguage=$this->getObject('language','language');
           /*
           *
            *This is the title of the block
            *
            */
        $this->title=$this->ObjLanguage->languageText('mod_happybirthday_blocktitle','happybirthday');
        $this->blockContent=$this->ObjLanguage->languageText('mod_happybirthday_blockcontent','happybirthday');
       
      }
      public function show()
      {
          /*
          *Returning the object content string
          */
         return "A test text".$this->blockContent ;
         }      
         
   }
?>