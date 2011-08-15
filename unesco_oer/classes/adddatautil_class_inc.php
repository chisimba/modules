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
$this->loadClass('htmltable', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');

class adddatautil extends object {

    public function init() {
        parent::init();
    }

    /*
     * This function adds a title to a given table
     */
    function addTitleToTable($title, $titleType, $table, $colspan=null) {
        $table->startRow();
        $this->addTitleToRow($title, $titleType, $table, $colspan);
        $table->endRow();
    }
    
    function addTitleToRow($title, $titleType, $table){
        $header = new htmlHeading();
        $header->str = $title;
        $header->type = $titleType;
        $table->addCell($header->show());
    }

    function addButtonToRow($caption, $actionURI, $table) {
        $button = new button(str_replace(' ', '', $caption), $caption);
        $button->setOnClick('javascript: window.location=\'' . $actionURI . '\'');
        $table->addCell('&nbsp;&nbsp;' . $button->show());
    }

    function addButtonToTable($title, $titleType, $caption, $actionURI, $table) {
        $table->startRow();
        $this->addTitleToRow($title, $titleType, $table);
        $this->addButtonToRow($caption, $actionURI, $table);
        $table->endRow();
    }

    function addTextInputToRow($name, $size, $value, $table){
        $textinput = new textinput($name);
        $textinput->size = $size;
        $textinput->setValue($value);
        $textinput->cssClass = 'required';
        $table->addCell($textinput->show());
    }

    function addTextInputToTable($title, $titleType, $name, $size, $value, $table) {
        $table->startRow();
        $this->addTitleToRow($title, $titleType, $table);
        $table->endRow();
        $table->startRow();
        $this->addTextInputToRow($name, $size, $value, $table);
        $table->endRow();
    }

    function addDropDownToTable($title, $titleType, $name, $inputArray, $initValue, $field, $table, $value = null, $onChange = '') {
        $table->startRow();
        $this->addTitleToRow($title, $titleType, $table);
        $table->endRow();
        $table->startRow();
        $dropdown = $this->addDropDownToRow($name, $inputArray, $initValue, $field, $table, $value, $onChange);
        $table->endRow();
        return $dropdown;
    }

    function addDropDownToRow($name, $inputArray, $initValue, $field, $table, $value = null, $onChange = ""){
        $dropdown = new dropdown($name);
        $dropdown->addOption(NULL, NULL);
        foreach ($inputArray as $input) {
            $dropdown->addOption($input[$value], $input[$field]);
        }
        
        if (strlen($initValue) > 0){
            $dropdown->setSelected($initValue);
        }else{
            $dropdown->setSelected(NULL);
        }

        $dropdown->addOnchange($onChange);

        $table->addCell($dropdown->show());
        return $dropdown;
    }

}

?>
