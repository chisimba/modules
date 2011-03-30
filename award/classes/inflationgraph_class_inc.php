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
        $names = array();
        $values = array();
        foreach ($this->Dataset1->_data as $i) {
            $names[] = $i['X'];
            $values[] = $i['Y'];
        }
        $params = new stdClass();
        $params->cht = 'bvs';
        $params->chs = $this->width.'x'.$this->height;
        $params->chd = 't:'.implode(',', $values);
        $params->chco = '9C0000';
        $params->chds = min($values).','.max($values);
        $params->chxt = 'x,y';
        $params->chxl = '0:|'.implode('|', $names).'|';
        $params->chbh = 40;
        header('Location: https://chart.googleapis.com/chart?'.http_build_query($params));
    }
}
?>
