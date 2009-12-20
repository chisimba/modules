<?php
class nav extends object {

    public function  init() {
        $this->loadclass('link','htmlelements');
    }

    public  function show() {
        $twobtalent=new link($this->uri(array('action'=>'viewstory','category'=> 'twobtalent')));
        $twobtalent->extra=' id= "twobtalent"';
        $twobtalent->link='<span>two be talent</span>';

        $aboutus=new link($this->uri(array('action'=>'viewstory','category'=> 'aboutus')));
        $aboutus->extra=' id= "aboutus"';
        $aboutus->link='<span>about us</span>';

        $poems=new link($this->uri(array('action'=>'viewstory','category'=> 'poems')));
        $poems->extra=' id= "poems"';
        $poems->link='<span>poems</span>';

        $onlinegal=new link($this->uri(array('action'=>'viewstory','category'=> 'onlinegal')));
        $onlinegal->extra=' id= "onlinegal"';
        $onlinegal->link='<span>onlinegal</span>';

        $str='<br/><br/><ul>';
        $str.='<li>'.$aboutus->show().'</li>';
        $str.='<li>'.$twobtalent->show().'</li>';
        $str.='<li>'.$poems->show().'</li>';
        $str.='<li>'.$onlinegal->show().'</li>';
        $str.="</ul>";

        return $str;
    }
}

?>
