<?php
/**
* dbLoggerCalc class extends object
* @package hivaids
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* dbLoggerCalc class contains calculations performed on the statistical data contained in the logger table
* @author Megan Watson
* @copyright (c) 2007 UWC
* @version 0.1
*/

class dbLoggerCalc extends dbtable
{
    /**
    * Constructor method
    */
    public function init()
    {
        parent::init('tbl_logger');
        $this->table = 'tbl_logger';
    }
    
    /**
    * Method to get the total number of hits on the site
    *
    * @access public
    * @return integer
    */
    public function getTotalHits($track, $by = 'all')
    {
        $sql = "SELECT count(*) as cnt FROM {$this->table} l";
        
        if($track != 'everyone'){
            $sql .= ", tbl_hivaids_users hu, tbl_users u
            WHERE l.userid = hu.user_id AND hu.user_id = u.userid ";
         
            if($by != 'all'){   
                $sql .= "AND staff_student = '{$by}' ";
            }
        }
        
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data[0]['cnt'];
        }
        return 0;
    }
    
    /**
    * Method to get the total number of hits on the site by a given user
    *
    * @access public
    * @param string $userid
    * @return integer
    */
    public function getTotalHitsByUser($userid = '')
    {
        $sql = "SELECT count(*) as cnt FROM {$this->table} l, tbl_hivaids_users hu, tbl_users u
            WHERE l.userid = hu.user_id AND hu.user_id = u.userid ";
            
        if(!empty($userid)){
            $sql .= "AND hu.user_id = '{$userid}'";
        }
        
        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data[0]['cnt'];
        }
        return 0;
    }
    
    /**
    * Method to get the total number of hits on the site by a given group of users
    *
    * @access public
    * @param string $group The group type eg gender
    * @param string $subgroup The group eg males
    * @return integer
    */
    public function getTotalHitsByGroup($by = 'all', $group = '', $subgroup = '')
    {
        $sql = "SELECT count(*) as cnt FROM {$this->table} l, tbl_hivaids_users hu, tbl_users u
            WHERE l.userid = hu.user_id AND hu.user_id = u.userid ";
            
        if(!empty($group) && !empty($subgroup)){
            $sql .= "AND {$group} = '{$subgroup}' ";
        }
        if($by != 'all'){
            $sql .= "AND staff_student = '{$by}'";
        }

        $data = $this->getArray($sql);
        if(!empty($data)){
            return $data[0]['cnt'];
        }
        return 0;
    }

    /**
    * Method to get the total number of unique visitors on the site - by IP address or by userid if for registered users ($track != everyone)
    * If $view is students then only unique students are displayed.
    *
    * @access public
    * @return integer
    */
    public function getTotalUniqueVisitors($track, $by)
    {
        $sql = "SELECT DISTINCT(ipaddress), count(*) as cnt FROM {$this->table} l";
        
        if($track != 'everyone'){
            $sql .= ", tbl_hivaids_users hu, tbl_users u
            WHERE l.userid = hu.user_id AND hu.user_id = u.userid ";
            
            if($by != 'all'){   
                $sql .= "AND staff_student = '{$by}' ";
            }
            $sql .= "GROUP BY hu.user_id";
        }else{
            $sql .= " GROUP BY ipaddress";
        }
        
        $data = $this->getArray($sql);
        if(!empty($data)){
            $cnt = count($data);
            return $cnt;
        }
        return 0;
    }

    /**
    * Method to get the total number of unique visitors on the site - by IP address
    *
    * @access public
    * @return integer
    */
    public function getTotalUniqueVisitorsByGroup($by = 'all', $group = '', $subgroup = '')
    {
        $sql = "SELECT DISTINCT(ipaddress), count(*) as cnt FROM {$this->table} l, tbl_hivaids_users hu, tbl_users u
            WHERE l.userid = hu.user_id AND hu.user_id = u.userid ";
             
        if(!empty($group) && !empty($subgroup)){
            $sql .= "AND {$group} = '{$subgroup}' ";
        }
        if($by != 'all'){
            $sql .= "AND staff_student = '{$by}' ";
        }
        $sql .= "GROUP BY hu.user_id";

        $data = $this->getArray($sql);
        if(!empty($data)){
            $cnt = count($data);
            return $cnt;
        }
        return 0;
    }
    
    /**
    * Method to get the number of hits and visitors per module and action
    *
    * @access public
    * @return array
    */
    public function getModuleHitsByUser($userid)
    {
        $sql = "SELECT module, ipaddress, eventparamvalue, 
            count(module) as cnt, count(ipaddress) as ip_cnt, count(eventparamvalue) as act_cnt 
            FROM {$this->table} l, tbl_hivaids_users hu, tbl_users u 
            WHERE l.userid = hu.user_id AND hu.user_id = u.userid ";
            
        if(!empty($userid)){
            $sql .= "AND hu.user_id = '{$userid}' ";
        }
        
        $sql .= "GROUP BY module, eventparamvalue, ipaddress";
        
        $data = $this->getArray($sql);
        
        if(!empty($data)){
            // Split data by module
            // Find unique actions - remove additional parameters
            // Count IPs for each action / group of actions
            $arrModules = $this->organiseModuleData($data);
            return $arrModules;
        }
        return array();
    }
    
    /**
    * Method to get the number of hits and visitors per module and action
    *
    * @access public
    * @return array
    */
    public function getModuleHitsByGroup($by = 'all', $group = '', $subgroup = '')
    {
        $sql = "SELECT module, hu.user_id, eventparamvalue, 
            count(module) as cnt, count(ipaddress) as ip_cnt, count(eventparamvalue) as act_cnt 
            FROM {$this->table} l, tbl_hivaids_users hu, tbl_users u 
            WHERE l.userid = hu.user_id AND hu.user_id = u.userid ";
           
        if(!empty($group) && !empty($subgroup)){
            $sql .= "AND {$group} = '{$subgroup}' ";
        }
        if($by != 'all'){
            $sql .= "AND staff_student = '{$by}' ";
        }
        $sql .= "GROUP BY module, eventparamvalue, hu.user_id";
        
        $data = $this->getArray($sql);
        
        if(!empty($data)){
            // Split data by module
            // Find unique actions - remove additional parameters
            // Count IPs for each action / group of actions
            $arrModules = $this->organiseModuleData($data, 'user_id');
            return $arrModules;
        }
        return array();
    }

    /**
    * Method to get the number of hits and visitors per module and action
    *
    * @access public
    * @return array
    */
    public function getModuleHits()
    {
        $sql = "SELECT module, ipaddress, eventparamvalue, 
            count(module) as cnt, count(ipaddress) as ip_cnt, count(eventparamvalue) as act_cnt 
            FROM {$this->table} t GROUP BY module, eventparamvalue, ipaddress";
        
        $data = $this->getArray($sql);
        
        if(!empty($data)){
            // Split data by module
            // Find unique actions - remove additional parameters
            // Count IPs for each action / group of actions
            $arrModules = $this->organiseModuleData($data);
            return $arrModules;
        }
        return array();
    }
    
    /**
    * Method to organise the module hits and visitors in a usable array for display.
    *
    * @access public
    * @return array
    */
    public function organiseModuleData($data, $userType = 'ipaddress')
    {   
        if(!empty($data)){
            // Split data by module
            // Find unique actions - remove additional parameters
            // Count IPs for each action / group of actions
            $arrModules = array();
            foreach($data as $item){
                
                $action = '';
                if(!is_null($item['eventparamvalue']) && !empty($item['eventparamvalue'])){
                    $arrAction = explode('&', $item['eventparamvalue']);
                    
                    if(!empty($arrAction)){
                        foreach($arrAction as $val){
                            $pos = strpos($val, 'action=');
                            $action = ($pos == 0 && $pos !== FALSE) ? substr($val, 7) : '';
                        }
                    }       
                }
                
                $arrModules[$item['module']][$action]['params'][] = $item['eventparamvalue'];
                $arrModules[$item['module']][$action]['hits'] = isset($arrModules[$item['module']][$action]['hits']) ? $arrModules[$item['module']][$action]['hits'] + $item['cnt'] : $item['cnt'];
                
                $arrModules[$item['module']][$action]['users'][$item[$userType]] = isset($arrModules[$item['module']][$action]['users'][$item[$userType]]) ? $arrModules[$item['module']][$action]['users'][$item[$userType]] + $item['cnt'] : $item['cnt'];
            }
            return $arrModules;
        }
        return array();
    }
    
    /**
    * Method to get all the forum categories and associated statistics
    *
    * @access public
    * @return array
    */
    public function getForumCategories($by = 'all')
    {
        $sql = "SELECT forum_id, forum_name, t.views, t.replies 
            FROM tbl_forum_topic t, tbl_forum f
            WHERE f.id = t.forum_id
            ORDER BY forum_name";
        
        $data = $this->getArray($sql);
        $arrCats = $this->organiseForumData($data);
        
        return $arrCats;
    }
    
    /**
    * Method to organise the module hits and visitors in a usable array for display.
    *
    * @access public
    * @return array
    */
    public function organiseForumData($categories = '')
    {
        $arrCats = array();
        if(!empty($categories)){
            foreach($categories as $item){
                $arrCats[$item['forum_id']]['name'] = $item['forum_name'];
                $arrCats[$item['forum_id']]['views'] = isset($arrCats[$item['forum_id']]['views']) ? $arrCats[$item['forum_id']]['views'] + $item['views'] : $item['views'];
                $arrCats[$item['forum_id']]['replies'] = isset($arrCats[$item['forum_id']]['replies']) ? $arrCats[$item['forum_id']]['replies'] + $item['replies'] : $item['replies'];
                $arrCats[$item['forum_id']]['topics'] = isset($arrCats[$item['forum_id']]['topics']) ? $arrCats[$item['forum_id']]['topics'] + 1 : 1;
            }
        }
        return $arrCats;
    }
    
    /**
    * Method to get all the forum categories and associated statistics
    *
    * @access public
    * @return array
    */
    public function getForumTopics($catId, $by = 'all')
    {
        $sql = "SELECT f.forum_name, t.id as topicid, t.views, t.replies, pt.post_title 
            FROM tbl_forum f, tbl_forum_topic t, tbl_forum_post p, tbl_forum_post_text pt
            WHERE t.forum_id = '{$catId}' AND f.id = t.forum_id 
            AND t.first_post = p.id AND p.post_parent = '0' AND pt.post_id = p.id 
            ORDER BY pt.post_title";
        
        $data = $this->getArray($sql);
        return $data;
    }
    
    /**
    * Method to get all the views of each category
    *
    * @access public
    * @return array
    */
    public function getForumCatViews($by = 'all', $type = 'cat', $num = 21)
    {
        $sql = "SELECT l.eventparamvalue, hu.user_id, hu.course, hu.study_year, hu.language, u.sex FROM tbl_logger l, tbl_users u, tbl_hivaids_users hu
                WHERE u.userid = hu.user_id AND u.userid = l.userid 
                AND module = 'hivaidsforum' AND eventparamvalue LIKE 'action=show{$type}&%' ";
        
        if($by != 'all'){
            $sql .= "AND hu.staff_student = '{$by}'";
        }
        
        $data = $this->getArray($sql);
        
        $arrViews = $this->organiseViewData($data, $num);
        return $arrViews;
    }

    /**
    * Method to organise the module hits and visitors in a usable array for display.
    *
    * @access public
    * @return array
    */
    public function organiseViewData($views = '', $num = 21)
    {
        $arrViews = array();
        if(!empty($views)){
            foreach($views as $item){
                $catId = substr($item['eventparamvalue'], $num);
                $arrViews[$catId]['total'] = isset($arrViews[$catId]['total']) ? $arrViews[$catId]['total']+1 : 1;
                $arrViews[$catId]['rows'] = isset($arrViews[$catId]['language'][$item['language']]) ? $arrViews[$catId]['rows'] : $arrViews[$catId]['rows']+1;
                $arrViews[$catId]['rows'] = isset($arrViews[$catId]['course'][$item['course']]) ? $arrViews[$catId]['rows'] : $arrViews[$catId]['rows']+1;
                $arrViews[$catId]['rows'] = isset($arrViews[$catId]['sex'][$item['sex']]) ? $arrViews[$catId]['rows'] : $arrViews[$catId]['rows']+1;
                $arrViews[$catId]['rows'] = isset($arrViews[$catId]['study_year'][$item['study_year']]) ? $arrViews[$catId]['rows'] : $arrViews[$catId]['rows']+1;
                
                $arrViews[$catId]['language'][$item['language']] = isset($arrViews[$catId]['language'][$item['language']]) ? $arrViews[$catId]['language'][$item['language']] + 1 : 1;
                $arrViews[$catId]['course'][$item['course']] = isset($arrViews[$catId]['course'][$item['course']]) ? $arrViews[$catId]['course'][$item['course']] + 1 : 1;
                $arrViews[$catId]['sex'][$item['sex']] = isset($arrViews[$catId]['sex'][$item['sex']]) ? $arrViews[$catId]['sex'][$item['sex']] + 1 : 1;
                $arrViews[$catId]['study_year'][$item['study_year']] = isset($arrViews[$catId]['study_year'][$item['study_year']]) ? $arrViews[$catId]['study_year'][$item['study_year']] + 1 : 1;
                
            }
        }
        return $arrViews;
    }

    /**
    * Method to clear the logged info
    *
    * @access public
    * @return array
    */
    public function clearLogger()
    {
        $sql = "DELETE FROM tbl_logger";
        
        $this->getArray($sql);
    }
}
?>