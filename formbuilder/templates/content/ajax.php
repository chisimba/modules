
<?php
 $this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
if (isset($_POST['sendToValue'])){
	$value = $_POST['sendToValue'];
}else{
	$value = "";
}

echo json_encode(array("returnFromValue"=>"This is returned from PHP : ".$value));

?>

<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Ajax With Jquery</title>
		<style type="text/css" media="screen">
			body{
				background-color:#111;
				color:#999;
				font-family: Arial, "MS Trebuchet", sans-serif;
			}
			#display{
				padding-top:10px;
				color: white;
			}
		</style>


		<script type="text/javascript">

			$(document).ready(function(){
				$('#txtValue').keyup(function(){
					sendValue($(this).val());
				});
			});

			function sendValue(str){
                            var  url="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=ajax";
				$.post(url, { sendToValue: str },
				function(data){
				    $('#display').html(data.returnFromValue);
				}, "json");
			}

		</script>
	</head>


	<body>
		<p>On keyup this text box sends a request to PHP and a value is returned.</p>
		<label for="txtValue">Enter a value : </label><input type="text" name="txtValue" value="" id="txtValue">
		<div id="display"></div>
	</body>
</html>