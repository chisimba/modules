/* javascript */
jQuery(document).ready(function() {
       
   jQuery("#input_D5_5").blur(function() {
       // check all the input fields from 1 to 8
       var inp1 = parseInt(jQuery("#input_D5_1").val()),
           inp2 = parseInt(jQuery("#input_D5_2").val()),
           inp3 = parseInt(jQuery("#input_D5_3").val()),
           inp4 = parseInt(jQuery("#input_D5_4").val()),
           inp5 = parseInt(jQuery("#input_D5_5").val());

       if(isNaN(inp1) || inp1.length == 0) {
           alert("Please enter a number for a.");
           jQuery("#input_D5_1").focus();
       }
       else if (isNaN(inp2) || inp2.length == 0) {
           alert("Please enter a number for b.");
           jQuery("#input_D5_2").focus();

       }
       else if (isNaN(inp3) || inp3.length == 0) {
           alert("Please enter a number for c.");
           jQuery("#input_D5_3").focus();

       }
       else if (isNaN(inp4) || inp4.length == 0) {
           alert("Please enter a number for d.");
           jQuery("#input_D5_4").focus();

       }
       else if (isNaN(inp5) || inp5.length == 0) {
           alert("Please enter a number for e.");
           jQuery("#input_D5_5").focus();

       }
       else {
           // do the calculations
           var totalContactTime = inp1*(inp2 + inp3 + inp4 + inp5);
           jQuery("#totContTime").text(totalContactTime);
       }

   });

   jQuery("#input_D5_6").blur(function() {
       var inp1 = parseInt(jQuery("#input_D5_1").val()),
           inp2 = parseInt(jQuery("#input_D5_2").val()),
           inp6 = parseInt(jQuery("#input_D5_6").val());

      if(isNaN(inp1) || inp1.length == 0) {
           alert("Please enter a number for a.");
           jQuery("#input_D5_1").focus();
       }
       else if (isNaN(inp2) || inp2.length == 0) {
           alert("Please enter a number for b.");
           jQuery("#input_D5_2").focus();

       }
       else if (isNaN(inp6) || inp6.length == 0) {
           alert("Please enter a number for f.");
           jQuery("#input_D5_6").focus();

       }
       else {
           // calculate the total notional study
           var totNotStudy = inp1*inp2*inp6;
           jQuery("#totNotTime").text(totNotStudy);
       }
   });

   jQuery("#input_D5_8").blur(function() {
       var inp7 = parseFloat(jQuery("#input_D5_7").val()),
           inp8 = parseFloat(jQuery("#input_D5_8").val());

      if(isNaN(inp7) || inp7.length == 0) {
           alert("Please enter a number for g.");
           jQuery("#input_D5_7").focus();
       }
       else if ( isNaN(inp8) || inp8.length == 0) {
           alert("Please enter a number for h.");
           jQuery("#input_D5_8").focus();

       }
       else {
           // calculate the total notional study
           var totExamTime = inp7*inp8;
           jQuery("#totExamTime").text(totExamTime);
       }
   });

   jQuery("#input_D5_9").blur(function(){
       // get the total notional hours (excluding studying)
       var totNotHrs1 = parseInt(jQuery("#totNotTime").text()),
           totNotHrs2 = parseInt(jQuery("#input_D5_9").val());

       if(isNaN(totNotHrs1) || totNotHrs1.length == 0) {
           alert("Total Notional Hours excluding exams have not yet been calculated.");
       }
       else if(isNaN(totNotHrs2) || totNotHrs2.length == 0) {
           alert("Please enter a number for i.");
           jQuery("#input_D5_9").focus();
       }
       else {
           // calculate the total notional hours and display on form
           jQuery("#totNotTime2").text(totNotHrs1 + totNotHrs2);
       }
   });
});

