
<?php

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
if (isset($_POST['name'])){
   echo $value = $_POST['name'];  
}else{
   echo "welcoenm".$value = "";
}

//if( $_REQUEST["name"] )
//{
//   $name = $_REQUEST['name'];
//   echo "Welcome ". $name . "<br>";
//}
//echo "Welcome ". $name;
?>

<html>
<head>
<title>the title</title>

   <script type="text/javascript" language="javascript">
   $(document).ready(function() {
      $("#driver").click(function(event){
          var name = $("#name").val();
          var somting ='something vaiable';
         var  url="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=ajaxnew";
     $.post(url, {"name":name} );
     
     $("#stage").append("<?php if (isset($_POST['name'])){
         echo $value = $_POST['name'];} ?>");
      });
   });
   </script>
</head>
<body>
   <p>Enter your name and click on the button:</p>
   <input type="input" id="name" size="40" /><br />
   <div id="stage" >
<?php

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
if (isset($_POST['name'])){
   echo $value = $_POST['name'];
}else{
   echo "welcoenm".$value = "";
}

//if( $_REQUEST["name"] )
//{
//   $name = $_REQUEST['name'];
//   echo "Welcome ". $name . "<br>";
//}
//echo "Welcome ". $name;
?>
   </div>
   <input type="button" id="driver" value="Show Result" />
</body>
</html>

