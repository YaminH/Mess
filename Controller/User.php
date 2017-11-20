<?php
/**
 * Created by PhpStorm.
 * User: HanYaMin
 * Date: 2017/11/17
 * Time: 13:55
 */
class User
{
    public $user_id;
    public $user_name;

    public function __construct($user_id='',$user_name='')
    {
        $this->user_id=$user_id;
        $this->user_name=$user_name;
    }

    public function login($pass_word){
        $user=new Model('table_user');
        $where=array('user_name'=>"$this->user_name");
        $field=array('user_id', 'user_name','pass_word');
        $data=$user->getData($where,$field);
        if($data){
            $tempUser=$data->fetch();
            if($tempUser['pass_word']==$pass_word){
                $_SESSION['user_name']=$this->user_name;
                $_SESSION['user_id']=$tempUser['user_id'];
                $this->user_id=$tempUser['user_id'];
                return array('success'=>true,'message'=>'登陆成功');
            }else{
                return array('success'=>false,'message'=>'登陆失败');
            }
        }else{
            return array('success'=>false,'message'=>'用户不存在');
        }

//        $sql = "select user_id, user_name, pass_word from table_user where user_name=:user_name limit 1";
//        $result=Db::getDb()->prepare($sql);
//        $result->execute(array(':user_name'=>$this->user_name));
//        var_dump($result);
//        if($result){
//            $tempUser=$result->fetch();
//            if($tempUser['pass_word']==$pass_word){
//                $_SESSION['user_name']=$this->user_name;
//                $_SESSION['user_id']=$tempUser['user_id'];
//                $this->user_id=$tempUser['user_id'];
//                return array('success'=>true,'message'=>'登陆成功');
//            }
//        }else{
//            return array('success'=>false,'message'=>'用户不存在');
//        }
    }

    public function register(&$data,$picture=''){
        $data1=[
            'user_id'=>$this->user_id,
            'user_real_name'=>isset($data['user_real_name'])?$data['user_real_name']:'',
            'city'=>isset($data['city'])?$data['city']:'',
            'school'=>isset($data['school'])?$data['school']:'',
            'picture'=>$picture,
        ];
        $data2=[
            'user_id'=>$this->user_id,
            'user_name'=>$this->user_name,
            'pass_word'=>isset($data['pass_word'])?$data['pass_word']:'',
            'creat_time'=>isset($data['creat_time'])?$data['creat_time']:'',
            'audit'=>0,
        ];
        Db::getDb()->beginTransaction();
        $mess1=new Model('table_user_info');
        $result1=$mess1->insert($data1);
        $mess2=new Model('table_user');
        $result2=$mess2->insert($data2);
        if($result1 && $result2){
            Db::getDb()->commit();
            return array('success'=>true,'message'=>'注册成功');
        }else{
            Db::getDb()->rollBack();
            return array('success'=>false,'message'=>'注册失败');
        }

//        $user_id=$this->user_id;
//        $user_name=$this->user_name;
//        $user_real_name=isset($data['user_real_name'])?$data['user_real_name']:'';
//        $city=isset($data['city'])?$data['city']:'';
//        $school=isset($data['school'])?$data['school']:'';
//        $pass_word=isset($data['pass_word'])?$data['pass_word']:'';
//        $creat_time=isset($data['creat_time'])?$data['creat_time']:'';
//
//        Db::getDb()->beginTransaction();
//            $sql1="insert into table_user_info  values ('$user_id',:user_real_name,:city,:school,'$picture')";
//            $sql2="insert into table_user values('$user_id',:user_name,:pass_word,'$creat_time','0')";
//            $result1=Db::getDb()->prepare($sql1);
//            $result1->execute(array(':user_real_name'=>$user_real_name,':city'=>$city,':school'=>$school));
//            $result2=Db::getDb()->prepare($sql2);
//            $result2->execute(array(':user_name'=>$user_name,':pass_word'=>$pass_word));
//            if($result1 && $result2){
//                Db::getDb()->commit();
//                return array('success'=>true,'message'=>'注册成功');
//            }else{
//                Db::getDb()->rollBack();
//                return array('success'=>false,'message'=>'注册失败');
//        }
    }

    public function post(Mess $mess){
        $mess->setId(getUniId());
        return $mess->postContent();
    }
}