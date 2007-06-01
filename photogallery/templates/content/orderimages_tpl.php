<?php

$this->loadClass('htmltable','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('checkbox','htmlelements');
$this->loadClass('button','htmlelements');
$objThumbnail = & $this->getObject('thumbnails','filemanager');
$link = $this->getObject('link','htmlelements');
//$button = $this->getObject('button','htmlelements');
//$dropdown = $this->getObject('dropdown','htmlelements');
$icon = $this->getObject('geticon', 'htmlelements');
$h1 = $this->getObject('htmlheading','htmlelements');
$form = $this->getObject('form', 'htmlelements');


$h1->type = 2;
$h1->str = 'Sort Album ';
$str .= $h1->show();

$link->href = $this->uri(array('action' => 'editsection'));
$link->link = '&laquo; back to the list';
$str .= $link->show().' | ';

$link->href = $this->uri(array('action' => 'editalbum', 'albumid' => $this->getParam('albumid')));
$link->link = 'Edit Album';
$str .= $link->show().' | ';

$link->href = $this->uri(array('action' => 'viewalbum', 'albumid' => $this->getParam('albumid')));
$link->link = 'View Album';
$str .= $link->show().' | ';



$script = '  <script type="text/javascript" src="/zen/zen/admin.js"></script>	
<script src="/zen/zen/scriptaculous/prototype.js" type="text/javascript"></script>
		<script src="/zen/zen/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript"><!--
			';

$str .= '<div class="box" style="padding: 15px;">
    
    <p>Sort the images by dragging them...</p>
    
      <div id="images">';
 $cnt = 0;     
foreach($thumbnails as $thumbnail)
{
 	$cnt++;
 	$filename = $this->_objFileMan->getFileName($thumbnail['file_id']); 
 	$path = $objThumbnail->getThumbnail($thumbnail['file_id'],$filename);
 	$bigPath = $this->_objFileMan->getFilePath($thumbnail['file_id']);
	$str .= '<img class="imagethumb" id="id_'.$cnt.'" src="'.$path.'" alt="'.$thumbnail['title'].'"  title="'.$thumbnail['description'].'"/>';
	
}
$str .='</div>';

$this->appendArrayVar('headerParams', $script);

echo '<div id="main">'.$str;
?>      

  
      <br/><br/>
        <div>
      		<form action="<?php echo $this->uri(array('action' => 'saveimageorder', 'albumid' => $this->getParam('albumid'))) ?>" method="POST" onSubmit="populateHiddenVars();" name="sortableListForm" id="sortableListForm">
						<input type="text" name="imageOrder" id="imageOrder" size="60">
						<input type="text" name="sortableListsSubmitted" value="true">
						<input type="submit" value="Save" class="button">
		</form>
		      </div>

      
     
       </div>
	
       
  </div>
  
  <script language="JavaScript" type="text/javascript"><!--
			function populateHiddenVars() {
			 alert('here');
									document.getElementById('imageOrder').value = Sortable.serialize('images');
									alert('dd');
									return true;
			}
		
			//-->
		</script>
  
  		 <script type="text/javascript">
			// <![CDATA[
							Sortable.create('images',{tag:'img',overlap:'horizontal',constraint:false});
							// ]]>
		 </script>
