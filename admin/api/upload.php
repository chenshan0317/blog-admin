<?php
//返回文件保存后的路径
if(empty($_FILES['avatar'])){
    exit('必须上传文件');
}

$avatar=$_FILES['avatar'];
if($_avatar['error']!==UPLOAD_ERR_OK){
    exit('上传失败');
}
//校验文件大小
//移动文件到网站制定目录
$ext=pathinfo($avatar['name'],PATHINFO_EXTENSION);
$target='../../static/uploads/img-'.uniqid().'.'.$ext;
if(!move_uploaded_file($avatar['tmp_name'],$target)){
    exit('上传失败');
}
echo substr($target,5);//去掉5个
