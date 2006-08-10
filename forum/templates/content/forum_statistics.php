<?
// Load HTML Classes as Needed
$this->loadClass('htmlheading','htmlelements');
$this->loadClass('link', 'htmlelements');

$icon =& $this->getObject('geticon', 'htmlelements');

// Generation of User Stats
/*
* To generate the user stats, we first create an array where the user id will be the key
* Next we add users, but not their posts. It first goes through the list of posters - they are given the status of unknown
* Next we add guests. If the user is listed, their status gets updated to guest. New Users are added, and this continues for students and lecturers.
* This way we can also see whether there are people who have not contributed at all. All Users get zero as a default for posts, topics and tangents
*
* After this, we update the number of posts, topics and tangents they have made.
*/
$userStats = array();

$objManageGroups=& $this->getObject('managegroups', 'contextgroups');

function createUserInArray(&$array, $userId, $name, $role)
{
    $array[$userId]['name'] = $name;
    $array[$userId]['role'] = $role;
    $array[$userId]['posts'] = 0;
    $array[$userId]['topics'] = 0;
    $array[$userId]['tangents'] = 0;
    $array[$userId]['otherpostsrated'] = 0;
    $array[$userId]['otherpostssumrated'] = 0;
    $array[$userId]['otherpostsmaxrated'] = 0;
    $array[$userId]['otherpostsminrated'] = 0;
    $array[$userId]['selfpostsrated'] = 0;
    $array[$userId]['selfpostssumrated'] = 0;
    $array[$userId]['selfpostsmaxrated'] = 0;
    $array[$userId]['selfpostsminrated'] = 0;
    $array[$userId]['wordcountposts'] = 0;
    $array[$userId]['wordcountsum'] = 0;
    $array[$userId]['wordcountmax'] = 0;
    $array[$userId]['wordcountmin'] = 0;
}

foreach ($posterStats as $posterStat)
{
    createUserInArray($userStats, $posterStat['userId'], $posterStat['firstName'].' '.$posterStat['surname'], 'unknown');
}

// Fis
$guests = $objManageGroups->contextUsers('Guests', $contextCode);
foreach ($guests as $guest)
{
    createUserInArray($userStats, $guest['userId'], $guest['fullName'], 'guest');
}

$students = $objManageGroups->contextUsers('Students', $contextCode);
foreach ($students as $student)
{
    createUserInArray($userStats, $student['userId'], $student['fullName'], 'student');
}

$lecturers = $objManageGroups->contextUsers('Lecturers', $contextCode);
foreach ($lecturers as $lecturer)
{
    createUserInArray($userStats, $lecturer['userId'], $lecturer['fullName'], 'lecturer');
}

// Done adding users to list

foreach ($posterStats as $posterStat)
{
    $userStats[$posterStat['userId']]['posts'] = $posterStat['posts'];
}

foreach ($posterTopics as $posterTopic)
{
    $userStats[$posterTopic['userId']]['topics'] = $posterTopic['topics'];
}

foreach ($posterTangents as $posterTangent)
{
    $userStats[$posterTangent['userId']]['tangents'] = $posterTangent['tangents'];
}

foreach ($userRatesOther as $userRates)
{
    $userStats[$userRates['userId']]['otherpostsrated'] = $userRates['postsrated'];
    $userStats[$userRates['userId']]['otherpostssumrated'] = $userRates['totalvalue'];
    $userStats[$userRates['userId']]['otherpostsmaxrated'] = $userRates['maxvalue'];
    $userStats[$userRates['userId']]['otherpostsminrated'] = $userRates['minvalue'];
}

foreach ($userRatesSelf as $userRates)
{
    $userStats[$userRates['userId']]['selfpostsrated'] = $userRates['postsrated'];
    $userStats[$userRates['userId']]['selfpostssumrated'] = $userRates['totalvalue'];
    $userStats[$userRates['userId']]['selfpostsmaxrated'] = $userRates['maxvalue'];
    $userStats[$userRates['userId']]['selfpostsminrated'] = $userRates['minvalue'];
}

foreach ($userWordCount as $userWords)
{
    $userStats[$userWords['userId']]['wordcountposts'] = $userWords['postscounted'];
    $userStats[$userWords['userId']]['wordcountsum'] = $userWords['totalwords'];
    $userStats[$userWords['userId']]['wordcountmax'] = $userWords['maxvalue'];
    $userStats[$userWords['userId']]['wordcountmin'] = $userWords['minvalue'];
}




$header = new htmlheading();
$header->type=1;
$header->str=$this->objLanguage->languageText('mod_forum_forumstatisticsfor').' '.$forumDetails['forum_name'];
echo $header->show();

echo '<h3>';
echo $this->objLanguage->languageText('mod_forum_foruminformation');
echo '</h3>';

$table = $this->getObject('htmltable', 'htmlelements');
$table->cellpadding = 5;

// Name of Forum
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_forum_nameofforum'), NULL, NULL, 'right');
$table->addCell($forumDetails['forum_name'], NULL, NULL, NULL, NULL, 'colspan="3"');
$table->endRow();

// Description
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_forum_forumdescription'), NULL, NULL, 'right');
$table->addCell($forumDetails['forum_description'], NULL, NULL, NULL, NULL, 'colspan="3"');
$table->endRow();

// Forum Locked
$table->startRow();

$table->addCell($this->objLanguage->languageText('mod_forum_forumvisible'), NULL, NULL, 'right');
$results = ($forumDetails['forum_visible'] == 'Y') ? $this->objLanguage->languageText('word_yes') : $this->objLanguage->languageText('word_no');
$table->addCell($results);

$table->addCell($this->objLanguage->languageText('mod_forum_forumlocked'), NULL, NULL, 'right');
$results = ($forumDetails['forumlocked'] == 'Y') ? $this->objLanguage->languageText('word_yes') : $this->objLanguage->languageText('word_no');
$table->addCell($results);

$table->endRow();

// ---------------------------
$table->startRow();

$table->addCell($this->objLanguage->languageText('mod_forum_ratingposts'), NULL, NULL, 'right');
$results = ($forumDetails['ratingsenabled'] == 'Y') ? $this->objLanguage->languageText('word_yes') : $this->objLanguage->languageText('word_no');
$table->addCell($results);

$table->addCell(ucwords($this->objLanguage->code2Txt('mod_forum_studentsstartTopics')), NULL, NULL, 'right');
$results = ($forumDetails['studentstarttopic'] == 'Y') ? $this->objLanguage->languageText('word_yes') : $this->objLanguage->languageText('word_no');
$table->addCell($results);

$table->endRow();

// ---------------------------
$table->startRow();

$table->addCell($this->objLanguage->languageText('mod_forum_attachmentsallowed'), NULL, NULL, 'right');
$results = ($forumDetails['attachments'] == 'Y') ? $this->objLanguage->languageText('word_yes') : $this->objLanguage->languageText('word_no');
$table->addCell($results);

$table->addCell($this->objLanguage->languageText('mod_forum_emailsubscriptions'), NULL, NULL, 'right');
$results = ($forumDetails['subscriptions'] == 'Y') ? $this->objLanguage->languageText('word_yes') : $this->objLanguage->languageText('word_no');
$table->addCell($results);

$table->endRow();

echo $table->show();

echo '<h3>';
echo $this->objLanguage->languageText('mod_forum_forumsummarystatistics');
echo '</h3>';

$table = $this->getObject('htmltable', 'htmlelements');
$table->cellpadding = 5;


$table->startRow();
$table->addCell('<strong>'.$this->objLanguage->languageText('mod_forum_numberofthreads').'</strong>', '30%');
$table->addCell($forumSummaryStats['topics'], '50%');
$table->endRow();

$table->startRow();
$table->addCell('<strong>'.$this->objLanguage->languageText('mod_forum_numberofposts').'</strong>', '30%');
$table->addCell($forumSummaryStats['posts'], '50%');
$table->endRow();

$table->startRow();
$table->addCell('<strong>'.$this->objLanguage->languageText('mod_forum_numberoftangents').'</strong>', '30%');
if ($tangents['tangents'] == '') {
    $tangentNum = 0;
} else {
    $tangentNum = $tangents['tangents'];
}
$table->addCell($tangentNum, '50%');
$table->endRow();

$table->startRow();
$table->addCell('<strong>'.$this->objLanguage->languageText('mod_forum_threadpostratio').'</strong>', '30%');

if ($forumSummaryStats['posts'] == 0 || $forumSummaryStats['topics'] == 0) {
    $results = 0;
} else {
    $results = round($forumSummaryStats['posts']/$forumSummaryStats['topics'], 2);
}
$table->addCell($results, '50%');
$table->endRow();

$table->startRow();
$table->addCell('<strong>'.$this->objLanguage->languageText('mod_forum_numberofuniqueposters').'</strong>', '30%');
$table->addCell($posters['posters'], '50%');
$table->endRow();

// $table->startRow();
// $table->addCell('<strong>Percentage of Posts by Top Poster</strong>', '30%');
// $table->addCell($forumSummaryStats['posts'], '50%');
// $table->endRow();

// $table->startRow();
// $table->addCell('<strong>Percentage of Posts by Lecturer/Admin</strong>', '30%');
// $table->addCell($forumSummaryStats['posts'], '50%');
// $table->endRow();

// $table->startRow();
// $table->addCell('<strong>Percentage of Posts by Students</strong>', '30%');
// $table->addCell($forumSummaryStats['posts'], '50%');
// $table->endRow();

// $table->startRow();
// $table->addCell('<strong>Percentage of Posts by Guests</strong>', '30%');
// $table->addCell($forumSummaryStats['posts'], '50%');
// $table->endRow();


echo $table->show();





// Put User Stats into table

$table = $this->getObject('htmltable', 'htmlelements');
$table->cellpadding = 5;

$table->startHeaderRow();
$table->addHeaderCell($this->objLanguage->languageText('word_role'), 20);
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_nameofuser'));
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_topicsstarted'));
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_tangentsstarted'));
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_numberofposts'));
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_percentagetotalposts'));

$table->endHeaderRow();

foreach ($userStats as $userStat)
{
    $table->startRow();
    if ($userStat['role'] == 'unknown') {
        $icon->setIcon('cancel');
        $icon->title = $this->objLanguage->languageText('word_unknown');
        $icon->alt = $this->objLanguage->languageText('word_unknown');
    } else {
        $icon->setIcon($userStat['role']);
        $icon->title = $userStat['role'];
        $icon->alt = $userStat['role'];
    }
    
    $table->addCell($icon->show());
    $table->addCell($userStat['name'], '30%');
    $table->addCell($userStat['topics']);
    $table->addCell($userStat['tangents']);
    $table->addCell($userStat['posts']);
    
    if ($userStat['posts'] == 0 || $forumSummaryStats['posts'] == 0) {
        $results = 0;
    } else {
        $results = ($userStat['posts'] / $forumSummaryStats['posts']) * 100;
        $results = round($results, 2);
    }
    
    $table->addCell($results.'%');
    $table->endRow();
}

echo '<h3>'.$this->objLanguage->languageText('mod_forum_userstatistics').'</h3>';
echo $table->show();
// END - User Stats

// Language Statistics
$table = $this->getObject('htmltable', 'htmlelements');
$table->cellpadding = 5;

$table->startHeaderRow();
$table->addHeaderCell($this->objLanguage->languageText('word_role'), 20);
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_nameofuser'));
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_minimumwords'));
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_maximumwords'));
$table->addHeaderCell($this->objLanguage->languageText('word_average'));
$table->endHeaderRow();

foreach ($userStats as $userStat)
{
    if ($userStat['role'] == 'unknown') {
        $icon->setIcon('cancel');
        $icon->title = $this->objLanguage->languageText('word_unknown');
        $icon->alt = $this->objLanguage->languageText('word_unknown');
    } else {
        $icon->setIcon($userStat['role']);
        $icon->title = $userStat['role'];
        $icon->alt = $userStat['role'];
    }
    
    $table->addCell($icon->show());
    $table->addCell($userStat['name'], '30%');
    $table->addCell($userStat['wordcountmin']);
    $table->addCell($userStat['wordcountmax']);
    
    if ($userStat['wordcountsum'] == 0 || $userStat['wordcountposts'] == 0) {
        $result = 0;
    } else {
        $result = $userStat['wordcountsum'] / $userStat['wordcountposts'];
        $result = round($result, 2);
    }
    $table->addCell($result);
    $table->endRow();
}

echo '<h3>'.$this->objLanguage->languageText('mod_forum_languagestatistics').'</h3>';
echo '<p>'.$this->objLanguage->languageText('mod_forum_languagestatisticsinfor').'</p>';
echo $table->show();

// User Ratings Received
$table = $this->getObject('htmltable', 'htmlelements');
$table->cellpadding = 5;

$table->startHeaderRow();
$table->addHeaderCell($this->objLanguage->languageText('word_role'), 20);
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_nameofuser'));
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_numpostsrated'));
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_minimumratingsreceived'));
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_maximumratingsreceived'));
$table->addHeaderCell($this->objLanguage->languageText('word_average'));
$table->endHeaderRow();

foreach ($userStats as $userStat)
{
    if ($userStat['role'] == 'unknown') {
        $icon->setIcon('cancel');
        $icon->title = $this->objLanguage->languageText('word_unknown');
        $icon->alt = $this->objLanguage->languageText('word_unknown');
    } else {
        $icon->setIcon($userStat['role']);
        $icon->title = $userStat['role'];
        $icon->alt = $userStat['role'];
    }
    
    $table->addCell($icon->show());
    $table->addCell($userStat['name'], '30%');
    $table->addCell($userStat['selfpostsrated']);
    $table->addCell($userStat['selfpostsminrated']);
    $table->addCell($userStat['selfpostsmaxrated']);
    
    if ($userStat['selfpostssumrated'] == 0 || $userStat['selfpostsrated'] == 0) {
        $result = 0;
    } else {
        $result = round(($userStat['selfpostssumrated'] / $userStat['selfpostsrated']), 2);
    }
    $table->addCell($result);
    $table->endRow();
}

echo '<h3>'.$this->objLanguage->languageText('mod_forum_ratingofpostsreceived').'</h3>';
echo '<p>'.$this->objLanguage->languageText('mod_forum_ratingofpostsreceivedinfo').'</p>';
echo $table->show();

// User Ratings
$table = $this->getObject('htmltable', 'htmlelements');
$table->cellpadding = 5;

$table->startHeaderRow();
$table->addHeaderCell($this->objLanguage->languageText('word_role'), 20);
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_nameofuser'));
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_numpostsrated'));
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_minimumratingsgiven'));
$table->addHeaderCell($this->objLanguage->languageText('mod_forum_maximumratingsgiven'));
$table->addHeaderCell($this->objLanguage->languageText('word_average'));
$table->endHeaderRow();

foreach ($userStats as $userStat)
{
    if ($userStat['role'] == 'unknown') {
        $icon->setIcon('cancel');
        $icon->title = $this->objLanguage->languageText('word_unknown');
        $icon->alt = $this->objLanguage->languageText('word_unknown');
    } else {
        $icon->setIcon($userStat['role']);
        $icon->title = $userStat['role'];
        $icon->alt = $userStat['role'];
    }
    
    $table->addCell($icon->show());
    $table->addCell($userStat['name'], '30%');
    $table->addCell($userStat['otherpostsrated']);
    $table->addCell($userStat['otherpostsminrated']);
    $table->addCell($userStat['otherpostsmaxrated']);
    
    if ($userStat['otherpostssumrated'] == 0 || $userStat['otherpostsrated'] == 0) {
        $result = 0;
    } else {
        $result = round(($userStat['otherpostssumrated'] / $userStat['otherpostsrated']), 2);
    }
    $table->addCell($result);
    $table->endRow();
}

echo '<h3>'.$this->objLanguage->languageText('mod_forum_ratingofpostsgiven').'</h3>';
echo '<p>'.$this->objLanguage->languageText('mod_forum_ratingofpostsgiveninfo').'</p>';
echo $table->show();






// Footer
echo '<p>';
$backtoForumLink = new link ($this->uri(array('action'=>'forum', 'id'=>$id)));
$backtoForumLink->link = $this->objLanguage->languageText('mod_forum_backtoforum');

echo $backtoForumLink->show();

echo ' / ';

$backtoAllForumsLink = new link ($this->uri(NULL));
$backtoAllForumsLink->link = $this->objLanguage->languageText('mod_forum_backtoforumsincontent').' '.$contextTitle;

echo $backtoAllForumsLink->show();

echo '</p>';


?>