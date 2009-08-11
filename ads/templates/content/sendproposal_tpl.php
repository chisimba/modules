<?php

   $submission = $this->objDocumentStore->sendProposal($lname, $fname, $email, $phone, $this->id);
   //$submission = true;
   echo "<div id=\"submitResponse\">";
   if($submission) {
       echo "Your details have been submitted successfully";
   }
   else {
       echo "There was a problem submitting your information. Please try again.";
   }
   echo "</div>";
?>