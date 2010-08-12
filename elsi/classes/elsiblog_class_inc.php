<?php

class elsiblog extends dbTable {

    function init() {

    }

    function getLatestBlog($category) {
        $this->_changeTable('tbl_blog_posts');
        $id = $this->getCategoryId($category);
        $sql = "SELECT *
FROM   tbl_blog_posts p, tbl_blog_cats ca
WHERE   p.puid= (SELECT MAX(puid)  FROM tbl_blog_posts) and ca.id=p.post_category
and ca.id='$id'";

        return $this->getArray($sql);
    }

    function getCategoryId($name) {
        $this->_changeTable('tbl_blog_cats');
        $data = $this->getRow("cat_name", $name);
        return $data['id'];
    }

    /**
     * Method to dynamically switch tables
     *
     * @param  string  $table
     * @return boolean
     * @access private
     */
    private function _changeTable($table) {
        try {
            parent::init($table);
            return TRUE;
        } catch (customException $e) {
            customException::cleanUp();
            return FALSE;
        }
    }

}
?>
