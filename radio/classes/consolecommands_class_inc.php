<?php
class consolecommands extends object
{
	public function init()
	{

	}

	public function commands($command){
		if($command == "settings")
		{
			$station = $data_temp[1];
			$out = settings::get($station);
			return $out;
		}
	}
}
?>