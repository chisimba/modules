<?php
class stations extends object
{

	public $parth;


	 /**
    * Constructor for the class
    */
    public function init()
    {
        $this->parth = $this->getResourcePath('includes/station','radio');
        $this->objLanguage = $this->getObject('language','language');

    }


	public function get()
	{
		$a = null;
		$data = null;
		if ($handle = opendir($this->parth)) {

		    while (false !== ($file = readdir($handle))) {
		        if ($file != "." && $file != "..") {
		           $data .= $file."&";
		           $a = "1";
		        }
		    }
		    closedir($handle);
		}
		if($a == ""){$data = "test&";}
		return $data;
	}



	public function default_s($station = "0")
	{
		$data = null;
		if($station == "0" or $station == "")
		{

			$once = "0";
			if ($handle = opendir($this->parth)) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						if($once == "0")
						{
							$data = $file;
							$once = "1";
						}
					}
				}
				closedir($handle);
			}
			if($data == ""){$data = "test";}
			return $data;
		}
		return $station;
	}

	public function del($station = "0")
	{
		if($station != "0"){
			if (is_dir($this->parth."/".$station)) {
				if ($handle = opendir($this->parth."/".$station)) {
					while (false !== ($file = readdir($handle))) {
						if ($file != "." && $file != "..") {
							unlink($this->parth."/".$station."/".$file);
						}
					}
				}
				closedir($handle);
				rmdir($this->parth."/".$station);
			}
		}
	}


	public function login($station = "0", $uname = "0", $password = "0")
	{

		if (is_dir($this->parth."/".$station)) {
			if (file_exists($this->parth."/".$station."/".$uname.".data")) {
				$fp = fopen($this->parth."/".$station."/".$uname.".data", "rb");
				$passwd = fread($fp,filesize($this->parth."/".$station."/".$uname.".data"));
				fclose($fp);
				$password = md5("$password");
				if($passwd  == $password){return true;}

			}
			return false;
		}
	}

	public function add_admin($station = "0", $uname = "0", $password = "")
	{
		if (is_dir($this->parth."/".$station)) {
			if (!file_exists($this->parth."/".$station."/".$uname.".data")) {
				$fp = fopen($this->parth."/".$station."/".$uname.".data", "w+b");
				$password = md5("$password");
				fwrite($fp, $password);
				fclose($fp);
				return true;
			}
			return false;
		}
	}

	public function del_admin($station = "0", $uname = "0")
	{
		if (file_exists($this->parth."/".$station."/".$uname.".data")) {
			unlink($this->parth."/".$station."/".$uname.".data");
			return true;
		}
		return false;
	}


	public function get_admins()
	{

		if ($handle = opendir($this->parth)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if($once == "")
					{
						$data .= $file;
						$once = "1";
					}else{
						$data .= ";".$file;
					}
					if ($handle2 = opendir($this->parth.'/'.$file)) {
						while (false !== ($file2 = readdir($handle2))) {
							if ($file2 != "." && $file2 != "..") {
								$data .= "&".$file2;
							}
						}
					}
					closedir($handle2);
				}
			}
		}
		closedir($handle);
		return $data;
	}

}
?>