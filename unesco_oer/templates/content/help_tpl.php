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

?>
<div class="productsListView">
    <?php
    $this->loadClass('htmlheading', 'htmlelements');
    $header = new htmlHeading();
    $header->str = $this->getParam('helphead');
//    $header->align = 'CENTER';
    $header->type = 3;
    echo $header->show();

    echo "<p>".$this->objLanguage->languageText($this->getParam('helpcode'),$this->getParam('helpmodule'))."</p>";

    //            $objJquery = $this->getObject('jquery','jquery');
    //            $objJquery->loadCluetipPlugin();
    ?>
</div>
<!--<div class="ttip" title="Hello Worlds">TOOLTIP JS TEST</div>
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function(){
        jQuery(".ttip").cluetip();
    });
//
//    var $ = jQuery.noConflict();
//    $(function){
//
//    }

</script>-->