<?php
/**
 * Created by PhpStorm.
 * User: HanYaMin
 * Date: 2017/11/16
 * Time: 0:19
 */
include "Controller/User.php";
include "Controller/Mess.php";
include "Controller/Rmess.php";
include "Controller/Common.php";
include "Controller/Db.php";
include "Controller/Model.php";
$in=new Test();
session_start();
$in->go();
class Test{
    private  $user;
    public function __construct()
    {
        $this->user=new User();
    }

    public function go(){
        $cmd=isset($_REQUEST['action'])?$_REQUEST['action']:'login';
        switch ($cmd){
            case 'login':
                $this->view('userLogin.html');
                break;
            case 'gotolist':
                if(isset($_SESSION['user_id'])){
                    $this->view('messageList.html');
                }else{
                    $this->view('userLogin.html');
                }
                break;
            case 'gotorig':
                $this->view('userRigister.html');
                break;
            case 'userlogin':
                $this->userlogin();
                break;
            case 'upload_file':
                $this->upload_file();
                break;
            case 'register':
                $this->register();
                break;
            case 'post':  //提交新留言
                $this->post();
                break;
            case 'edit_save':  //编辑保存留言
                $this->edit_save();
                break;
            case 'res':   //回复编辑留言
                $this->res_save();
                break;
            case 'sedrevsave':
                $this->sedrevsave();
                break;
            case 'deleteRevMess':
                $this->deleteRevMess();
                break;
            case 'inform':
                $this->inform();
                break;
            case 'delete':  //删除留言
                $this->delete();
                break;
            case 'isCurUser':
                $this->isCurUser();
                break;
            case 'prepage':   //上一页
            case 'nextpage':   //下一页
            case 'show':  //显示留言列表
                $this->show();
                break;
            default :break;
        }
    }

    protected function view($html){
        include ($html);
    }

    protected function upload_file(){
        if($_FILES["image"]){
            if ((($_FILES["image"]["type"] == "image/gif")
                    || ($_FILES["image"]["type"] == "image/jpeg")
                    || ($_FILES["image"]["type"] == "image/pjpeg"))
                && ($_FILES["image"]["size"] < 20000))
            {
                $oldName=$_FILES['image']['name'];
                $tmp=explode(".",$oldName);
                $newName=getUniId().".".$tmp[1];
                move_uploaded_file($_FILES["image"]["tmp_name"], "images/" .$newName);
                $_SESSION['picture']=$newName;
                echo '上传成功';
                $this->view('userRigister.html');
            }
        }
    }

    protected function register(){
        if(empty($_POST)){
            echo '非法进入';  //001非法进入
        }else{
            $user=new User();
            $user->user_id=getUniId();
            $user->user_name=$_POST['user_name'];
            $data=[
                'pass_word'=>md5($_POST['password']),
                'user_real_name'=>$_POST['user_real_name'],
                'city'=>$_POST['city'],
                'school'=>$_POST['school'],
                'creat_time'=>date('Y-m-d H:i:s',time()),
            ];
            if(!isset($_SESSION['picture'])){
                $picture="";
            }else{
                $picture=$_SESSION['picture'];
            }
            $result=$user->register($data,$picture);
            if($result['success']){
                echo 'success';
            }else{
                echo 'error';
            }
        }
    }

    protected function userlogin(){
        if (!empty($_POST)) {
            $user_name = $_POST['user_name'];
            $pass_word = md5($_POST['pass_word']);
        } else {
            echo '001';  //非法进入
        }
        $this->user->user_name=$user_name;
        $result=$this->user->login($pass_word);
        if ($result['success']) {
            echo 'success';
        } else {
            echo $result['message'];
        }
    }

    protected function show(){
        $rowsPerPage=3;
        $st=Db::getDb()->query("select * from table_message where audit='0'");
        $rows=count($st->fetchAll(PDO::FETCH_COLUMN,1));
        $pages=ceil($rows/$rowsPerPage);
        if(isset($_POST['curPage'])){
            $curPage=$_POST['curPage'];
        }else{
            $curPage=1;
        }
        if($curPage>$pages){
            echo 'last';
        }else if($curPage<=0){
            echo 'first';
        }else{
            $sql="select mess_id,user_name,table_message.user_id,mess_content,creat_time,picture from table_message left join table_user_info on table_message.user_id=table_user_info.user_id where audit=0 order by creat_time DESC limit ".($curPage-1)*$rowsPerPage.",".$rowsPerPage;
            foreach (Db::getDb()->query($sql)as $row){
                $un=strip_tags($row['user_name']);
                $mc=strip_tags($row['mess_content']);

                echo "<div id='list_div'>
                <div id='li_user_left'>
                用户名：{$un}<br/>
                <img src='images/{$row['picture']}' width='100' height='100' /><br/>
                </div>
                <div id='li_con_right'>
                    <div id='con_right_top'>
                    发表时间:{$row['creat_time']}
                    <button class=\"btn btn-xs\" onclick=\"inform({$row['mess_id']})\">举报</button><br/>
                    </div>                    
                    <div id='con_right_mid'>
                    <textarea class=\"form-control\" id=\"{$row['mess_id']}\" disabled='disabled' cols='80' rows='5'>{$mc}</textarea>";
                    if($_SESSION['user_id']==$row['user_id']){
                        echo  "<div class=\"btn-group\" id='button_group'>  
                                <a onclick=\"editMess({$row['mess_id']})\">  <span class=\"glyphicon glyphicon-pencil\"></span>  </a>
                                <a onclick=\"deleteMess({$row['mess_id']})\" >  <span class=\"glyphicon glyphicon-trash\"></span> </a>
                            </div>
                        </div> ";
                    }
                    $subsql="select ap_mess_id,ap_mess_content,ap_user_id,ap_user_name,creat_time from table_append_mess where ap_parent_id=:ap_parent_id and pid=0";
                    $rs=Db::getDb()->prepare($subsql);
                    $rs->execute(array(':ap_parent_id'=>$row['mess_id']));
                    foreach ($rs as $r) {
                        $aun = $r['ap_user_name'];
                        $amc = $r['ap_mess_content'];
                        echo "<div style=\"padding: 0px 50px 0px;\"><div>{$aun}<a onclick=\"sedrev({$row['mess_id']},{$r['ap_mess_id']})\"><span class=\"glyphicon glyphicon-share-alt\"></span></a>";
                            if ($_SESSION['user_id']==$r['ap_user_id']){
                                echo "<a onclick=\"deleteRevMess({$r['ap_mess_id']})\" > <span class=\"glyphicon glyphicon-trash\"></span> </a>";
                            }
                            echo "</div><textarea  class=\"form-control\"  readonly='readonly' cols='80' rows='1'>{$amc}</textarea>
                            <div id='{$r['ap_mess_id']}'></div>
                        </div>";
                        $pid=$r['ap_mess_id'];
                        $parentId=$row['mess_id'];
                        $this->loop($parentId,$pid,$aun);
                    }
                    echo "<div id='con_right_but' style=\"padding: 0px 50px 0px;\">
                    回复：<textarea class=\"form-control\" id=\"rv{$row['mess_id']}\"  name=\"res_content\" cols='50' rows='1'></textarea>
                   <button class=\"btn btn-sm\" onclick=\"subRes({$row['mess_id']})\">提交</button>
                   </div>
                </div>
            </div><br/>";
            }
            echo "<br/>当前为第<input id='curPage' value='{$curPage}'></input>页";
        }
    }

    protected function loop($parentId,$pid,$aun){
        $subsubsql="select ap_mess_id,ap_mess_content,ap_user_id,ap_user_name,creat_time from table_append_mess where pid=$pid";
        $rsrs=Db::getDb()->prepare($subsubsql);
        $rsrs->execute();
        if($rsrs){
            foreach ($rsrs as $rsr){
                $namc=$rsr['ap_mess_content'];
                $naun=$rsr['ap_user_name'];
                echo "<div style=\"padding: 0px 70px 0px;\">
                        <div>{$naun}回复{$aun}:<a onclick=\"sedrev($parentId,{$rsr['ap_mess_id']})\"><span class=\"glyphicon glyphicon-share-alt\"></span></a>";
                if($_SESSION['user_id']==$rsr['ap_user_id']){
                    echo "<a onclick=\"deleteRevMess({$rsr['ap_mess_id']})\" >  <span class=\"glyphicon glyphicon-trash\"></span> </a>";
                }
                echo "</div>
                      <textarea class=\"form-control\"  readonly='readonly' cols='80' rows='1'>{$namc}</textarea>
                      <div id='{$rsr['ap_mess_id']}'></div>
              </div>";
                $this->loop($parentId,$rsr['ap_mess_id'],$naun);
            }
        }
    }

    protected function post(){   //发表新留言
        $mess=new Mess();
        $mess->setContent($_POST['new_mess']);
        $result=$this->user->post($mess);
        if($result['success']){
            $this->show();
        }else{
            echo 'error';
        }
    }

    protected function inform(){
        if (isset($_POST['mess_id'])) {
            $mess_id = $_POST['mess_id'];
        } else {
            echo 'error';
        }
        $mess=new Mess();
        $mess->setId($mess_id);
        $result=$mess->inform();
        if ($result['success']) {
            echo $this->show();
        } else {
            echo 'error';
        }
    }

    protected function delete(){
            if (!isset($_POST['mess_id'])) {
                echo '001';   //非法进入
            } else {
                $mess_id = $_POST['mess_id'];
                $mess=new Mess();
                $mess->setId($mess_id);
                $result=$mess->deleteContent();
                if($result['success']){
                    echo $this->show();
                } else {
                    echo '003';   //删除失败
                }
            }
    }

    protected function edit_save(){
        if (empty($_POST)) {
            echo '001';  //非法进入
        } else {
            $mess_id = $_POST['mess_id'];
            $mess_content=$_POST['mess_content'];
            $mess=new Mess();
            $mess->setId($mess_id);
            $mess->setContent($mess_content);
            $result=$mess->editContent();
            if ($result['success']) {
                $this->show();
            } else {
                echo '003';  //数据库插入失败
            }
        }
    }

    protected function res_save(){
        if (!empty($_POST)) {
            $mess=new Rmess();
            $mess->setContent($_POST['ap_mess_content']);
            $mess->setParentId($_POST['mess_id']);
            $result=$mess->postContent();
//            $mess->setId($_POST['mess_id']);
//            $result=$mess->replyContent($_POST['ap_mess_content']);
            if ($result['success']) {
                $this->show();
            } else {
                echo '003';  //数据库插入失败
            }
        } else {
            echo '001';  //非法进入
        }
    }

    protected function sedrevsave(){
        if(!empty($_POST)){
            $mess=new Rmess();
            $mess->setContent($_POST['ap_mess_content']);
            $mess->setParentId($_POST['parent_id']);
            $result=$mess->postContent($_POST['mess_id']);
//            $mess=new Mess();
//            $mess->setId($_POST['parent_id']);
//            $result=$mess->sedreplyContent($_POST['mess_id'],$_POST['ap_mess_content']);
            if ($result['success']) {
                $this->show();
            } else {
                echo '003';  //数据库插入失败
            }
        } else {
            echo '001';  //非法进入
        }
    }

    protected function deleteRevMess(){
        if (!isset($_POST['mess_id'])) {
            echo '001';   //非法进入
        } else {
            $mess_id = $_POST['mess_id'];
            $mess=new Rmess();
            $mess->setId($mess_id);
            $result=$mess->deleteContent();
            if($result['success']){
                echo $this->show();
            } else {
                echo '003';   //删除失败
            }
        }
    }

}

