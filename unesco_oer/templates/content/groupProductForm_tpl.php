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

//$button = new button('Add Button',$this->objLanguage->languageText('mod_unesco_oer_add_data_product_heading', 'unesco_oer'));
//$button->setToSubmit();
//$addGroupLink =new link($this->uri(array('action' =>"saveProductMetaData")));
//$addGroupLink->link = $button->show();
////echo $addGroupLink->show();

$controlPannel = new button('backButton', $this->objLanguage->languageText('mod_unesco_oer_group_back_button', 'unesco_oer'));
$controlPannel->setToSubmit();
$BackToControlPannelLink = new link($this->uri(array('action' => "groupListingForm")));
$BackToControlPannelLink->link = $controlPannel->show();

//button search user
$buttonGO = new button('searchButton',$this->objLanguage->languageText('mod_unesco_oer_group_go_button', 'unesco_oer'));
//$buttonGO->setOnClick("javascript: searchThis()");
$buttonGO->show();
//text input search user
$search = new textinput('search','',"",20);


echo '&nbsp;'.$BackToControlPannelLink->show(). '&nbsp;'. $search->show(). '&nbsp;'.$buttonGO->show();
echo '</div>';
$myTable = $this->newObject('htmltable', 'htmlelements');
$myTable->width = '100%';
$myTable->border = '0';
$myTable->cellspacing = '0';
$myTable->cellpadding = '0';

$myTable->startHeaderRow();
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_title', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_creator', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_publisher', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_status', 'unesco_oer'),null,null,left,"userheader",null);
//$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_edit', 'unesco_oer'),null,null,left,"userheader",null);
//$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_delete', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->endHeaderRow();


// This must get product from tbl_product by creatorID
//$group=$this->objGroups->getGroupProductadaptation($this->getParam('id'));


if (count($groups) > 0) {
    foreach ($groups as $group) {
        $myTable->startRow();
        $myTable->addCell($group['title'],null, null, null, "user", null, null);
        //$myTable->addCell($group['creator'], null, null, null, "user", null, null);
        $myTable->addCell($group['publisher'], null, null, null, "user", null, null);
        $myTable->addCell($group['status'], null, null, null, "user", null, null);
        $objIcon->setIcon('edit');
        $editLink =new link($this->uri(array('action' =>"groupEditingForm",'id' =>$group['id'])));
        $editLink->link = $objIcon->show();
        $myTable->addCell($editLink->show());

//        $objIcon->setIcon('delete');
//        if ($this->objDbProducts->hasAnAdaptation($this->getParam('id'))) { //product has an adaptation can't be deleted
//            $deleteLink = new link($this->uri(array('action' => "deleteGroup", 'id' => $group['id'])));
//            $deleteLink->link = $objIcon->show();
//            $deleteLink->cssClass = 'deleteuser';
//            $myTable->addCell($deleteLink->show());
//        } else { //product has no adaptation can be deleted
//            $deleteLink = new link($this->uri(array('action' => "deleteGroup", 'id' => $group['id'])));
//            $deleteLink->link = $objIcon->show();
//            $deleteLink->cssClass = 'deleteresourcer';
//            $myTable->addCell($deleteLink->show());
//        }
//        $deleteLink =new link($this->uri(array('action' => "",'id' => $group['id'])));
//        $deleteLink->link = $objIcon->show();
//        $deleteLink->cssClass = 'deleteuser';
//        $myTable->addCell($deleteLink->show());

    }
}

echo $nogroupfound; // this must be a script

$fs = new fieldset();
$fs->setLegend($this->objLanguage->languageText('mod_unesco_oer_group_legend_oerprosucts', 'unesco_oer'));
$fs->addContent($myTable->show());
echo $fs->show();

?>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">

jQuery(document).ready(function(){

    jQuery("a[class=deleteuser]").click(function(){

        var r=confirm( "This Product is being Adaptation \n Can not be deleted !!!");
        if(r== true){
            window.location=this.href;
        }
        return false;
    }


);
}
);


jQuery(document).ready(function(){

    jQuery("a[class=deleteresource]").click(function(){

        var r=confirm( "Are you sure you want to delete this Product?");
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