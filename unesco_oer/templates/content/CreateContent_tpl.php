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



echo '<div class=rightWideColumnDiv style="border: 1px #004e89 solid;">';
echo $contentManager->showInput($this->getParam('prevAction'));
echo "</div>";

?>
<html>
    <head>
 <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script> 
  <script type="text/javascript" src="packages/unesco_oer/resources/jquery.validate.js"></script>
<script  >
    $(document).ready(
        function()
        {
            
            $("#form_Form").validate();
            
        
     
        
        });

function edit(section_id){
    //$('.root').hide();
    $('.root').load('index.php?module=unesco_oer&action=saveContent&option=edit&pair=' + section_id + '&productID=' + $('.product_id').attr('id'));
    //$('.root').slideToggle();
}

function newSection(path){
    $('.root').load('index.php?module=unesco_oer&action=saveContent&option=new&pair=' + path + '&productID=' + $('.product_id').attr('id'));
}







</script>
    </head>
    
    </html>