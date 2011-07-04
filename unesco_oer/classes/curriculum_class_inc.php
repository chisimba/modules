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



    public function showInput($prevAction = NULL) {
        $uri = $this->uri(array(
            'action' => "saveProductMetaData",
            'productID' => $this->_identifier,
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

        $fieldName = 'Foreward';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '150px';
        //$editor->width = '70%';
        $editor->setBasicToolBar();
        //$editor->setContent($this->getDescription());

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->showFCKEditor());
        $table->endRow();

        $fieldName = 'Background';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '150px';
        //$editor->width = '70%';
        $editor->setBasicToolBar();
        //$editor->setContent($this->getDescription());

        $table->startRow();
        //$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
        $table->addCell($fieldName);
        $table->endRow();

        $table->startRow();
        $table->addCell($editor->showFCKEditor());
        $table->endRow();

        $fieldName = 'Introductory Description';
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = $fieldName;
        $editor->height = '150px';
        //$editor->width = '70%';
        $editor->setBasicToolBar();
        //$editor->setContent($this->getDescription());

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

        $form_data->addToForm($table->show() . $buttonSubmit->show());

        return $form_data->show();
    }

    public function init() {
        $this->setType('curriculum');
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