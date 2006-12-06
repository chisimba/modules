<?php
//die("server: ".$server);

?>

<APPLET CODE="freemind.main.FreeMindApplet.class"
   ARCHIVE="freemindbrowser.jar" 
   CODEBASE="<?php echo $this->objConfig->siteRoot()?>modules/freemind/java/" WIDTH="100%" HEIGHT="700">
		<PARAM NAME="type" VALUE="application/x-java-applet;version=1.4">
		<PARAM NAME="scriptable" VALUE="false">
		<PARAM NAME="modes" VALUE="freemind.modes.browsemode.BrowseMode">
		<PARAM NAME="browsemode_initial_map"
       		VALUE="<?php echo $map?>">
		<param NAME="initial_mode" VALUE="Browse">
		<param NAME="selection_method" VALUE="selection_method_direct">
		
</applet>