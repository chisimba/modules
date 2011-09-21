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
$this->loadClass('textinput', 'htmlelements');

// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->cssClass="manageusers";
$header->str = "Unesco_OER GROUPS:";
echo '<div id="userheading">';
echo $header->show();




$button = new button('Add Button', $this->objLanguage->languageText('mod_unesco_oer_group_heading', 'unesco_oer'));
$button->setToSubmit();
$addGroupLink =new link($this->uri(array('action' =>"groupRegistationForm")));
$addGroupLink->link = $button->show();
//echo $addGroupLink->show();

$controlPannel = new button('backButton', $this->objLanguage->languageText('mod_unesco_oer_group_back_button', 'unesco_oer'));
$controlPannel->setToSubmit();
$BackToControlPannelLink = new link($this->uri(array('action' => "controlpanel")));
$BackToControlPannelLink->link = $controlPannel->show();

//button search user
$buttonGO = new button('searchButton',$this->objLanguage->languageText('mod_unesco_oer_group_go_button', 'unesco_oer') );
//$buttonGO->setOnClick("javascript: searchThis()");
$buttonGO->show();
//text input search user
$search = new textinput('search','',"",20);


echo $addGroupLink->show() .'&nbsp;'. '&nbsp;'. $search->show(). '&nbsp;'.$buttonGO->show();
echo '</div>';

$myTable = $this->newObject('htmltable', 'htmlelements');
$myTable->width = '100%';
$myTable->border = '0';
$myTable->cellspacing = '0';
$myTable->cellpadding = '0';

$myTable->startHeaderRow();
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_name', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_email', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_number_of_group_user', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_join', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_leave', 'unesco_oer'),null,null,left,"userheader",null);
//$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_legend_oerprosucts', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->endHeaderRow();

$groups = $this->objDbGroups->getAllGroups();


if (count($groups) > 0) {
    foreach ($groups as $group) {
        $myTable->startRow();
        $myTable->addCell($group['name'],null, null, null, "user", null, null);
        $myTable->addCell($group['email'], null, null, null, "user", null, null);
        $myTable->addCell($this->ObjDbUserGroups->groupMembers($group['id']), null, null, null, "user", null, null);
        $objIcon->setIcon('add');
       // echo $this->objUseExtra->getUserbyUserIdbyUserID($this->objUser->userId());
//echo $this->ObjDbUserGroups->ismemberOfgroup($this->objUseExtra->getUserbyUserIdbyUserID($this->objUser->userId()),$group['id']); die();
        if($this->ObjDbUserGroups->ismemberOfgroup($this->objUseExtra->getUserbyUserIdbyUserID($this->objUser->userId()),$group['id'])){
            $joinLink = new link($this->uri(array('action' =>"groupListingFormMain")));
            $joinLink->link= $objIcon->show();
            $joinLink->cssClass ='memberofgroup';
            $myTable->addCell($joinLink->show());

        }else{

            $joinLink = new link($this->uri(array('action' =>"joingroup", 'id' => $group['id'])));
            $joinLink->link = $objIcon->show();
            $joinLink->cssClass = 'deleteuser';
            $myTable->addCell($joinLink->show());

            }

        $objIcon->setIcon('leave');
        //$currLoggedInID=$this->objUseExtra->getUserbyUserIdbyUserID($this->objUser->userId());
        //$this->ObjDbUserGroups->joingroup($currLoggedInID,$group['id']);
        $deleteLink = new link($this->uri(array('action' =>"", 'id' => $group['id'])));
        $deleteLink->link = $objIcon->show();
        $deleteLink->cssClass = 'leavegroup';
        $myTable->addCell($deleteLink->show());



    }
}

echo $nogroupfound; // this must be a script

$fs = new fieldset();
$fs->setLegend($this->objLanguage->languageText('mod_unesco_oer_group_users', 'unesco_oer'));
$fs->addContent($myTable->show());
echo $fs->show();

?>
<script type="text/javascript" src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript">

jQuery(document).ready(function(){

    jQuery("a[class=deleteuser]").click(function(){

        var r=confirm( "Are you sure you want to join this group?");
        if(r== true){
            window.location=this.href;
        }
        return false;
    }
);

}
);

jQuery(document).ready(function(){

    jQuery("a[class=memberofgroup]").click(function(){

        var r=confirm( "Your are a member of this group\n you can not join !!!");
        if(r== true){
            window.location=this.href;
        }
        return false;
    }


);
}
);


jQuery(document).ready(function(){

    jQuery("a[class=leavegroup]").click(function(){

        var r=confirm( "Are you want to leave this group?");
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
        window.location = 'index.php?module=unesco_oer&action=searchGroup&search='+ $('input[name=search]').val();
    }
);
</script>