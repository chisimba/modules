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
 * Description of helplink_class_inc
 *
 * @author manie
 */
class helplink extends object {

    public $customURI;

    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');

        $this->customURI = FALSE;
        $this->popup = TRUE;

        $this->objPop= $this->getObject('windowpop','htmlelements');
        $this->objPop->set('window_name','help');
        $this->objPop->set('width','1000');
        $this->objPop->set('height','750');
        $this->objPop->set('resizable','yes');
        $this->objPop->set('left','300');
        $this->objPop->set('top','300');
        $this->objPop->set('scrollbars', 'yes');

        $this->linkText = "<img src='skins/unesco_oer/images/icon-help.png' alt='help' width='15' height='15'>";
    }

    function show($helpTextCode = FALSE, $helpHead = FALSE, $helpTextModule = 'unesco_oer') {

        $output = '';


        if (($this->customURI === FALSE) && ($helpTextCode === FALSE)) {
            $helpTextCode = 'mod_unesco_oer_help_missing';
        }

        if ($this->customURI === FALSE) {
            $location=$this->uri(array(
                                    'action'=>'help',
                                    'helpcode'=>$helpTextCode,
                                    'helpmodule'=>$helpTextModule,
                                    'helphead'=>$helpHead)
                                ,'unesco_oer');
         } else {
             $location = $this->customURI;
         }
        
        
        if ($this->popup == TRUE) {            
            $this->objPop->set('location',$location);
            $this->objPop->set('linktext', $this->linkText);
            //        echo $this->objPop->putJs(); // you only need to do this once per page
            $output = $this->objPop->show();
        } else {
            $output = ''; //create hyper link instead of popup
        }

        return $output;
    }
    
}
?>