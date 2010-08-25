<HTML>
 <BODY>
  <?php
  $this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
   if (isset($_GET[action])){
	// Retrieve the GET parameters and executes the function
	 echo $funcName	 = $_GET[action] ,"<br>";
	 echo $vars	  = $_GET[vars];
	//  $funcName($vars);
	 } else if (isset($_POST[action])){
	  // Retrieve the POST parameters and executes the function
	  $funcName	 = $_POST[action];
	$vars	  = $_POST[vars];
	//$funcName($vars);

	 } else {
	  // If there is no action in the URL, then do this
	echo "<INPUT ID='driver' NAME='btnSubmitAdmin' TYPE='button' ' VALUE='Call Javafunction() which redirects to a PHP function'>";
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



<script type="text/javascript">
//    $(document).ready(function() {
//      $("#driver").click(function(event){
//	// In the varArray are all the variables you want to give with the function
	var varArray = new Array();
	varArray[0] = "checkit out";
	varArray[1] = "var2";
//$.post("<?php //echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=test&vars=varsd", function(data){
//   alert("Data Loaded: " + data);
// });
 
	// the url which you have to reload is this page, but you add an action to the GET- or POST-variable
	var url="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=test&vars="+varArray;

	// Opens the url in the same window
	   window.open(url, "_self");
	//  }
  </script>
 </BODY>
</HTML>
