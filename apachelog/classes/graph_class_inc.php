<?php
ini_set("error_reporting", E_ALL & ~E_NOTICE & ~E_WARNING);
require_once('Image/Graph.php');

class graph extends object
{
	public $graph;
	public $width;
	public $height;
	public $plotarea;
	public $plot;
	public $dataset;

	public function init()
	{

	}

	public function setup($width, $height)
	{
		$this->graph =& Image_Graph::factory('graph', array($width, $height));
		$this->plotarea =& $this->graph->addNew('plotarea');
		$this->dataset =& Image_Graph::factory('dataset');
	}

	public function addSimpleData($data, $param, $value)
	{
		//data should be array('june' => '1000')
		$this->dataset->addPoint($data[$param], $data[$value]);
	}

	public function addPlotArea($charttype = 'bar', $linecolour = 'gray', $fillcolour = 'blue@0.2')
	{
		$this->plot =& $this->plotarea->addNew($charttype, &$this->dataset);
		// set a line color
		$this->plot->setLineColor($linecolour);
		// set a standard fill style
		$this->plot->setFillColor($fillcolour);
	}

	public function show($filename)
	{
		return $this->graph->done(array('filename' => $filename));
	}
}
?>