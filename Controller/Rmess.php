<?php
/**
 * Created by PhpStorm.
 * User: HanYaMin
 * Date: 2017/11/21
 * Time: 10:41
 */

class Rmess extends Mess
{
    protected $parent_id;

    public function setParentId($parent_id){
        $this->parent_id=$parent_id;
    }

    public function getParentId(){
        return $this->parent_id;
    }

    public function postContent($pid=0){
        $data=[
            'ap_mess_id'=>getUniId(),
            'ap_parent_id'=>$this->getParentId(),
            'ap_mess_content'=>$this->getContent(),
            'ap_user_id'=>$_SESSION['user_id'],
            'ap_user_name'=>$_SESSION['user_name'],
            'creat_time'=>date('Y-m-d H:i:s'),
            'operate_time'=>date('Y-m-d H:i:s'),
            'audit'=>0,
            'pid'=>$pid,
        ];
        $mess=new Model('table_append_mess');
        $result=$mess->insert($data);
        if($result){
            return array('success'=>true,'message'=>'回复成功');
        }else{
            return array('success'=>false,'message'=>'回复失败');
        }
    }

    public function deleteContent(){
        $mess=new Model('table_append_mess');
        $where=array('ap_mess_id'=>$this->getId());
        $result1=$mess->delete($where);
        $this->deleteContentByPid($this->getId());
        if($result1){
            return array('success'=>true,'message'=>'删除成功');
        } else {
            return array('success'=>false,'message'=>'删除失败');
        }
    }
    private function deleteContentByPid($pid){
        $mess=new Model('table_append_mess');
        $where=array('pid'=>$pid);
        $result=$mess->delete($where);
        if($result){
            return true;
        } else {
            return false;
        }
    }
    public function deleteContentByParentId(){
        $mess=new Model('table_append_mess');
        $where=array('ap_parent_id'=>$this->getParentId());
        $result=$mess->delete($where);
        if($result){
            return true;
        } else {
            return false;
        }
    }
}