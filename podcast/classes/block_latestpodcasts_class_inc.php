<?
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* The class that shows the latest podcast
*
* @author Tohir Solomons
*
*/
class block_latestpodcasts extends object
{
    var $title;
    
    /**
    * Constructor for the class
    */
    function init()
    {
        //Create an instance of the language object
        $this->objLanguage = $this->getObject('language','language');
        $this->objPodcast = $this->getObject('dbpodcast','podcast');
        $this->objUser = $this->getObject('user','security');
        $this->loadClass('link', 'htmlelements');
        //Set the title
        $this->title=$this->objLanguage->languageText('mod_podcast_latespodcasts', 'podcast', 'Latest Podcasts');
    }
    
    /**
    * Method to output a block with information on how help works
    */
    function show()
    {
        $podcasts = $this->objPodcast->getLast5();
        
        if (count($podcasts) == 0) {
            return $this->objLanguage->languageText('mod_podcast_nopodcastsavailable', 'podcast');
        } else {
           //print_r($podcasts);
           
           $str = '';
           
           foreach ($podcasts as $podcast)
           {
                $link = new link ($this->uri(array('action'=>'viewpodcast', 'id'=>$podcast['id'])));
                $link->link = htmlentities($podcast['title']);
                
                $str .= '<p><strong>'.$link->show().'</strong>';
                $str .= '<br /><span class="minute">by '.htmlentities($this->objUser->fullName($podcast['creatorid'])).'</span>';
                $str .= '</p>';
           }
           /*
           $str = '<strong>'.htmlentities($podcast['title']).'</strong>';
           $str .= '<br />by '.htmlentities($this->objUser->fullName($podcast['creatorid']));
           $str .= '<br />'.htmlentities($podcast['description']);
           
           $link = new link($this->uri(NULL, 'podcast'));
           $link->link = 'Podcast Home';
           
           $this->objPop=&new windowpop;
            $this->objPop->set('location',$this->uri(array('action'=>'playpodcast', 'id'=>$podcast['id']), 'podcast'));
            $this->objPop->set('linktext', $this->objLanguage->languageText('mod_podcast_listenonline', 'podcast'));
            $this->objPop->set('width','280');
            $this->objPop->set('height','120');
            //leave the rest at default values
            $this->objPop->putJs(); // you only need to do this once per page
           
           $str .= '<p>'.$this->objPop->show().' / '.$link->show().'</p>';
           */
           return $str;
        }
    }
}
