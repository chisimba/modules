<?php
  //echo ", =" . $_FILES["File"]["type"] . "  <br>\n";

  //if ($_FILES["File"]["type"] != "audio/x-gsm")
  //{
	//// Invalid type -- delete the file and quit
	//die("' is NOT a GSM AUDIO file");
  //}  else
  //{
     echo htmlentities($_FILES["File"]["name"]);
     if (move_uploaded_file($_FILES["File"]["tmp_name"],
			    getcwd()."/audio/".$_FILES["File"]["name"]))
		echo " has been uploaded to: ";
     else
		echo " **FAILED** to upload to: " ;

	  echo getcwd()."/audio<br>\n";
  //}
?>
