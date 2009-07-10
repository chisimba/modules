<?php

class gift extends controller
{
	function init()
	{}
  
	function dispatch($action)
	{
		switch ($action)
		{
			default: return "home_tpl.php";
		}
	}
}
?>
