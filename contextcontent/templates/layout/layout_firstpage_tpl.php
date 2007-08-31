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
    $showChapter = TRUE;
    
    if ($chapter['visibility'] == 'N') {
        $showChapter = FALSE;
    }
    
    if ($this->isValid('viewhiddencontent')) {
        $showChapter = TRUE;
    }
    
    if ($showChapter) {
        if ($chapter['pagecount'] == 0) {
            $content .= '<li title="Chapter has no content pages">'.$chapter['chaptertitle'].'</li>';
        } else {
            $link = new link ($this->uri(array('action'=>'viewchapter', 'id'=>$chapter['chapterid'])));
            $link->link = $chapter['chaptertitle'];
            $content .= '<li>'.$link->show().'</li>';
        }
    }
}

$content .= '</ol>';

$objDBContext = $this->getObject('dbcontext', 'context');

if($objDBContext->isInContext())
{
    
    $objModules = $this->getObject('modules', 'modulecatalogue');
    
    if ($objModules->checkIfRegistered('contextdesigner')) {
        $objContextUtils = & $this->getObject('utilities','context');
        $cm = $objContextUtils->getHiddenContextMenu('eventscalendar','show');
    } else {
        $cm = '';
    }
} else {
    $cm = '';
}

//add the blog block
$objBlocks =  $this->getObject('blocks', 'blocks');
$blog = $objBlocks->showBlock('latest', 'blog');

$cssLayout->setLeftColumnContent($content.$cm.$blog);

$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();


?>