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
        $this->objUser = $this->getObject("user", "security");


        $this->loadClass('link', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('checkbox', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('form', 'htmlelements');

        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
    }

    public function addBookmark($label, $description, $url, $parentid, $userid) {


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

    public function getBookmark($userid) {

        $sql = "select * from $this->_tableName where user_id = $userid";

        return $this->getArray($sql);
    }

    public function deleteBookmark($userids) {

        foreach ($userids as $userid) {

            $this->update("id", "$userid", $data = array('deleted' => 1));
        }
    }

    public function updateBookmark($label, $description, $bookmarkid, $time) {



        $this->update("id", "$bookmarkid", $data = array('label' => $label, 'description' => $description,));
    }

    public function populateListView($products) {





        $content = '    
                           <script src="http://code.jquery.com/jquery-latest.js"></script>
                            <script>
                           $(document).ready(function(){' . " $('#deletebookmark').click(function(){
                 if (confirm('Are you sure you want to delete')) {
    
                  document.forms['displaytext'].submit();
                  }

                  });"








        ;



        foreach ($products as $product) {
            if ($product['deleted'] == 0) {

                $divheading = '.' . $product['id'] . 'Div';
                $linkheading = '.' . $product['id'] . 'Link';
                $titleheading = '.' . $product['id'] . 'Title';

                $content.= "
                  $('$divheading').hide();

                  $('$linkheading').show();
                 


 

                  $('$linkheading').click(function(){

                  $('$divheading').slideToggle();
                   $('$titleheading ').slideToggle(); 

                  });
                
                
               ";
            }
        }

        $content .= '        


                                    });

                            </script>
                                        ';

        $display = new form("displaytext", $this->uri(array('action' => 'deleteBookmarks')));


        foreach ($products as $product) {

            if ($product['deleted'] == 0) {


                $divheading = $product['id'] . 'Div';
                $linkheading = $product['id'] . 'Link';
                $titleheading = $product['id'] . 'Title';

                $checkbox = new checkbox('selectedusers[]', $product['id']);
                $checkbox->value = $product['id'];
                $checkbox->cssId = 'user_' . $product['id'];

                $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $product['product_id'])));
                $abLink->cssClass = "listingLanguageLinkAndIcon";
                $abLink->link = $product['label'];



                $display->addToForm($checkbox);
                $display->addToForm($abLink);
                $display->addToForm("<br>");






                $bookmarkid = $product['id'];
                $textname = $product['id'] . "text";
                $commentboxname = $product['id'] . "comment";

                $textinput = new textinput($textname);
                $textinput->value = $product['label'];

                $commentText = new textarea($commentboxname);
                $commentText->setCssClass("commentTextBox");
                $commentText->value = $product['description'];


                //TODO make parameter pagename dynamic
                $uri = $this->uri(array('action' => 'createCommentSubmit', 'id' => $productID, 'pageName' => 'home'));

                $button = new button('submitComment', "Save Bookmark");
                $button->cssId = 'btn';
                $button->onclick = "  javascript:bookmarkupdate('$time','$textname','$commentboxname','$bookmarkid')  ";


                $form = new form('3a_comments_ui', $uri);
                $form->addToForm("Label * <br>");
                $form->addToForm($textinput);
                $form->addToForm("<br>Bookmark Description *<br> ");
                $form->addToForm($commentText);
                $form->addToForm("<br><br>");
                $form->addToForm($button->show()); //TODO use text link instead of button




                $display->addToForm("<br><br>
            <div class='productsListView'>
                   <h2>" . "</h2><br>
                <a href='javascript:void(0)'   class='$linkheading'>Edit</a> 
                   <div class='$divheading'> " . $form->show() . "

                                   
                          
                      </div>  
              
                
            </div>
                
                
                
                         
     ");
            }
        }
        $content .= $display->show();








        return $content;
    }

    public function populateGridView($products) {

        $content = "<script src='http://code.jquery.com/jquery-latest.js'></script>";

        foreach ($products as $product) {

            $divheading = '#' . $product['id'] . "div";

            $divid = $product['id'] . "div";
            $buttonheading = '#' . $product['id'] . "btn";
            $buttonid = $product['id'] . "btn";
             $cancelbtnheading = '#' . $product['id'] . "cancelbtn";
            $cancelbtnid = $product['id'] . "cancelbtn";


            $IDheading = '#' . $product['id'];
            $content .= "    
                            <script>
                           $(document).ready(function(){
                           
     

                  $('$IDheading').click(function(){

                  $('$divheading').slideToggle();
                  $('#filterDiv').slideToggle();
                   
                 

                  });
                
                $('$buttonheading').click(function(){

                  $('$divheading').slideToggle();
                  $('#filterDiv').slideToggle();
                   
                 

                  });
                
                  $('$cancelbtnheading').click(function(){

                  $('$divheading').slideToggle();
                  $('#filterDiv').slideToggle();
                   
                 

                  });
                          

                     });

                            </script>
            


";







            $parentid = $product['id'];
            $textname = $product['id'] . "text";
            $commentboxname = $product['id'] . "comment";
            $buttonname = $product['id'];
            $textinput = new textinput($textname);
            $textinput->value = $product['title'];

            $commentText = new textarea($commentboxname);
            $commentText->setCssClass("commentTextBox");

            //TODO make parameter pagename dynamic
            $uri = $this->uri(array('action' => 'createCommentSubmit', 'id' => $productID, 'pageName' => 'home'));

            $cancelbtn = new button('Cancel', "Cancel");
            $cancelbtn->cssId = $cancelbtnid;
            $button = new button('submitComment', "Save Bookmark");
            $button->cssId = $buttonid;


            $time = time();
            //  $userid = objdbuserextra->
            $userid = $this->objUser->userId();



            $location = $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];


            $button->onclick = "javascript:bookmarksave('$time','$parentid','$userid','$textname','$commentboxname') ;";








            //javascript:bookmarkupdate('$time','$parentid','$userid','$textname','$commentboxname'); 


            $form = new form('3a_comments_ui');
            $form->addToForm("Label * <br>");
            $form->addToForm($textinput);
            $form->addToForm("<br>Bookmark Description *<br> ");
            $form->addToForm($commentText);
            $form->addToForm("<br><br>");
            $form->addToForm($button->show());
            $form->addToForm($cancelbtn->show());
            
               $thumbnail = '<img src="' . $product['thumbnail'] . '" width="79" height="101">';
               $producttitle = $product['title'];

            $content .= "<div id='$divid' style= 'display:none'
                   
                     <div class='bookthumb' ". $thumbnail ." <br> ". $producttitle . "
    
                            </div>".
               
                $form->show() ." 
                                </div>      
            ";
        }

        return $content;
    }

}

?>
