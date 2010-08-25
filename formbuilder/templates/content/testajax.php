<?php

$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
//if (isset($_POST['name'])){
//   echo $value = $_POST['name'];
//}else{
//   echo "welcoenm".$value = "";
//}

$this->loadClass('form','htmlelements');
  //Load the textinput class
  $this->loadClass('textinput','htmlelements');

   $objForm = new form('testajax', '');
           $objTxtInput = new textinput('test', 'This text should be passed');
            $objForm->addToForm($objTxtInput->show() , "<br />");
                echo   $objForm->show();

if( $_REQUEST["uname"] )
{
   $name = $_REQUEST['uname'];
   echo "Welcome ". $name . "<br>";
}
echo "Welcome ". $name;
?>
<div id="testing">

</div>
<script type="text/javascript">
$(document).ready(function() {
   
  $('#form_testajax').submit(function () {
var paramterbeingpassed = $('#input_test').val();
//$('#testing').after($('#input_test').val());
   // var htmldata = $(this).html();
 //   var data = 'uname=' + name;
 //   var datas="commentsfile 1";
  //  var selcolor = '<% $selcolor %>';
  //  var url="<?php //echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=helloajax";
    var url = '<?php $this->uri(array("action" => "testajax"), "formbuilder" );?>';
    //var url = '/hello.php';
 $("#testing").load(url, {"uname":paramterbeingpassed});
 });
//    $.ajax({
//      type:"POST",
//      url: url,
//      data: data,
//      success: function (html) {
//              var name = $('#teaser').html();
//        $('#message').html(html);
//      }
//    });

    return false;
  });


    </script>
