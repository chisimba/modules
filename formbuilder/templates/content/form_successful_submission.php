<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    $formNumber = $this->getParam('formNumber',NULL);
        $currentSubmitNumber = $this->getParam('submitNumber',NULL);


$objDBFormSubmitResults = $this->getObject('dbformbuilder_submit_results','formbuilder');
$submitNumber = $objDBFormSubmitResults->getCurrentSubmitNumberofSubmitter($formNumber,$currentSubmitNumber);
if (isset ($mailandDatabaseSuccess))
{
       if ($mailandDatabaseSuccess == "Success")
    {
    echo "Form has been successfully submitted. Results have been sent via email and saved in the database for future viewing.<br>
        This is the $submitNumber submission of this form.<br> &nbsp;&nbsp;&nbsp;";
    }
    else
    {
        echo "Error. Results could not be sent via email. Results are only saved in the database. Please contact form designer.<br>&nbsp;&nbsp;&nbsp;";
    }
}
else if (isset($mailSuccess))
{
    if ($mailSuccess == "Success")
    {
    echo "Form has been successfully submitted. Results have been sent via email.<br> &nbsp;&nbsp;&nbsp;";

    }
    else
    {
        echo "Error. Results could not be sent via email. Please contact form designer.<br>&nbsp;&nbsp;&nbsp;";
    }

}
else
{
echo "Form has been successfully submitted. Results have been saved in the database.<br>
        This is the $submitNumber submission of this form.<br> &nbsp;&nbsp;&nbsp;";
}
 


?>
<div id="linkToFormBuilder">
    <?php
    $this->loadClass('button','htmlelements');
$this->loadClass('link','htmlelements');
$objButton=new button('returnToFormBuilder');
$objButton->setValue('Return To Form Builder');

  $mnglink = html_entity_decode($this->uri(array(
   'module'=>'formbuilder',
   'action'=>'listAllForms'
  )));


$objButton->setOnClick("parent.location='$mnglink'");
  //Set the link text/image
  $mnglink = $objButton->show();
  //Build the link
  echo $linkManage = $mnglink;

    ?>
</div>
<div id="mailSuccessBool">
    <?php
    if (isset ($mailandDatabaseSuccess))
{
       if ($mailandDatabaseSuccess == "Success")
       {
           echo 1;
       }
       else
       {
           echo 0;
       }
}
else if (isset($mailSuccess))
{
    if ($mailSuccess == "Success")
    {
        echo 1;
    }
    else
    {
        echo 0;
    }
}
else
{
    echo 1;
}
    ?>

</div>
<div id="publishFormParamters">
    <?php
     $objDBFormPublishingOptions = $this->getObject('dbformbuilder_publish_options','formbuilder');
   $formPublishingData =  $objDBFormPublishingOptions->getFormPublishingData($formNumber);
   $this->loadClass('hiddeninput','htmlelements');

   $formNumberHidden= new  hiddeninput("formNumberHidden", $formNumber);
echo $formNumberHidden->show();
$formPublishingOption= new  hiddeninput("publishingOption", $formPublishingData["0"]['publishoption']);
echo $formPublishingOption->show();
$specificSiteURL= new  hiddeninput("siteUrl", $formPublishingData["0"]['siteurl']);
echo $specificSiteURL->show();
$chisimbaModule= new  hiddeninput("chisimbaModule", $formPublishingData["0"]['chisimbamodule']);
echo $chisimbaModule->show();
$chisimbaAction= new  hiddeninput("chisimbaAction", $formPublishingData["0"]['chisimbaaction']);
echo $chisimbaAction->show();
$chisimbaParameters= new  hiddeninput("chisimbaParameters", $formPublishingData["0"]['chisimbaparameters']);
echo $chisimbaParameters->show();
$divertDelay= new  hiddeninput("divertDelay", $formPublishingData["0"]['chisimbadiverterdelay']);
echo $divertDelay->show();
    ?>
</div>
<div id="countDownTimer">

</div>
<script type="text/javascript">
          function fiveSecondCountdownTimer(publishingOption,siteUrl,delay)
          {
if (delay==5)
    {
          setTimeout(function() {

                 jQuery("#countDownTimer").html("<h3>Diverting to in 5 seconds</h3>");
                                                 setTimeout(function() {
                 jQuery("#countDownTimer").html("<h3>Diverting in 4 seconds</h3>");
                                          	  setTimeout(function() {
                 jQuery("#countDownTimer").html("<h3>Diverting in 3 seconds</h3>");
                                          	  setTimeout(function() {
                 jQuery("#countDownTimer").html("<h3>Diverting in 2 seconds</h3>");
                                          	  setTimeout(function() {
                 jQuery("#countDownTimer").html("<h3>Diverting in 1 seconds</h3>");
                                          	  setTimeout(function() {
                 jQuery("#countDownTimer").html("<h3>Diverting in 0 seconds</h3>");
                if (publishingOption == "simple")
                {
                window.location.replace(siteUrl);
                }
                else
                    {

    var myurlToGotToNextAction = "<?php echo html_entity_decode($this->uri(array('action'=>'successfulSubmissionDivertToNextAction','formNumber'=>$formNumber,'submitNumber'=>$currentSubmitNumber),'formbuilder')); ?>";

             window.location.replace(myurlToGotToNextAction);
      
                }
        }, 1000);
        }, 1000);
        }, 1000);
        }, 1000);
        }, 1000);
        }, 2000);
}
else if (delay==10)
{
          setTimeout(function() {

                 jQuery("#countDownTimer").html("<h3>Diverting to in 10 seconds</h3>");
                                                 setTimeout(function() {
                 jQuery("#countDownTimer").html("<h3>Diverting in 8 seconds</h3>");
                                          	  setTimeout(function() {
                 jQuery("#countDownTimer").html("<h3>Diverting in 6 seconds</h3>");
                                          	  setTimeout(function() {
                 jQuery("#countDownTimer").html("<h3>Diverting in 4 seconds</h3>");
                                          	  setTimeout(function() {
                 jQuery("#countDownTimer").html("<h3>Diverting in 2 seconds</h3>");
                                          	  setTimeout(function() {
                 jQuery("#countDownTimer").html("<h3>Diverting in 0 seconds</h3>");
                if (publishingOption == "simple")
                {
                window.location.replace(siteUrl);
                }
                else
                    {

    var myurlToGotToNextAction = "<?php echo html_entity_decode($this->uri(array('action'=>'successfulSubmissionDivertToNextAction','formNumber'=>$formNumber,'submitNumber'=>$currentSubmitNumber),'formbuilder')); ?>";

             window.location.replace(myurlToGotToNextAction);

                }
        }, 2000);
        }, 2000);
        }, 2000);
        }, 2000);
        }, 2000);
        }, 3000);

}
else
{
                if (publishingOption == "simple")
                {
                window.location.replace(siteUrl);
                }
                else
                    {

    var myurlToGotToNextAction = "<?php echo html_entity_decode($this->uri(array('action'=>'successfulSubmissionDivertToNextAction','formNumber'=>$formNumber,'submitNumber'=>$currentSubmitNumber),'formbuilder')); ?>";

             window.location.replace(myurlToGotToNextAction);

                }
}

//        
//var i=0;
//   setTimeout(function() {
//for (i=0;i<=5;i++)
//{
//
//   setTimeout(function() {
//jQuery("#countDownTimer").append(i);
//
//    },1000);
//
//}
//    },1000);
//jQuery.each([0,1,2,3,4,5], function(index, value) {
//
//  jQuery("#countDownTimer").append(index + ': ' + value);
//});

//jQuery.each('', function(i, val) {
//jQuery("#countDownTimer").append("<h3>Diverting to in 5 seconds</h3>"+val);
//});

//        var i=0;
//for (i=0;i<=delay;i++)
//{
//
//jQuery("#countDownTimer").html("<h3>Diverting to in 5 seconds</h3>"+i);
//
//}



          }

          jQuery(document).ready(function() {

                 jQuery("#linkToFormBuilder").hide();
    jQuery("#mailSuccessBool").hide();
       var mailSuccessBool =  jQuery("#mailSuccessBool").html();
                           var formNumber = jQuery("input:hidden[name=formNumberHidden]").val();
                 var publishingOption = jQuery("input:hidden[name=publishingOption]").val();
                 var siteUrl = jQuery("input:hidden[name=siteUrl]").val();
                                  var divertDelay = jQuery("input:hidden[name=divertDelay]").val();

                // var chisimbaModule = jQuery("input:hidden[name=chisimbaModule]").val();
               //  var chisimbaAction = jQuery("input:hidden[name=chisimbaAction]").val();
               //  var chisimbaParameters = jQuery("input:hidden[name=chisimbaParameters]").val();


if (mailSuccessBool == 0)
    {
                                        jQuery("#linkToFormBuilder").show();
    }
        else
            {
            if (publishingOption == "simple")
                     {
                         if (siteUrl == '')
                             {
                                     jQuery("#linkToFormBuilder").show();
                           //  
                             }
                             else
                                 {
fiveSecondCountdownTimer(publishingOption,siteUrl,divertDelay);
                                 }
                     }
                     else if (publishingOption == "advanced")
                         {
fiveSecondCountdownTimer(publishingOption,siteUrl,divertDelay);
                         }
            }

	});



</script>

