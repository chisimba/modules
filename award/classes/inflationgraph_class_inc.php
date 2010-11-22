<?php
// security check - must be included in all scripts
if ( !$GLOBALS['kewl_entry_point_run'] ) {
die( "You cannot view this page directly" );
}
/**
*
* This class is a basic wrapper of the PEAR Image_Graph class,
* used specifically to generate inflation graphs for LRS
*
* @package Award
* @copyright UWC 2006
* @license GNU/GPL
* @author Nic Appleby
* @version $id$
*/

class inflationgraph extends object {

	var $Graph;
	var $Font;
	var $Dataset1;
	var $title = 'Title';
	var $xAxis = 'X axis';
	var $yAxis = 'Y axis';
	var $label = 'Label';



	/**
	 * Constructor method to set up Graph object
	 *
	 */
	function init() {
		$this->objConfig = $this->getObject('altconfig','config');
		if (!include_once 'Image/Graph.php') {
			die('Could not find PEAR extension Image_Graph, please install.');
		}
		$this->width = 450;
		$this->height = 300;
		//ini_set('error_reporting','E_ALL & ~E_NOTICE');
	}

	/**
	 * method to set labels for various graph attributes
	 *
	 * @param string $title The graph Title
	 * @param string $xAxis The label for the x axis
	 * @param string $yAxis The label for the y axis
	 * @param stringe $data The label for the actual data
	 */
	function setLabels($title,$xAxis,$yAxis,$data) {
		$this->xAxis = $xAxis;
		$this->yAxis = $yAxis;
		$this->title = $title;
		$this->label = $data;
	}

	/**
	 * Method to add the data to the graph
	 *
	 * @param array $arrData array of co-ordinate pairs to plot on the graph
	 */
	function addData($arrData) {
		//$this->Dataset1 =& Image_Graph::factory('random', array(8, 10, 100, true));

		$this->Dataset1 =& Image_Graph::factory('dataset');
		foreach ($arrData as $pair) {
			$this->Dataset1->addPoint($pair['x'], $pair['y']);
		}
	}

	/**
	 * Method to generate and show the graph
	 *
	 */
	function show() {
		$this->Graph = Image_Graph::factory('graph', array($this->width,$this->height));
		// add a TrueType font
		$this->Font = $this->Graph->addNew('font', $this->getResourceUri('times.ttf'));
		// set the font size
		$this->Font->setSize(9);		
		$this->Graph->setFont($this->Font);
		$this->Graph->add(Image_Graph::vertical(Image_Graph::factory('title', array($this->title, 8)),
							Image_Graph::vertical($this->Plotarea = Image_Graph::factory('plotarea'),
							$Legend = Image_Graph::factory('legend'),90),5));


		// make the legend use the plotarea (or implicitly it's plots)
		$Legend->setPlotarea($this->Plotarea);

		// create a grid and assign it to the secondary Y axis
		$GridY2 =& $this->Plotarea->addNew('bar_grid', IMAGE_GRAPH_AXIS_Y_SECONDARY);
		$GridY2->setFillStyle(Image_Graph::factory('gradient',array(IMAGE_GRAPH_GRAD_VERTICAL, 'white', 'lightgrey')));

		// create a bar plot using a the specified dataset
		$Plot1 =& $this->Plotarea->addNew('bar', &$this->Dataset1);
		$Plot1->setLineColor('red');
		$Plot1->setFillColor('red@0.2');

		// set the titles for the plots
		$Plot1->setTitle($this->label,9);

		$AxisX = $this->Plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
		$AxisX->setTitle($this->xAxis,9);
		$AxisY = $this->Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
		$AxisY->setTitle($this->yAxis,9);

		// output the Graph
		$this->Graph->done();
		//ini_restore('error_reporting');
	}
}
?>
