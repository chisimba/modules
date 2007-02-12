<?php
/*
 * Created on Feb 3, 2007
 * 
 */
// Create the configuration object
$objRsConfig = $this->getObject('altconfig', 'config');
$moduleBase =  "http://" . $_SERVER['SERVER_NAME'] . $objRsConfig->getItem('MODULE_URI');
$tlBase = $this->Uri(array("mode" => "plain"), "timeline");
$mapBase = $this->Uri(array("mode" => "plain"), "simplemap");
$tlUrl = htmlentities("http://localhost/chisimba/index.php?module=timeline&mode=plain");
?>
<div id="threecolumn">
	<div id="wrapper"> 
		<div id="content"> 
			<div id="contentcontent">
			<iframe id="mytimelines" name="mytimelines" 
			  src="index.php?module=timeline&amp;mode=plain" width="100%" 
			  height="350">
			</iframe>
			<?php 
			$hideMap = $this->getParam("hideMap", FALSE);
			if (!$hideMap == "TRUE") {
				?>
				<br /><br />
				<iframe  id="mymap" name="mymap" 
				  src="index.php?module=simplemap&amp;mode=plain&amp;width=1000" 
				  width="100%" height="620"></iframe>
		    	<?php
			}
			?>
			</div>
		</div>
	</div>
    <div id="left"> 
		<div id="leftcontent">
			<h3>Key times</h3>
			<ul>
				<li><a href="<?php echo $tlBase; ?>" target="mytimelines">Restart</a></li>
				<li><a href="<?php echo $tlBase; ?>&amp;focusDate=1920" target="mytimelines">1920</a></li>
				<li><a href="<?php echo $tlBase; ?>&amp;focusDate=1930" target="mytimelines">1930</a></li>
				<li><a href="<?php echo $tlBase; ?>&amp;focusDate=1940" target="mytimelines">1940</a></li>
				<li><a href="<?php echo $tlBase; ?>&amp;focusDate=1950" target="mytimelines">1950</a></li>
				<li><a href="<?php echo $tlBase; ?>&amp;focusDate=1960" target="mytimelines">1960</a></li>
				<li><a href="<?php echo $tlBase; ?>&amp;focusDate=1970" target="mytimelines">1970</a></li>
				<li><a href="<?php echo $tlBase; ?>&amp;focusDate=1980" target="mytimelines">1980</a></li>
				<li><a href="<?php echo $tlBase; ?>&amp;focusDate=1990" target="mytimelines">1990</a></li>
				<li><a href="<?php echo $tlBase; ?>&amp;focusDate=2000" target="mytimelines">2000</a></li>
				<li><a href="<?php echo $tlBase; ?>&amp;focusDate=2010" target="mytimelines">World Cup</a></li>
			</ul>				
		</div>
	</div>
    <div id="right"> 
		<div id="rightcontent">
			<h3>Key places</h3>
			<ul>
				<?php if (!$hideMap == "TRUE") { ?>
					<li><a href="<?php echo $mapBase; ?>&amp;gLat=-31.333576&amp;gLong=28.979391&amp;width=1000" target="mymap">Qunu, South Africa</a></li>
					<li><a href="<?php echo $mapBase; ?>&amp;gLat=-25.732912&amp;gLong=28.187903&amp;width=1000" target="mymap">Pretoria, South Africa</a></li>
					<li><a href="<?php echo $mapBase; ?>&amp;gLat=59.902214&amp;gLong=10.742189&amp;width=1000" target="mymap">Oslo, Norway</a></li>
					<li><a href="<?php echo $mapBase; ?>&amp;gLat=43.641709&amp;gLong=-79.391686&amp;width=1000" target="mymap">Toronto, Canada</a></li>
				<?php
				} else {
				    echo "<li>Turn on map to see list of places</li>";
				}
				?>
			</ul>
			<br />
			<br />
			<h3>Control map</h3>
			<ul>
				<?php if ($hideMap == "TRUE") { ?>
				    <li><a href="index.php?module=simplemaptime"><?php echo $this->objLanguage->languageText("mod_simplemaptime_showmap", "simplemaptime"); ?></a></li>
					<?php 
				} else {
					?> 
					<li><a href="index.php?module=simplemaptime&amp;hideMap=TRUE"><?php echo $this->objLanguage->languageText("mod_simplemaptime_hidemap", "simplemaptime"); ?></a></li>
					<?php
				} ?>
			</ul>
			
		</div>
	</div>
</div>
<br />&nbsp;
<br />&nbsp;
