<?php

$objmsg = $this->getObject('userbizcard', 'useradmin');
$objmsg->setUserArray($user);

echo $objmsg->show();

echo '<p>'.$this->objLanguage->languageText('mod_libraryforms_commentsent', 'l;ibraryforms', '').':</p>';

echo '<ul>';
echo '<li><strong>'.$this->objLanguage->languageText('word_username', 'system').'</strong>: '.$user['username'].'</li>';

echo '</ul>';

echo '<br /><br />';
?>
