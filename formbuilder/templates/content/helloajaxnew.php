<body>
    <div id="bottomdiv">
        <?php
$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
//$name = $_POST['uname'];
//echo "Welcome ". $name;
$this->loadClass('radio','htmlelements');
      $objElement = new radio('sex_radio');

                          //      $objElement->addOption('m','Male');
   
if( $_REQUEST["uname"] )
{

   $name = $_REQUEST['uname'];
   $objElement->addOption('m',$name);
   echo $objElement->show();
}

?>
    </div>
<!--    <form id="testform">-->
    <label>Enter your Name</label>
    <input type="text" name="uname" class="uname"/>
    <input type="submit" id="submit"/>
<!--  </form>-->
  <div id="tempdiv"></div>
    <div id="message"></div>
</body>

   <script type="text/javascript" language="javascript">
//$(document).ready(function() {
//  $('#submit').click(function () {
//    var name = $('.uname').val();
//    var data = 'uname=' + name;
//    //var url="<?php //echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=helloajaxnew";
//    var myurl = "<?php //$this->uri(array("action" => "helloajaxnew"), "formbuilder" );?>";
//    $("#message").after(data);
//  //   $("#message").load(myurl, {"uname":data});
//          //   $("#message").load(url, {"uname":name} );
////$.post(myurl, data, function (html) {
////$("#topdiv").html();
//        $('#tempdiv').html(html);
//        //$('#message').).hide(1000);
//        //     var a=$('#tempdiv #topdiv').html();
//
//             //$('#tempdiv #topdiv').clone().insertBefore('#tempdiv');
//             //   $('#tempdiv').replaceWith('<div id="tempdiv"></div>');
//               //$('#tempdiv').empty();
//                // $(this).clone().insertAfter(a);
//                 //$(this).remove();
//      })
////   $.ajax({
////      type:"POST",
////      url:url,
////      data: data,
////      success: function (html) {
////$("#topdiv").html();
////        $('#message').html(html);
////      }
////    });
////    return false;
////  });
$(document).ready(function() {
  $('#submit').click(function () {
    var name = $('.uname').val();
   // var data = 'uname=' + name;
    var data = {"uname": name}
    var myurl="<?php echo $_SERVER[PHP_SELF];?>?module=formbuilder&action=hello";
  //  var myurl = <?php// echo $this->uri(array("action" => "helloajaxnew"), "formbuilder" );?>;
  //   var myurl="<?php //echo $_SERVER[PHP_SELF].$this->uri(array("action" => "helloajaxnew"), "formbuilder" );?>";
    $.ajax({
      type:"POST",
      url:myurl,
      data: data,
      success: function (html) {
        $('#tempdiv').html(html);
        var a=$('#tempdiv #topdiv').html();
        $('#tempdiv').empty();
        $('#message').append(a);
        //.clone().insertBefore('#tempdiv');
       // 
      }
    });
    return false;
  });
});

		</script>