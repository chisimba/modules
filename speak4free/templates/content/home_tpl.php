<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
<?php
$this->loadclass('link','htmlelements');
$minjs = '<script src="'.$this->getResourceUri('jquery/1.3.2/jquery-1.3.2.min.js','htmlelements').'" type="text/javascript"></script>';

$mainjs = '<script src="'.$this->getResourceUri('js/jquery.cycle.js').'" type="text/javascript"></script>';
$this->appendArrayVar('headerParams', $minjs);
$this->appendArrayVar('headerParams', $mainjs);

$style="
<style type=\"text/css\">
.slideshow { height: 536px; width: 422px; margin: auto }
.slideshow img { padding: 15px; background-color: transparent; }
</style>
";

$this->appendArrayVar('headerParams', $style);

$cycle="
<script type=\"text/javascript\">
jQuery(document).ready(function() {
    jQuery('.slideshow').cycle({
		fx: 'fade' // choose your transition type, ex: fade, scrollUp, shuffle, etc...
	});
});
</script>

";


$this->appendArrayVar('headerParams', $cycle);

$objLogin =  $this->getObject('speak4freeloginInterface');

$slideshow='
        <div class="login">'.$objLogin->renderLoginBox().'</div>
	<div class="slideshow">
		<img src="'.$this->getResourceUri("images/globes/globe1.png").'" width="536" height="422" />
		<img src="'.$this->getResourceUri("images/globes/globe2.png").'" width="536" height="422" />
		<img src="'.$this->getResourceUri("images/globes/globe3.png").'" width="536" height="422" />
		<img src="'.$this->getResourceUri("images/globes/globe4.png").'" width="536" height="422" />
		<img src="'.$this->getResourceUri("images/globes/globe5.png").'" width="536" height="422" />

	</div>

';
// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$twobtalent=new link($this->uri(array('action'=>'twobtalent')));
$twobtalent->extra=' id= "twobtalent"';
$twobtalent->link='<span>two be talent</span>';

$aboutus=new link($this->uri(array('action'=>'aboutus')));
$aboutus->extra=' id= "aboutus"';
$aboutus->link='<span>about us</span>';

$nav='<br/><br/><ul>';
$nav.='<li>'.$twobtalent->show().'</li>';
$nav.='<li>'.$aboutus->show().'</li>';
$nav.="</ul>";
$leftColumnContent=$nav;
$cssLayout->setLeftColumnContent($leftColumnContent);
$rightSideColumn =  $slideshow;
$cssLayout->setMiddleColumnContent($rightSideColumn);
echo $cssLayout->show();

?>
