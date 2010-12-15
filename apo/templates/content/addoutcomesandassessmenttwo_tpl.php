<?php
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');

$this->setVar('pageSuppressXML', TRUE);

$xtitle = $this->objLanguage->languageText('mod_wicid_newdocument', 'wicid', 'Section D: Outcomes and Assessments - Page Two');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

$legend = "Outcomes and Assessment";

$form = new form('outcomesandassessmenttwoform');

$table = $this->newObject('htmltable', 'htmlelements');

$radio = new radio ('d4');
$radio->addOption('1'," Identify and solve problems in which responses display that responsible decisions using critical and creative thinking have been made.");
$radio->addOption('2',"Work effectively with others as a member of a team, group, organisation, community.");
$radio->addOption('3',"Organise and manage oneself and one’s activities responsibly and effectively.");
$radio->addOption('4',"Collect, analyse, organise and critically evaluate information.");
$radio->addOption('5',"Communicate effectively using visual, mathematical and/or language skills in the modes of oral and/ or written presentation.");
$radio->addOption('6',"	Use science and technology effectively and critically, showing responsibility towards the environment and health of others.");
$radio->addOption('7',"Demonstrate an understanding of the world as a set of related systems by recognising that problem-solving contexts do not exist in isolation.");
$radio->addOption('8',"	In order to contribute to the full personal development of each learner and the social economic development of the society at large, it must be the intention underlying any programme of learning to make an individual aware of the importance of:
<br> - Reflecting on and exploring a variety of strategies to learn more effectively;</b>
<br> - Participating as responsible citizens in the life of local, national and global communities;
<br> - Being culturally and aesthetically sensitive across a range of social contexts;
<br> - Exploring education and career opportunities; and
<br> - Developing entrepreneurial opportunities.</b>");
$radio->setSelected('1');
$radio->setBreakSpace('</p><p>');
$table->startRow();
$table->addCell("D.4 Specify the critical cross-field outcomes (CCFOs) integrated into the course/unit using the list provided.:");
$table->addCell($radio->show());
$table->endRow();


$efs = new fieldset();

$efs->setLegend('Errors');
if (count($errormessages) > 0) {

    $errorstr = '<ul>';

    foreach ($errormessages as $errormessage) {
        $errorstr.='<li class="error">' . $errormessage . '<li/>';
    }
    $errorstr.='</li>';
    $efs->addContent($errorstr);
    $form->addToForm($efs);
}

$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('next', $this->objLanguage->languageText('word_next'));
$uri = $this->uri(array('action' => 'addoutcomesandassessmentthree'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm('<br/>' .$button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'addOutcomesAssessment'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());


echo $form->show();

?>
