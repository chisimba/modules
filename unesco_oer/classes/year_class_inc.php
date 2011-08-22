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
 * Description of year_class_inc
 *
 * @author manie
 */
class year extends content {

    private $objDbYears;

    public function init() {
        $this->setType('year');
        $this->objDbYears = $this->newObject('dbyears','unesco_oer');
        $this->_content_types = array('module'=>'module');
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
        $table->cssClass = "greytexttable";

        $fieldName = 'year';
        $textinput = new textinput($fieldName);
         $textinput->cssClass = "required";
        $textinput->setValue($this->_title);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell(Year);
        $table->endRow();

        $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();

         $buttonSubmit = new button('upload', 'Save');
        $buttonSubmit->cssId = "upload";
        //$action = "";
        //$buttonSubmit->setOnClick('javascript: ' . $action);
        $buttonSubmit->setToSubmit();

        $form_data->addToForm($table->show() . $buttonSubmit->show());

        if (strcmp($option, 'saveedit') == 0){
            $buttonDelete = new button('btn_delete', 'Delete');
             $uri2 = $this->uri(array(
                'action' => "saveContent",
                'productID' => $productID,
                'pair' => $pair,
                'option' => 'delete',
                'nextAction' => $prevAction));
            $buttonDelete->setOnClick('javascript: window.location=\'' . $uri2 . '\'');
            $form_data->addToForm($buttonDelete->show());
        }

        return $form_data->show();
    }

    public function handleUpload() {
        $this->_title = $this->getParam('year');

        if (empty($this->_id)) {
            $this->saveNew();
        }else{
            $this->updateExisting();
        }

        return TRUE;
    }

    protected function saveNew() {
        $this->_id =  $this->objDbYears->addYear(
                    $this->_title, // This is the ID of the product this curruculum is contained in.
                    $this->getParentID()
                    );
    }

    protected function updateExisting() {
        $this->objDbYears->updateYear(
                    $this->_id,
                    $this->_title, // This is the ID of the product this curruculum is contained in.
                    $this->getParentID()
                    );
    }


    public function getContentsByParentID($parentID) {
        $yearsData = $this->objDbYears->getYearsByCalendarID($parentID);
        $yearsArray = array();
        foreach ($yearsData as $yearData){
            $tempYear = $this->newObject('year');
            $tempYear->load($yearData);
            array_push($yearsArray, $tempYear);
        }

        return $yearsArray;
    }

    public function load($id) {
        $dataArray = NULL;
        if (is_array($id)){
            $dataArray = $id;
        }else{
            $dataArray = $this->objDbYears->getYearByID($id);
        }


        $this->_id = $dataArray['id'];
        $this->_title = $dataArray['year'];
        $this->_parentID = $dataArray['calendar_id'];
        $this->_deleted = $dataArray['deleted'];

        //TODO add code for this years's contents
        $this->getContents();   
    }

    public function delete() {
        $success = $this->objDbYears->update('id',  $this->_id, array('deleted'=>'1'));
        $this->_deleted = $success ? '1' : $this->_deleted;
        return $success;
    }
}
?>
