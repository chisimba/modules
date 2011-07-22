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
 * Description of module_class_inc
 *
 * @author manie
 */
class module extends content {
    private $objDbModules;

    private $_metaDataArray;

    public function init() {
        $this->setType('module');
        $this->objDbModules = $this->newObject('dbmodules','unesco_oer');
        $this->_content_types = NULL;
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
        $table->addCell('Section Title');
        $table->endRow();

        $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();

        $fieldName = 'audience';
        $textinput = new textinput($fieldName);
        $textinput->setValue($this->_metaDataArray['audience']);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell('Level');
        $table->endRow();

        $table->startRow();
        $table->addCell($textinput->show());
        $table->endRow();

         $buttonSubmit = new button('upload', 'upload');
        //$action = "";
        //$buttonSubmit->setOnClick('javascript: ' . $action);
        $buttonSubmit->setToSubmit();

        $form_data->addToForm($table->show() . $buttonSubmit->show() . '......' . $this->getParentID());

        return $form_data->show();
    }

    public function handleUpload() {
        $this->_title = $this->getParam('title');

        $data = array(
                    'title' => $this->_title,
                    'audience' => $this->getParam('audience'),
                    'year_id' => $this->getParentID()
            );

        if (empty($this->_id)) {
            $this->_id =  $this->objDbModules->addModule($data);
        }else{
            $this->objDbModules->updateModule($this->_id, $data);
        }

        return TRUE;
    }

    public function getContentsByParentID($parentID) {
        $modulesData = $this->objDbModules->getModulesByYearID($parentID);
        $modulesArray = array();
        foreach ($modulesData as $moduleData){
            $tempModule = $this->newObject('module');
            $tempModule->load($moduleData);
            array_push($modulesArray, $tempModule);
        }

        return $modulesArray;
    }

    public function load($id) {
        $dataArray = NULL;
        if (is_array($id)){
            $dataArray = $id;
        }else{
            $dataArray = $this->objDbModules->getModuleByID($id);
        }


        $this->_id = $dataArray['id'];
        $this->_title = $dataArray['title'];
        $this->_parentID = $dataArray['year_id'];

        $this->_metaDataArray = $dataArray;

        //TODO add code for this modules's contents
        //$this->getContents();
    }
}
?>
