<?php
/**
*
* WTM coordinate class
*
* This file provides a position search class for the WTM module.
* It receives a gps coordinate and compass direction and determines which building
* is being targeted. Its purpose is to determine which building the phone is pointing at
* 
* @category Chisimba
* @package wtm
* @author Yen-Hsiang Huang <wtm.jason@gmail.com>
* @copyright 2007 AVOIR
* @license http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
* @version CVS: $Id:$
* @link: http://avoir.uwc.ac.za 
*/

// security check
/**
* The $GLOBALS is an array used to control access to certain constants.
* Here it is used to check if the file is opening in engine, if not it
* stops the file from running.
*
* @global entry point $GLOBALS['kewl_entry_point_run']
* @name $kewl_entry_point_run
*/
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

class coordinate extends object 
{
	public $objLanguage;
 
	public $objDBEvents;
	
	public $objDBBuildings;
	
	/**campus boundaries
	public $longeast = 28.032419;
	public $longwest = 28.025325;
	public $latnorth = -26.186956;
	public $latsouth = -26.193139;*/
 
	public function init()
	{
		//Instantiate the language object
		$this->objLanguage = $this->getObject('language','language');
		//Load the DB object
		$this->objDBEvents = $this->getObject('dbwtm_events','wtm');  
		//Load the DB object
		$this->objDBBuildings = $this->getObject('dbwtm_buildings','wtm');  
	}
	
	//function which searches for the building at which the given coordinates are directed at
	public function search($longcoord,$latcoord,$angle)
	{
		//allocating the horizontal parameter to a variable which can be modified
		$longcoordinate = $longcoord * 100000;
		//allocating the vertical parameter to a variable which can be modified
		$latcoordinate = $latcoord * 100000;
		//equivalent to approx 5m
		$length = 0.00005 * 100000;
		//fetch the buildings from the DB
		$allBuildings = $this->objDBBuildings->listAll();
		
		$counter = 0;

		//while the given coordinate is within campus boundaries
		//while ($longcoordinate < $this->longeast && $longcoordinate >$this->longwest && $latcoordinate < $this->latnorth && $latcoordinate > $this->latsouth)
		
		//approx 100m range
		while ($counter < 20)
		{
			foreach($allBuildings as $thisBuilding)
			{
				//setting building boundaries
				$buildingeast = $thisBuilding["longcoordinate"] + $thisBuilding["xexpand"];
				$buildingwest = $thisBuilding["longcoordinate"] - $thisBuilding["xexpand"];
				$buildingnorth = $thisBuilding["latcoordinate"] + $thisBuilding["yexpand"];
				$buildingsouth = $thisBuilding["latcoordinate"] - $thisBuilding["yexpand"];
				
				//echo $buildingeast . "<br />" . $buildingwest . "<br />" . $buildingnorth . "<br />" . $buildingsouth . "<br />";
				//exit;
				
				if ($longcoordinate < $buildingeast && $longcoordinate > $buildingwest)
				{
					//echo "within long boundaries";
					//exit;
					if ($latcoordinate < $buildingnorth && $latcoordinate > $buildingsouth)
					{
						//echo $longcoordinate . "<br />" . $latcoordinate . "<br />";
						echo trim($thisBuilding["building"]) . "\n";
						echo trim($thisBuilding["id"]);
						exit;
					}
				}
			}
			
			switch ($angle)
			{
				case ($angle <= M_PI/2):
					//increasing horizontal coordinate
					$longcoordinate += $length * sin($angle);
					//increasing vertical coordinate
					$latcoordinate += $length * cos($angle);
					//echo $longcoordinate . "<br />" . $latcoordinate . "<br />";
					break;
				case ($angle > M_PI/2 && $angle <= M_PI):
					//increasing horizontal coordinate
					$longcoordinate += $length * sin($angle);
					//decreasing vertical coordinate
					$latcoordinate += $length * cos($angle);
					//echo $longcoordinate . "<br />" . $latcoordinate . "<br />";
					break;
				case ($angle > M_PI && $angle <= (2*M_PI)/3):
					//decreasing horizontal coordinate
					$longcoordinate += $length * sin($angle);
					//decreasing vertical coordinate
					$latcoordinate += $length * cos($angle);
					//echo $longcoordinate . "<br />" . $latcoordinate . "<br />";
					break;
				case ($angle > (2*M_PI)/3):
					//decreasing horizontal coordinate
					$longcoordinate += $length * sin($angle);
					//increasing vertical coordinate
					$latcoordinate += $length * cos($angle);
					//echo $longcoordinate . "<br />" . $latcoordinate . "<br />";
					break;
			}
			
			$counter += 1;
		}
	}
	
	//function which retrieves all the events for a specific building
	public function retrieve ($buildingid)
	{
		//fetch the events from the DB
		$allEvents = $this->objDBEvents->listAll();
		
		foreach($allEvents as $thisEvent)
		{
			if ($buildingid == $thisEvent["buildingid"])
			{
				//if text description exists
				if ($thisEvent["description"] != NULL)
				{
					$text = 1;
				}
				else
				{
					$text = 0;
				}
				//if images exist
				if ($thisEvent["imagename"] != NULL)
				{
					$image = 1;
				}
				else
				{
					$image = 0;
				}
				//if videos exist
				if ($thisEvent["videoname"] != NULL)
				{
					$video = 1;
				}
				else
				{
					$video = 0;
				}
				
				echo trim($thisEvent["id"]) . "\n";
				echo trim($thisEvent["date"]) . ": " . trim($thisEvent["event"]) . $text . $image . $video . "\n";
			}
		}
	}
	
	public function retrievemedia($eventid, $num)
	{
		$allEvents = $this->objDBEvents->listAll();
		
		foreach($allEvents as $thisEvent)
		{
			if ($eventid == $thisEvent["id"])
			{
				switch ($num)
				{
					case 1:
						echo $thisEvent["description"];
						exit;
					case 2:
						echo $thisEvent["imagename"];
						exit;	
					case 3:
						echo $thisEvent["videoname"];
						exit;		
				}
			}
		}
	}
	
	
}
?>