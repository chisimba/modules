<?php

echo '<h1>'.$this->objLanguage->languageText('mod_userregistration_registrationsuccess', 'userregistration', 'Your Message has been successufuly sent').'</h1>';

$objmsg = $this->getObject('usermsg', 'useradmin');
$objmsg->setUserArray($user);

echo $objmsg->show();

echo '<p>'.$this->objLanguage->languageText('mod_userregistration_emailsent', 'userregistration', 'An email has been sent to your email address with your details').':</p>';

echo '<ul>';
echo '<li><strong>'.$this->objLanguage->languageText('word_username', 'system').'</strong>: '.$user['msg'].'</li>';
//echo '<li><strong>'.$this->objLanguage->languageText('word_password', 'system').'</strong>: ***** </li>';
echo '</ul>';

echo '<br /><br />';
?>
