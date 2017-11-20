<?php
/**
 * Created by PhpStorm.
 * User: HanYaMin
 * Date: 2017/11/17
 * Time: 15:30
 */
function getUniId(){
    $str=time();
    return  $str.str_pad(rand(0,10000),4,0);
}