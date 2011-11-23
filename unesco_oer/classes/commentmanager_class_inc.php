<?php

/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/**
 * Description of commentManager_class_inc
 *
 * @author manie
 */
class commentmanager extends object {

  

    public function init() {

        $this->objLanguage = $this->getObject("language", "language");
          $this->objDbComments = $this->getobject('dbcomments', 'unesco_oer');

        $this->loadClass('link', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
 
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
          


    }






      
    public function commentbox($productID) {

        $commentText = new textarea('newComment');
        $commentText->setCssClass("commentTextBox");

        //TODO make parameter pagename dynamic
        $uri = $this->uri(array('action' => 'createCommentSubmit', 'id' => $productID, 'pageName' => 'ViewProduct'));
        $commentLink = new link($uri);
        $commentLink->cssClass = "searchGoLink";
        $linkText = $this->objLanguage->languageText('mod_unesco_oer_add_data_newcommentBtn', 'unesco_oer');
        $commentLink->link = $linkText;

        $commentSubmitDiv = '<div class="commentSubmit">';
        $submiTextDiv = '<div class="submiText">';
     
        $closeDiv = '</div>';

        $button = new button('submitComment', $linkText);
        $button->setToSubmit();

        $form = new form('3a_comments_ui', $uri);
        $form->addToForm($commentText->show());
        $form->addToForm($commentSubmitDiv);
        $form->addToForm($submiTextDiv);
        //$form->addToForm($commentLink->show());
        $form->addToForm($button->show()); //TODO use text link instead of button
        $form->addToForm($closeDiv);
        $form->addToForm($submitCommentImage);
        $form->addToForm($closeDiv);



        return $form->show();
    }


    public function recentcomment($productID){

      $comment = array();

       $totalcomments = $this->objDbComments->getTotalcomments($productID);
     
       $comment[3] = $last;
       
       if (($this->objDbComments->getTotalcomments($productID) >= 2)){
       
       $comments = $this->objDbComments->getComment($productID);
             $comment1 = $comments[$totalcomments-1]['product_comment'];
              $comment2 = $comments[$totalcomments-2]['product_comment'];



      } else if (($this->objDbComments->getTotalcomments($productID) == 1)){

          $comments = $this->objDbComments->getComment($productID);
             $comment1 = $comments[$totalcomments-1]['product_comment'];
              $comment2 = '';

           
       }

//       if (strlen($comment1) >8){
//
//           $comment[1] = substr($comment1,0,20)  . '...';
//       }
//           else $comment[1] = $comment1;
//
//
//  if (strlen($comment2) >8){
//
//           $comment[2] = substr($comment2,0,20)  . '...';
//       }
//           else $comment[2] = $comment2;
//
//
//
       $comment[1] = $comment1;
        $comment[2] = $comment2;

       return $comment;




    }
    
    
     public function populateListView($products) {

       
                            $myTable = $this->newObject('htmltable', 'htmlelements');
                            $myTable->width = '100%';
                            $myTable->border = '0';
                            $myTable->cellspacing = '0';
                            $myTable->cellpadding = '0';

                            $myTable->startHeaderRow();
                            //$str, $width=null, $valign="top", $align='left', $class=null, $attrib=Null)
                            $myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_search_title', 'unesco_oer'), null, null, left, "userheader", null);
                            $myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_institution_description', 'unesco_oer'), null, null, left, "userheader", null);       
                            $myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_edit', 'unesco_oer'), null, null, left, "userheader", null);
                            $myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_delete', 'unesco_oer'), null, null, left, "userheader", null);
                            $myTable->endHeaderRow();
//                            
                 



        $content = '    
                           <script src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script>
                            <script>
                           $(document).ready(function(){' . " $('#deletebookmark').click(function(){
                 if (confirm('Are you sure you want to delete')) {
    
                  document.forms['displaytexts'].submit();
                  }

                  });"


        ;

        

        foreach ($products as $product) {
            if ($product['deleted'] == 0) {

                $divheading = '.' . $product['id'] . 'Div';
                $linkheading = '#' . $product['id'] . 'Link';
                $titleheading = '#' . $product['id'] . 'Title';
                $btnheading = '#' . $product['id'] . 'btn';

                $content.= "
                  $('$divheading').hide();

                  $('$linkheading').show();
                 


 

                  $('$linkheading').click(function(){

                  $('$divheading').slideToggle();
                   $('$titleheading').slideToggle(); 

                  });
                
                
                  $('$btnheading').click(function(){

                  $('$divheading').slideToggle();
                   $('$titleheading').slideToggle(); 

                  });
                
                
                
               ";
            }
        }

        $content .= '        


                                    });

                            </script>
                                        ';

        $display = new form("displaytexts", $this->uri(array('action' => 'deleteBookmark')));
        
        


        foreach ($products as $product) {

            if ($product['deleted'] == 0) {


                $divheading = $product['id'] . 'Div';
                $linkheading = $product['id'] . 'Link';
                $titleheading = $product['id'] . 'Title';
                $btnheading = $product['id'] . 'btn';

                    $checkbox = new checkbox('selectedusers[]', $product['id']);
                    $checkbox->value = $product['id'] ;
                    $checkbox->cssId = 'user_' . $product['id'];

                $editLink = new link("javascript:void(0)");
                $editLink->cssId = $linkheading;
                $editLink->link =  $this->objLanguage->languageText('mod_unesco_oer_bookmark_edit', 'unesco_oer');
                
                $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $product['product_id'])));
                $abLink->cssClass = "listingLanguageLinkAndIcon";
                $abLink->link = $product['label'];


//
             //   $display->addToForm($checkbox);
//                $display->addToForm($abLink);
//                $display->addToForm("<br>");


                 $myTable->startRow();
                 $myTable->addCell($abLink->show(), null, null, null, "user", null, null);
                 $myTable->addCell($product['description'], null, null, null, "user", null, null);
                 $myTable->addCell($editLink->show(), null, null, null, "user", null, null);
                 $myTable->addCell($checkbox->show(), null, null, null, "user", null, null);
                 

                
            

                $bookmarkid = $product['id'];
                $productID = $product['product_id'];
                $textname = $product['id'] . "text";
                $commentboxname = $product['id'] . "comment";

                $textinput = new textinput($textname);
                $textinput->value = $product['label'];

                $commentText = new textarea($commentboxname);
                $commentText->setCssClass("commentTextBox");
                $commentText->value = $product['description'];


                //TODO make parameter pagename dynamic
                $uri = $this->uri(array('action' => 'createCommentSubmit', 'id' => $productID, 'pageName' => 'home'));

                $button = new button('submitComment', $this->objLanguage->languageText('mod_unesco_oer_bookmark_add', 'unesco_oer'));
                $button->cssId =  $btnheading;
                $button->onclick = "  javascript:bookmarkupdate('$time','$textname','$commentboxname','$bookmarkid','$productID')  ";
                


              $content2 ='';
              $content2 .= "Label * <br>";
                $content2 .=  $textinput->show();
               $content2 .=   "<br>Bookmark Description *<br> ";
               $content2 .=  $commentText->show();
            $content2 .=   "<br><br>";
             $content2 .= $button->show(); //TODO use text link instead of button




                $display->addToForm("
            <div>
                  
               
                   <div class='$divheading'> " . $content2. "

                                   
                          
                      </div>  
              
                
            </div>
                
                
                
                         
     ");
            }
        }
      //  $content .= $display->show();
        
        
        
        
        
        
    $display->addToForm(" <br><div id='$titleheading'>"); 
    $display->addToForm($myTable->show());
     $display->addToForm(" </div>")   ; 

     echo $display->show();




        return $content;
    }


}
?>