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
           'id' => $parentid,
           'user_id' => $userid,
          //   'location' => $url,
           'label' => $label,
             'description' => $description,
        //   'created_on' => $time
        );
       $this->insert($data);
        
        
        
   
        
        
        }
        
        
     
        
//         public function populateListView($products) {
//             
//             
//             
//       
//        $content = '    
//                           <script src="http://code.jquery.com/jquery-latest.js"></script>
//                            <script>
//                           $(document).ready(function(){';
//             
//             
//             
//             foreach ($products as $product){
//                           
//                $divheading = '.'. $product['title'] . 'Div';
//                $linkheading = '.' . $product['title']. 'Link';
//                $titleheading = '.' . $product['title']. 'Title';
//
//                $content.= "
//                  $('$divheading').hide();
//
//                  $('$linkheading').show();
//                 
//
//
// 
//
//                  $('$linkheading').click(function(){
//
//                  $('$divheading').slideToggle();
//                   $('$titleheading ').slideToggle(); 
//
//                  });";
//
//   
//        
//                                           
//                                           
//        
//        }
//        
//        $content .= '        
//
//
//                                    });
//
//                            </script>
//                                        ';
//        
//        
//        
//              
//         foreach ($products as $product) {
//             
//           $divheading =  $product['title'] . 'Div';
//           $linkheading =  $product['title']. 'Link';    
//           $titleheading =  $product['title']. 'Title';
//         
//             $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $product['id'])));
//             $abLink->cssClass = "listingLanguageLinkAndIcon";
//             $abLink->link = $product['title'];
//        
//           $editbutton = new button();
//        $editbutton->cssClass = "listingLanguageLinkAndIcon";
//       
//
//        $parentid = $product['id'];
//        
//        $textinput = new textinput("bookmarktitle");
//        $textinput->value = $product['title'];
//                          
//        $commentText = new textarea('newComment');
//        $commentText->setCssClass("commentTextBox");
//
//        //TODO make parameter pagename dynamic
//        $uri = $this->uri(array('action' => 'createCommentSubmit', 'id' => $productID, 'pageName' => 'home'));
//    
//        $button = new button('submitComment', $linkText);
//        $button->setToSubmit();
//
//        $form = new form('3a_comments_ui', $uri);
//           $form->addToForm($textinput);
//           $form->addToForm("<br><br>");
//        $form->addToForm($commentText);
//           $form->addToForm("<br><br>");
// 
//        $form->addToForm($button->show()); //TODO use text link instead of button
//
//        
//        
//        $content.="
//        
//            <div class=' $titleheading'>"
//                  .  $abLink->show() . "             
//                      
//            </div>
// <a href='javascript:void(0)'   class='$linkheading'>Edit</a>
//              
//                
//                        
//
//                            <div class='$divheading'> " . $form->show() ."
//
//     
//
//
//                                    
//
//
//        
//                                        
//                                           
//                                           
//                      </div>  
//                  
//                  
//                    
//                 ";
//
//        
//
//        $content .= ' 
//                   </div>
//                   <br>
//                   
//                
//        ';
//        
//        
//         }
//        return $content;
//    }
//
//        
        
        
        
        
    }
    











?>
