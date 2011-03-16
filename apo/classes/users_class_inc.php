<?php

/**
 * This class contains user management helper functions
 *  PHP version 5
 *
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
 *
 * @category  Chisimba
 * @package   apo (document management system)
 * @author    Nguni Phakela
 * @copyright 2010
 */
if (!
    /**
     * Description for $GLOBALS
     * @global string $GLOBALS['kewl_entry_point_run']
     * @name   $kewl_entry_point_run
     */
    $GLOBALS['kewl_entry_point_run']) {
        die("You cannot view this page directly");
}

class users extends object {

    // The Label for the Users
    private $userLabel;

    public function init() {
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('fieldset', 'htmlelements');
        $this->objUtils = $this->getObject('userutils');
        $this->userLabel = "User";
    }

    function showUserForm($name='') {
        $roles = array(
                    array('label'=>'subsidy', 'value' => 'Subsidy Office'),
                    array('label'=>'library', 'value'=>'Library'),
                    array('label'=>'facultyregistrar', 'value' =>'Faculty Registrar'));

        $form = new form('registeruser', $this->uri(array('action' => 'registeruser')));
        //$textinput = new textinput('facultyname');
        //$textinput->value = $name;
        $label = new label('Name of ' . $this->userLabel . ': ', 'input_username');

        // Opening date
        $table = $this->newObject('htmltable', 'htmlelements');

        $label = new label('Name of ' . $this->userLabel . ': ', 'input_username');
        $textinput = new textinput('name');
        $textinput->size = 40;
        $table->startRow();
        $table->addCell($label->show());
        $table->addCell($textinput->show());
        $table->endRow();

        
        $textinput = new dropdown('role');
        $textinput->addOption("", "Please select role...");
        $textinput->addFromDB($roles, 'value', 'label');
        $table->startRow();
        $table->addCell("<b>Role</b>");
        $table->addCell($textinput->show());
        $table->endRow();

        $textinput = new textinput('email');
        $textinput->size = 40;
        $table->startRow();
        $table->addCell("<b>Email</b>");
        $table->addCell($textinput->show());
        $table->endRow();

        $textinput = new textinput('telephone');
        $textinput->size = 40;
        $table->startRow();
        $table->addCell("<b>Telephone number</b>");
        $table->addCell($textinput->show());
        $table->endRow();

        $button = new button('create', 'Create ' . $this->userLabel);
        $button->setToSubmit();

        $form->addToForm($table->show());
        $form->addToForm('<br/>' . $button->show());

        $fs = new fieldset();
        $fs->setLegend($this->userLabel);
        $fs->addContent($form->show());

        return $fs->show();
    }

}