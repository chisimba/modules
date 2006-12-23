<?php
	$this->loadClass('windowpop','htmlelements');
  $this->objFont=&new windowpop;
  $this->objFont->set('location','http://www.mozilla.org/projects/mathml/fonts');
  $this->objFont->set('linktext','Click to download fonts');
  $this->objFont->set('width','800');
  $this->objFont->set('height','600');
  $this->objFont->set('left','300');
  $this->objFont->set('top','400');
  $this->objFont->putJs(); 
  
  $this->objHelp=&new windowpop;
  $this->objHelp->set('location','http://en.wikipedia.org/wiki/MathML');
  $this->objHelp->set('linktext','Click here to learn more about MathML');
  $this->objHelp->set('width','800');
  $this->objHelp->set('height','600');
  $this->objHelp->set('left','300');
  $this->objHelp->set('top','400');
  $this->objHelp->putJs();
  
  print $ar."<br />".$this->objFont->show()."<br />".$this->objHelp->show();
  
  echo '<br /><br /><p>'.$image.'</p>';
  
  $this->objHelp=new windowpop;
  $this->objHelp->set('location','modules/mathml/resources/phpmathpublisher/doc/help.html');
  $this->objHelp->set('linktext','Click here to learn more about rendering MathML as an image');
  $this->objHelp->set('width','800');
  $this->objHelp->set('height','600');
  $this->objHelp->set('left','300');
  $this->objHelp->set('top','400');
  $this->objHelp->set('scrollbars', TRUE);
  $this->objHelp->set('resizable', TRUE);
  $this->objHelp->set('window_name', 'phpmathml');
  $this->objHelp->putJs();
  
  echo $this->objHelp->show();
  
  
  
  //http://www.w3.org/TR/MathML2/, http://en.wikipedia.org/wiki/MathML,
//http://www.mathmlcentral.com/format.html
?>


