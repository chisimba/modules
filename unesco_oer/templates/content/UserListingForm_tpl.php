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
$header->str = "Users:";
echo $header->show();

$button = new button('Add Button', "Add User");
$button->setToSubmit();
$addUserLink =new link($this->uri(array('action' => "userRegistrationForm",'id' => $user['id'])));
$addUserLink->link = $button->show();
echo $addUserLink->show();

$myTable = $this->newObject('htmltable', 'htmlelements');
$myTable->width = '60%';
$myTable->border = '1';
$myTable->cellspacing = '1';
$myTable->cellpadding = '10';

$myTable->startHeaderRow();
$myTable->addHeaderCell('Unesco_oer Users');
$myTable->addHeaderCell('firstname');
$myTable->addHeaderCell('Title');
$myTable->addHeaderCell('Email');
$myTable->addHeaderCell('Edit');
$myTable->addHeaderCell('Delete');

$myTable->endHeaderRow();

$users = $this->objUseExtra->getAllUser();

if (count($users) > 0) {
    foreach ($users as $user) {
        $myTable->startRow();
        $myTable->addCell($user['username']);
        $myTable->addCell($user['firstname']);
        $myTable->addCell($user['title']);
        $myTable->addCell($user['emailaddress']);
        //$array=$user;

        $objIcon->setIcon('edit');
        $editLink =new link($this->uri(array('action' => "editUserDetailsForm",'id' => $user['id'],'userid'=>$user['userid'],'username'=>$user['username'])));
        $editLink->link = $objIcon->show();
        $myTable->addCell($editLink->show());

        $objIcon->setIcon('delete');
        $deleteLink =new link($this->uri(array('action' => "deleteUser",'id' => $user['id'],'userid'=>$user['userid'])));
        $deleteLink->link = $objIcon;
        $href=$deleteLink->href;
        $finaldeleteLink='<a class="deleteuser" href="'.$href.'">Delete</a>';
        $myTable->addCell($finaldeleteLink);
       


//
//        $button = new button('Edit Button', "Edit User");
//        $button->setToSubmit();
//        $editLink =new link($this->uri(array('action' => "editUserDetailsForm",'id' => $user['id'],'userid'=>$user['userid'],'username'=>$user['username'])));
//        $editLink->link = $button->show();
//        $myTable->addCell($editLink->show());
//        $button = new button('Delete', "delete User");
//        $button->setToSubmit();
//        $DeleteLink =new link($this->uri(array('action' => "deleteUser",'id' => $user['id'],'userid'=>$user['userid'])));
//        $href=$DeleteLink->href;
//        $finalDeleteLink='<a class="deleteuser" href="'.$href.'">Delete</a>';
//        $myTable->addCell($finalDeleteLink);
//        $myTable->endRow();
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