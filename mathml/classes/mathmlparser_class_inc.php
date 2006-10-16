<?php
include("mathmlsymbols_class_inc.php");
include('asciimathphp_class_inc.php');

/**
 * Wrapper class for mathml
 * @author Paul Scott
 * @package mathml
 */
class mathmlparser //extends object
{
	var $sym;
	var $ascii_math;
	
	/**
	 * Standard init function
	 */
	function init()
	{
		$this->sym = new mathmlsymbols();
		$this->ascii_math =& new asciimathphp($this->sym->symbols()); 
		
	}
	
	/**
	 * Method to invoke the mathml parser and return a string
	 * @param string $expr
	 * @return string markup
	 */
	function mathmlreturn($expr)
	{
		$sym = new mathmlsymbols();
		$ascii_math =& new asciimathphp($sym->symbols());
		$ascii_math->setExpr($expr); 
		$ascii_math->genMathML();
		return $ascii_math->getMathML();
	}
}
?>