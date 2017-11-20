<?php
/**
 * Created by PhpStorm.
 * User: HanYaMin
 * Date: 2017/11/16
 * Time: 0:19
 */

$in=new Test();
session_start();
$in->go();
class Test{
    protected $db;

    public function __construct()
    {
        $this->db=Db::getDb();
    }

    public function go(){
        if(isset($_POST['action'])){
            $cmd=$_POST['action'];
        }else if(isset($_GET['action'])){
            $cmd=$_GET['action'];
        }else{
            $cmd='login';
        }
//        $cmd=isset($_POST['action'])?$_POST['action']:'login';
//        echo $cmd;
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
                $newName=$this->getUniId().".".$tmp[1];
                move_uploaded_file($_FILES["image"]["tmp_name"], "images/" .$newName);
                $_SESSION['picture']=$newName;
                echo '上传成功';
                $this->view('userRigister.html');
            }
//            else
//            {
//                echo 'Invalid file';   //005 Invalid file
//            }
        }
    }

    protected function register(){
        if(empty($_POST)){
            echo '非法进入';  //001非法进入
        }else{
            $user_id=$this->getUniId();
            $user_name = $_POST['user_name'];
            $pass_word = md5($_POST['password']);
            $user_real_name=$_POST['user_real_name'];
            $city=$_POST['city'];
            $school=$_POST['school'];
            $creat_time=date('Y-n-d H:i:s',time());
            if(!isset($_SESSION['picture'])){
                $picture="";
            }else{
                $picture=$_SESSION['picture'];
            }
            $sql1="insert into table_user_info  values ('$user_id','$user_real_name','$city','$school','$picture')";
            $sql2="insert into table_user values('$user_id','$user_name','$pass_word','$creat_time','0')";
            $reslut=$this->db->exec($sql1);
            $re=$this->db->exec($sql2);
            if($reslut && $re){
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
        $sql = "select user_id, user_name, pass_word from table_user where user_name=:user_name limit 1";
        $result=$this->db->prepare($sql);
        $result->execute(array(':user_name'=>$user_name));
        if ($result) {
            $user = $result->fetch();
            if ($user['pass_word'] == $pass_word) {
                $_SESSION['user_name']=$user['user_name'];
                $_SESSION['user_id']=$user['user_id'];
                echo 'success';
            } else {
                echo '密码不正确';
            }
        } else {
            echo '用户不存在';
        }
    }

    protected function show(){
        $rowsPerPage=3;
        $st=$this->db->query("select * from table_message where audit='0'");
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
            foreach ($this->db->query($sql)as $row){
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
                    <button onclick=\"inform({$row['mess_id']})\">举报</button><br/>
                    </div>                    
                    <div id='con_right_mid'>
                    <textarea  id=\"{$row['mess_id']}\" disabled='disabled' cols='80' rows='5'>{$mc}</textarea>";
                    if($_SESSION['user_id']==$row['user_id']){
                        echo  "<button onclick=\"editMess({$row['user_id']},{$row['mess_id']})\">编辑</button>
                        <button onclick=\"saveMess({$row['mess_id']})\">保存</button>
                        <button onclick=\"deleteMess({$row['user_id']},{$row['mess_id']})\">删除</button><br/>
                        </div>                    
                        ";
                    }
                    $subsql="select ap_mess_content,ap_user_id,ap_user_name,creat_time from table_append_mess where ap_parent_id=:ap_parent_id";
                    $rs=$this->db->prepare($subsql);
                    $rs->execute(array(':ap_parent_id'=>$row['mess_id']));
                    foreach ($rs as $r){
                        $aun=$r['ap_user_name'];
                        $amc=$r['ap_mess_content'];
                        echo"<div id='ap_mess'>
                            <div id='ap_maess_user'>{$aun}<br/></div>
                            <textarea  readonly='readonly' cols='80' rows='1'>{$amc}</textarea>
                        </div>";
                    }
                    echo "<div id='con_right_but'>
                    回复：<textarea id=\"ap_mess_content\"  name=\"res_content\" cols='50' rows='1'></textarea>
                   <button onclick=\"subRes({$row['mess_id']})\">提交</button>
                   </div>
                </div>
            </div><br/>";
            }
            echo "当前为第<input id='curPage' value='{$curPage}'></input>页";
        }
    }

    protected function post(){   //发表新留言
        if(isset($_POST['new_mess'])){
            $mess_content=$_POST['new_mess'];
            $user_id=$_SESSION['user_id'];
            $user_name=$_SESSION['user_name'];
            $mess_id=$this->getUniId();
            $create_time=date('Y-m-d H:i:s',time());
            $operate_time=date('Y-m-d H:i:s',time());
            $sql = "insert into table_message VALUES ('$mess_id',:mess_content,'$user_id','$user_name','$create_time','$operate_time','0')";
            $result=$this->db->prepare($sql);
            $result->execute(array(':mess_content'=>$mess_content));
            if($result){
                $this->show();
            }else{
                echo 'error';
            }
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
        $sql = "update table_message set audit='1' where mess_id='{$mess_id}'";
        $reslut = $this->db->exec( $sql);
        if ($reslut) {
            echo $this->show();
        } else {
            echo 'error';
        }
    }

    protected function delete(){
        if (isset($_POST['mess_id'])) {
            $mess_id = $_POST['mess_id'];
            $user_id = $_POST['user_id'];
        } else {
            echo '001';   //非法进入
        }
        if($user_id!=$_SESSION['user_id']){
            echo '002';   //002 非本人留言，不能删除
        }else{
            $sql = "delete from table_message where mess_id='{$mess_id}'";
            $reslut = $this->db->exec($sql);
            if($reslut){
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
            $operate_time=date('Y-m-d H:i:s',time());
            $sql = "update table_message set mess_content=:mess_content,operate_time='{$operate_time}' where mess_id='{$mess_id}'";
            $result=$this->db->prepare($sql);
            $result->execute(array(':mess_content'=>$mess_content));
            if ($result) {
                $this->show();
            } else {
                echo '003';  //数据库插入失败
            }
        }
    }

    protected function res_save(){
        if (!empty($_POST)) {
            $ap_mess_id=$this->getUniId();
            $ap_parent_id = $_POST['mess_id'];
            $ap_mess_content=$_POST['ap_mess_content'];
            $ap_user_id = $_SESSION['user_id'];
            $ap_user_name=$_SESSION['user_name'];
            $creat_time=date('Y-m-d H:i:s',time());
            $operate_time=date('Y-m-d H:i:s',time());
        } else {
            echo '001';  //非法进入
        }
        $sql = "insert into table_append_mess values('$ap_mess_id','$ap_parent_id',:ap_mess_content,'$ap_user_id','$ap_user_name','$creat_time','$operate_time','0')";
        $result=$this->db->prepare($sql);
        $result->execute(array(':ap_mess_content'=>$ap_mess_content));
        if ($result) {
            $this->show();
        } else {
            echo '003';  //数据库插入失败
        }
    }

    protected function isCurUser(){
        if(isset($_POST['user_id'])){
            if(!empty($_SESSION['user_id'])){
                if($_POST['user_id']==$_SESSION['user_id']){
                    $sql="select mess_content from table_message where mess_id={$_POST['mess_id']}";
                    $res=$this->db->query($sql)->fetch();
                    $_SESSION['mess_content']=$res['mess_content'];
                    $_SESSION['mess_id']=$_POST['mess_id'];
                    echo 'true';
                }else{
                    echo 'false';
                }
            }else{
                echo '001';
            }
        }else{
            echo 'false';
        }
    }

    private function getUniId(){
        $str=time();
        return  $str.str_pad(rand(0,10000),4,0);
    }
}

class Db
{
    private static $db;
    public static $dsn="mysql:host=localhost;dbname=mess;";
    public static $user='root';
    public static $pass='root';

    private function __construct(){}
    private function __clone(){}

    public static function getDb(){
        if(is_null(self::$db)){
            self::$db=new PDO(self::$dsn,self::$user,self::$pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES'utf8';"));
        }
        return self::$db;
    }
}


