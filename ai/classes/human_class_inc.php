<?php
class human
{
	public $strength;
	public $dexterity;
	public $resistance;
	public $intelligence;
	
	public function makeHuman($strength=0,$dexterity=0,$resistance=0,$intelligence=0) 
	{
		$this->strength = $strength;
		$this->dexterity = $dexterity;
		$this->resistance = $resistance;
		$this->intelligence = $intelligence;
		//return $this;
	}
	
	public function reset()
	{
		$this->strength = 0;
		$this->dexterity = 0;
		$this->resistance = 0;
		$this->intelligence = 0;
	}
	
	public function debug($x)
	{
		echo "<pre style='border: 1px solid black'>";
		print_r($x);
		echo '</pre>';
	}


	//This will be the mutation function. Just increments the property.
	public function inc($x)
	{
		echo "incrementing...<br />";
		return $x+1;
	}

	//This will be the crossover function. Is just the average of all properties.
	public function avg($a,$b)
	{
		echo "Averaging....<br />";
		return round(($a+$b)/2);
	}

	//This will be the fitness function. Is just the sum of all properties.
	public function total($obj)
	{
		echo "Totalling...<br />";
		//echo $this->strength + $this->dexterity + $this->resistance + $this->intelligence."<br/>";
		return $this->strength + $this->dexterity + $this->resistance + $this->intelligence;
	}
}
?>