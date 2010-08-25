<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
//$testjs = '<script language="JavaScript" src="'.$this->getResourceUri('/js/test.js', 'formbuilder').'" type="text/javascript"></script>';
//$this->appendArrayVar('headerParams', $testjs);
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(1);
?>

   
<html>
  <head>

</head>
<body>
	 <p>Enter your name and click on the button:</p>
   <input type="input" id="name" size="40" /><br />
   <div id="stage" style="background-color:blue;">
          STAGE
   </div>
   <input type="button" id="driver" value="Show Result" />



     
<!--        <div id="formDropDown">
         <?php
         $a = $this->getObject('form_element_inserter', 'formbuilder');
 $this->loadClass('form','htmlelements');
  //Load the textinput class
  $this->loadClass('textinput','htmlelements');

   $objForm = new form('comments', '');
           $objTitle = new textinput('title', 'show this');
                   $objForm->addToForm($objTitle->show() , "<br />");
                echo   $objForm->show();
$cssLayout->setMiddleColumnContent($a->showFormElementInserterDropDown());
$cssLayout->setFooterContent("jfhsdkfhdsjklfhadklfhadjklh");
echo $cssLayout->show();
    $this->loadClass('radio','htmlelements');
//        $this->loadClass('dropdown','htmlelements');
//    $dd=&new dropdown('add_elements_to_form');
//$dd->addOption('Default','Insert a form entity...');   // will add a blank option
//$dd->addOption('radio','Radio Option');
//$dd->addOption('drop_down','Drop Down Option');
//$dd->setSelected('Default');
////$dd->addOnchange("$(document).ready(function() {
////  $('#mydiv').click(function() {
////	alert('Hello world!');
////  });
////});");
////$dd->onchangeScript;
//    $ddfor=&new dropdown('formelement');
//$ddfor->addOption('Default','vvvvvvvvvvvvv...');
//
//echo $dd->show();
?></div>
        <div id="radio">
        <?php
   // $this->loadClass('radio','htmlelements');
   // will add a blank option
//$dd1->addOption('radio','sdsadsdadasd');
//$dd1->addOption('drop_down','asdasdasdasds');
//$dd1->setSelected('Default');
//$dd->addOnchange("$(document).ready(function() {
//  $('#mydiv').click(function() {
//	alert('Hello world!');
//  });
//});");
//$dd->onchangeScript;
//echo $dd1->show();

 $objElement = new radio('sex_radio');
                $objElement->addOption('m','Male');
                          //      $objElement->addOption('m','Male');
                $objElement->addOption('f','Female');
                $objElement->addOption('n','Seaweed');
                $objElement->setSelected('f');
              //  echo $objElement->show().'<br>';
//echo 'testing pmodule';
?>

        </div>
        <div class="test">
            dfgdfgdfgdfgdfgdf
        </div>-->
    </body>
    </head>
</html>


<script type="text/javascript">
    $(document).ready(function() {
      $("#driver").click(function(event){
          var name = $("#name").val();
          $("#stage").load('test.php', {"name":name} );
      });
   });


//$(document).ready(function() {
//
// $("#form_comments").submit(function() {
//      if ($("#input_title").val() == "show") {
//        $("span").text($("#input_title").val()).show();
//        return true;
//      }
//      $("span").text("Not valid!").show().fadeOut(1000);
//      return false;
//    });
//
//
//
//
//////      if ($("input:first").val() == "a") {
//////        $("span").text("Validated...").show();
//////        return true;
//////      }
//////      $("span").text("Not valid!").show().fadeOut(1000);
//////      return false;
////    });
////
////
//////    $(".test").hide();
//////   //$("#input_add_elements_to_form").
//////   $('input').change(function() {
//////        $('input').val();
//////                $("#radio").prepend('<?php  //   echo  $objElement->show().'<br>'; ?>');
//            //  $("#radio").prepend( $('input').attr("title"));
//   }
//)
//  $("#input_add_form_elements_drop_down").change(function() {
//      var formDropDownOptions = $("#input_add_form_elements_drop_down");
//var form_option = formDropDownOptions.val();
//var url ='/controller.php';
////function(url,rmDropDownOptions);
////POST or GET
////var val = $GET(rmDropDownOptions):
////    $data = select * from tbl where id = val;
// //  return $data;
////alert(form_option);
//if (form_option =='radio')
//    {
//        //$(this).fadeOut(1000);
//        $("#radio").prepend('<?php     //echo  $objElement->show().'<br>'; ?>');
//        $("#radio").prepend(formDropDownOptions.val())
//        // $(this).remove();
//      // var q= $(this).clone();
//$(this).fadeOut(1000);
//       //  $(this).insertBefore(".test");
//
//
//    }
//if (form_option =='drop_down')
//    {
//      //  $(".test").show();
//       $(this).fadeOut(1000);
//
//         $("#radio").prepend('<?php// echo $objElement->show().'<br>';
?>//');
//             //    $("#formDropDown").replaceWith();
////                $("#formDropDown").replaceWith('<?php
   //    $dd=&new dropdown('add_elements_to_form');
//$dd->addOption('Default','Insert a form entity...');
//echo $dd->show();
              //  $b = $this->getObject('form_element_inserter', 'formbuilder');
//echo $b->showFormElementInserterDropDown().'<br>';
                ?>////');
//
//
////         $(this).before(
////$("#radio").prepend('<?php// echo $a->showFormElementInserterDropDown(); ?>');
//                //
////                $(this).hide();
//           // $(this).fadeIn(1000);
//
//              // $(this).insertAfter(this);
//               //$(this).show();
//    }
//
//     // $.print('Handler for .chande() called.')
//     //var a = 'fsdfdfdsf';
//	//alert(a);
//        function switchText()
//{
//	if ($(this).val() == $(this).attr('title'))
//		$(this).val('').removeClass('exampleText');
//	else if ($.trim($(this).val()) == '')
//		$(this).addClass('exampleText').val($(this).attr('title'));
//}
//
//$('input[type=text][title!=""]').each(function() {
//	if ($.trim($(this).val()) == '') $(this).val($(this).attr('title'));
//	if ($(this).val() == $(this).attr('title')) $(this).addClass('exampleText');
//}).focus(switchText).blur(switchText);
//
//$('form').submit(function() {
//	$(this).find('input[type=text][title!=""]').each(function() {
//		if ($(this).val() == $(this).attr('title')) $(this).val('');
//	});
//});
//
//  });
////});
    </script>