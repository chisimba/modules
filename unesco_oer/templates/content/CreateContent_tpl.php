
<html>
    <head>
 <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script> 
  <script type="text/javascript" src="packages/unesco_oer/resources/jquery.validate.js"></script>
<script  >
   

function edit(section_id){
    //$('.root').hide();
    
    $('.root').load('index.php?module=unesco_oer&action=saveContent&option=edit&pair=' + section_id + '&productID=' + $('.product_id').attr('id'));
     $("#form_add").validate();
    //$('.root').slideToggle();
}

function newSection(path){
   
    $('.root').load('index.php?module=unesco_oer&action=saveContent&option=new&pair=' + path + '&productID=' + $('.product_id').attr('id'));
     $("#form_add").validate();
}


 $(window).load(
        function()
        {
            
            $("#form_add").validate();
        
       
        });




</script>
    </head>
    
    </html>


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

$this->setLayoutTemplate('maincontent_layout_tpl.php');
echo '<div class=leftColumnDiv style="border: 1px #004e89 solid;" >';

echo $contentManager->getContentTree(TRUE);
echo '</div>';
 $uri = $this->uri(array(
            'action' => "saveContent",
            'productID' => $productID,
            'pair' => $pair,
            'option' => $option,
            'nextAction' => $prevAction));
        $form_data = new form('add', $uri);
        
        
         $fieldName = 'title';
        $textinput = new textinput($fieldName);
        $textinput->name = "title";
        $textinput->cssClass = "required";
        $textinput->setValue($this->_title);
        
        $form_data->addToForm($textinput->show());
         $form_data->addToForm('<div class="form-row"><input class="submit" type="submit" value="Submit"></div> 
            ');
        
        
       echo $form_data->show();


echo '<div class=rightWideColumnDiv style="border: 1px #004e89 solid;">';
echo $contentManager->showInput($this->getParam('prevAction'));
echo "</div>";

?>
