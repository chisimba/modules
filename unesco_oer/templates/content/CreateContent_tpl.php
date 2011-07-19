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
echo'<h1>This is just a filler</h1> ';
echo $content->getContentTree(TRUE);
echo '</div>';



echo '<div class=rightWideColumnDiv style="border: 1px #004e89 solid;">'. $content->showInput($this->getParam('prevAction')) . "</div>";

?>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" >
    $(document).ready(
        function()
        {
        $('select[name=new_dropdown]').change(
        function()
            {
                //$('.' + $('select[name=root_dropdown]').val()).slideToggle();
                switch ($(this).val())
                {
                    case 'none':
                        $('.root').html('');
                        break;
                    case 'new':
                        $('.root').show();
                        break;
                    default:
                        $('.root').load('index.php?module=unesco_oer&action=saveContent&option=new&path=' + $(this).val());
                        $('.root').show();
                        break;
                }
            }
        );

        $('select[name=edit_dropdown]').change(
        function()
            {
                switch ($(this).val()) {
                case 'none':
                    $('.root').html('');
                    break;
                default:
                    $('.root').load('index.php?module=unesco_oer&action=saveContent&option=edit&path=' + $(this).val());
                    $('.root').show();
                    break;
                }

            }
        );
        
        });

function edit(section_id){
    //$('.root').hide();
    $('.root').load('index.php?module=unesco_oer&action=saveContent&option=edit&path=' + section_id);
    //$('.root').slideToggle();
}

function newSection(path){
    $('.root').load('index.php?module=unesco_oer&action=saveContent&option=new&path=' + path);
}

</script>