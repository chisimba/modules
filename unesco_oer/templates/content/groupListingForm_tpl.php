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
?>
<div style="clear:both;"></div>
<div class="breadCrumb module">
    <div id='breadcrumb'>
        <ul><li class="first">Home</li>
            <li><a href='?module=unesco_oer&action=controlpanel' alt='Adminstrative Tools' title='Adminstrative Tools'>Adminstrative Tools</a></li>
           <li>Product groups</li>
            <!--<li><a href='/newsroom/2430/newsitems.html' alt='Click here to view NewsItems' title='Click here to view NewsItems'>NewsItems</a></li>
            <li><a href='#' alt='Click here to view 2011-07' title='Click here to view 2011-07'>2011-07</a></li>
            <li>witsjunction</li>
           -->
        </ul>
    </div>

</div>
<?php
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


echo $addGroupLink->show() .'&nbsp;'.$BackToControlPannelLink->show(). '&nbsp;'. $search->show(). '&nbsp;'.$buttonGO->show();
echo '</div>';

$myTable = $this->newObject('htmltable', 'htmlelements');
$myTable->width = '100%';
$myTable->border = '0';
$myTable->cellspacing = '0';
$myTable->cellpadding = '0';

$myTable->startHeaderRow();
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_name', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_email', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_edit', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_delete', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_users', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_legend_oerprosucts', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->endHeaderRow();

//$groups = $this->objDbGroups->getAllGroups();
//get user from the database
$groups = "";
//$mode=$this->getParam('mode');
if (strcmp($mode, 'addfixup') == 0){
    $groups=$group;
}else{
    $groups = $this->objDbGroups->getAllGroups();
}

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
        if ($grouphasanadaptationcannotbedeleted) { #############  NEED CONDITION  ########
            $deleteLink->link = $objIcon->show();
            $deleteLink->cssClass = 'deletegroupadaptation';
            $myTable->addCell($deleteLink->show());
        } else {
            $deleteLink = new link($this->uri(array('action' => "deleteGroup", 'id' => $group['id'])));
            $deleteLink->link = $objIcon->show();
            $deleteLink->cssClass = 'deleteuser';
            $myTable->addCell($deleteLink->show());
        }

        $objIcon->setIcon('view');
        $mode='groupuser';
        $editLink =new link($this->uri(array('action' =>"userListingForm",'id' =>$group['id'],'mode'=>$mode)));
        $editLink->link = $objIcon->show();
        $myTable->addCell($editLink->show());
        
        $objIcon->setIcon('view');
        $editLink =new link($this->uri(array('action' =>"groupProductForm",'id' =>$group['id'],'page'=>'2a_tpl.php')));
        $editLink->link = $objIcon->show();
        $myTable->addCell($editLink->show());
        $myTable->endRow();

    }
}

echo $nogroupfound; // this must be a script

$fs = new fieldset();
$fs->setLegend($this->objLanguage->languageText('mod_unesco_oer_group_users', 'unesco_oer'));
$fs->addContent($myTable->show());
echo $fs->show();



require_once 'Pager/Pager.php';
/* We will bypass the database connection code ... */
$sqlQuery = "SOME SQL QUERY";
$result = mysql_query($sqlQuery);
$totalRows = 10;

$pager_options = array(
'mode'       => 'Sliding',
'perPage'    => 10,
'delta'      => 4,
'totalItems' => $totalRows,
);
$pager = Pager::factory($pager_options);
echo $pager->links;

?>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
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

jQuery(document).ready(function(){

    jQuery("a[class=deletegroupadaptation]").click(function(){

        var r=confirm( "This group is in use with product adaptation\n First delete it's adaptations then you can delete the group !!!");
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

