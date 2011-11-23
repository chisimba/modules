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
          $this->objGroups = $this->getObject('groupadminmodel', 'groupadmin');
               $this->objGroupAdminModel = $this->getObject("groupadminmodel", "groupadmin");


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
        
        
             $objIndexData = $this->getObject('indexdata', 'search');

            // Prep Data
            $docId = 'unesco_oer_bookmarks_'.$parentid;
    
            $url = $this->uri(array('action'=>'ViewProduct', 'id'=>$parentid), 'unesco_oer');
            $title = stripslashes($label);

            // Remember to add all info you want to be indexed to this field
            $contents = stripslashes($description);

            // A short overview that gets returned with the search results
            $objTrim = $this->getObject('trimstr', 'strings');
            $teaser = $objTrim->strTrim(strip_tags(stripslashes($description)), 300);

            $module = 'unesco_oer';

         

            $userId = $this->objUser->userId();

//            if (is_array($tags)) $tags = 'array';
//            else $tags = 'noarray';

            // Add to Index
            $objIndexData->luceneIndex($docId, $docDate, $url, $title, $contents,
            $teaser, $module, $userId, $tags, NULL, NULL, NULL, NULL, NULL, NULL,NULL);
        
        
        
 
        
        
    }

    public function getBookmark($userid) {

        $sql = "select * from $this->_tableName where user_id = $userid";

        return $this->getArray($sql);
    }
    
     public function getBookmarkbyID($prodid,$userid) {

        $sql = "select * from $this->_tableName where product_id = '$prodid' and user_id = '$userid'";

        return $this->getArray($sql);
    }
    
     public function getBookmarkbyorigionalID($prodid,$userid) {

        $sql = "select * from $this->_tableName where id = '$prodid' and user_id = '$userid'";

        return $this->getArray($sql);
    }

    public function deleteBookmark($ids,$userid) {
        
          
             $objIndexData = $this->getObject('indexdata', 'search');
  
            // Prep Data
           
        
        

        foreach ($ids as $id) {
            $product = $this->getBookmarkbyorigionalID($id, $userid);
            $docId = 'unesco_oer_bookmarks_'.$product[0]["product_id"];
            $objIndexData->removeIndex($docId);
            $this->update("id", "$id", $data = array('deleted' => 1));
        }
    }

    public function updateBookmark($label, $description, $bookmarkid, $time,$productID) {
        
        
        
        



        $this->update("id", "$bookmarkid", $data = array('label' => $label, 'description' => $description, 'deleted' => 0));
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

    public function populateGridView($products) {

        $content = "<script src='packages/unesco_oer/resources/js/jquery-1.6.2.min.js'></script>";

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

                            </script>";


            $parentid = $product['id'];
            $textname = $product['id'] . "text";
            $commentboxname = $product['id'] . "comment";
            $buttonname = $product['id'];
            $textinput = new textinput($textname);
          

            $commentText = new textarea($commentboxname);
            $commentText->setCssClass("commentTextBox");
            

            //TODO make parameter pagename dynamic
            $uri = $this->uri(array('action' => 'createCommentSubmit', 'id' => $productID, 'pageName' => 'home'));

            $cancelbtn = new button('Cancel', $this->objLanguage->languageText('mod_unesco_oer_bookmark_cancel', 'unesco_oer'));
            $cancelbtn->cssId = $cancelbtnid;
            $button = new button('submitComment', $this->objLanguage->languageText('mod_unesco_oer_bookmark_add', 'unesco_oer'));
            $button->cssId = $buttonid;


            $time = time();
            //  $userid = objdbuserextra->
            $userid = $this->objUser->userId();
              


            $location = $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $bookmarks = $this->getBookmarkbyID($product['id'],$userid);
            $bookmarkid = $bookmarks[0]['id'];
            
            if ($bookmarks[0]['product_id'] != $parentid){
               $button->onclick = "javascript:bookmarksave('$time','$parentid','$userid','$textname','$commentboxname') ;";
               $textinput->value = $product['title'];
            } else {
                
                 $button->onclick = "  javascript:bookmarkupdate('$time','$textname','$commentboxname','$bookmarkid')  ";
                   $textinput->value = $bookmarks[0]['label'];
                   $commentText->value = $bookmarks[0]['description'];
                
                
            }

 

            //javascript:bookmarkupdate('$time','$parentid','$userid','$textname','$commentboxname'); 

               $thumbnail = '<img src="' . $product['thumbnail'] . '" width="79" height="101" >';
               $producttitle = $product['title'];

            $content .= "<div id='$divid' style= 'display:none'>
                   
                     <div class='bookthumb'> ". $thumbnail ."<br/> ". $producttitle . "
    
                            </div>";
            
            

            $form = new form('3a_comments_ui');
            $form->addToForm("Label * <br>");
            $form->addToForm($textinput);
            $form->addToForm("<br>Bookmark Description *<br> ");
            $form->addToForm($commentText);
            $form->addToForm("<br><br>");
            
              if ($this->hasMemberPermissions()){
                       $form->addToForm($button->show());
                       $form->addToForm($cancelbtn->show());
              }
            
        
              $content .=   $form->show() ." 
                                </div>      
            ";
        }
        

        return $content;
    }
    
    
      function hasMemberPermissions() {
        $userId = $this->objUser->userid();
        $groupId = $this->objGroups->getId('Members');
        return $this->objGroupAdminModel->isGroupMember($userId, $groupId) || $this->objUser->isAdmin() || $this->hasEditorPermissions();
    } 
    
     function hasEditorPermissions() {
        $userId = $this->objUser->userid();
        $groupId = $this->objGroups->getId('Editors');
       
        return $this->objGroupAdminModel->isGroupMember($userId, $groupId)  || $this->objUser->isAdmin();
    }

}

?>