<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$cssLayout = $this->newObject('csslayout', 'htmlelements');
$content = '<fieldset>
<legend>Search for: </legend>
<form id="form1" name="form1" method="post" action="">
  <label>
  <input type="text" name="textfield" />
  </label>
  <input name="Button" type="button" value="Go" onclick="alert(\'I dont work!\');" />
</form>
</fieldset>



';


$content .= '<h3>Chapters:</h3><ol>';

foreach ($chapters as $chapter)
{
    $link = new link ($this->uri(array('action'=>'viewchapter', 'id'=>$chapter['chapterid'])));
    $link->link = $chapter['chaptertitle'];

    $content .= '<li>'.$link->show().'</li>';
}

$content .= '</ol>';

$cssLayout->setLeftColumnContent($content);
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();

?>