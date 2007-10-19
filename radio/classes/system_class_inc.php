<?php

class system extends object
{
	public $playlist;

	public $stream;

	public $settings;

	public $stations;

	public $console;

	public $stats;

	public function int(){
		clearstatcache();
		#error_reporting(6143);
		error_reporting(0);
		$version = "1";
		$this->stream = new stream;
		$this->playlist = new playlist;
		$this->stations = new stations;
		$this->settings = new settings;
		$this->console = new console;
		$this->stats = new stats;
		$this->console->ban_check();
		$station = getParam('station');
		$key = getParam('debug');
		$station = $this->stations->default_s($station);
		$playlist_name = $this->playlist->get_playlist_list($station);
		$settings_data = $this->settings->get($station);
		$settings_data_temp = explode("&", $settings_data);
		$header_title = $settings_data_temp[0];
		$header_genre = $settings_data_temp[1];
		$header_bitrate = $stats->bitrate($station, $playlist_name);
		if ($header_bitrate == "0" or $header_bitrate == "")
		{
		$header_bitrate = $settings_data_temp[2];
		}
		$header_site = $settings_data_temp[3];
		$debugkey = $settings_data_temp[4];
		$site_temp = explode("/", $_SERVER["PHP_SELF"]);
		$laast_one = count($site_temp) -1;
		$between = str_replace($site_temp[$laast_one], "", $_SERVER["PHP_SELF"]);
		$station_site = "http://".$_SERVER["HTTP_HOST"].$between;
	}
	public function debug($key, $key2)
	{
			if($key != "" && $key2 != "" && $key == $key2)
			{
				return true;
			}else {return false;}
			if (debug($key, $debugkey))
			{
			$debug = true;
			}else {$debug = false;}
	}


}
?>
