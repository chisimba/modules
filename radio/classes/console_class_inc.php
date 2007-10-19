<?php
class console extends object
{
	public $log;

	public $users;

	public $ban;

	public $console_commands;

	/**
    * Constructor for the class
    */
    public function init()
    {
        $this->log = $this->getResourcePath('includes/log/','radio');
        $this->users = $this->getResourcePath('includes/users','radio');
        $this->ban = $this->getResourcePath('includes/ban','radio');
        $this->console_commands = $this->newObject('consolecommands','radio');

    }

	public function start_up($station = "0")
	{
		$data = "0";
		if($station != "0")
		{
		 if (file_exists($this->log.$station.".data")) {
		 	$fp = fopen($this->log.$station.".data", "rb");
		 	$data = fread($fp, filesize($this->log.$station.".data"));
		 	fclose($fp);
			 }else{return "0";}
		}
		return $data;
	}

	public function add_log($station = "0",$data_q = "0")
	{
		$time_stamp = time();
		if($station != "0")
		{

		 if (file_exists($this->log.$station.".data")) {
		 	$fp = fopen($this->log.$station.".data", "rb");
		 	$data = fread($fp, filesize($this->log.$station.".data"));
		 	fclose($fp);
		 	$data .= $data.$data_q.";".$time_stamp."&";
		 	$fp = fopen($this->log.$station.".data", "w+b");
		 	fwrite($fp,$data);
		 	fclose($fp);
		 	}else
		 	{
			$data = $data_q.";".$time_stamp."&";
		 	$fp = fopen($this->log.$station.".data", "w+b");
		 	fwrite($fp,$data);
		 	fclose($fp);
			}
		}
	}

	public function add_online_user($station = "0")
	{

		$ip = $_SERVER["REMOTE_ADDR"];
		$time = time() + 300;
		if($station != "0" && $ip != "0")
		{
			if ($handle = opendir($this->users)) {
	    		while (false !== ($file = readdir($handle))) {
	        		if ($file != "." && $file != "..") {
	        			if ($handle2 = opendir($this->users.'/'.$file)) {
	    					while (false !== ($file2 = readdir($handle2))) {
	        					if ($file2 != "." && $file2 != "..") {
	        						unlink($this->users.'/'.$file."/".$file2);
	        	  				}
	    					}
	    					closedir($handle2);
						}
	        	  	}
	    		}
	    		closedir($handle);
				}
			if (is_dir($this->users.'/'.$ip)) {
				$fp = fopen($this->users.'/'.$ip."/".$station.".".$time.".data","w+b");
				fclose($fp);
			}else{
				mkdir($this->users.'/'.$ip, 0777);
				$fp = fopen($this->users.'/'.$ip."/".$station.".".$time.".data","w+b");
				fclose($fp);
			}
		}
	}


	public function update_online_users()
	{
		$time_end = time() + 300;
		if ($handle = opendir($this->users)) {
	    	while (false !== ($file = readdir($handle))) {
	        	if ($file != "." && $file != "..") {
	            	if ($handle2 = opendir($this->users.'/'.$file)) {
	    				while (false !== ($file2 = readdir($handle2))) {
	        				if ($file2 != "." && $file2 != "..") {
	          					$fp = fopen($this->users.'/'.$file."/".$file2, "rb");
			  					$time_start = fread($fp,filesize($this->users.'/'.$file."/".$file2));
			  					fclose($fp);
			  						if($time_end <= $time_start){
			  							unlink($this->users.'/'.$file."/".$file2);
			  						}
	        				}
	    				}
	    				closedir($handle2);
					}
	        	}
	    	}
	    	closedir($handle);
		}
	}

	public function ban_check()
	{
			if ($handle = opendir($this->ban)) {
	    		while (false !== ($file = readdir($handle))) {
	        		if ($file != "." && $file != "..") {
	            		if($file == $_SERVER["REMOTE_ADDR"].".data")
	            		{
							exit();
						}
	           }
	        }
	    }
	    closedir($handle);

	}

	public function ban_check2($ip = "0")
	{
			if ($handle = opendir($this->ban)) {
	    		while (false !== ($file = readdir($handle))) {
	        		if ($file != "." && $file != "..") {
	            		if($file == $ip.".data")
	            		{
							return true;
						}
	           		}
	        	}
	    	}
	    	closedir($handle);
	    	return false;

	}
	public function add_to_ban($ip = "0")
	{
		if($ip != "0" && $ip != "")
		{
		$fp = fopen($this->ban.$ip.".data", "w+b");
		fclose($fp);
		}
	}

	public function remove_ban($ip)
	{
		if(file_exists($this->ban.$ip.".data"))
		{
			unlink($this->ban.$ip.".data");
		}
	}

	public function commands($command)
	{
		$data =	$command;
		if($data != ""){
			$data_temp = explode(" ",$data);
			$options_teller = count($data_temp);
			$command = $data_temp[0];
			$result =  $this->console_commands->commands($command);
			return $result;
		}
	}

}
?>