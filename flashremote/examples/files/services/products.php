<?php
class products{
	//change to match your needs
	var $dbhost = "localhost";
	var $dbname = "Catalog";
	var $dbuser = "root";
	var $dbpass = "";

      function products(){
        $this->methodTable = array(
            "getItems" => array(
              "description" => "Returns products table",
              "access" => "remote" // available values are private, public, remote
              //"arguments" => array ("message")
            ),
            "setItems" => array(
              "description" => "Echoes the passed argument back to Flash (no need to set the return type)",
              "access" => "remote", // available values are private, public, remote
              "arguments" => array ("rs")
            )
        );

			// Initialize db connection
			$this->conn = mysql_pconnect($this->dbhost, $this->dbuser, $this->dbpass);
			mysql_select_db ($this->dbname);

      }
      function getItems(){
        return mysql_query("select * from list");
      }
	  function setItems($rs){
	   $error = false;
		for($i=0; $i<sizeof($rs); $i++){
			$result = mysql_query("replace into list values('".$rs[$i]['PkProduct']."', '".$rs[$i]['Name']."', '".$rs[$i]['Weight']."', '".$rs[$i]['Price']."')");
			if(!$result) $error = true;
		}
		if(!$error) return "Ok"; else return "Error";
	  }
}
?>