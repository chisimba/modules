<?php
//header("Content-type: image/png");
$head = '<script src="'.$this->getResourceUri("mscross119.js").'" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $head);

$cssLayout = $this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('usermenu', 'toolbar');
$objFeatureBox = $this->newObject('featurebox', 'navigation');

// Set columns to 2
$cssLayout->setNumColumns(2);
$leftMenu = NULL;
$leftCol = NULL;
$middleColumn = NULL;

$script = '<div id="map_tag" style="position:absolute; 
    top:10px; left:10px; width: 800px; height: 600px; border-width:1px;
    border-color:#000088;"></div>';

$script .= '<script type="text/javascript">; 
	//<![CDATA[ 
     myMap1 = new msMap( document.getElementById(\'map_tag\') ); 
	 myMap1.setCgi( \'http://localhost/cgi-bin/mapserv.exe\' ); 
	 // alert("you gotta do MINX, MAXX, MinY as the args here...);
	 myMap1.setFullExtent( 34.930152, 39.353933, -18.876982 );
     myMap1.setMapFile( \'zambezia2.map\' ); 
     myMap1.setLayers( \'MOZ_ZA_District_2007_polygon\' ); 
	 myMap1.redraw();
     //]]> 
</script>';
$middleColumn .= $script;
$leftCol .= $objSideBar->show();

$middleColumn .= '<div id="map_tag" style="position:absolute; 
    top:10px; left:10px; width: 800px; height: 600px; border-width:1px;
    border-color:#000088;">'.$themap.'</div>';

$middleColumn .= header("Content-type: image/png");
//$middleColumn .= "<img src=".$themap.">";

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
echo $cssLayout->show(); 
?>