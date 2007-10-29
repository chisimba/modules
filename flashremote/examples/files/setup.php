<html>
<head>
<title>Instalation Remoting Connector Tutorial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<p><b>Instalation Script for using Remoting Connector tutorial</b><br>
</p>
</body>
<?php
include("./services/inc_sql.php");
###########################
# SQL functions
###########################
function mysql_table_exists($table , $db){ 
	$tables=mysql_list_tables($db); 
	while (list($temp)=mysql_fetch_array($tables)) { 
	  if($temp == $table) return 1;
	} 
	return 0; 
} 
function db_connect($user,$pwd, $host, $base) { 
	$connection = @mysql_connect($host,$user,$pwd);
	if (!$connection) {	    
	   echo "<BR><BR>Sorry, an error has occured !<br>";
	   if(DEBUG) echo mysql_errno().": ".mysql_error()."<br>"; 
	   exit;
	 }					   
	
	if (!mysql_select_db($base,$connection )) {	  	  
		echo "ERROR: Can't connect to the database<br>";
		if(DEBUG) echo mysql_errno().": ".mysql_error()."<br>"; 
		exit;
	}
	return $connection;	
}
function query($query) {
	$result=@mysql_query($query);
	if($result) {
		return $result;
	} else {
	 $message = "<p> SQL error !</p>";					
	 exit;
  }   
}	

//BEGINS INSTALLATION PROCCES
	$error = false;
	//Connecting to MySQL
	$connection = @mysql_connect(HOSTNAME,USERNAME,PASSWORD);
  	if (!$connection) {
    		  print("<b>Step 1:</b> MySQL connection                             FAILED<br>");
    		  print("Could not connect to MySQL-Server!<br>");
			  print("Check if MySQL is running properly.<br>");
   			  print("Check your HOSTNAME, USERNAME, PASSWORD and try again.<br>");
			  $error = true;
   			 } else {
   			  print("<b>Step 1</b>: MySQL connection                             OK!<br>");
  	}
	//Creating DB 
   if (!$error) {		
	   if (@mysql_select_db(DATABASE)) {
      		  print("<b>Step 2:</b> Catalog Database detected                  OK!<br>");
    		} else {
     		 print("<b>Step 2:</b> Creating Catalog Database...<br>"); 			       
     		 $query = "CREATE DATABASE ".DATABASE;
      		 $result = @mysql_query($query);
      		 if (!$result) {
      		 print("                         FAILED!<br>");        
        	 print("        MySQLError: ".mysql_errno().": ".mysql_error()."<br>");
             $error = true;
      		} else {
       		 print("<b>Step 2:</b> Catalog Database created                   OK!<br>");
      		}
   		} 
  }

  //Creating Table

  ////////////////////////////////////////////////////////////////////////////////////
  //// LIST TABLE
  ////////////////////////////////////////////////////////////////////////////////////  
  if (!$error) {      
    if (mysql_table_exists('list', DATABASE)) {
      print("<b>Step 3:</b> list-Table already created               OK!<br>");
    } else {
      print("<b>Step 3:</b> Creating list-Table...<br>");	
	  $query = "CREATE TABLE list (
				  PkProduct int(11) NOT NULL auto_increment,
				  Name varchar(255) NOT NULL default '',
				  Weight varchar(10) NOT NULL default '',
				  Price varchar(10) NOT NULL default '',
				  PRIMARY KEY (PkProduct)
				) TYPE=MyISAM;";    		   		
      $result = @mysql_query($query);
	  // REPLACE
	  if ($result) {
	  	//Family products
		$family[0] = "INSERT INTO list VALUES (1, 'Handmade spicy sausage', '2.00', '5.20');";
		$family[1] = "INSERT INTO list VALUES (2, '\'Gran Vela\' spicy sausage', '9.90', '7.10');";
		$family[2] = "INSERT INTO list VALUES (3, 'Spicy sausage with pepper', '6.40', '7.20');";
		$family[3] = "INSERT INTO list VALUES (4, '\'Red Label\' spicy sausage', '9.90', '7.60');";
		$family[4] = "INSERT INTO list VALUES (5, 'Home-made spicy sausage', '3.00', '5.30');";
		$family[5] = "INSERT INTO list VALUES (6, 'Home-made extra spicy sausage', '3.00', '4.50');";
		$family[6] = "INSERT INTO list VALUES (7, '\'Pamplona\' spicy sausage', '4.00', '6.00');";
		$family[7] = "INSERT INTO list VALUES (8, 'Extra quality spicy sausage 1 kg', '6.00', '8.20');";
		$family[8] = "INSERT INTO list VALUES (9, '\'Velita\' extra quality sausage', '3.00', '9.00');";
		$family[9] = "INSERT INTO list VALUES (10, '\'Cardenal\' extra quality sausage', '6.80', '8.65');";



		for($i=0; $i<sizeof($family); $i++) {
		        $query = $family[$i];
				$result = @mysql_query($query);
      			if (!$result) {      				 
       				 print("		   Couldn't insert product nr $i!<br>");
        			 print("        MySQLError: ".mysql_errno().": ".mysql_error()."<br>");
					 print($query);
        			 $error = true;		
               }
		}
	  }  	  
      if (!$result) {
        print("<b>Step 3:</b>                                              FAILED!<br>");
        print("        Could not create list Table!<br>");
        print("        MySQLError: ".mysql_errno().": ".mysql_error()."<br>");
        $error= true;		
      } else {
        print("<b>Step 3:</b> list-Table created                       OK!<br>");
      }
	 }
	}
  
  if (!$error) {
  			print("<br><br><br>Congratulations, Installation is complete !<br>");
  			//print("<a href=\"admin.php\">Launch Admin page</a>");
			} else {
			print("<br><br><br>Ooops, Installation is uncomplete !<br>");
			print("Please correct your inc_globals.php and 
			<a href=\"setup.php\">reload this page</a><br>");  
  
 }					

?>
</html>