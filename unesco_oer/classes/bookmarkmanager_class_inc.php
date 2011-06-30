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




class bookmarkmanager extends dbtable {
    
    
    public function init() {
           parent::init("tbl_unesco_oer_bookmarks");
             $this->objLanguage = $this->getObject("language", "language");
   

        $this->loadClass('link', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
 
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
    }


    public function addbookmark($label,$description,$url,$parentid,$userid){
        
        
       $data = array(
           'product_id' => $parentid,
           'user_id' => $userid,
          //   'location' => $url,
           'label' => $label,
             'description' => $description,
        //   'created_on' => $time
        );
       $this->insert($data);
        
        
        
   
        
        
        }
        
        
       public function getBookmark($userid){
             
             $sql = "select * from $this->_tableName where user_id = $userid";

        return $this->getArray($sql);
             
             
             
             
         }
         
        public function deleteBookmark($userid){
             
           

      $this->update("id","$userid[0]",$data = array( 'deleted'=> 1 ));
         
         
        }     
        
   

         public function populateListView($products) {
             
           
             
             
       
        $content = '    
                           <script src="http://code.jquery.com/jquery-latest.js"></script>
                            <script>
                           $(document).ready(function(){';
             
             
             
             foreach ($products as $product){
               $temp = str_replace (" ", "", $product['label']);
             $divheading = '.' . $temp . 'Div';
            $linkheading = '.' . $temp . 'Link';
            $titleheading = '.' . $temp . 'Title';

                $content.= "
                  $('$divheading').hide();

                  $('$linkheading').show();
                 


 

                  $('$linkheading').click(function(){

                  $('$divheading').slideToggle();
                   $('$titleheading ').slideToggle(); 

                  });
                
                
                 $('#deletebookmark').click(function(){

                  document.forms['displaytext'].submit();

                  });";
                
                
                
                                 
        
        }
        
        $content .= '        


                                    });

                            </script>
                                        ';
        
           $display =   new form("displaytext",$this->uri(array('action' => 'deleteBookmarks')));
        
              
         foreach ($products as $product) {
             
             
             $temp = str_replace (" ", "", $product['label']);
            $divheading = $temp. 'Div';
            $linkheading = $temp . 'Link';
            $titleheading = $temp . 'Title';
         
           $checkbox = new checkbox('selectedusers[]', $product['id']);
            $checkbox->value = $product['id'];
             $checkbox->cssId = 'user_' . $product['id'];
           
             $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $product['product_id'])));
             $abLink->cssClass = "listingLanguageLinkAndIcon";
             $abLink->link = $product['label'];
        
           
        
           $display->addToForm($checkbox);
           $display->addToForm($abLink);
           $display->addToForm("<br>");
            
           
             
        $editbutton = new button();
        $editbutton->cssClass = "listingLanguageLinkAndIcon";
       

        $parentid = $product['product_id'];
        $textinput = new textinput("bookmarktitle");
        $textinput->value = $product['label'];
                          
        $commentText = new textarea('newComment');
        $commentText->setCssClass("commentTextBox");
        

        //TODO make parameter pagename dynamic
        $uri = $this->uri(array('action' => 'createCommentSubmit', 'id' => $productID, 'pageName' => 'home'));
    
        $button = new button('submitComment', $linkText);
        $button->setToSubmit();

         
            $form = new form('3a_comments_ui', $uri);
            $form->addToForm("Label * <br>");
            $form->addToForm($textinput);
            $form->addToForm("<br>Bookmark Description *<br> ");
            $form->addToForm($commentText);
            $form->addToForm("<br><br>");
            $form->addToForm($button->show()); //TODO use text link instead of button

        
        
       
         $display->addToForm("<br><br>
            <div class='productsListView'>
                   <h2>"  . "</h2><br>
                <a href='javascript:void(0)'   class='$linkheading'>Edit</a> 
                   <div class='$divheading'> " . $form->show() ."

                                   
                          
                      </div>  
              
                
            </div>
                
                
                
                         
     ");   
        
         }
       $content .=   $display->show();
               
               
                   
                
        
        
        
         
        return $content;
    }

        
        
        
        
        
    }
    











?>
