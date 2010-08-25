  <?php
   if (isset($_GET[action])){
	// Retrieve the GET parameters and executes the function
	  $funcName	 = $_GET[action];
	  $vars	  = $_GET[vars];
          echo $vars;
	  //$funcName($vars);
	 } else if (isset($_POST[action])){
	  // Retrieve the POST parameters and executes the function
	  $funcName	 = $_POST[action];
	$vars	  = $_POST[vars];
	//$funcName($vars);

	 } else {
	  // If there is no action in the URL, then do this
	echo "<INPUT NAME='btnSubmitAdmin' TYPE='button' ONCLICK='javascript:javaFunction()' VALUE='Call Javafunction() which redirects to a PHP function'>";
   }

   function phpFunction($v1){
	// makes an array from the passed variable
	// (note: $vars = 1 string while it used to be a javascript Array)
	// with explode you can make an array from 1 string. The seperator is a ,
	$varArray = explode(",", $v1);

	echo "<BR>function phpFunction<BR><BR>";
	echo "v1: $varArray[0] <BR>";
	echo "v2: $varArray[1]<BR>";

   }
  ?>