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

/*This is a Edit User  UI
 *
 */


// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');

// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->str = "Unesco_OER GROUPS:";
echo $header->show();

$button = new button('Add Button', "Add Group");
$button->setToSubmit();
$addGroupLink =new link($this->uri(array('action' =>"groupRegistationForm")));
$addGroupLink->link = $button->show();
echo $addGroupLink->show();


$myTable = $this->newObject('htmltable', 'htmlelements');
$myTable->width = '60%';
$myTable->border = '2';
$myTable->cellspacing = '1';
$myTable->cellpadding = '10';

$myTable->startHeaderRow();
$myTable->addHeaderCell('Unesco_oer Group');
$myTable->addHeaderCell('Group E-mail');
$myTable->addHeaderCell('Edit');
$myTable->addHeaderCell('Delete');
$myTable->endHeaderRow();

$groups = $this->objDbGroups->getAllGroups();

if (count($groups) > 0) {
    foreach ($groups as $group) {
        $myTable->startRow();
        $myTable->addCell($group['name']);
        $myTable->addCell($group['email']);
    
        $objIcon->setIcon('edit');
        $editLink =new link($this->uri(array('action' => "",'id' =>$group['id'])));
        $editLink->link = $objIcon->show();
        $myTable->addCell($editLink->show());

        $objIcon->setIcon('delete');
        $deleteLink =new link($this->uri(array('action' => "deleteGroup",'id' => $group['id'])));
        $deleteLink->link = $objIcon;
        $href=$deleteLink->href;
        $finaldeleteLink='<a class="deleteuser" href="'.$href.'">Delete</a>';
        $myTable->addCell($finaldeleteLink);
       //$myTable->endRow();
    }
}
echo $myTable->show();

?>
<script type="text/javascript">

jQuery(document).ready(function(){

    jQuery("a[class=deleteuser]").click(function(){

        var r=confirm( "Are you sure you want to delete this user?");
        if(r== true){
            window.location=this.href;
        }
        return false;
    }


);

}


);
</script>