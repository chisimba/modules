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
   function nit()
   {
           /*
           *
            *This is the title of the block
            *Will be converted into language item soon
            */
        $this->title='Happy birthday module';
      }
      public function show()
      {
          /*
          *returning a string, will be converted into language item soon
          */
         return 'practise on blocks';
         }      
         
   }
?>