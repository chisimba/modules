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
$header->str = "Unesco_OER GROUPS :";
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




$button = new button('Add Button', $this->objLanguage->languageText('mod_unesco_oer_add_resource_heading', 'unesco_oer'));
$button->setToSubmit();
$addGroupLink =new link($this->uri(array('action' =>"addOERform")));
$addGroupLink->link = $button->show();
//echo $addGroupLink->show();

$controlPannel = new button('backButton', $this->objLanguage->languageText('mod_unesco_oer_group_back_button', 'unesco_oer'));
$controlPannel->setToSubmit();
$BackToControlPannelLink = new link($this->uri(array('action' => "controlpanel")));
$BackToControlPannelLink->link = $controlPannel->show();

//button search user
$buttonGO = new button('searchButton',$this->objLanguage->languageText('mod_unesco_oer_group_go_button', 'unesco_oer') );
$buttonGO->show();
$search = new textinput('search','',"",20);


echo $addGroupLink->show() .'&nbsp;'.$BackToControlPannelLink->show(). '&nbsp;'. $search->show(). '&nbsp;'.$buttonGO->show();
echo '</div>';

$myTable = $this->newObject('htmltable', 'htmlelements');
$myTable->width = '100%';
$myTable->border = '0';
$myTable->cellspacing = '0';
$myTable->cellpadding = '0';

$myTable->startHeaderRow();
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_resorce_name','unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_resorce_type','unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_resorce_author','unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_resorce_puublisher','unesco_oer'),null,null,left,"userheader",null);
$myTable->addHeaderCell($this->objLanguage->languageText('mod_unesco_oer_group_delete', 'unesco_oer'),null,null,left,"userheader",null);
$myTable->endHeaderRow();

if (strcmp($mode, 'addfixup') == 0){
    $resources=$this->objDbOERresources->getResource($groupid);
}else{
    $groupid=$this->getParam('groupid');
    $resources=$this->objDbOERresources->getResource($groupid);

}


if (count($resources) > 0) {
    foreach ($resources as $resource) {
        $myTable->startRow();
        //Add items in a table
        $myTable->addCell($resource['resource_name'], null, null, null, "user", null, null);
        $myTable->addCell($resource['resource_type'], null, null, null, "user", null, null);
        $myTable->addCell($resource['author'], null, null, null, "user", null, null);
        $myTable->addCell($resource['publisher'], null, null, null, "user", null, null);
        $objIcon->setIcon('delete');
        $deleteLink = new link($this->uri(array('action' =>"deleteOERresource", 'id' => $group['id'])));
        $deleteLink->link = $objIcon->show();
        $deleteLink->cssClass = 'deleteresource';
        $myTable->addCell($deleteLink->show());
        //close row
        $myTable->endRow();

    }
}

echo $nogroupfound; // this must be a script

$fs = new fieldset();
$fs->setLegend($this->objLanguage->languageText('mod_unesco_oer_group_resources', 'unesco_oer'));
$fs->addContent($myTable->show());
echo $fs->show();




?>
<script type="text/javascript" src="packages/unesco_oer/resources/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript">

jQuery(document).ready(function(){

    jQuery("a[class=deleteresource]").click(function(){

        var r=confirm( "Are you sure you want to delete this resource?");
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

