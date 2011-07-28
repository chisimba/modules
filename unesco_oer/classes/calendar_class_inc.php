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



require_once 'content_class_inc.php';

/**
 * Description of calendar_class_inc
 *
 * @author manie
 */
class calendar extends content
{

    private $objDbCalendar;

    public function init() {
        $this->setType('calendar');
        $this->objDbCalendar = $this->getObject('dbcalendar');
        $this->_content_types = array('year' => 'year');
    }

    public function showInput($productID, $prevAction = NULL) {
        $pair = $option = '';
        if ($this->getID()) {
            $pair = $this->getPairString();
            $option = 'saveedit';
        }else{
            $pair = $this->getParentID().'__'.$this->getType();
            $option = 'save';
        }

        $uri = $this->uri(array(
            'action' => "saveContent",
            'productID' => $productID,
            'pair' => $pair,
            'option' => $option,
            'nextAction' => $prevAction));
        $form_data = new form('add_products_ui', $uri);

        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";

        $fieldName = 'title';
        $textinput = new textinput($fieldName);
        $textinput->setValue($this->_title);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();

         $buttonSubmit = new button('upload', 'upload');
        //$action = "";
        //$buttonSubmit->setOnClick('javascript: ' . $action);
        $buttonSubmit->setToSubmit();

        $form_data->addToForm($table->show() . $buttonSubmit->show());

        return $form_data->show();
    }

    public function  handleUpload() {
        $this->_title = $this->getParam('title');

        if (empty($this->_id)) {
            $this->_id =  $this->objDbCalendar->addCalendar(
                    $this->_title, // This is the ID of the product this curruculum is contained in.
                    $this->getParentID()
                    );
        }else{
            $this->objDbCalendar->updateCalendar(
                    $this->_id,
                    $this->_title, // This is the ID of the product this curruculum is contained in.
                    $this->getParentID()
                    );
        }

        return TRUE;
    }

    public function  getContentsByParentID($parentID) {
        $calendarsData = $this->objDbCalendar->getCalendarsByCurriculumID($parentID);
        $calendarsArray = array();
        foreach ($calendarsData as $calendarData){
            $tempCalendar = $this->newObject('calendar');
            $tempCalendar->load($calendarData);
            array_push($calendarsArray, $tempCalendar);
        }

        return $calendarsArray;
    }

    public function load($id){
        $dataArray = NULL;
        if (is_array($id)){
            $dataArray = $id;
        }else{
            $dataArray = $this->objDbCalendar->getCurriculumByID($id);
        }


        $this->_id = $dataArray['id'];
        $this->_title = $dataArray['title'];
        $this->_parentID = $dataArray['curriculum_id'];

        //TODO add code for this calendar's contents
        $this->getContents();
    }
}
?>