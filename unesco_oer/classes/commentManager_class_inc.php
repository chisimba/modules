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

class commentManager_class_inc extends object
{
    var $objDbComments;

    var $textArea;

    var $submitLink;

    public function  init() {
        parent::init();
        $this->objDbComments = $this->getObject('dbcomments');
        $this->textArea = $this->getObject('textarea','htmlelements');
        $this->submitLink = $this->getObject('link','htmlelements');
        $this->submitLink->href = 'none';
    }

    public function setCommentTextArea($name, $cssClass) {
        $this->textArea->name = $name;
        if ($cssClass != NULL) {
            $test->setCssClass($cssClass);
        }
    }

    public function setCommentLink($link, $action, $id, $cssClass, $user) {
        $this->submitLink->href = $this->uri(array('action' => $action, 'id' => $id, 'user' => $user));
        if ($cssClass != null){
            $this->submitLink->cssClass = $cssClass;
        }
        $this->submitLink->link = $link;
    }

    public function showCommentInput(){
        $output = '';
        $output .= $this->textArea->show();
        $output .= $this->submitLink->show();
        return $output;
    }

    public function  handleCommentUpload(){
        
    }
}
?>
