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
        $linkText = $this->objLanguage->
                        languageText('mod_unesco_oer_submit', 'unesco_oer');
        $commentLink->link = $linkText;

        $commentSubmitDiv = '<div class="commentSubmit">';
        $submiTextDiv = '<div class="submiText">';
        $submitCommentImage = '<img src="skins/unesco_oer/images/button-search.png" alt="Submit" width="17" height="17" class="submitCommentImage">';
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


}
?>
