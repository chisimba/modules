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
 * Description of curriculum_class_inc
 *
 * @author manie
 */

require_once 'content_class_inc.php';

class curriculum extends content {

    private $objDbCurricula;

    private $_forward;
    private $_background;
    private $_introductory_description;


    public function showInput($prevAction = NULL) {

        $path = $option = '';
        if ($this->getID()) {
            $path = $this->getFullPath();
            $option = 'saveedit';
        }else{
            $path = $this->getPath().'__'.$this->getType();
            $option = 'save';
        }

        $uri = $this->uri(array(
            'action' => "saveContent",
            'path' => $path,
            'option' => $option,
            'nextAction' => $prevAction));
        $form_data = new form('add_products_ui', $uri);

//        $html .= '  <h4 class="greyText fontBold labelSpacing">Foreward</h4>
//                    <h4 class="greyText fontBold labelSpacing">
//                        <span class="wideDivider">
//                            <textarea name="textarea3" class="wideInputTextAreaField"></textarea>
//                        </span>
//                    </h4>
//                    <h4 class="greyText fontBold labelSpacing">Background</h4>
//                    <h4 class="greyText fontBold labelSpacing">
//                        <span class="wideDivider">
//                            <textarea name="textarea4" class="wideInputTextAreaField"></textarea>
//                        </span>
//                    </h4>
//                    <h4 class="greyText fontBold labelSpacing">Introductory Description</h4>
//                    <h4 class="greyText fontBold labelSpacing">
//                        <span class="wideDivider">
//                            <textarea name="textarea4" class="wideInputTextAreaField"></textarea>
//                        </span>
//                    </h4>';

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

        $fieldName = 'forward';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '150px';
        //$editor->width = '70%';
        $editor->setBasicToolBar();
        $editor->setContent($this->_forward);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->showFCKEditor());
        $table->endRow();

        $fieldName = 'background';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '150px';
        //$editor->width = '70%';
        $editor->setBasicToolBar();
        $editor->setContent($this->_background);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->showFCKEditor());
        $table->endRow();

        $fieldName = 'introductory_description';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '150px';
        //$editor->width = '70%';
        $editor->setBasicToolBar();
        $editor->setContent($this->_introductory_description);

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->showFCKEditor());
        $table->endRow();

        $buttonSubmit = new button('upload', 'upload');
        //$action = "";
        //$buttonSubmit->setOnClick('javascript: ' . $action);
        $buttonSubmit->setToSubmit();

        $form_data->addToForm($table->show() . $buttonSubmit->show() . '......' . $this->_path);

        return $form_data->show();
    }

    public function init() {
        $this->setType('curriculum');
        $this->objDbCurricula = $this->getObject('dbcurricula');
        $this->_content_types = array('calendar' => 'calendar');
    }

    public function handleUpload() {
        $this->_background = $this->getParam('background');
        $this->_forward = $this->getParam('forward');
        $this->_introductory_description = $this->getParam('introductory_description');
        $this->_title = $this->getParam('title');

        $pathArray = $this->getPathArray();

        if (empty($this->_id)) {
            $this->_id =  $this->objDbCurricula->addCurriculum(
                    $pathArray[0], // This is the ID of the product this curruculum is contained in.
                    $this->_title,
                    $this->_forward,
                    $this->_background,
                    $this->_introductory_description
                    );
        }else{
            $this->objDbCurricula->updateCurriculum(
                    $this->_id,
                    $pathArray[0], // This is the ID of the product this curruculum is contained in.
                    $this->_title,
                    $this->_forward,
                    $this->_background,
                    $this->_introductory_description
                    );
        }

        

        return TRUE;
    }

    public function loadContent($containerID = NULL) {
        $curriculaData = $this->objDbCurricula->getCurriculaByProductID($containerID);
        $curriculaArray = array();

        foreach ($curriculaData as $curriculumData){
            $tempCurriculum = $this->newObject('curriculum');
            $tempCurriculum->loadCurriculum($curriculumData);
            array_push($curriculaArray, $tempCurriculum);
        }

        return $curriculaArray;
    }

    public function loadCurriculum($id){
        $dataArray = NULL;
        if (is_array($id)){
            $dataArray = $id;
        }else{
            $dataArray = $this->objDbCurricula->getCurriculumByID($id);
        }

        $this->_background = $dataArray['background'];
        $this->_forward = $dataArray['forward'];
        $this->_introductory_description = $dataArray['introductory_description'];
        $this->_id = $dataArray['id'];
        $this->_title = $dataArray['title'];
        $this->_path = $dataArray['product_id'];

        //TODO add code for this curriculum's contents
    }

}
?>

<!--<h4 class="greyText fontBold labelSpacing">Foreward</h4>
<h4 class="greyText fontBold labelSpacing">
    <span class="wideDivider">
        <textarea name="textarea3" class="wideInputTextAreaField"></textarea>
    </span>
</h4>
<h4 class="greyText fontBold labelSpacing">Background</h4>
<h4 class="greyText fontBold labelSpacing">
    <span class="wideDivider">
        <textarea name="textarea4" class="wideInputTextAreaField"></textarea>
    </span>
</h4>
<h4 class="greyText fontBold labelSpacing">Introductory Description</h4>
<h4 class="greyText fontBold labelSpacing">
    <span class="wideDivider">
        <textarea name="textarea4" class="wideInputTextAreaField"></textarea>
    </span>
</h4>-->