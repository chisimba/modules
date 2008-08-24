<?php
/**
 * Popularity controller class
 * 
 * Class to control the popularity contest module
 * 
 * PHP version 5
 * 
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
 * 
 * @category  chisimba
 * @package   remotepopularity
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2008 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id: $
 * @link      http://avoir.uwc.ac.za
 * @see       api
 */

class remotepopularity extends controller 
{
	/**
    *
    * Standard constructor method to retrieve the action from the
    * querystring, and instantiate the user and lanaguage objects
    *
    */
  public  function init()
    {
    	try {
    		$this->objDbPop = $this->getObject('dbpopularity');
        	$this->objUser =  $this->getObject("user", "security");
        	//Create an instance of the language object
        	$this->objLanguage = $this->getObject("language", "language");
    	}
    	catch (customException $e)
    	{
    		customException::cleanUp();
    		exit;
    	}
    }

    /**
    * Standard dispatch method to handle adding and saving
    * of comments
    *
    * @access public
    * @param void
    * @return void
    */
 	public  function dispatch()
    {
        $action = $this->getParam('action');
    	switch ($action) {
            case null:
            	// this will be the case to show the stats
            	// generate the flash graph data from the table and display it
            	
 				$objFlashGraph = $this->getObject('flashgraph', 'utilities');
 				$objFlashGraph->dataSource = $this->uri(array('action'=>'getdata'));
 				$graph = $objFlashGraph->show(); 
 				$this->setVarByRef('graph', $graph);
 				return 'graph_tpl.php';
            	break;
            	
            case 'getdata':
            	$colours = array(
            	            '#000000',
'#000033',
'#000066',
'#000099',
'#0000CC',
'#0000FF',
'#003300',
'#003333' ,
'#003366',
'#003399' ,
'#0033CC' ,
'#0033FF' ,
'#006600' ,
'#006633' ,
'#006666' ,
'#006699' ,
'#0066CC' ,
'#0066FF' ,
'#009900' ,
'#009933' ,
'#009966' ,
'#009999' ,
'#0099CC' ,
'#0099FF' ,
'#00CC00' ,
'#00CC33' ,
'#00CC66' ,
'#00CC99' ,
'#00CCCC' ,
'#00CCFF' ,
'#00FF00' ,
'#00FF33' ,
'#00FF66' ,
'#00FF99' ,
'#00FFCC' ,
'#00FFFF' ,
'#330000' ,
'#330033' ,
'#330066' ,
'#330099' ,
'#3300CC' ,
'#3300FF' ,
'#333300' ,
'#333333' ,
'#333366' ,
'#333399' ,
'#3333CC' ,
'#3333FF' ,
'#336600' ,
'#336633' ,
'#336666' ,
'#336699' ,
'#3366CC' ,
'#3366FF' ,
'#339900' ,
'#339933' ,
'#339966' ,
'#339999' ,
'#3399CC' ,
'#3399FF' ,
'#33CC00' ,
'#33CC33' ,
'#33CC66' ,
'#33CC99' ,
'#33CCCC' ,
'#33CCFF' ,
'#33FF00' ,
'#33FF33' ,
'#33FF66' ,
'#33FF99' ,
'#33FFCC' ,
'#33FFFF' ,
'#660000' ,
'#660033' ,
'#660066' ,
'#660099' ,
'#6600CC' ,
'#6600FF' ,
'#663300' ,
'#663333' ,
'#663366' ,
'#663399' ,
'#6633CC' ,
'#6633FF' ,
'#666600' ,
'#666633' ,
'#666666' ,
'#666699' ,
'#6666CC' ,
'#6666FF' ,
'#669900' ,
'#669933' ,
'#669966' ,
'#669999' ,
'#6699CC' ,
'#6699FF' ,
'#66CC00' ,
'#66CC33' ,
'#66CC66' ,
'#66CC99' ,
'#66CCCC' ,
'#66CCFF' ,
'#66FF00' ,
'#66FF33' ,
'#66FF66' ,
'#66FF99' ,
'#66FFCC' ,
'#66FFFF' ,
'#990000' ,
'#990033' ,
'#990066' ,
'#990099' ,
'#9900CC' ,
'#9900FF' ,
'#993300' ,
'#993333' ,
'#993366' ,
'#993399' ,
'#9933CC' ,
'#9933FF' ,
'#996600' ,
'#996633' ,
'#996666' ,
'#996699' ,
'#9966CC' ,
'#9966FF' ,
'#999900',
'#999933',
'#999966' ,
'#999999' ,
'#9999CC' ,
'#9999FF' ,
'#99CC00' ,
'#99CC33' ,
'#99CC66' ,
'#99CC99' ,
'#99CCCC' ,
'#99CCFF' ,
'#99FF00' ,
'#99FF33' ,
'#99FF66' ,
'#99FF99' ,
'#99FFCC' ,
'#99FFFF' ,
'#CC0000' ,
'#CC0033' ,
'#CC0066' ,
'#CC0099',
'#CC00CC' ,
'#CC00FF' ,
'#CC3300' ,
'#CC3333' ,
'#CC3366' ,
'#CC3399' ,
'#CC33CC' ,
'#CC33FF' ,
'#CC6600' ,
'#CC6633' ,
'#CC6666' ,
'#CC6699' ,
'#CC66CC' ,
'#CC66FF' ,
'#CC9900' ,
'#CC9933' ,
'#CC9966' ,
'#CC9999' ,
'#CC99CC' ,
'#CC99FF' ,
'#CCCC00' ,
'#CCCC33' ,
'#CCCC66' ,
'#CCCC99' ,
'#CCCCCC' ,
'#CCCCFF' ,
'#CCFF00' ,
'#CCFF33' ,
'#CCFF66' ,
'#CCFF99' ,
'#CCFFCC' ,
'#CCFFFF' ,
'#FF0000' ,
'#FF0033' ,
'#FF0066' ,
'#FF0099' ,
'#FF00CC' ,
'#FF00FF' ,
'#FF3300' ,
'#FF3333' ,
'#FF3366' ,
'#FF3399' ,
'#FF33CC' ,
'#FF33FF' ,
'#FF6600' ,
'#FF6633' ,
'#FF6666' ,
'#FF6699' ,
'#FF66CC' ,
'#FF66FF' ,
'#FF9900' ,
'#FF9933' ,
'#FF9966' ,
'#FF9999' ,
'#FF99CC' ,
'#FF99FF' ,
'#FFCC00' ,
'#FFCC33' ,
'#FFCC66' ,
'#FFCC99' ,
'#FFCCCC' ,
'#FFCCFF' ,
'#FFFF00' ,
'#FFFF33' ,
'#FFFF66' ,
'#FFFF99',
'#FFFFCC',
);
            	$objFlashGraphData = $this->newObject('flashgraphdata', 'utilities');
 				$objFlashGraphData->graphType = 'pie';
 				
 				// Get the unique names of the modules
 				$mods = $this->objDbPop->getModList();
 				foreach($mods as $mod)
 				{
 					// get the record count
 					$count = $this->objDbPop->getRecCount($mod);
 					$choice = array_rand($colours);
 					$colour = $colours[$choice];
 					$objFlashGraphData->addPieDataSet($count, $colour, $mod);
 				}
 				
 				$graphdata = $objFlashGraphData->show();
            	echo $graphdata;
            	break;
            		
            default:
            	die("unknown action");
            	break;
        }
    }
	
}
