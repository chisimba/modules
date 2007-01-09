<?php
/**
* Class to Display a Freemind File as a Flash Object
* 
* Using this flash object, the user only needs to have flash installed,
* no need for Java. This flash object also has the following built in features:
*
* - Search
* - Snapshot
* - Max width for nodes
* - Zoom capability
*
* @author Tohir Solomons
*/
class flashfreemind extends object 
{
    /**
    * @var string $mindMap Path and Name of the Mind Map file
    */
    var $mindMap = 'modules/freemind/resources/freeMindFlashBrowser.mm'; //example
    
    /**
    * @var string $mindMapId JavaScript Id of the Mindmap
    */
    var $mindMapId = 'mindmap'; // Name of the Flash Object
    
    /**
    * @var string $width Width of the Flash Object
    */
    var $width = '100%';
    
    /**
    * @var string $height Height of the Flash Object
    */
    var $height = '500px';
    
    /**
    * @var string $openUrl Target Window for opening links
    */
    var $openUrl = '_self';
    
    /**
    * @var int $startCollapsedToLevel Level from which nodes should be collapsed. -1 for all nodes
    */
    var $startCollapsedToLevel = 10;
    
    /**
    * @var string $mainNodeShape Shape of Main Node, either elipse or rectangle
    */
    var $mainNodeShape = 'elipse';
    
    /**
    * @var int $defaultWordWrap Max width of text node, 600 is the plugin's default
    */
    var $defaultWordWrap = 600;
    
    /**
    * @var int $ShotsWidth Width of Snapshot, 200 is the plugin's default
    */
    var $ShotsWidth = '200';
    
    
    /**
    * Constructor
    */
    function init()
    {
    
    }
    
    /**
    * Method to set the path to the Mindmap file
    * @param string $url Url to the Mindmap File
    */
    function setMindMap($url)
    {
        $url = str_replace('&amp;', '&', $url);
        $this->mindMap = $url;
    }
    
    /**
    *
    */
    function getMindmapScript()
    {
        return $this->getJavascriptFile('flashobject.js', 'freemind');
    }   
    
    /**
    * Method to show the Flash Freemind Object
    */
    function show()
    {
        $this->appendArrayVar('headerParams', $this->getMindmapScript());
        
        return '<div id="flashcontent_'.$this->mindMapId.'" style="z-index:0; width:'.$this->width.'; height:'.$this->height.'">
		 Flash plugin or Javascript are turned off.
		 Activate both  and reload to view the mindmap
	</div>
    <script type="text/javascript">
    		// <![CDATA[
    		var fo = new FlashObject("modules/freemind/resources/visorFreemind.swf", "'.$this->mindMapId.'", "'.$this->width.'", "'.$this->height.'", 6, "#ffffff");
    		fo.addParam("quality", "high");
    		fo.addParam("bgcolor", "#ffffff");
    		fo.addParam("wmode", "transparent");
    		fo.addVariable("openUrl", "'.$this->openUrl.'");
    		fo.addVariable("initLoadFile", "'.$this->mindMap.'");
    		fo.addVariable("startCollapsedToLevel","'.$this->startCollapsedToLevel.'");
            fo.addVariable("mainNodeShape","'.$this->mainNodeShape.'");
            fo.addVariable("defaultWordWrap","'.$this->defaultWordWrap.'");
            fo.addVariable("ShotsWidth","'.$this->ShotsWidth.'");
    		fo.write("flashcontent_'.$this->mindMapId.'");
    		// ]]>
    	</script>';
    }
 
} 
