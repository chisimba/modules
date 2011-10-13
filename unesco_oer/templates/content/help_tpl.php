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

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('windowpop', 'htmlelements');

$header = new htmlHeading();
//$header->str = $this->objLanguage->
//        languageText('mod_unesco_oer_add_data_heading', 'unesco_oer');
$header->str = 'Site Reference';
$header->align = 'CENTER';
$header->type = 2;
echo $header->show();


            //Popup window
            $objPop= &new windowpop; echo 'me';
            $uri =  $this->uri(array('action'=>'home'));
            $objPop->set('location',$uri);

            $toolTip = "<span>toolTip</span>";
            $image = "<img src='skins/unesco_oer/images/icon-help.png' alt='help' width='15' height='15'>";

            $objPop->set('linktext'," <div class='tooltip' >$image $toolTip</div>");
            $objPop->set('width','800');
            $objPop->set('height','600');
            $objPop->set('left','300');
            $objPop->set('top','400');
            //leave the rest at default values
            $objPop->putJs(); // you only need to do this once per page
            echo $objPop->show();

?>