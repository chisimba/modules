<style type="text/css">
		<!--
		div.imgTotal {
			border-top: 1px solid #ccc;
			border-left: 1px solid #ccc;
			border-right: 1px solid #ccc;
		}
		div.imgBorder {
			height: 70px;
			vertical-align: middle;
			width: 88px;
			overflow: hidden;
		}
		div.imgBorder a {
			height: 70px;
			width: 88px;
			display: block;
		}
		div.imgBorder a:hover {
			height: 70px;
			width: 88px;
			background-color: #f1e8e6;
			color : #FF6600;
		}
		.imgBorderHover {
			background: #FFFFCC;
			cursor: hand;
		}
		div.imginfoBorder {
			background: #f6f6f6;
			width: 84px !important;
			width: 90px;
			height: 35px;
			vertical-align: middle;
			padding: 2px;
			overflow: hidden;
			border: 1px solid #ccc;
		}

		div.imgBorder a {
			cursor: pointer;
		}

		.buttonHover {
			border: 1px solid;
			border-color: ButtonHighlight ButtonShadow ButtonShadow ButtonHighlight;
			cursor: hand;
			background: #FFFFCC;
		}

		.buttonOut {
		 	border: 0px;
		}

		.imgCaption {
			font-size: 9pt;
			font-family: "MS Shell Dlg", Helvetica, sans-serif;
			text-align: center;
		}
		.dirField {
			font-size: 9pt;
			font-family: "MS Shell Dlg", Helvetica, sans-serif;
			width:110px;
		}
		div.image {
			padding-top: 10px;
		}
		-->
		</style>
<?php

$filter = 'image';
$mode = "insert";

$link = & $this->newObject('link', 'htmlelements');
$domTT = & $this->newObject('domtt', 'htmlelements');
$delImage = $this->newObject('geticon', 'htmlelements');
$folImage = $this->newObject('geticon', 'htmlelements');
$this->_objSkin = & $this->newObject('skin' , 'skin');
			

$delImage->setIcon('edit_trash');
$folImage->setIcon('folder');
	$folImage->width = "15";
	$folImage->height="15";
	$folImage->border = "0";
	
$str = '<div class="manager">';
if($folders)
{
		
	foreach ($folders as $folder)
	{
		$cnt++;
		$link->href = $this->uri(array('action' => 'showmedia', 'folder' => $folder['foldername']));
		$link->link = $folImage->show();
		$folLink =$this->uri(array('action' => 'showmedia', 'folder' => $folder['path']));
		
		$link->href = $this->uri(array('action' => 'showmedia', 'folder' => $folder['path']));
		$link->link = $delImage->show();
		$link->target = "_top";
		$link->title = "Delete Item";
		$delLink = $link->show();
		
		
		$ttText = 'Click to Open';
		
		$str .= '				<div style="float:left; padding: 5px">
			<div class="imgTotal" >
				<div align="center" class="imgBorder">
					
							'.$domTT->show(str_replace( '/', '', $folder['foldername']). '  Folder', $ttText, $folImage->show(), $folLink).'	
				
				</div>
			</div>
			<div class="imginfoBorder">
				<small>

					'. $folder['foldername'].'
									</small>
				<div class="buttonOut">
				'.$link->show().'
					
				</div>
			</div>
		</div>';
	} 
}
/*
echo '<pre>';
var_dump($files);
echo '</pre>';
*/
if($files)
{
	
	
	foreach($files as $file)
	{
		$cnt++;
		
		
		
		
		$link->href = $this->uri(array('action' => 'delimage', 'path' => $file['path'], 'folder' => $this->getParam('folder')));
		$link->link = $delImage->show();
		$link->target = "_top";
		$link->title = "Delete Item";
		$delLink = $link->show();
		//print_r($file['sizes']);
		$ttText = 'Width = '.$file['sizes'][0].'px <br />';
		$ttText .= 'Height = '.$file['sizes'][1].'px <br />';
		$ttText .= 'File Type = '.$file['sizes']['mime'].' <br />';
		$ttText .= 'Filesize = '.$file['filesize'].' bytes<p /><br />';
		
		$ttText .= 'Click to view the enlarged image';
		$uri = $this->_objSkin->getSkinUrl().$this->getParam('folder').'/'.$file['name'];
		if($mode == "insert")
		{
			$imgOnclick = " onclick=\"insertImage('".$file["path"]."')\" ";
		} else {
			$imgOnclick = " onclick=\"javascript: window.open( '". $uri ."', 'win1', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=1.5,height=1.5,directories=no,location=no,left=120,top=80'); \"";
		}
		
		$wrap = '<div class="image">
			
										<img src="'.$file['path'].'" width="32" height="32" alt="'.$file['name'].' - 1.32 Kb" border="0" />
									</div>';
		
		$str .= '	<div style="float:left; padding: 5px">
						<div class="imgTotal"  onmouseover="" onmouseout="">
							<div align="center" class="imgBorder">
								
							'.$domTT->show($file['name'], $ttText, $wrap, '#', $imgOnclick).'
						
									
							</div>
						</div>
						<div class="imginfoBorder">
							<small>
									'.$file['name'].'
							</small>
							<div class="buttonOut">
			
								'.$link->show().'
								
							</div>
						</div>
				</div>';
		
	}
}
	
	$str .= '</div>';
	if($cnt == 0)
	{
		$str .= '<div align="center" style="font-size:large;font-weight:bold;color:#CCCCCC;font-family: Helvetica, sans-serif;">No Media Files Found</div>';
	}
	echo $str;
//$str .='<form id="images" name="images"><input type="text" name="hideme" id="hideme" ><form>';
//print $str;
?>

<script type="text/javascript" language="javascript">

	function 1insertImage(path)
	{
	//	alert(path);	
		document.forms[0].hideme.value = path;
	}
	
	function insertImage(symbol) 
	{alert('here');
	  if (window.opener && !window.opener.closed)
	  alert('moving' + symbol);
	    window.opener.document.imgform.hiddenimg.value = symbol;
	  window.close();
	}
</script>