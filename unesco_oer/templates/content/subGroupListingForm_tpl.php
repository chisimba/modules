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
$parent_id=$this->getParam('parent_id');
$parentInfo=$this->objDbGroups->getGroupInfo($parent_id);



// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->cssClass = "manageusers";
$header->str =$parentInfo[0]['name']." ".$this->objLanguage->languageText('mod_unesco_oer_sub_group_create', 'unesco_oer');

?>
<div style="clear:both;"></div>
<div class="breadCrumb module">
    <div id='breadcrumb'>
        <ul><li class="first">Home</li>
            <li><a href='?module=unesco_oer&action=10&page=10a_tpl.php' alt='Adminstrative Tools' title='Adminstrative Tools'>Groups</a></li>
            <li><a href='?module=unesco_oer&action=11a&id=<?php echo $this->getParam("parent_id")?>&page=10a_tpl.php' alt='Adminstrative Tools' title='Adminstrative Tools'><?php echo $parentInfo[0]['name']." "."Home"?></a></li>
            <li><a href='?module=unesco_oer&action=8a&id=<?php echo $this->getParam("parent_id")?>&page=10a_tpl.php' alt='Adminstrative Tools' title='Adminstrative Tools'><?php echo $parentInfo[0]['name']; ?></a></li>
            <li>Subgroup</li>
            </ul>
        </div>
    </div>

<?php
echo '<div id="userheading">';
echo $header->show();



//
//$button = new button('Add Button', "Add User");
//$button->setToSubmit();
//$addUserLink = new link($this->uri(array('action' => "userRegistrationForm", 'id' => $user['id'])));
//$addUserLink->link = $button->show();




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


//echo $addUserLink->show() .'&nbsp;'.$BackToControlPannelLink->show(). '&nbsp;'. $search->show(). '&nbsp;'.$buttonGO->show();
echo '</div>';
$myTable = $this->newObject('htmltable', 'htmlelements');
$myTable->width = '100%';
$myTable->border = '0';
$myTable->cellspacing = '0';
$myTable->cellpadding = '0';

$myTable->startHeaderRow();
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_sub_group_name', 'unesco_oer'), null, null, left, "userheader", null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_sub_group_description', 'unesco_oer'), null, null, left, "userheader", null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_website', 'unesco_oer'), null, null, left, "userheader", null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_interest', 'unesco_oer'), null, null, left, "userheader", null);
$myTable->endHeaderRow();

//get user from the database
$users ;
if (strcmp($mode, 'addfixup') == 0) {
      $users =$user;
    //echo 'deals';

} else {
    $users = $this->objUseExtra->getAllUser();


}

$this->objUseExtra->editCheck($this->objUser->PKID(),$this->objUser->userId());

$subgroups=$this->objDbGroups->getGroupSubgroup($parent_id);
if (count($subgroups) > 0) {
    foreach ($subgroups as $subgroup) {
        $myTable->startRow();
        $myTable->addCell($subgroup['name'], null, null, null, "user", null, null);
        $myTable->addCell($subgroup['description'], null, null, null, "user", null, null);
        $myTable->addCell($subgroup['website'], null, null, null, "user", null, null);
        $myTable->addCell($subgroup['interests'], null, null, null, "user", null, null);
//
//        $objIcon->setIcon('edit');
//
//        $editLink = new link($this->uri(array('action' => "editUserDetailsForm", 'id' => $user['id'], 'userid' => $user['userid'], 'username' => $user['username'])));
//        $editLink->link = $objIcon->show();
//        $myTable->addCell($editLink->show());
//
//        $objIcon->setIcon('delete');
//        $deleteLink = new link($this->uri(array('action' => "deleteUser", 'id' => $user['id'], 'userid' => $user['userid'])));
//        $deleteLink->link = $objIcon->show();
//        $deleteLink->cssClass = 'deleteuser';
//        //$href=$deleteLink->href;
//        //$finaldeleteLink='<a class="deleteuser" href="'.$href.'">Delete</a>';
//        $myTable->addCell($deleteLink->show()); //$finaldeleteLink);
//        //$myTable->addCell($finaldeleteLink);
    }
}

$fs = new fieldset();
$fs->setLegend($this->objLanguage->languageText('mod_unesco_oer_sub_group_create', 'unesco_oer'));
$fs->addContent($myTable->show());
echo $fs->show();
?>
<script type="text/javascript" src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script>
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