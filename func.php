<?php
require_once 'config.php';
//获取当前页面的php页面
function getCurrentPage(){
    $str_url=$_SERVER['PHP_SELF'];
    $str_arr=explode('/',$str_url);
    return $str_arr[count($str_arr)-1];
}
//得到url后面的键值对
function getKeyValue(){
  $arr=Array();
  $str_url=$_SERVER['QUERY_STRING'];
  $arr_key_value=explode('&',$str_url);
  foreach($arr_key_value as $item){
    $arr1=explode('=',$item);
    $arr[$arr1[0]]=$arr1[1];
  }
  return $arr;
}
//数据库中查找所有数据
function findAllData($sql){
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);
  if (!$conn) {
    exit('连接失败');
  }
  $query = mysqli_query($conn, $sql);
  if (!$query) {
    // 查询失败
    return false;
  }
  while ($row = mysqli_fetch_assoc($query)) {
    $result[] = $row;
  }
  mysqli_free_result($query);
  mysqli_close($conn);
  return $result;
}


//数据库中查找一条符合条件的
function findOneData($sql){
    $data=findAllData($sql);
    return isset($data)?$data[0]:'';
}
//修改删除增加一条数据库中的y数据
function updateOneData($sql){
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);
  if(!$conn){
    exit('数据库连接失败');
  }
  $query= mysqli_query($conn, $sql);
  if(!$query){
    exit('数据库查询失败de');
  }
  $affected_row=mysqli_affected_rows($conn);
  mysqli_close($conn);
  return $affected_row;
}
//获取session中的用户
function getSessionUser(){
  session_start();  
  if(empty($_SESSION['user'])){
    header('Location:/admin/login.php');
    exit();
  }else{
    return $_SESSION['user'];
  }
}
?>