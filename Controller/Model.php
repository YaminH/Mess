<?php
/**
 * Created by PhpStorm.
 * User: HanYaMin
 * Date: 2017/11/20
 * Time: 13:56
 */

class Model
{
    private $entity_name;
    private $db;

    public function __construct($entuty_name)
    {
        $this->entity_name=$entuty_name;
        $this->db=Db::getDb();
    }

    //select
    public function getData($where=1,$field="*"){
        if(is_array($field)){
            $field=implode(',',$field);
        }
        $sql='select '.$field.' from '.$this->entity_name.' where ';
        if(is_array($where)){
            $key=array_keys($where);
            $keys=implode("=?,",$key);
        }
        $sql.=$keys.'=?';
        $data=$this->db->prepare($sql);
        $data->execute(array_values($where));
        return $data;
    }

    //insert
    public function insert($value){
        $field=implode(',',array_keys($value));
        $val=array_fill(0,count($value),'?');
        $sql='insert into '.$this->entity_name.' ('.$field.') values( '.implode(',',$val).' )';
        $result=$this->db->prepare($sql);
        $result->execute(array_values($value));
        return $result;
    }

    //delete
    public function delete($where=1){
        $sql='delete from '.$this->entity_name.' where ';
        $newwhere=1;
        if(is_array($where)){
            foreach ($where as $key=>$value){
                $newwhere.=' and '.$key.'='.$value;
            }
        }
        $sql.=$newwhere;
        return $this->db->exec($sql);
    }

    //update
    public function update($sets='',$where=1){
        $sql='update '.$this->entity_name.' set ';
        if(is_array($sets)){
            $key=array_keys($sets);
            $keys=implode("=?,",$key);
        }
        $sql.=$keys.'=? where ';
        $newwhere=1;
        if(is_array($where)){
            foreach ($where as $key=>$value){
                $newwhere.=' and '.$key.'='.$value;
            }
        }
        $sql.=$newwhere;
        $data=$this->db->prepare($sql);
        $data->execute(array_values($sets));
        return $data;
    }
}