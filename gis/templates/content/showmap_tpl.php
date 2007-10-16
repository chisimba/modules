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

$minx = floatval(-47.1234);
$maxx = floatval(73.1755);
$ymin = floatval(-38.4304);

$middleColumn .= <<<EOT
<div style="width: 800px; height: 600px; border: 50px;" id="map_tag" ></div>
   <div style="width: 190px; height: 400px;" id="ref_tag"></div>

   <script type="text/javascript">
     //<![CDATA[
     myMap1 = new msMap( document.getElementById('map_tag'), 'standardRight');
     myMap1.setCgi( '/cgi-bin/mapserv' );
     myMap1.setFullExtent( -47.1234, 73.1755, -38.4304 );
     myMap1.setMapFile( '/var/www/chisimba_framework/app/zambezia2.map' );
     myMap1.setLayers( 'country2_' );
	 myMap1.setMode('map');
	 
	 myMap2 = new msMap( document.getElementById("ref_tag") );
	 myMap2.setActionNone();
	 myMap2.setFullExtent(-47.1234, 73.1755, -38.4304);
	 myMap2.setMapFile('/var/www/chisimba_framework/app/zambezia2.map');
	 myMap2.setLayers('country2_');
	 
	 myMap1.setReferenceMap(myMap2);

	 myMap1.redraw(); myMap2.redraw();
	 
	chgLayers();


	function chgLayers()
	{
  		var list = "country2_ ";
  		var objForm = document.forms[0];
  		for(i=0; i<document.forms[0].length; i++)
  		{
    		if( objForm.elements["layer[" + i + "]"].checked )
    		{
      			list = list + objForm.elements["layer[" + i + "]"].value + " ";
    		}
  		}
  		myMap1.setLayers( list );
  		myMap1.redraw();
	}
     myMap1.redraw();
     //]]>
   </script>
EOT;

   $leftCol .= $objSideBar->show();

$middleColumn .= '<div id="map_tag2" style="position:absolute; 
    top:10px; left:10px; width: 800px; height: 600px; border-width:1px;
    border-color:#000088;">'.$themap.'</div>';

$middleColumn .= header("Content-type: image/png");
//$middleColumn .= "<img src=".$themap.">";

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
echo $cssLayout->show(); 
?>