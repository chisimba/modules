<?php
$this->loadClass('htmltable','htmlelements');
$link = $this->getObject('link','htmlelements');
$icon = $this->getObject('geticon', 'htmlelements');
$cnt =0 ;
$str = '';
foreach($arrAlbum as $album)
{
	$cnt++;
	$table = new htmltable();
	$table->cellspacing='0';
	$table->width = '100%';
	$table->startRow();
	$link->href = $this->uri(array('action' => 'editalbum', 'albumid' => $album['id']));
	$link->link = '<img height="40" width="40" src="'.$this->_objDBImage->getThumbNailFromFileId($album['thumbnail']).'" />';
	$table->addCell($link->show(),20);
	$link->link = $album['title'];
	$table->addCell($link->show());
	$link->href = 'javascript: confirmDeleteAlbum(\'?page=edit&action=deletealbum&album=zach\');';
	$icon->setIcon('delete');
	$link->link = $icon->show(); 
	$table->addCell($icon->getDeleteIconWithConfirm($album['id'],array('action' => 'deletealbum', 'albumid' => $album['id']),'photogallery'),null,null,'right');
	$table->endRow();
	$str .= '<div id="id_'.$cnt.'">'.$table->show().'</div>';
	
}

$table = new htmltable();
$table->cssClass = 'bordered';
$table->startHeaderRow();
$table->addHeaderCell('Thumb',55);
$table->addHeaderCell('Edit this album');
$table->endHeaderRow();

$table->startRow();
$table->addCell('<div id="albumList" class="albumList">'.$str.'</div>',null,null,null,null,'colspan="2" style="padding: 0px 0px;"');
$table->endRow();

$script = '  <script type="text/javascript" src="'.$this->getResourceUri('admin.js','photogallery').'"></script>	
<script src="'.$this->getResourceUri('scriptaculous/prototype.js','photogallery').'" type="text/javascript"></script>
		<script src="'.$this->getResourceUri('scriptaculous/scriptaculous.js','photogallery').'" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript"><!--
			function populateHiddenVars() {
									document.getElementById(\'albumOrder\').value = Sortable.serialize(\'albumList\');
								
									return true;
			}
			//-->
		</script>';

$this->appendArrayVar('headerParams', $script);
echo '<div id="main"><h2>Edit Gallery</h2>

Drag the albums into the order you wish them displayed. Select an album to edit its description and data';
echo $table->show();
?>
        
                
        <div>
      		<form action="<?php echo $this->uri(array('action' => 'savealbumorder'), 'photogallery') ?>" method="POST" onSubmit="populateHiddenVars();" name="sortableListForm" id="sortableListForm">
						<input type="hidden" name="albumOrder" id="albumOrder" size="60">
						<input type="hidden" name="sortableListsSubmitted" value="true">
						<input type="submit" value="Save Order" class="button">
						
		</form>
		      </div></div>
			  
			  <script type="text/javascript">
			// <![CDATA[
							Sortable.create('albumList',{tag:'div'});
							// ]]>
		 </script>