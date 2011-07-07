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
$this->loadClass('fieldset','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');

// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->cssClass="manageusers";
$header->str = "Unesco_OER GROUPS:";
echo '<div id="userheading">';
echo $header->show();
echo '</div>';
$button = new button('Add Button', "Add Group");
$button->setToSubmit();
$addGroupLink =new link($this->uri(array('action' =>"groupRegistationForm")));
$addGroupLink->link = $button->show();
echo $addGroupLink->show();


$myTable = $this->newObject('htmltable', 'htmlelements');
$myTable->width = '100%';
$myTable->border = '0';
$myTable->cellspacing = '0';
$myTable->cellpadding = '0';

$myTable->startHeaderRow();
$myTable->addHeaderCell('Group name',null,null,left,"userheader",null);
$myTable->addHeaderCell('Group E-mail',null,null,left,"userheader",null);
$myTable->addHeaderCell('Edit',null,null,left,"userheader",null);
$myTable->addHeaderCell('Delete',null,null,left,"userheader",null);
$myTable->endHeaderRow();

$groups = $this->objDbGroups->getAllGroups();

if (count($groups) > 0) {
    foreach ($groups as $group) {
        $myTable->startRow();
        $myTable->addCell($group['name'],null, null, null, "user", null, null);
        $myTable->addCell($group['email'], null, null, null, "user", null, null);
    
        $objIcon->setIcon('edit');
        $editLink =new link($this->uri(array('action' =>"groupEditingForm",'id' =>$group['id'])));
        $editLink->link = $objIcon->show();
        $myTable->addCell($editLink->show());

        $objIcon->setIcon('delete');
        $deleteLink =new link($this->uri(array('action' => "deleteGroup",'id' => $group['id'])));
        $deleteLink->link = $objIcon->show();
        $deleteLink->cssClass = 'deleteuser';
        //$href=$deleteLink->href;
        //$finaldeleteLink='<a class="deleteuser" href="'.$href.'">Delete</a>';
        $myTable->addCell($deleteLink->show());
//        $finaldeleteLink='<a class="deleteuser" href="'.$href.'">Delete</a>';
//        $myTable->addCell($finaldeleteLink);
       //$myTable->endRow();
    }
}

$fs = new fieldset();
$fs->setLegend("Groups");
$fs->addContent($myTable->show());
echo $fs->show();

?>
<script type="text/javascript">

jQuery(document).ready(function(){

    jQuery("a[class=deleteuser]").click(function(){

        var r=confirm( "Are you sure you want to delete this group?");
        if(r== true){
            window.location=this.href;
        }
        return false;
    }


);

}


);
</script>