<?php
/**
 * Created by PhpStorm.
 * User: HanYaMin
 * Date: 2017/11/17
 * Time: 11:05
 */

class Rmess extends PMess  //回复
{
    private $ap_parent_id;
    public function getPId()
    {
        return $this->ap_parent_id;
    }
    public function setPId($ap_parent_id){
        $this->ap_parent_id=$ap_parent_id;
    }

    public function postContent()
    {
        // TODO: Implement postContent() method.

    }

    public function editContent()
    {
        // TODO: Implement editContent() method.
    }

    public function deleteContent()
    {
        // TODO: Implement deleteContent() method.
        $mess_id=$this->getId();
        $sql = "delete from table_append_message where mess_id='{$mess_id}'";
        $reslut = Db::getDb()->exec($sql);
        if($reslut){
            return array('success'=>true,'message'=>'删除成功');
        } else {
            return array('success'=>false,'message'=>'删除失败');
        }
    }

    public function replyContent($ap_mess_content)
    {
        // TODO: Implement replyContent() method.
        return array('success'=>false,'maeesge'=>'暂未开通');
    }
}