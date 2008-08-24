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
            	$colours = array('#3334AD', '#00ff00', '#9900CC');
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