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
        $html = '';

        $html .= '  <h4 class="greyText fontBold labelSpacing">Foreward</h4>
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
                    </h4>';

        return $html;
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