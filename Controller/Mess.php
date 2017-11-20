<?php
/**
 * Created by PhpStorm.
 * User: HanYaMin
 * Date: 2017/11/17
 * Time: 11:03
 */

class Mess  //留言
{
    private $id;
    private  $content;

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

    public function postContent()
    {
        // TODO: Implement postContent() method.
        $data=[
            'mess_id'=>$this->getId(),
            'mess_content'=>$this->getContent(),
            'user_id'=>$_SESSION['user_id'],
            'user_name'=>$_SESSION['user_name'],
            'creat_time'=>date('Y-m-d H:i:s',time()),
            'operate_time'=>date('Y-m-d H:i:s',time()),
            'audit'=>0,
        ];
        $mess=new Model('table_message');
        $result=$mess->insert($data);
        if($result){
            return array('success'=>true,'message'=>'发表成功');
        }else{
            return array('success'=>false,'message'=>'发表失败');
        }

//        $mess_id=$this->getId();
//        $mess_content=$this->getContent();
//        $user_id=$_SESSION['user_id'];
//        $user_name=$_SESSION['user_name'];
//        $create_time=date('Y-m-d H:i:s',time());
//        $operate_time=date('Y-m-d H:i:s',time());
//        $sql="insert into table_message values('$mess_id',:mess_content,'$user_id',:user_name,'$create_time','$operate_time',0)";
//        $result=Db::getDb()->prepare($sql);
//        $result->execute(array(':mess_content'=>$mess_content,':user_name'=>$user_name));
//        if($result){
//            return array('success'=>true,'message'=>'发表成功');
//        }else{
//            return array('success'=>false,'message'=>'发表失败');
//        }
    }

    public function inform(){
        $sets=array('audit'=>'1');
        $where=array('mess_id'=>$this->getId());
        $mess=new Model('table_message');
        $result=$mess->update($sets,$where);
        if ($result) {
            return array('success'=>true,'message'=>'举报成功');
        } else {
            return array('success'=>false,'message'=>'举报失败');
        }

//        $sql = "update table_message set audit='1' where mess_id='{$this->getId()}'";
//        $result = Db::getDb()->exec( $sql);
//        if ($result) {
//            return array('success'=>true,'message'=>'举报成功');
//        } else {
//            return array('success'=>false,'message'=>'举报失败');
//        }
    }

    public function editContent()
    {
        // TODO: Implement editContent() method.
        $sets=[
            'mess_content'=>$this->getContent(),
            'operate_time'=>date('Y-m-d H;i:s',time()),
        ];
        $where=array('mess_id'=>$this->getId());
        $mess=new Model('table_message');
        $result=$mess->update($sets,$where);
        if($result){
            return array('success'=>true,'message'=>'更改成功');
        }else{
            return array('success'=>false,'message'=>'更新失败');
        }

//        $mess_id=$this->getId();
//        $mess_content=$this->getContent();
//        $operate_time=date('Y-m-d H;i:s',time());
//        $sql="update table_message set mess_content=:mess_content, operate_time='$operate_time' where mess_id='$mess_id'";
//        $result=Db::getDb()->prepare($sql);
//        $result->execute(array(':mess_content'=>$mess_content));
//        if($result){
//            return array('success'=>true,'message'=>'更改成功');
//        }else{
//            return array('success'=>false,'message'=>'更新失败');
//        }
    }

    public function deleteContent()
    {
        // TODO: Implement deleteContent() method.
        $mess=new Model('table_message');
        $where=array('mess_id'=>$this->getId());
        $result=$mess->delete($where);
        if($result){
            return array('success'=>true,'message'=>'删除成功');
        } else {
            return array('success'=>false,'message'=>'删除失败');
        }

//        $mess_id=$this->getId();
//        $sql = "delete from table_message where mess_id='$mess_id'";
//        $result = Db::getDb()->exec($sql);
//        if($result){
//            return array('success'=>true,'message'=>'删除成功');
//        } else {
//            return array('success'=>false,'message'=>'删除失败');
//        }
    }

    public function replyContent($ap_mess_content)
    {
        // TODO: Implement replyContent() method.
        $data=[
            'ap_mess_id'=>getUniId(),
            'ap_parent_id'=>$this->getId(),
            'ap_mess_content'=>$ap_mess_content,
            'ap_user_id'=>$_SESSION['user_id'],
            'ap_user_name'=>$_SESSION['user_name'],
            'creat_time'=>date('Y-m-d H:i:s'),
            'operate_time'=>date('Y-m-d H:i:s'),
            'audit'=>0,
        ];
        $mess=new Model('table_append_mess');
        $result=$mess->insert($data);
        if($result){
            return array('success'=>true,'message'=>'回复成功');
        }else{
            return array('success'=>false,'message'=>'回复失败');
        }

//        $ap_mess_id=getUniId();   //getUniId()写到一个公共文件中，在入口文件include
//        $ap_parent_id=$this->getId();
//        $ap_user_id=$_SESSION['user_id'];
//        $ap_user_name=$_SESSION['user_name'];
//        $create_time=date('Y-m-d H;i;s');
//        $operate_time=date('Y-m-d H:i:s');
//        $sql="insert into table_append_mess values('$ap_mess_id','$ap_parent_id',:ap_mess_content,'$ap_user_id','$ap_user_name','$create_time','$operate_time',0)";
//        $result=Db::getDb()->prepare($sql);
//        $result->execute(array(':ap_mess_content'=>$ap_mess_content));
//        if($result){
//            return array('success'=>true,'message'=>'回复成功');
//        }else{
//            return array('success'=>false,'message'=>'回复失败');
//        }
    }
}