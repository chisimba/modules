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

/* This is a Edit User  UI
 *
 */


// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$objIcon = $this->newObject('geticon', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');

// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->cssClass = "manageusers";
$header->str = "Users";
echo '<div id="userheading">';
echo $header->show();
echo '</div>';
$button = new button('Add Button', "Add User");
$button->setToSubmit();
$addUserLink = new link($this->uri(array('action' => "userRegistrationForm", 'id' => $user['id'])));
$addUserLink->link = $button->show();



$table = $this->newObject('htmltable', 'htmlelements');
$search = new textinput('state');
$search->size =10;
$table->startRow();
$table->addCell('Search');
$table->addCell($search->show());
$table->endRow();
//echo $table->show();


$controlPannel = new button('backButton', "Back");
$controlPannel->setToSubmit();
if(strcmp($this->getParam('mode'), 'groupuser') == 0){
    $action="groupListingForm";
}else{
    $action="controlpanel";
}
$BackToControlPannelLink = new link($this->uri(array('action' => $action)));
$BackToControlPannelLink->link = $controlPannel->show();

//button search user
$buttonGO = new button('searchButton', "Go");
//$buttonGO->setOnClick("javascript: searchThis()");
$buttonGO->show();
//text input search user
$search = new textinput('search','',"",20);


echo $addUserLink->show() .'&nbsp;'.$BackToControlPannelLink->show(). '&nbsp;'. $search->show(). '&nbsp;'.$buttonGO->show();

$myTable = $this->newObject('htmltable', 'htmlelements');
$myTable->width = '100%';
$myTable->border = '0';
$myTable->cellspacing = '0';
$myTable->cellpadding = '0';

$myTable->startHeaderRow();
//$str, $width=null, $valign="top", $align='left', $class=null, $attrib=Null)
$myTable->addHeaderCell('Title', null, null, left, "userheader", null);
$myTable->addHeaderCell('Username', null, null, left, "userheader", null);
$myTable->addHeaderCell('First name', null, null, left, "userheader", null);
$myTable->addHeaderCell('Email', null, null, left, "userheader", null);
$myTable->addHeaderCell('Edit', null, null, left, "userheader", null);
$myTable->addHeaderCell('Delete', null, null, left, "userheader", null);
$myTable->endHeaderRow();

 
//get user from the database
$users = "";
$mode = $this->getParam('mode');
if (strcmp($mode, 'addfixup') == 0) {
    $users = $user;
} else {
    if (strcmp($this->getParam('mode'), 'groupuser') == 0) {
        $groupname = $this->objDbGroups->getGroupName($this->getParam('id'));
        $userIds = $this->objUseExtra->getAlluserGroup($groupname);
        $users = array();
        for ($i = 0; $i < count($userIds); $i++) {
            $currUser = $this->objUseExtra->getUserbyId($userIds[$i]['id']);
            $users[] = $currUser[0];
            // array_push($users,$currUser[$i]);
//            print_r($users)        ;
///            die();
        }
    } else {
        $users = $this->objUseExtra->getAllUser();
//        print_r($users);
//        die();
    }
}


if (count($users) > 0) {
    foreach ($users as $user) {
        $myTable->startRow();
        //($str, $width=null, $valign="top", $align=null, $class=null, $attrib=Null,$border = '0')
        $myTable->addCell($user['title'], null, null, null, "user", null, null);
        $myTable->addCell($user['username'], null, null, null, "user", null, null);
        $myTable->addCell($user['firstname'], null, null, null, "user", null, null);
        $myTable->addCell($user['emailaddress'], null, null, null, "user", null, null);

        $objIcon->setIcon('edit');
        $editLink = new link($this->uri(array('action' => "editUserDetailsForm", 'id' => $user['id'], 'userid' => $user['userid'], 'username' => $user['username'])));
        $editLink->link = $objIcon->show();
        $myTable->addCell($editLink->show());

        $objIcon->setIcon('delete');
        $deleteLink = new link($this->uri(array('action' => "deleteUser", 'id' => $user['id'], 'userid' => $user['userid'])));
        $deleteLink->link = $objIcon->show();
        $deleteLink->cssClass = 'deleteuser';
        //$href=$deleteLink->href;
        //$finaldeleteLink='<a class="deleteuser" href="'.$href.'">Delete</a>';
        $myTable->addCell($deleteLink->show()); //$finaldeleteLink);
        //$myTable->addCell($finaldeleteLink);
    }
}
echo $nouserfound;
// some script language
if($nouserfound!=''){

}
$fs = new fieldset();
$fs->setLegend("Users");
$fs->addContent($myTable->show());
echo $fs->show();
?>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
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


$('button[name=searchButton]').click(
    function() {
        window.location = 'index.php?module=unesco_oer&action=searchUser&search='+ $('input[name=search]').val();
    }
);

//function searchThis(){
//	alert("You are searching for: "+ document.getElementById("input_search").value);
//        var oldAction = document.forms["addProductRating_ui"].action;
////        var newAction = oldAction.replace("rateSubmit=","rateSubmit=" + sel.id.replace("_", ''));
////        document.forms["addProductRating_ui"].action = newAction;
////        document.forms["addProductRating_ui"].submit();
//}
</script>