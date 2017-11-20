<?php
/**
 * Created by PhpStorm.
 * User: HanYaMin
 * Date: 2017/11/17
 * Time: 10:49
 */

abstract class PMess
{
    private $id;
    private  $content;

    public function __construct($id='',$content='')
    {
        $this->id=$id;
        $this->$content=$content;
    }
    public function setId($id){
        $this->id=$id;
    }
    public function setContent($newContent){
        $this->content=$newContent;
    }
    public function getId(){
        return $this->id;
    }
    public function getContent(){
        return $this->content;
    }
    public abstract function postContent();
    public abstract function editContent();
    public abstract function deleteContent();
    public abstract function replyContent($ap_mess_content);
}